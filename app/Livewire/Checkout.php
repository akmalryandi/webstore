<?php

namespace App\Livewire;


use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class Checkout extends Component
{

    public function mount()
    {
        if(!Gate::inspect('is_stock_available')->allowed()) {
            return redirect()->route('cart');
        }
    }
    public function render()
    {
        return view('livewire.checkout');
    }
}
