<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsApp\WhatsAppInboundService;
use App\Services\WhatsAppAi\WhatsAppAiAccessService;
use App\Services\WhatsAppAi\WhatsAppAiAssistantService;
use App\Services\WhatsAppCloudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppAiWebhookController extends Controller
{
    protected $inboundService;
    protected $accessService;
    protected $assistantService;
    protected $cloudService;

    public function __construct(
        WhatsAppInboundService $inboundService,
        WhatsAppAiAccessService $accessService,
        WhatsAppAiAssistantService $assistantService,
        WhatsAppCloudService $cloudService
    ) {
        $this->inboundService = $inboundService;
        $this->accessService = $accessService;
        $this->assistantService = $assistantService;
        $this->cloudService = $cloudService;
    }

    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode') ?? $request->query('hub.mode');
        $token = $request->query('hub_verify_token') ?? $request->query('hub.verify_token');
        $challenge = $request->query('hub_challenge') ?? $request->query('hub.challenge');
        $verifyToken = (string) config('services.whatsapp.verify_token');

        if ($mode === 'subscribe' && $verifyToken !== '' && hash_equals($verifyToken, (string) $token)) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    public function handle(Request $request)
    {
        if (!$this->hasValidSignature($request)) {
            Log::warning('WhatsApp AI webhook invalid signature');
            return response()->json(['ok' => false], 403);
        }

        $payload = $request->all();
        $messages = $this->inboundService->extractMessages($payload);
        $statuses = $this->inboundService->extractStatuses($payload);

        Log::info('WhatsApp AI webhook received', [
            'messages' => count($messages),
            'statuses' => count($statuses),
        ]);

        foreach ($statuses as $status) {
            Log::info('WhatsApp AI message status', $status);
        }

        foreach ($messages as $message) {
            try {
                $this->processMessage($message);
            } catch (\Throwable $e) {
                Log::error('WhatsApp AI message processing failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'from' => $message['from'] ?? null,
                    'type' => $message['type'] ?? null,
                ]);
            }
        }

        return response()->json(['ok' => true], 200);
    }

    protected function processMessage(array $message): void
    {
        $from = $this->accessService->normalizePhone((string) ($message['from'] ?? ''));

        if ($from === '') {
            Log::warning('WhatsApp AI message without sender', ['message' => $message]);
            return;
        }

        if (!$this->accessService->isAllowed($from)) {
            $this->cloudService->sendText($from, 'Numero no autorizado para este asistente.');
            return;
        }

        $input = $this->inboundService->extractUserInput($message);

        if (($input['type'] ?? '') !== 'text') {
            $this->cloudService->sendText($from, 'Por ahora leo mensajes de texto. Mandame tu consulta escrita.');
            return;
        }

        $text = trim((string) ($input['value'] ?? ''));
        $privileged = $this->accessService->isPrivileged($from);
        $metaMessageId = trim((string) ($message['id'] ?? ''));
        $result = $this->assistantService->respond($from, $text, $privileged, $metaMessageId);
        $reply = trim((string) ($result['reply'] ?? ''));

        foreach ($this->chunks($reply, (int) config('services.whatsapp.ai.max_reply_chars', 3600)) as $chunk) {
            $this->cloudService->sendText($from, $chunk);
        }

        $document = $result['document'] ?? null;

        if (is_array($document) && !empty($document['path'])) {
            $this->cloudService->sendDocumentFromPath(
                $from,
                (string) $document['path'],
                (string) ($document['filename'] ?? basename((string) $document['path'])),
                (string) ($document['caption'] ?? 'Documento generado por el asistente.'),
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            );
        }
    }

    protected function chunks(string $text, int $limit): array
    {
        $text = trim($text);
        $limit = max(500, $limit);

        if ($text === '') {
            return [];
        }

        if (mb_strlen($text, 'UTF-8') <= $limit) {
            return [$text];
        }

        $chunks = [];

        while (mb_strlen($text, 'UTF-8') > $limit) {
            $slice = mb_substr($text, 0, $limit, 'UTF-8');
            $breakAt = max(
                (int) mb_strrpos($slice, "\n", 0, 'UTF-8'),
                (int) mb_strrpos($slice, '. ', 0, 'UTF-8')
            );

            if ($breakAt < 250) {
                $breakAt = $limit;
            }

            $chunks[] = trim(mb_substr($text, 0, $breakAt, 'UTF-8'));
            $text = trim(mb_substr($text, $breakAt, null, 'UTF-8'));
        }

        if ($text !== '') {
            $chunks[] = $text;
        }

        return $chunks;
    }

    protected function hasValidSignature(Request $request): bool
    {
        $secret = (string) config('services.whatsapp.app_secret', '');

        if ($secret === '') {
            return true;
        }

        $signature = (string) $request->header('X-Hub-Signature-256', '');

        if ($signature === '') {
            return false;
        }

        $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

        return hash_equals($expected, $signature);
    }
}
