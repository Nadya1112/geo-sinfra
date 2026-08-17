<?php

namespace App\Livewire\TimTeknis;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Infrastruktur;
use Illuminate\Support\Facades\DB;

class ValidasiTable extends Component
{
    use WithPagination;

    public $statusFilter = 'Pending';
    public $kecamatan = '';
    public $start_date = '';
    public $end_date = '';
    public $show = '10';

    protected $queryString = [
        'statusFilter' => ['except' => 'Pending', 'as' => 'status'],
        'kecamatan' => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'show' => ['except' => '10'],
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

    public function render()
    {
        $query = Infrastruktur::with(['kelurahan.kecamatan', 'user', 'analisis', 'cnn'])
            ->where('status_verifikasi', 'Verified')
            ->orderBy('created_at', 'desc');
            
        if ($this->statusFilter !== 'All') {
            $query->where('status_validasi', $this->statusFilter);
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
