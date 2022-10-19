<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'stripe_plan' => 'price_1LuC54Dn7JPIapKMY8RXLoXy',
                'price' => 5,
                'description' => 'Basic'
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'stripe_plan' => 'price_1LuC5ZDn7JPIapKMER2yx0bW',
                'price' => 15,
                'description' => 'Premium'
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
