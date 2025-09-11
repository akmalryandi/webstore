<?php
declare(strict_types=1);
namespace App\Services;

use App\Contract\CartServiceInterface;
use App\Data\CartItemData;
use App\Data\CartData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Spatie\LaravelData\DataCollection;

// Kelas SessionCartService mengimplementasikan interface CartServiceInterface
class SessionCartService implements CartServiceInterface
{
    // Property yang menyimpan key session untuk keranjang belanja
    protected $session_key = 'cart';

    /**
     * Memuat data keranjang dari session.
     * Mengembalikan objek DataCollection berisi data item keranjang.
     */
    protected function load(): DataCollection
    {
        // Ambil data keranjang dari session menggunakan key, jika tidak ada gunakan array kosong
        $raw = Session::get($this->session_key, []);
        // Buat objek DataCollection dari data mentah yang diambil
        return new DataCollection(CartItemData::class, $raw);
    }

    /**
     * Menyimpan koleksi item ke dalam session.
     * @param Collection<int,CartItemData> $items Koleksi item yang akan disimpan
     */
    protected function save(Collection $items): void
    {
        // Simpan data ke session dengan key 'cart', hanya ambil value yang sudah dirapikan (values()->all())
        Session::put($this->session_key, $items->values()->all());
    }

    /**
     * Menambahkan item ke keranjang atau memperbarui jika item sudah ada.
     */
    public function addOrUpdate(CartItemData $item): void
    {
        // 1. Ambil data keranjang dari session dan ubah menjadi koleksi
        $collection = $this->load()->toCollection();
        $update = false; // Penanda apakah item di-update atau tidak

        // 2. Lakukan mapping pada setiap item di keranjang
        $cart = $collection->map(function (CartItemData $i) use ($item, &$update) {
            // Jika SKU item sama, update data item
            if ($i->sku == $item->sku) {
                $update = true; // Tandai bahwa item di-update
                return $item;   // Ganti dengan item baru
            }
            // Jika tidak sama, kembalikan item lama
            return $i;
        })->values()->collect();

        // Jika item belum ada di keranjang, tambahkan item baru
        if (!$update) {
            $cart->push($item);
        }

        // 3. Simpan data keranjang yang sudah diperbarui ke session
        $this->save($cart);
    }

    /**
     * Menghapus item dari keranjang berdasarkan SKU.
     */
    public function remove(string $sku): void
    {
        // Ambil data keranjang, filter item yang bukan SKU yang dihapus
        $cart = $this->load()->toCollection()
            ->reject(fn(CartItemData $i) => $i->sku == $sku) // Hapus item yang SKU-nya cocok
            ->values()   // Reset index
            ->collect(); // Jadikan koleksi kembali

        // Simpan hasil ke session
        $this->save($cart);
    }

    /**
     * Menghapus semua item dari keranjang.
     */
    public function clear(): void
    {
        // Hapus data keranjang dari session
        Session::forget($this->session_key);
    }

    /**
     * Mengambil satu item dari keranjang berdasarkan SKU.
     * Mengembalikan CartItemData atau null jika tidak ditemukan.
     */
    public function getItemBySku(string $sku): ?CartItemData
    {
        // Cari item pertama yang SKU-nya cocok, jika tidak ada kembalikan null
        return $this->load()->toCollection()->first(fn(CartItemData $item) => $item->sku == $sku);
    }

    /**
     * Mengambil seluruh data keranjang dalam bentuk CartData.
     */
    public function all(): CartData
    {
        // Buat objek CartData dari data keranjang yang sudah dimuat
        return new CartData($this->load());
    }
}

