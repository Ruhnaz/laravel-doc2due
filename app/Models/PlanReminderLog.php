<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanReminderLog extends Model
{
    protected $fillable = [
        'company_id',
        'days_before',
        'expires_on',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_on' => 'date',
            'sent_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
