<?php

declare(strict_types=1);

namespace App\States\SalesOrder\Transitions;

use App\Models\SalesOrder;
use App\Data\SalesOrderData;
use Spatie\ModelStates\Transition;
use App\States\SalesOrder\Progress;
use App\Events\SalesOrderProgressedEvent;

class PendingToProgress extends Transition
{
   public function __construct(
    private SalesOrder $sales_order
   )
   {

   }

   public function handle()
   {
    $this->sales_order->update([
        'status' => Progress::class
    ]);

     event(new SalesOrderProgressedEvent(
        SalesOrderData::fromModel($this->sales_order)
    ));

    return $this->sales_order;
   }
}
