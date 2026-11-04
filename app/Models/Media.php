<?php

namespace App\Models;

class Media extends EditorialRecord
{
    protected $table = 'media';

    protected $hidden = ['path', 'authorization_reference'];

    protected function casts(): array
    {
        return [...parent::casts(), 'authorization_expires_at' => 'datetime', 'restricted_to_testimonial' => 'boolean', 'authorization_reference' => 'encrypted'];
    }

    public function isAuthorized(): bool
    {
        return $this->status === 'published' && $this->reviewed_at && $this->published_at?->lte(now())
            && filled($this->authorization_reference)
            && (! $this->authorization_expires_at || $this->authorization_expires_at->isFuture());
    }
}
