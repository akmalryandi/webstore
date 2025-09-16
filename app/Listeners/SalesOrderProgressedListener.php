<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Mail\SalesOrderProgressedMail;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\SalesOrderProgressedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalesOrderProgressedListener
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
    public function handle(SalesOrderProgressedEvent $event): void
    {
        Mail::queue(
            new SalesOrderProgressedMail($event->sales_order)
        );
    }
}
