<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['company_id', 'name'])]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use BelongsToCompany, HasFactory;

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
