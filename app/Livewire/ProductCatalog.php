<?php
declare(strict_types=1);
namespace App\Livewire;

use App\Models\Tag;
use App\Models\Product;
use Livewire\Component;
use App\Data\ProductData;
use App\Data\ProductCollectionData;

class ProductCatalog extends Component
{
    public function render()
    {
        $result_collection = Tag::query()->withType('collection')->withCount('products')->get();

        $result = Product::paginate(1); //ORM //Database Query
        $products = ProductData::collect($result);
        $collection = ProductCollectionData::collect($result_collection);

        return view('livewire.product-catalog', compact('products', 'collection'));
    }
}
