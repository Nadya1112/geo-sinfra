<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UserTable extends Component
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

    public function updatingShow()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'LIKE', "%{$this->search}%")
                  ->orWhere('email', 'LIKE', "%{$this->search}%");
            });
        }

        if ($this->show == 'all') {
            $users = $query->get();
        } else {
            $users = $query->paginate((int) $this->show);
        }

        return view('livewire.admin.user-table', compact('users'));
    }
}
