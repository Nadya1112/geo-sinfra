<?php

namespace App\Livewire\Surveyor;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Infrastruktur;

class HistoryTable extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $status = '';

    #[Url]
    public $show = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingShow()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Infrastruktur::with(['kelurahan.kecamatan', 'analisis', 'cnn'])
            ->where('id_user', auth()->id())
            ->orderByDesc('created_at');

        $query->when($this->search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('nama_objek', 'like', "%{$search}%")
                    ->orWhere('nama_infrastruktur', 'like', "%{$search}%")
                    ->orWhere('jenis', 'like', "%{$search}%")
                    ->orWhere('kondisi', 'like', "%{$search}%")
                    ->orWhere('status_validasi', 'like', "%{$search}%")
                    ->orWhere('status_verifikasi', 'like', "%{$search}%")
                    ->orWhereHas('kelurahan', function ($k) use ($search) {
                        $k->where('nama_kelurahan', 'like', "%{$search}%")
                          ->orWhereHas('kecamatan', function ($kec) use ($search) {
                              $kec->where('nama_kecamatan', 'like', "%{$search}%");
                          });
                    });
            });
        });

        $query->when($this->status, function ($q, $status) {
            match ($status) {
                'Menunggu' => $q->where('status_verifikasi', 'Pending')->where('status_validasi', 'Pending'),
                'Terverifikasi' => $q->where('status_verifikasi', 'Verified')->where('status_validasi', '!=', 'Rejected'),
                'Ditolak' => $q->where('status_validasi', 'Rejected'),
                'Di-ACC' => $q->where('status_validasi', 'Validated'),
                default => $q
            };
        });

        $riwayat = $this->show === 'all' 
            ? $query->get() 
            : $query->paginate(10);

        return view('livewire.surveyor.history-table', compact('riwayat'));
    }
}
