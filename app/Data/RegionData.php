<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Computed;

class RegionData extends Data
{
    #[Computed]
    public string $label;
    public function __construct(
        public string $code,
        public string $province,
        public string $city,
        public string $district,
        public string $sub_distric,
        public string $postal_code,
        public string $country = 'indonesia'
    ) {
        $this->label = "$sub_distric, $district, $city, $province, $postal_code";
    }
}
