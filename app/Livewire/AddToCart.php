<?php

namespace App\Livewire;

use Livewire\Component;

class AddToCart extends Component
{
    public $quantity = 1;

    public function addToCart(){
        dd($this->quantity);
    }
    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
