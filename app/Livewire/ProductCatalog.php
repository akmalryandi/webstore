<?php

namespace App\Livewire;

use Livewire\Component;

class ProductCatalog extends Component
{
    public function render()
    {
        $products = \App\Models\Product::all();
        return view('livewire.product-catalog', compact('products'));
    }
}
