<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class InfrastrukturTable extends Component
{
    use WithPagination;

    public $search = '';
    public $show = '10';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingShow()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DB::table('infrastruktur')
            ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
            ->leftJoin('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->leftJoin('analisis_ai', 'infrastruktur.id_infrastruktur', '=', 'analisis_ai.id_infrastruktur')
            ->leftJoin('citra_cnn', 'infrastruktur.id_infrastruktur', '=', 'citra_cnn.id_infrastruktur')
            ->whereNull('infrastruktur.deleted_at');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('infrastruktur.nama_objek', 'LIKE', "%{$this->search}%")
                  ->orWhere('infrastruktur.jenis', 'LIKE', "%{$this->search}%")
                  ->orWhere('infrastruktur.id_infrastruktur', 'LIKE', "%{$this->search}%")
                  ->orWhere('kelurahan.nama_kelurahan', 'LIKE', "%{$this->search}%")
                  ->orWhere('kecamatan.nama_kecamatan', 'LIKE', "%{$this->search}%");
            });
        }

        $query = $query->orderBy('infrastruktur.id_infrastruktur', 'asc')
            ->select(
                'infrastruktur.*', 
                'kelurahan.nama_kelurahan', 
                'kecamatan.nama_kecamatan', 
                'kelurahan.id_kecamatan',
                'analisis_ai.label_prioritas as dt_label_prioritas',
                'analisis_ai.skor_dt as dt_skor_dt',
                'analisis_ai.rekomendasi as dt_rekomendasi',
                'citra_cnn.label_kondisi as cnn_label_kondisi',
                'citra_cnn.skor_cnn as cnn_skor_cnn'
            );

        if ($this->show == 'all') {
            $infrastruktur = $query->get();
        } else {
            $infrastruktur = $query->paginate(10);
        }

        $semuaKecamatan = DB::table('kecamatan')->get();
        $semuaKelurahan = DB::table('kelurahan')->get(); 

        return view('livewire.admin.infrastruktur-table', compact('infrastruktur', 'semuaKecamatan', 'semuaKelurahan'));
    }
}
