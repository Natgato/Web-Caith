<?php

namespace App\Models;

class Statistic extends EditorialRecord
{
    protected function casts(): array
    {
        return [...parent::casts(), 'value' => 'decimal:3', 'verified_at' => 'datetime'];
    }
}
