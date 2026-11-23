<?php

namespace App\Models;

class Opinion extends EditorialRecord
{
    protected function casts(): array
    {
        return [...parent::casts(), 'submitted_at' => 'datetime'];
    }
}
