<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consent extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['details', 'evidence_path', 'withdrawal_reason'];

    protected function casts(): array
    {
        return ['details' => 'encrypted', 'withdrawal_reason' => 'encrypted', 'allow_text' => 'boolean', 'allow_image' => 'boolean',
            'involves_minor' => 'boolean', 'guardian_verified' => 'boolean', 'granted_at' => 'date', 'expires_at' => 'date',
            'withdrawn_at' => 'datetime', 'reviewed_at' => 'datetime'];
    }

    public function scopeActive(Builder $q): void
    {
        $q->where('allow_text', true)->whereNotNull('reviewed_at')->whereNotNull('evidence_path')
            ->whereNull('withdrawn_at')->whereDate('granted_at', '<=', today())
            ->where(fn (Builder $q) => $q->whereNull('expires_at')->orWhereDate('expires_at', '>=', today()))
            ->where(fn (Builder $q) => $q->where('involves_minor', false)->orWhere('guardian_verified', true));
    }

    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
