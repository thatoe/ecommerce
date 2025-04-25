<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserOrders extends Component
{
    public $orders;

    public function render()
    {
        return view('livewire.user-orders')->layout('layouts.app');;
    }

    public function mount()
    {
        $this->orders = Auth::user()->orders()->latest()->get();
    }
}
