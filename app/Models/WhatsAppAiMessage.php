<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppAiMessage extends Model
{
    protected $fillable = [
        'phone',
        'direction',
        'body',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
