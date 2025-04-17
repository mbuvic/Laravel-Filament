<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
