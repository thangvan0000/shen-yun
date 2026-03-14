<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'address',
        'timezone',
    ];

    public function eventSessions(): HasMany
    {
        return $this->hasMany(EventSession::class);
    }
}
