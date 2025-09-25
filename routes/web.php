<?php

use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\HomePage;
use App\Models\SalesOrder;
use App\Data\SalesOrderData;
use App\Livewire\ProductCatalog;
use App\Livewire\SalesOrderDetail;
use App\Mail\SalesOrderCreatedMail;
use App\Mail\SalesOrderCancelledMail;
use App\Mail\SalesOrderCompletedMail;
use Illuminate\Support\Facades\Route;
use App\Mail\SalesOrderProgressedMail;
use App\Http\Controllers\ProductController;
use App\Livewire\PageStatic;
use App\Mail\ShippingReceiptNumberUpdatedMail;

Route::get('/', HomePage::class)->name('home');
Route::get('/products', ProductCatalog::class)->name('product-catalog');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product');
Route::get('/cart', Cart::class)->name('cart');
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/order-confirmed/{sales_order:trx_id}', SalesOrderDetail::class)->name('order-confirmed');
Route::get('/page/{page:slug?}', PageStatic::class)->name('page');

Route::get('/mailable', function () {
    return new ShippingReceiptNumberUpdatedMail(
        SalesOrderData::from(
            SalesOrder::latest()->first()
        )
    );
});

Route::webhooks('moota/callback');
