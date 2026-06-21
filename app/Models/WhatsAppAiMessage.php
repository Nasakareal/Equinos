<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppAiMessage extends Model
{
    protected $table = 'whatsapp_ai_messages';

    protected $fillable = [
        'phone',
        'meta_message_id',
        'direction',
        'body',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
