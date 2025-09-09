<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\PaymentData;
use App\Models\PaymentMethod;
use App\Data\PaymentMethodData;
use Spatie\LaravelData\DataCollection;
use App\Contract\PaymentDriverInterface;
use App\Drivers\Payment\OfflinePaymentDriver;

class PaymentMethodQueryService
{
    protected array $drivers = [];

    public function __construct()
    {
        $this->drivers = [
            new OfflinePaymentDriver(),
        ];
    }

     public function getDriver(PaymentData $payment_data) : PaymentDriverInterface
    {
       return collect($this->drivers)
            ->first(fn(PaymentDriverInterface $driver)=> $driver->driver === $payment_data->driver);
    }

    public function getPaymentMethods(): DataCollection
    {
        return collect($this->drivers)
            ->flatMap(fn(PaymentDriverInterface $driver) => $driver->getMethods()->toCollection())
            ->pipe(fn($items)=> PaymentData::collect($items, DataCollection::class));
    }

     public function getPaymentMethodByHash(String $hash): ?PaymentData
    {
        return $this->getPaymentMethods()
            ->toCollection()
            ->first(fn(PaymentData $data) => $data->hash === $hash);
    }

    public function shouldShowButton($sales_order) : bool
    {
        return $this->getDriver(
            $sales_order->payment_driver
        )->shouldShowPayNowButton($sales_order);
    }

    public function getRedirectUrl($sales_order) : ?string
    {
        return $this->getDriver(
            $sales_order->payment_driver
        )->getRedirectUrl($sales_order);
    }
}
