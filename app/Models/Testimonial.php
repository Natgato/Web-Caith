<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Testimonial extends EditorialRecord
{
    protected function casts(): array
    {
        return [...parent::casts(),
            'testimony_date' => 'date',
            'submitted_at' => 'datetime',
            'image_requested_at' => 'datetime',
            'contributor_withdrawn_at' => 'datetime',
        ];
    }

    public function consent(): HasOne
    {
        return $this->hasOne(Consent::class);
    }

    public function scopeAuthorized(Builder $q): void
    {
        $q->published()->whereNull('contributor_withdrawn_at')->whereHas('consent', fn (Builder $c) => $c->active());
    }
}
