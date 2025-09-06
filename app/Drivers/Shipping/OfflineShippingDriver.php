<?php

declare(strict_types=1);

namespace App\Drivers\Shipping;

use App\Data\CartData;
use App\Data\RegionData;
use App\Data\ShippingData;
use App\Data\ShippingServiceData;
use Spatie\LaravelData\DataCollection;
use App\Contract\ShippingDriverInterface;

class OfflineShippingDriver implements ShippingDriverInterface
{

    public readonly string $driver;

    public function __construct()
    {
        $this->driver = 'offline';
    }

    /** @return DataCollection<ShippingServiceData> */
    public function getServices(): DataCollection
    {
        return ShippingServiceData::collect([
            [
                'driver' => $this->driver,
                'code' => 'offline-flat-15',
                'courier' => 'Internal Courier',
                'service' => 'Instant',
            ],

            [
                'driver' => $this->driver,
                'code' => 'offline-flat-5',
                'courier' => 'Internal Courier',
                'service' => 'SameDay',
            ]

        ], DataCollection::class);
    }

    public function getRate(
        RegionData $origin,
        RegionData $destination,
        CartData $cart,
        ShippingServiceData $shipping_services
    ): ?ShippingData {

        $data = null;

        switch ($shipping_services->code) {
            case 'offline-flat-15':
                $data = ShippingData::from([
                    'driver' => $this->driver,
                    'courier' => $shipping_services->courier,
                    'service' => $shipping_services->service,
                    'estimated_delivery' => '1-2 jam',
                    'cost' => 15000,
                    'weight' => $cart->totalWeight,
                    'origin' => $origin,
                    'destination' => $destination,
                ]);
                break;

            case 'offline-flat-5':
                $data = ShippingData::from([
                    'driver' => $this->driver,
                    'courier' => $shipping_services->courier,
                    'service' => $shipping_services->service,
                    'estimated_delivery' => '1 hari',
                    'cost' => 5000,
                    'weight' => $cart->totalWeight,
                    'origin' => $origin,
                    'destination' => $destination,
                ]);
                break;
        }

        return $data;
    }
}
