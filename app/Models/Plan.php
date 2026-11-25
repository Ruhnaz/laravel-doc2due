<?php

namespace App\Models;

use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'price', 'price_inr', 'document_limit'])]
class Plan extends Model
{
    /** @use HasFactory<PlanFactory> */
    use HasFactory;

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function inr(): int
    {
        $catalog = config('plans.'.$this->slug);

        return (int) (is_array($catalog) ? $catalog['price_inr'] : $this->price_inr);
    }

    public function priceLabel(): string
    {
        return $this->inr() === 0 ? 'Free' : '₹'.number_format($this->inr()).'/year';
    }
}
