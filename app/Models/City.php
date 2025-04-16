<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $fillable = [
        'name',
        'county_id',
    ];

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }
}
