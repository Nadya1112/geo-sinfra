<?php

namespace App\Livewire\TimTeknis;

use Livewire\Component;
use Livewire\WithPagination;

class NotifikasiTable extends Component
{
    use WithPagination;

    public $show = '10';

    protected $queryString = [
        'show' => ['except' => '10'],
    ];

    public function updatingShow()
    {
        $this->resetPage();
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function delete($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->delete();
        }
    }

    public function render()
    {
        $query = auth()->user()->notifications();

        if ($this->show == 'all') {
            $notifications = $query->get();
        } else {
            $notifications = $query->paginate((int)$this->show);
        }

        return view('livewire.tim-teknis.notifikasi-table', compact('notifications'));
    }
}
