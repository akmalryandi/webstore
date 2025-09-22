<?php

declare(strict_types=1);

namespace App\Drivers\Shipping;

use Illuminate\Support\Facades\Log;
use App\Data\CartData;
use App\Data\RegionData;
use App\Data\ShippingData;
use App\Data\ShippingServiceData;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelData\DataCollection;
use App\Contract\ShippingDriverInterface;

class APIKurirShippingDriver implements ShippingDriverInterface
{

    public readonly string $driver;

    public function __construct()
    {
        $this->driver = 'Sandbox';
    }

    /** @return DataCollection<ShippingServiceData> */
    public function getServices(): DataCollection
    {
        return ShippingServiceData::collect([
            [
                'driver' => $this->driver,
                'code' => 'jne-reguler',
                'courier' => 'JNE',
                'service' => 'Reguler',
            ],
            [
                'driver' => $this->driver,
                'code' => 'jne-same-day',
                'courier' => 'JNE',
                'service' => 'Same Day',
            ],
            [
                'driver' => $this->driver,
                'code' => 'ninja-express-reguler',
                'courier' => 'Ninja Xpress',
                'service' => 'Reguler',
            ]
        ], DataCollection::class);
    }

    public function getRate(
        RegionData $origin,
        RegionData $destination,
        CartData $cart,
        ShippingServiceData $shipping_services
    ): ?ShippingData {
        $response = Http::timeout(120)->withBasicAuth(
            config('shipping.api_kurir.username'),
            config('shipping.api_kurir.password')
        )->post('https://sandbox.apikurir.id/shipments/v1/open-api/rates', [
                    'isUseInsurance' => true,
                    'isPickup' => true,
                    'isCod' => false,
                    'weight' => $cart->totalWeight,
                    'packagePrice' => $cart->total,
                    'origin' => [
                            'postalCode' => $origin->postal_code
                        ],
                    'destination' => [
                        'postalCode' => $destination->postal_code
                    ],
                    'logistics' => [$shipping_services->courier],
                    'services' => [$shipping_services->service]
                ]);

        $data = $response->collect('data')->flatten(1)->values()->first();
        if (empty($data)) {
            return null;
        }

        $est = data_get($data, 'minDuration') . ' - ' . data_get($data, 'maxDuration') . ' ' . data_get($data, 'durationType');
        return new ShippingData(
            $this->driver,
            $shipping_services->courier,
            $shipping_services->service,
            $est,
            data_get($data, 'price'),
            data_get($data, 'weight'),
            $origin,
            $destination,
            data_get($data, 'logoUrl'),
        );

    }
}
