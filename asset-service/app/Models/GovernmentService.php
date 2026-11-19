<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentService extends Model
{
    protected $fillable = [
        'name', 'ministry', 'region', 'prefecture', 'address', 'manager', 'contact', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
