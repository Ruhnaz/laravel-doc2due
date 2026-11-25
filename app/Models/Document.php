<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Database\Factories\DocumentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['company_id', 'category_id', 'contractor_id', 'title', 'expiry_date', 'notes', 'file_path', 'file_name'])]
class Document extends Model
{
    /** @use HasFactory<DocumentFactory> */
    use BelongsToCompany, HasFactory;

    protected static function booted(): void
    {
        static::deleting(function (Document $document): void {
            if ($document->file_path) {
                Storage::disk('private')->delete($document->file_path);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
        ];
    }

    protected function status(): Attribute
    {
        return Attribute::get(function (): string {
            $days = (int) now()->startOfDay()->diffInDays($this->expiry_date->copy()->startOfDay(), false);

            return match (true) {
                $days < 0 => 'expired',
                $days <= 7 => 'soon',
                $days <= 30 => 'warning',
                default => 'safe',
            };
        });
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'expired' => 'Expired',
            'soon' => '0-7 days',
            'warning' => '8-30 days',
            default => '31+ days',
        };
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function scopeFiltered(Builder $query, array $filters): Builder
    {
        $search = trim((string) ($filters['search'] ?? ''));

        if ($search !== '') {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhereHas('contractor', function (Builder $query) use ($search): void {
                        $query->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        $today = now()->startOfDay();

        match ($filters['status'] ?? '') {
            'expired' => $query->whereDate('expiry_date', '<', $today),
            'due' => $query->whereDate('expiry_date', '>=', $today)->whereDate('expiry_date', '<=', $today->copy()->addDays(30)),
            'soon' => $query->whereDate('expiry_date', '>=', $today)->whereDate('expiry_date', '<=', $today->copy()->addDays(7)),
            'warning' => $query->whereDate('expiry_date', '>=', $today->copy()->addDays(8))->whereDate('expiry_date', '<=', $today->copy()->addDays(30)),
            'safe' => $query->whereDate('expiry_date', '>=', $today->copy()->addDays(31)),
            default => null,
        };

        if (! empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (! empty($filters['contractor'])) {
            $query->where('contractor_id', $filters['contractor']);
        }

        return $query;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class);
    }
}
