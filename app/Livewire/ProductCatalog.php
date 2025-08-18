<?php
declare(strict_types=1);
namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Data\ProductData;

class ProductCatalog extends Component
{
    public function render()
    {
        $result = Product::paginate(1); //ORM //Database Query
        $products = ProductData::collect($result);
        return view('livewire.product-catalog', compact('products'));
    }
}
