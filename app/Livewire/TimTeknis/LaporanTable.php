<?php

namespace App\Livewire\TimTeknis;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Infrastruktur;
use Illuminate\Support\Facades\DB;

class LaporanTable extends Component
{
    use WithPagination;

    public $search = '';
    public $kecamatan = '';
    public $kondisi = '';
    public $jenis = '';
    public $start_date = '';
    public $end_date = '';
    public $show = '10';

    protected $queryString = [
        'search'     => ['except' => ''],
        'kecamatan'  => ['except' => ''],
        'kondisi'    => ['except' => ''],
        'jenis'      => ['except' => ''],
        'start_date' => ['except' => ''],
        'end_date'   => ['except' => ''],
        'show'       => ['except' => '10'],
    ];

    /**
     * Inisialisasi filter dari URL query string saat component pertama kali dimuat.
     * Contoh: ?kondisi=Berat dari banner Peringatan Darurat di dashboard.
     */
    public function mount()
    {
        $this->kondisi    = request()->query('kondisi', '');
        $this->kecamatan  = request()->query('kecamatan', '');
        $this->jenis      = request()->query('jenis', '');
        $this->search     = request()->query('search', '');
        $this->start_date = request()->query('start_date', '');
        $this->end_date   = request()->query('end_date', '');
    }

    public function updating($field)
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'kecamatan', 'kondisi', 'jenis', 'start_date', 'end_date', 'show']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Infrastruktur::with(['kelurahan.kecamatan', 'user', 'analisis'])->where('status_verifikasi', 'Verified');

        if ($this->search) {
            $query->where('nama_objek', 'LIKE', '%' . $this->search . '%');
        }

        if ($this->kecamatan) {
            $query->whereHas('kelurahan', function($q) {
                $q->where('id_kecamatan', $this->kecamatan);
            });
        }

        if ($this->kondisi) {
            $query->whereHas('analisis', function($q) {
                $q->where('label_prioritas', $this->kondisi);
            });
        }

        if ($this->jenis) {
            $query->where('jenis', $this->jenis);
        }

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date . ' 00:00:00', $this->end_date . ' 23:59:59']);
        }

        $query->orderBy('created_at', 'desc');

        if ($this->show == 'all') {
            $reports = $query->get();
        } else {
            $reports = $query->paginate((int)$this->show);
        }

        $allKecamatan = DB::table('kecamatan')->get();
        
        $totalLaporan = Infrastruktur::where('status_verifikasi', 'Verified')->count();
        $totalBaik = Infrastruktur::whereHas('analisis', function($q) { $q->where('label_prioritas', 'Baik'); })->where('status_verifikasi', 'Verified')->count();
        $totalSedang = Infrastruktur::whereHas('analisis', function($q) { $q->where('label_prioritas', 'Rusak Sedang'); })->where('status_verifikasi', 'Verified')->count();
        $totalBerat = Infrastruktur::whereHas('analisis', function($q) { $q->where('label_prioritas', 'Rusak Berat'); })->where('status_verifikasi', 'Verified')->count();

        return view('livewire.tim-teknis.laporan-table', compact('reports', 'allKecamatan', 'totalLaporan', 'totalBaik', 'totalSedang', 'totalBerat'));
    }
}
