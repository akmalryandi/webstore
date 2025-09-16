<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\ShippingReceiptNumberUpdatedMail;
use App\Events\ShippingReceiptNumberUpdateEvent;

class ShippingReceiptNumberUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ShippingReceiptNumberUpdateEvent $event): void
    {
        Mail::queue(
            new ShippingReceiptNumberUpdatedMail($event->sales_order)
        );
    }
}
