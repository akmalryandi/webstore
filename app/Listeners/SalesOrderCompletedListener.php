<?php

namespace App\Listeners;

use App\Data\SalesOrderData;
use Illuminate\Support\Facades\Mail;
use App\Mail\SalesOrderCompletedMail;
use App\Events\SalesOrderCompletedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalesOrderCompletedListener
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
    public function handle(SalesOrderCompletedEvent $event): void
    {
        Mail::queue(
            new SalesOrderCompletedMail($event->sales_order)
        );
    }
}
