<?php

namespace App\Livewire\TimTeknis;

use Livewire\Component;
use Livewire\WithPagination;

class NotifikasiTable extends Component
{
    use WithPagination;

    public $show = '10';
    public $search = '';
    public $statusFilter = 'All'; // All, Unread, Read

    protected $queryString = [
        'show' => ['except' => '10'],
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'All'],
    ];

    public function updatingShow()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
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

        // Filter by Status
        if ($this->statusFilter === 'Unread') {
            $query->whereNull('read_at');
        } elseif ($this->statusFilter === 'Read') {
            $query->whereNotNull('read_at');
        }

        // Search by JSON Data (title or message)
        if (!empty($this->search)) {
            $searchTerm = '%' . strtolower($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                // For JSON columns in MySQL/PostgreSQL
                $q->whereRaw("LOWER(JSON_EXTRACT(data, '$.title')) LIKE ?", [$searchTerm])
                  ->orWhereRaw("LOWER(JSON_EXTRACT(data, '$.message')) LIKE ?", [$searchTerm]);
            });
        }

        if ($this->show == 'all') {
            // Give a generous limit for 'all' to prevent massive memory usage, or just paginate a huge number
            $notifications = $query->paginate(1000);
        } else {
            $notifications = $query->paginate((int)$this->show);
        }

        $counts = [
            'all' => auth()->user()->notifications()->count(),
            'unread' => auth()->user()->unreadNotifications()->count(),
            'read' => auth()->user()->readNotifications()->count(),
        ];

        return view('livewire.tim-teknis.notifikasi-table', compact('notifications', 'counts'));
    }
}
