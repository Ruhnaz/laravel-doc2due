<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReminderLog extends Model
{
    protected $fillable = [
        'document_id',
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

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
