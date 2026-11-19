<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMovement extends Model
{
    protected $fillable = [
        'reference', 'asset_id', 'asset_reference', 'type', 'from_ministry', 'to_ministry', 'from_location',
        'to_location', 'occurred_at', 'reason', 'recorded_by',
    ];

    protected function casts(): array
    {
        return ['occurred_at' => 'date'];
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
