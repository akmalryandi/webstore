<?php

declare(strict_types=1);

namespace App\Drivers\Payment;

use App\Data\PaymentData;
use App\Data\SalesOrderData;
use Spatie\LaravelData\DataCollection;
use App\Contract\PaymentDriverInterface;

class OfflinePaymentDriver implements PaymentDriverInterface
{

    public readonly string $driver;

    public function __construct()
    {
        $this->driver = 'offline';
    }

    /** @return DataCollection<PaymentData> */
    public function getMethods(): DataCollection
    {
        return PaymentData::collect([
            PaymentData::from([
                'driver' => $this->driver,
                'method' => 'bca-bank-transfer',
                'label' => 'Bank Transfer BCA',
                'payload' => [
                    'account_number' => '123456789',
                    'account_name' => 'Akmal Ryandi'
                ]
            ])
        ], DataCollection::class);
    }

    public function process(SalesOrderData $sales_order)
    {
        // TODO: Implement process() method.
    }

    public function shouldShowPayNowButton(SalesOrderData $sales_order): bool
    {
        return false;
    }

    public function getRedirectUrl(SalesOrderData $sales_order): ?string
    {
        return null;
    }
}
