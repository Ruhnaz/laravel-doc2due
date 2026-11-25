<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Database\Factories\ContractorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['company_id', 'name', 'email', 'phone', 'notes'])]
class Contractor extends Model
{
    /** @use HasFactory<ContractorFactory> */
    use BelongsToCompany, HasFactory;

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
