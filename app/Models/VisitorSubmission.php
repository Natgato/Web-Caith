<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

abstract class VisitorSubmission extends Model
{
    protected $guarded = ['id', 'reference', 'status', 'assigned_to'];
    protected $hidden = ['name', 'email', 'phone', 'message', 'availability', 'internal_notes'];

    protected static function booted(): void
    {
        static::creating(fn ($record) => $record->reference = (string) Str::uuid());
    }

    protected function casts(): array
    {
        return ['name' => 'encrypted', 'email' => 'encrypted', 'phone' => 'encrypted', 'message' => 'encrypted',
            'availability' => 'encrypted', 'internal_notes' => 'encrypted', 'consented_at' => 'datetime'];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
