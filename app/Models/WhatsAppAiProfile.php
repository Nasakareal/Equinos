<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppAiProfile extends Model
{
    protected $fillable = [
        'phone',
        'assistant_name',
        'oficio_letterhead_text',
        'oficio_letterhead_updated_at',
        'welcome_template_name',
        'welcome_template_language',
        'welcome_template_sent_at',
        'welcome_template_message_id',
        'welcome_template_payload',
        'last_welcome_attempt_at',
    ];

    protected $casts = [
        'oficio_letterhead_updated_at' => 'datetime',
        'welcome_template_sent_at' => 'datetime',
        'welcome_template_payload' => 'array',
        'last_welcome_attempt_at' => 'datetime',
    ];
}
