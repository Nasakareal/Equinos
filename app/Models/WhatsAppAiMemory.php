<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppAiMemory extends Model
{
    protected $fillable = [
        'phone',
        'fact',
        'source',
        'trusted',
        'last_used_at',
    ];

    protected $casts = [
        'trusted' => 'boolean',
        'last_used_at' => 'datetime',
    ];
}
