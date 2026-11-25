<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PlanSeeder::class);

        $company = Company::factory()->create([
            'name' => 'Acme Ltd',
        ]);

        User::factory()->admin()->create([
            'company_id' => $company->id,
            'name' => 'Alex Admin',
            'email' => 'admin@acme.test',
        ]);

        User::factory()->create([
            'company_id' => $company->id,
            'name' => 'Eve Employee',
            'email' => 'employee@acme.test',
        ]);
    }
}
