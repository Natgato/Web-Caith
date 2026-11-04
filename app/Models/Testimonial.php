<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Testimonial extends EditorialRecord
{
    protected function casts(): array
    {
        return [...parent::casts(), 'testimony_date' => 'date'];
    }

    public function consent(): HasOne
    {
        return $this->hasOne(Consent::class);
    }

    public function scopeAuthorized(Builder $q): void
    {
        $q->published()->whereHas('consent', fn (Builder $c) => $c->active());
    }
}
