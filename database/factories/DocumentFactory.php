<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'category_id' => Category::factory(),
            'contractor_id' => null,
            'title' => fake()->sentence(3),
            'expiry_date' => now()->addDays(40)->toDateString(),
            'notes' => null,
            'file_path' => 'documents/example.pdf',
            'file_name' => 'example.pdf',
        ];
    }
}
