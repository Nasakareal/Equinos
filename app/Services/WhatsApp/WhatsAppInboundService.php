<?php

namespace App\Services\WhatsApp;

class WhatsAppInboundService
{
    public function extractMessages(array $payload): array
    {
        $messages = [];

        foreach ((array) ($payload['entry'] ?? []) as $entry) {
            foreach ((array) ($entry['changes'] ?? []) as $change) {
                $value = (array) ($change['value'] ?? []);

                foreach ((array) ($value['messages'] ?? []) as $message) {
                    if (is_array($message)) {
                        $messages[] = $message;
                    }
                }
            }
        }

        return $messages;
    }

    public function extractStatuses(array $payload): array
    {
        $statuses = [];

        foreach ((array) ($payload['entry'] ?? []) as $entry) {
            foreach ((array) ($entry['changes'] ?? []) as $change) {
                $value = (array) ($change['value'] ?? []);

                foreach ((array) ($value['statuses'] ?? []) as $status) {
                    if (is_array($status)) {
                        $statuses[] = $status;
                    }
                }
            }
        }

        return $statuses;
    }

    public function extractUserInput(array $message): array
    {
        $type = (string) ($message['type'] ?? '');

        if ($type === 'text') {
            return [
                'type' => 'text',
                'value' => trim((string) ($message['text']['body'] ?? '')),
            ];
        }

        if ($type === 'interactive') {
            $interactive = (array) ($message['interactive'] ?? []);
            $interactiveType = (string) ($interactive['type'] ?? '');

            if ($interactiveType === 'button_reply') {
                $reply = (array) ($interactive['button_reply'] ?? []);

                return [
                    'type' => 'interactive',
                    'value' => (string) ($reply['id'] ?? $reply['title'] ?? ''),
                    'title' => (string) ($reply['title'] ?? ''),
                ];
            }

            if ($interactiveType === 'list_reply') {
                $reply = (array) ($interactive['list_reply'] ?? []);

                return [
                    'type' => 'interactive',
                    'value' => (string) ($reply['id'] ?? $reply['title'] ?? ''),
                    'title' => (string) ($reply['title'] ?? ''),
                ];
            }
        }

        if ($type === 'button') {
            return [
                'type' => 'button',
                'value' => (string) ($message['button']['payload'] ?? $message['button']['text'] ?? ''),
            ];
        }

        return [
            'type' => $type ?: 'unknown',
            'value' => '',
        ];
    }
}
