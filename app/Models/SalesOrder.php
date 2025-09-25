<?php

namespace App\Models;

use Spatie\ModelStates\HasStates;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use App\States\SalesOrder\SalesOrderState;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrder extends Model
{
    use HasStates, LogsActivity;
    protected $with = ['items'];
    protected $casts = [
        'status' => SalesOrderState::class,
        'payment_payload' => 'array'
        // 'payment_payload' => 'json'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['status', 'total']);
    }
}
