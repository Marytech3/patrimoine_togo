<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'reference', 'name', 'category', 'type', 'ministry', 'location',
        'status', 'acquired_at', 'value_xof', 'description',
    ];

    protected function casts(): array
    {
        return ['acquired_at' => 'date', 'value_xof' => 'integer'];
    }
}
