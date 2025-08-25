<?php

namespace App\Livewire;

use Livewire\Component;
use App\Data\ProductData;
use App\Contract\CartServiceInterface;

class CartItemRemove extends Component
{
    public string $sku;

    public function mount(ProductData $product)
    {
        $this->sku = $product->sku;
    }

    public function remove(CartServiceInterface $cart)
    {
        $cart->remove($this->sku);

        session()->flash('success', "Produk berhasil dihapus dari keranjang");
        $this->dispatch('cart-updated');

        return redirect()->route('cart');
    }
    public function render()
    {
        return view('livewire.cart-item-remove');
    }
}
