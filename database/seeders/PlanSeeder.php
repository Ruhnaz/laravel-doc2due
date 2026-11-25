<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('plans') as $slug => $plan) {
            Plan::query()->updateOrCreate(['slug' => $slug], [
                'name' => $plan['name'],
                'price' => $plan['price_inr'],
                'price_inr' => $plan['price_inr'],
                'document_limit' => $plan['document_limit'],
            ]);
        }
    }
}
