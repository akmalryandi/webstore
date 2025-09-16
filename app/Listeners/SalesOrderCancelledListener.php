<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Mail\SalesOrderCancelledMail;
use App\Events\SalesOrderCancelledEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalesOrderCancelledListener
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
    public function handle(SalesOrderCancelledEvent $event): void
    {
        Mail::queue(
            new SalesOrderCancelledMail($event->sales_order)
        );
    }
}
