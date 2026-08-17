<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;

class ActivityTable extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('type', 'LIKE', '%' . $this->search . '%')
                  ->orWhereHas('user', function($uq) {
                      $uq->where('name', 'LIKE', '%' . $this->search . '%');
                  });
            });
        }

        $activities = $query->paginate(15);

        return view('livewire.admin.activity-table', compact('activities'));
    }
}
