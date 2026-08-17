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

    public function render()
    {
        $query = Infrastruktur::with(['kelurahan.kecamatan', 'analisis', 'cnn'])
            ->where('id_user', auth()->id())
            ->orderBy('created_at', 'desc');
            
        if ($this->status != '') {
            $query->where('status_verifikasi', $this->status);
        }
            
        if ($this->show == 'all') {
            $riwayat = collect($query->get()); // Return collection to avoid pagination links error
        } else {
            $riwayat = $query->paginate(10);
        }

        return view('livewire.surveyor.history-table', compact('riwayat'));
    }
}
