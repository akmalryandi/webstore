<?php

namespace App\Livewire;

use App\Actions\ValidateCartStock;
use Illuminate\Support\Collection;
use Livewire\Component;
use App\Contract\CartServiceInterface;
use Dotenv\Exception\ValidationException;

class Cart extends Component
{
    // Properti publik yang akan digunakan untuk menampilkan subtotal dan total di view (Blade)
    public string $sub_total;
    public string $total;

    // Method mount() dijalankan pertama kali saat komponen Livewire di-load
    public function mount(CartServiceInterface $cart)
    {
        // Mengambil semua data keranjang dari service Cart
        $all = $cart->all();

        // Mengisi properti $sub_total dengan nilai subtotal yang sudah diformat dari service
        $this->sub_total = $all->totalFormatted;

        // Mengisi properti $total dengan nilai yang sama seperti subtotal
        // (mungkin nanti bisa ditambahkan biaya lain seperti ongkir)
        $this->total = $this->sub_total;
    }

    // Computed property di Livewire: method ini otomatis membuat properti dinamis bernama $items
    public function getItemsProperty(CartServiceInterface $cart): Collection
    {
        // Mengambil daftar item di keranjang dan mengubahnya menjadi Laravel Collection
        return $cart->all()->items->toCollection();
    }

    public function checkout()
    {
        // Lakukan proses checkout di sini
        try {
            // Lakukan pengecekan apakah ada item di keranjang
            ValidateCartStock::run();
            return redirect()->route('checkout');
        } catch (ValidationException $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->route('cart');
        }
    }

    // Method render() yang wajib ada di setiap komponen Livewire
    public function render()
    {
        // Mengembalikan view 'livewire.cart' dan mengirim data 'items' yang diambil dari computed property $items
        // Saat $this->items dipanggil, Livewire otomatis menjalankan getItemsProperty()
        return view('livewire.cart', [
            'items' => $this->items
        ]);
    }
}

