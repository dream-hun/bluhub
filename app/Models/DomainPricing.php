<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;

class DomainPricing extends Model
{
    protected $fillable = [
        'tld',
        'registration_price',
        'renewal_price',
        'transfer_price',
        'grace_period',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function formattedRegistrationPrice(): Money
    {
        return Money::RWF($this->registration_price);
    }

    public function formattedRenewalPrice(): Money
    {
        return Money::RWF($this->renewal_price);
    }

    public function formattedTransferPrice(): Money
    {
        return Money::RWF($this->transfer_price);
    }
}
