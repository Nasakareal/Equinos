<?php

namespace App\Services\WhatsAppAi;

use App\Services\WhatsAppCloudService;
use Illuminate\Support\Facades\Log;

class WhatsAppAiNotificationService
{
    protected WhatsAppCloudService $whatsApp;
    protected WhatsAppAiAccessService $access;

    public function __construct(WhatsAppCloudService $whatsApp, WhatsAppAiAccessService $access)
    {
        $this->whatsApp = $whatsApp;
        $this->access = $access;
    }

    public function notify(string $message, array $context = []): void
    {
        $to = $this->access->normalizePhone((string) config('services.whatsapp.ai.notify_number', ''));

        if ($to === '') {
            return;
        }

        $response = $this->whatsApp->sendText($to, $message);

        Log::info('WhatsApp AI notification response', [
            'to' => $to,
            'ok' => $response['ok'] ?? false,
            'status' => $response['status'] ?? null,
            'context' => $context,
        ]);
    }
}
