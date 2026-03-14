<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    protected $fillable = [
        'event_session_id',
        'full_name',
        'email',
        'phone',
        'adult_count',
        'ntl_count',
        'ntl_new_count',
        'child_count',
        'total_count',
        'attend_with_guest',
        'status',
    ];

    protected $casts = [
        'attend_with_guest' => 'boolean',
    ];

    public function eventSession(): BelongsTo
    {
        return $this->belongsTo(EventSession::class);
    }
}
