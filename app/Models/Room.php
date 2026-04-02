<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// AmprahanReport is in the same namespace, no explicit import needed

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'male_capacity',
        'female_capacity',
        'male_occupied',
        'female_occupied',
        'last_synced_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
    ];

    public function getMaleAvailableAttribute(): int
    {
        return $this->male_capacity - $this->male_occupied;
    }

    public function getFemaleAvailableAttribute(): int
    {
        return $this->female_capacity - $this->female_occupied;
    }

    public function amprahanReports(): HasMany
    {
        return $this->hasMany(AmprahanReport::class);
    }
}
