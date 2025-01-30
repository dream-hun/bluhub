<?php

namespace Database\Seeders;

use App\Models\DomainPricing;
use Illuminate\Database\Seeder;

class DomainPricingSeeder extends Seeder
{
    public function run(): void
    {
        $domains = [
            [
                'tld' => '.rw',
                'registration_price' => 16500, // ~$13
                'renewal_price' => 18750,      // ~$15
                'transfer_price' => 15000,     // ~$12
                'is_active' => true,
            ],
            [
                'tld' => '.co.rw',
                'registration_price' => 17500,
                'renewal_price' => 20000,
                'transfer_price' => 16250,
                'is_active' => true,
            ],
            [
                'tld' => '.org.rw',
                'registration_price' => 18750,
                'renewal_price' => 21250,
                'transfer_price' => 17500,
                'is_active' => true,
            ],
            [
                'tld' => '.ac.rw',
                'registration_price' => 12500,
                'renewal_price' => 15000,
                'transfer_price' => 11250,
                'is_active' => true,
            ],
        ];

        foreach ($domains as $domain) {
            DomainPricing::updateOrCreate(
                ['tld' => $domain['tld']],
                $domain
            );
        }
    }
}
