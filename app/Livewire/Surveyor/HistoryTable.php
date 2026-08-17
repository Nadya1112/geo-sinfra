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
            ->orderBy('created_at', 'desc');

        if ($this->search != '') {
            $query->where(function($q) {
                $q->where('nama_objek', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_infrastruktur', 'like', '%' . $this->search . '%')
                  ->orWhere('jenis', 'like', '%' . $this->search . '%')
                  ->orWhere('kondisi', 'like', '%' . $this->search . '%')
                  ->orWhere('status_validasi', 'like', '%' . $this->search . '%')
                  ->orWhere('status_verifikasi', 'like', '%' . $this->search . '%')
                  ->orWhereHas('kelurahan', function($k) {
                      $k->where('nama_kelurahan', 'like', '%' . $this->search . '%')
                        ->orWhereHas('kecamatan', function($kec) {
                            $kec->where('nama_kecamatan', 'like', '%' . $this->search . '%');
                        });
                  });
            });
        }
            
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
            $riwayat = collect($query->get()); 
        } else {
            $riwayat = $query->paginate(10);
        }

        return view('livewire.surveyor.history-table', compact('riwayat'));
    }
}
