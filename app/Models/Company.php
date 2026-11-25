<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'plan_id', 'plan_expires_at'])]
class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Company $company): void {
            if ($company->plan_id) {
                return;
            }

            $company->plan_id = Plan::query()->where('slug', 'starter')->value('id');
        });
    }

    protected function casts(): array
    {
        return [
            'plan_expires_at' => 'date',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function contractors(): HasMany
    {
        return $this->hasMany(Contractor::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function seedDefaultCategories(): void
    {
        foreach (['Insurance', 'License', 'Certificate', 'Contract'] as $name) {
            $this->categories()->create(['name' => $name]);
        }
    }
}
