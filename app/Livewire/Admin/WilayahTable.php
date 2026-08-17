<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class WilayahTable extends Component
{
    use WithPagination;

    public $search = '';
    public $show = '10';

    protected $queryString = [
        'search' => ['except' => ''],
        'show' => ['except' => '10'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DB::table('kelurahan')
            ->join('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->leftJoin('infrastruktur', 'kelurahan.id_kelurahan', '=', 'infrastruktur.id_kelurahan')
            ->select('kelurahan.*', 'kecamatan.nama_kecamatan', DB::raw('COUNT(infrastruktur.id_infrastruktur) as total_aset'))
            ->groupBy('kelurahan.id_kelurahan', 'kecamatan.nama_kecamatan', 'kelurahan.id_kecamatan', 'kelurahan.nama_kelurahan', 'kelurahan.geometri', 'kelurahan.created_at', 'kelurahan.updated_at');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('kelurahan.nama_kelurahan', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('kecamatan.nama_kecamatan', 'LIKE', '%' . $this->search . '%');
            });
        }
        
        if ($this->show == 'all') {
            $wilayah = $query->get();
        } else {
            $wilayah = $query->paginate((int)$this->show);
        }

        return view('livewire.admin.wilayah-table', compact('wilayah'));
    }
}
