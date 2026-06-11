<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppCloudService
{
    public function sendText(string $to, string $body): array
    {
        return $this->request([
            'messaging_product' => 'whatsapp',
            'to' => $this->normalizeTo($to),
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $body,
            ],
        ]);
    }

    public function sendTemplate(string $to, string $templateName, array $bodyParameters = [], string $language = 'es_MX'): array
    {
        $components = [];
        $parameters = [];

        foreach ($bodyParameters as $value) {
            $parameters[] = [
                'type' => 'text',
                'text' => (string) $value,
            ];
        }

        if (!empty($parameters)) {
            $components[] = [
                'type' => 'body',
                'parameters' => $parameters,
            ];
        }

        $template = [
            'name' => $templateName,
            'language' => [
                'code' => $language,
            ],
        ];

        if (!empty($components)) {
            $template['components'] = $components;
        }

        return $this->request([
            'messaging_product' => 'whatsapp',
            'to' => $this->normalizeTo($to),
            'type' => 'template',
            'template' => $template,
        ]);
    }

    public function sendDocument(string $to, string $mediaId, string $filename, ?string $caption = null): array
    {
        $document = [
            'id' => $mediaId,
            'filename' => $filename,
        ];

        if ($caption !== null && trim($caption) !== '') {
            $document['caption'] = $caption;
        }

        return $this->request([
            'messaging_product' => 'whatsapp',
            'to' => $this->normalizeTo($to),
            'type' => 'document',
            'document' => $document,
        ]);
    }

    public function sendDocumentFromPath(
        string $to,
        string $filePath,
        ?string $filename = null,
        ?string $caption = null,
        string $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ): array {
        $upload = $this->uploadMedia($filePath, $mimeType);

        if (!($upload['ok'] ?? false)) {
            $upload['stage'] = 'upload';
            return $upload;
        }

        $mediaId = $upload['body']['id'] ?? null;

        if (!$mediaId) {
            return [
                'ok' => false,
                'status' => $upload['status'] ?? 0,
                'body' => ['error' => 'Meta did not return a media id.'],
                'raw' => $upload['raw'] ?? null,
                'payload' => $upload['payload'] ?? null,
                'url' => $upload['url'] ?? null,
                'stage' => 'upload',
                'upload' => $upload,
            ];
        }

        $response = $this->sendDocument(
            $to,
            (string) $mediaId,
            $filename ?: basename($filePath),
            $caption
        );
        $response['upload'] = $upload;

        return $response;
    }

    public function uploadMedia(string $filePath, string $mimeType): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            return [
                'ok' => false,
                'status' => 0,
                'body' => ['error' => 'File does not exist or is not readable.'],
                'raw' => null,
                'payload' => ['file' => $filePath, 'type' => $mimeType],
                'url' => null,
            ];
        }

        [$graphVersion, $accessToken, $phoneNumberId] = $this->credentials();

        if ($accessToken === '' || $phoneNumberId === '') {
            Log::warning('WhatsApp Cloud media upload without credentials', [
                'file' => basename($filePath),
                'type' => $mimeType,
            ]);

            return [
                'ok' => false,
                'status' => 0,
                'body' => ['error' => 'Incomplete WhatsApp configuration.'],
                'raw' => null,
                'payload' => ['file' => $filePath, 'type' => $mimeType],
                'url' => null,
            ];
        }

        $url = "https://graph.facebook.com/{$graphVersion}/{$phoneNumberId}/media";
        $handle = fopen($filePath, 'r');

        if (!is_resource($handle)) {
            return [
                'ok' => false,
                'status' => 0,
                'body' => ['error' => 'Unable to open file for upload.'],
                'raw' => null,
                'payload' => ['file' => $filePath, 'type' => $mimeType],
                'url' => $url,
            ];
        }

        try {
            $response = Http::withToken($accessToken)
                ->attach('file', $handle, basename($filePath), ['Content-Type' => $mimeType])
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'type' => $mimeType,
                ]);
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }
        }

        Log::info('WhatsApp Cloud media response', [
            'file' => basename($filePath),
            'type' => $mimeType,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
            'raw' => $response->body(),
            'payload' => [
                'messaging_product' => 'whatsapp',
                'type' => $mimeType,
                'file' => basename($filePath),
            ],
            'url' => $url,
        ];
    }

    protected function request(array $payload): array
    {
        [$graphVersion, $accessToken, $phoneNumberId] = $this->credentials();

        if ($accessToken === '' || $phoneNumberId === '') {
            Log::warning('WhatsApp Cloud without credentials', [
                'to' => $payload['to'] ?? null,
                'type' => $payload['type'] ?? null,
            ]);

            return [
                'ok' => false,
                'status' => 0,
                'body' => ['error' => 'Incomplete WhatsApp configuration.'],
                'raw' => null,
                'payload' => $payload,
                'url' => null,
            ];
        }

        $url = "https://graph.facebook.com/{$graphVersion}/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->asJson()
            ->post($url, $payload);

        Log::info('WhatsApp Cloud response', [
            'to' => $payload['to'] ?? null,
            'type' => $payload['type'] ?? null,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
            'raw' => $response->body(),
            'payload' => $payload,
            'url' => $url,
        ];
    }

    protected function credentials(): array
    {
        return [
            (string) config('services.whatsapp.graph_version', 'v19.0'),
            (string) (config('services.whatsapp.token') ?: env('WHATSAPP_ACCESS_TOKEN')),
            (string) config('services.whatsapp.phone_number_id', ''),
        ];
    }

    protected function normalizeTo(string $to): string
    {
        return (string) preg_replace('/\D+/', '', $to);
    }
}
