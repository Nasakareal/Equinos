<?php

namespace App\Services\WhatsAppAi;

use App\Models\WhatsAppAiProfile;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class OficioDocumentService
{
    public function create(array $oficio, string $phone): ?array
    {
        $body = $this->paragraphs($oficio['cuerpo'] ?? $oficio['body'] ?? null);

        if (empty($body)) {
            return null;
        }

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection([
            'marginTop' => 1100,
            'marginRight' => 1100,
            'marginBottom' => 1100,
            'marginLeft' => 1100,
        ]);

        $this->addLetterhead($section, $phone);

        $folio = trim((string) ($oficio['folio'] ?? 'OFICIO GENERADO POR ASISTENTE'));
        $section->addText($folio, ['bold' => true], ['alignment' => Jc::RIGHT]);
        $section->addText('Morelia, Michoacan, a ' . now()->format('d/m/Y'), [], ['alignment' => Jc::RIGHT]);
        $section->addTextBreak(1);

        $destinatario = trim((string) ($oficio['destinatario'] ?? 'A QUIEN CORRESPONDA'));
        $cargo = trim((string) ($oficio['cargo_destinatario'] ?? ''));
        $section->addText(mb_strtoupper($destinatario, 'UTF-8'), ['bold' => true]);

        if ($cargo !== '') {
            $section->addText(mb_strtoupper($cargo, 'UTF-8'), ['bold' => true]);
        }

        $section->addText('PRESENTE.', ['bold' => true]);
        $section->addTextBreak(1);

        $asunto = trim((string) ($oficio['asunto'] ?? ''));

        if ($asunto !== '') {
            $section->addText('ASUNTO: ' . $asunto, ['bold' => true], ['alignment' => Jc::RIGHT]);
            $section->addTextBreak(1);
        }

        foreach ($body as $paragraph) {
            $section->addText($paragraph, [], [
                'alignment' => Jc::BOTH,
                'spaceAfter' => 180,
            ]);
        }

        $cierre = trim((string) ($oficio['cierre'] ?? 'Sin otro particular, reciba un cordial saludo.'));

        if ($cierre !== '') {
            $section->addTextBreak(1);
            $section->addText($cierre, [], ['alignment' => Jc::BOTH]);
        }

        $section->addTextBreak(2);
        $section->addText('ATENTAMENTE', ['bold' => true], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(2);

        $firmaNombre = trim((string) ($oficio['firma_nombre'] ?? config('services.whatsapp.ai.signature_name', '')));
        $firmaCargo = trim((string) ($oficio['firma_cargo'] ?? config('services.whatsapp.ai.signature_position', '')));

        if ($firmaNombre !== '') {
            $section->addText(mb_strtoupper($firmaNombre, 'UTF-8'), ['bold' => true], ['alignment' => Jc::CENTER]);
        }

        if ($firmaCargo !== '') {
            $section->addText(mb_strtoupper($firmaCargo, 'UTF-8'), [], ['alignment' => Jc::CENTER]);
        }

        $directory = storage_path('app/whatsapp_ai/oficios');

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $baseName = $this->safeFilename($oficio['filename'] ?? $asunto ?: 'oficio');
        $filename = now()->format('Ymd_His') . '_' . $baseName . '.docx';
        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        IOFactory::createWriter($phpWord, 'Word2007')->save($path);

        return [
            'path' => $path,
            'filename' => $filename,
            'caption' => trim((string) ($oficio['caption'] ?? 'Oficio generado por el asistente.')),
        ];
    }

    protected function paragraphs($value): array
    {
        if (is_array($value)) {
            $items = $value;
        } else {
            $items = preg_split('/\R{2,}|\R/', (string) $value);
        }

        $paragraphs = [];

        foreach ((array) $items as $item) {
            $text = trim((string) $item);

            if ($text !== '') {
                $paragraphs[] = $text;
            }
        }

        return $paragraphs;
    }

    protected function addLetterhead($section, string $phone): void
    {
        $phone = (string) preg_replace('/\D+/', '', $phone);
        $profile = WhatsAppAiProfile::query()->where('phone', $phone)->first();
        $letterhead = $profile ? trim((string) $profile->oficio_letterhead_text) : '';

        if ($letterhead === '') {
            $section->addText('GUARDIA CIVIL', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
            $section->addText('AGRUPAMIENTO DE EQUINOS Y CANINOS', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
            $section->addTextBreak(1);
            return;
        }

        foreach ($this->paragraphs($letterhead) as $line) {
            $section->addText($line, ['bold' => true, 'size' => 11], ['alignment' => Jc::CENTER]);
        }

        $section->addTextBreak(1);
    }

    protected function safeFilename(string $value): string
    {
        $value = trim($value);
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

        if ($ascii !== false) {
            $value = $ascii;
        }

        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '_', $value);
        $value = trim((string) $value, '_');

        if ($value === '') {
            $value = 'oficio';
        }

        return substr($value, 0, 80);
    }
}
