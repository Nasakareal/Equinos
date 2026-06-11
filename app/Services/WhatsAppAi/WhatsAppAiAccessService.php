<?php

namespace App\Services\WhatsAppAi;

class WhatsAppAiAccessService
{
    public function isAllowed(string $phone): bool
    {
        $phone = $this->normalizePhone($phone);

        if ($phone === '') {
            return false;
        }

        return in_array($phone, $this->allowedNumbers(), true);
    }

    public function isPrivileged(string $phone): bool
    {
        $phone = $this->normalizePhone($phone);

        if ($phone === '') {
            return false;
        }

        return in_array($phone, $this->privilegedNumbers(), true);
    }

    public function allowedNumbers(): array
    {
        $numbers = (array) config('services.whatsapp.ai.allowed_numbers', []);
        $numbers[] = (string) config('services.whatsapp.ai.fernanda_number', '');

        return $this->normalizeList($numbers);
    }

    public function privilegedNumbers(): array
    {
        $numbers = (array) config('services.whatsapp.ai.privileged_numbers', []);
        $numbers[] = (string) config('services.whatsapp.ai.fernanda_number', '');

        return $this->normalizeList($numbers);
    }

    public function normalizePhone(?string $value): string
    {
        return (string) preg_replace('/\D+/', '', (string) $value);
    }

    protected function normalizeList(array $values): array
    {
        $phones = [];

        foreach ($values as $value) {
            $phone = $this->normalizePhone((string) $value);

            if ($phone !== '') {
                $phones[] = $phone;

                foreach ($this->mexicoVariants($phone) as $variant) {
                    $phones[] = $variant;
                }
            }
        }

        return array_values(array_unique($phones));
    }

    protected function mexicoVariants(string $phone): array
    {
        if (preg_match('/^521(\d{10})$/', $phone, $matches)) {
            return ['52' . $matches[1]];
        }

        if (preg_match('/^52(\d{10})$/', $phone, $matches)) {
            return ['521' . $matches[1]];
        }

        return [];
    }
}
