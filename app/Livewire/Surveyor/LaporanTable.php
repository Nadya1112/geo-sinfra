<?php

namespace App\Livewire\Surveyor;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\LaporanWarga;

class LaporanTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    #[On('updateStatus')]
    public function updateStatus($params)
    {
        $id = $params['id'] ?? null;
        $status = $params['status'] ?? null;
        $laporan = LaporanWarga::findOrFail($id);
        $laporan->status = $status;
        $laporan->save();

        session()->flash('success', 'Status laporan berhasil diperbarui.');
        $this->dispatch('status-updated');
    }

    public function render()
    {
        $query = LaporanWarga::where('id_surveyor', auth()->id());
        
        if ($this->search != '') {
            $query->where(function($q) {
                $q->where('nama_pelapor', 'like', '%'.$this->search.'%')
                  ->orWhere('deskripsi', 'like', '%'.$this->search.'%')
                  ->orWhere('no_hp', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->status != 'all') {
            $query->where('status', $this->status);
        }

        $laporanWarga = $query->latest()->paginate(10);

        return view('livewire.surveyor.laporan-table', compact('laporanWarga'));
    }
}
