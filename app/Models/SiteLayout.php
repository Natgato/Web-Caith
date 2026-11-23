<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteLayout extends Model
{
    protected $fillable = ['area', 'draft', 'published'];

    protected function casts(): array
    {
        return ['draft' => 'array', 'published' => 'array'];
    }
}
