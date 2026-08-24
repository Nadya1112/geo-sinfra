<?php

namespace App\Livewire\TimTeknis;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Infrastruktur;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use App\Services\WhatsAppService;

class ValidasiTable extends Component
{
    use WithPagination;

    public $statusFilter = 'Pending';
    public $kecamatan = '';
    public $start_date = '';
    public $end_date = '';
    public $show = '10';
    public $search = '';

    public $showModal = false;
    public $modalAction = '';
    public $modalId = null;
    public $alasan = '';
    public $rekomendasi_manual = '';

    protected $queryString = [
        'statusFilter' => ['except' => 'Pending', 'as' => 'status'],
        'kecamatan' => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'show' => ['except' => '10'],
        'search' => ['except' => ''],
    ];

    public function updating($field)
    {
        $this->resetPage();
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function openModal($id, $action)
    {
        $this->modalId = $id;
        $this->modalAction = $action;
        $this->alasan = '';
        $this->rekomendasi_manual = '';
        $this->showModal = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->modalId = null;
        $this->modalAction = '';
        $this->alasan = '';
        $this->rekomendasi_manual = '';
    }

    public function prosesValidasi()
    {
        if ($this->modalAction === 'Rejected' && empty(trim($this->alasan))) {
            $this->addError('alasan', 'Catatan/Alasan wajib diisi untuk penolakan!');
            return;
        }

        $infra = Infrastruktur::findOrFail($this->modalId);
        $infra->status_validasi = $this->modalAction;
        
        if ($this->modalAction === 'Rejected') {
            $infra->alasan_penolakan = trim($this->alasan) ?: null;
        } elseif ($this->modalAction === 'Validated') {
            $infra->rekomendasi_manual = trim($this->rekomendasi_manual) ?: null;
            // Bersihkan alasan penolakan sebelumnya (jika ada)
            $infra->alasan_penolakan = null;
        }
        
        $infra->save();

        // Rekam Aktivitas Log
        $logCatatan = $infra->alasan_penolakan ? " dengan catatan: {$infra->alasan_penolakan}" : "";
        $logAction = $this->modalAction == 'Validated' ? "menyetujui (Validasi)" : "menolak (Validasi)";
        ActivityLog::record("Tim Teknis {$logAction} laporan infrastruktur{$logCatatan}", 'infrastruktur', $infra->id_infrastruktur);

        // Kirim Notifikasi WA ke Surveyor
        WhatsAppService::sendValidationResultNotification($infra);

        $this->closeModal();
        $this->statusFilter = $this->modalAction;
        session()->flash('success', $this->modalAction == 'Validated' ? 'Data berhasil divalidasi!' : 'Data telah ditolak!');
    }

    public function render()
    {
        $query = Infrastruktur::with(['kelurahan.kecamatan', 'user', 'analisis', 'cnn'])
            ->where('status_verifikasi', 'Verified')
            ->orderBy('created_at', 'desc');
            
        if ($this->statusFilter !== 'All') {
            $query->where('status_validasi', $this->statusFilter);
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nama_objek', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_infrastruktur', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->kecamatan) {
            $query->whereHas('kelurahan', function($q) {
                $q->where('id_kecamatan', $this->kecamatan);
            });
        }

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('infrastruktur.created_at', [
                $this->start_date . ' 00:00:00',
                $this->end_date . ' 23:59:59'
            ]);
        }

        if ($this->show == 'all') {
            $allUsulan = $query->get();
        } else {
            $allUsulan = $query->paginate((int)$this->show);
        }

        $counts = [
            'pending' => Infrastruktur::where('status_verifikasi', 'Verified')->where('status_validasi', 'Pending')->count(),
            'verified' => Infrastruktur::where('status_verifikasi', 'Verified')->where('status_validasi', 'Validated')->count(),
            'rejected' => Infrastruktur::where('status_verifikasi', 'Verified')->where('status_validasi', 'Rejected')->count(),
        ];

        $allKecamatan = \App\Models\Kecamatan::all();

        return view('livewire.tim-teknis.validasi-table', compact('allUsulan', 'counts', 'allKecamatan'));
    }
}
