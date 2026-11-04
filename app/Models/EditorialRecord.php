<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class EditorialRecord extends Model
{
    protected $guarded = ['id', 'author_id', 'reviewer_id', 'reviewed_at', 'published_at'];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime', 'published_at' => 'datetime', 'is_provisional' => 'boolean'];
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')->whereNotNull('reviewed_at')->where('published_at', '<=', now());
    }

    public function scopeInstitutionalPreview(Builder $query): void
    {
        $query->where(function (Builder $q) {
            $q->published();
            if (config('caith.preview_institutional') && app()->environment('local')) {
                $q->orWhere(fn (Builder $draft) => $draft->where('status', 'draft')->where('is_provisional', true));
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
