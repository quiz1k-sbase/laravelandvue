<?php

namespace Database\Seeders;

use App\Models\Balance;
use App\Models\Bill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $billing = [
            [
                'name' => 'Donate',
                'slug' => 'donate',
                'stripe_plan' => 'price_1LuKcVDn7JPIapKMzlhjYxdp',
                'description' => 'Basic',
            ],
        ];

        foreach ($billing as $bill) {
            Bill::create($bill);
        }
    }
}
