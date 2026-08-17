<?php

namespace App\Livewire\Surveyor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Infrastruktur;

class HistoryTable extends Component
{
    use WithPagination;

    public $status = '';
    public $show = '';

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
            ->orderBy('created_at', 'desc');
            
        if ($this->status != '') {
            if ($this->status == 'Menunggu') {
                $query->where(function($q) {
                    $q->where('status_verifikasi', 'Pending')
                      ->where('status_validasi', 'Pending');
                });
            } elseif ($this->status == 'Terverifikasi') {
                $query->where('status_verifikasi', 'Verified')
                      ->where('status_validasi', '!=', 'Rejected');
            } elseif ($this->status == 'Ditolak') {
                $query->where('status_validasi', 'Rejected');
            } elseif ($this->status == 'Di-ACC') {
                $query->where('status_validasi', 'Validated');
            }
        }
            
        if ($this->show == 'all') {
            $riwayat = collect($query->get()); // Return collection to avoid pagination links error
        } else {
            $riwayat = $query->paginate(10);
        }

        return view('livewire.surveyor.history-table', compact('riwayat'));
    }
}
