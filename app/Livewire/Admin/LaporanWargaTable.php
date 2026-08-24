<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LaporanWarga;
use App\Models\User;

class LaporanWargaTable extends Component
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

    public function updateStatus($id, $newStatus)
    {
        $laporan = LaporanWarga::find($id);
        if ($laporan) {
            $laporan->status = $newStatus;
            $laporan->save();
            session()->flash('success', 'Status laporan berhasil diperbarui.');
        }
    }

    public function assignSurveyor($id, $idSurveyor)
    {
        $laporan = LaporanWarga::find($id);
        if ($laporan) {
            $laporan->id_surveyor = $idSurveyor;
            $laporan->save();
            session()->flash('success', 'Penugasan surveyor berhasil diperbarui.');
        }
    }

    public function deleteLaporan($id)
    {
        $laporan = LaporanWarga::find($id);
        if ($laporan) {
            $laporan->delete();
            session()->flash('success', 'Laporan berhasil dihapus.');
        }
    }

    public function render()
    {
        $query = LaporanWarga::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_pelapor', 'like', '%'.$this->search.'%')
                  ->orWhere('deskripsi', 'like', '%'.$this->search.'%')
                  ->orWhere('no_hp', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $laporanWarga = $query->latest()->paginate(10);
        $surveyors = User::where('role', 'surveyor')->get();

        return view('livewire.admin.laporan-warga-table', compact('laporanWarga', 'surveyors'));
    }
}
