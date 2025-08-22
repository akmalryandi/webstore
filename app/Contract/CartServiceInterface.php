<?php

declare(strict_types=1);
namespace App\Contract;


use App\Data\CartData;
use App\Data\CartItemData;

// Interface CartServiceInterface digunakan untuk mendefinisikan kontrak layanan keranjang belanja
interface CartServiceInterface
{
    /**
     * Menambahkan item ke dalam keranjang atau memperbarui jika sudah ada.
     * @param CartItemData $item Representasi data item yang akan ditambahkan atau diupdate.
     */
    public function addOrUpdate(CartItemData $item): void;

    /**
     * Menghapus item dari keranjang berdasarkan SKU.
     * @param string $sku SKU (Stock Keeping Unit) unik dari item yang akan dihapus.
     */
    public function remove(string $sku): void;

    /**
     * Mengambil satu item dari keranjang berdasarkan SKU.
     * @param string $sku SKU item yang dicari.
     * @return CartItemData|null Kembalikan data item jika ditemukan, jika tidak null.
     */
    public function getItemBySku(string $sku): ?CartItemData;

    /**
     * Mengambil semua data item yang ada di keranjang.
     * @return CartData Objek yang berisi seluruh data keranjang (misalnya daftar item dan total harga).
     */
    public function all(): CartData;
}


