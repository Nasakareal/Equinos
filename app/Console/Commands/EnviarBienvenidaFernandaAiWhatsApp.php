<?php

namespace App\Console\Commands;

use App\Models\WhatsAppAiProfile;
use App\Services\WhatsAppAi\WhatsAppAiAccessService;
use App\Services\WhatsAppAi\WhatsAppAiNotificationService;
use App\Services\WhatsAppCloudService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EnviarBienvenidaFernandaAiWhatsApp extends Command
{
    protected $signature = 'whatsapp-ai:enviar-bienvenida-fernanda {--force : Envia aunque ya se haya marcado como enviada} {--dry-run : No envia, solo muestra que haria}';

    protected $description = 'Envia la plantilla inicial de la IA a Fernanda una sola vez';

    public function handle(
        WhatsAppCloudService $whatsApp,
        WhatsAppAiAccessService $access,
        WhatsAppAiNotificationService $notifications
    ): int
    {
        $to = $access->normalizePhone((string) config('services.whatsapp.ai.fernanda_number', ''));
        $template = (string) config('services.whatsapp.ai.welcome_template', 'bienvenida_fernanda_ai');
        $language = (string) config('services.whatsapp.ai.welcome_template_language', 'es_MX');

        if ($to === '') {
            $this->error('Falta WHATSAPP_AI_FERNANDA_NUMBER.');
            return 1;
        }

        if ($template === '') {
            $this->error('Falta WHATSAPP_AI_WELCOME_TEMPLATE.');
            return 1;
        }

        $profile = WhatsAppAiProfile::firstOrCreate(
            ['phone' => $to],
            [
                'welcome_template_name' => $template,
                'welcome_template_language' => $language,
            ]
        );

        if ($profile->welcome_template_sent_at && !$this->option('force')) {
            $this->info('La bienvenida ya fue enviada a ' . $to . ' el ' . $profile->welcome_template_sent_at . '.');
            return 0;
        }

        $profile->fill([
            'welcome_template_name' => $template,
            'welcome_template_language' => $language,
            'last_welcome_attempt_at' => now(),
        ])->save();

        if ($this->option('dry-run')) {
            $this->line('Dry-run: se enviaria la plantilla ' . $template . ' (' . $language . ') a ' . $to . '.');
            return 0;
        }

        $response = $whatsApp->sendTemplate($to, $template, [], $language);

        Log::info('WhatsApp AI bienvenida Fernanda response', [
            'to' => $to,
            'template' => $template,
            'language' => $language,
            'ok' => $response['ok'] ?? false,
            'status' => $response['status'] ?? null,
            'body' => $response['body'] ?? null,
        ]);

        if (!($response['ok'] ?? false)) {
            $this->error('Meta no acepto la plantilla. Se intentara de nuevo en el siguiente ciclo.');
            return 1;
        }

        $profile->fill([
            'welcome_template_sent_at' => now(),
            'welcome_template_message_id' => (string) ($response['body']['messages'][0]['id'] ?? ''),
            'welcome_template_payload' => $response,
        ])->save();

        $notifications->notify(
            'Aviso IA Equinos: Meta acepto enviar la plantilla inicial a Fernanda. ID: '
            . ($profile->welcome_template_message_id ?: 'N/D'),
            [
                'event' => 'welcome_template_sent',
                'to' => $to,
                'template' => $template,
                'language' => $language,
                'message_id' => $profile->welcome_template_message_id,
            ]
        );

        $this->info('Bienvenida enviada a Fernanda. ID Meta: ' . ($profile->welcome_template_message_id ?: 'N/D'));

        return 0;
    }
}
