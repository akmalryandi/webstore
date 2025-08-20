<?php

declare(strict_types=1);
namespace App\Data;

use Illuminate\Support\Number;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class CartData extends Data
{
    #[Computed()]
    public float $total;

    public int $totalWeight;

    public int $totalQuantity;

    public string $totalFormatted;
    public function __construct(
        #[DataCollectionOf(CartItemData::class)]
        public DataCollection $items
    ) {
        $items = $items->toCollection();
        $this->total = $items->sum(fn (CartItemData $item) => $item->price * $item->quantity);
        $this->totalWeight = $items->sum(fn (CartItemData $item) => $item->weight ?? 0);
        $this->totalQuantity = $items->sum(fn (CartItemData $item) => $item->quantity);
        $this->totalFormatted = Number::currency($this->total);
    }
}
