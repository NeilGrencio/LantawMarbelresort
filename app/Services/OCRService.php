<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OCRService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.ocrspace.url');
        $this->apiKey = config('services.ocrspace.key');
    }

    public function extractText($filePath, $language = 'eng')
    {
        $response = Http::asMultipart()->post($this->apiUrl, [
            'apikey'   => $this->apiKey,
            'file'     => fopen($filePath, 'r'),
            'language' => $language,
        ]);

        if ($response->failed()) {
            throw new \Exception('OCR request failed: ' . $response->body());
        }

        $data = $response->json();

        //dd($data);

        return $data['ParsedResults'][0]['ParsedText'] ?? '';
    }


    public function verify($filePath)
    {
        $ocrText = $this->extractText($filePath);

        $requiredHeaders = [
            'REPUBLIKA NG PILIPINAS',
            'Republic of the Philippines',
            'PAMBANSANG PAGKAKAKILANLAN',
            'Philippine Identification',
            'Philippine Identification Card'
        ];

        $headerFound = false;
        foreach ($requiredHeaders as $header) {
            if (stripos($ocrText, $header) !== false) {
                $headerFound = true;
                break;
            }
        }

        $normalizedText = preg_replace('/[^\d]/', '', $ocrText);

        // Check for a 16-digit PCN (dashes optional in OCR)
        $pcnFound = preg_match('/\d{16}/', $normalizedText);

        // Optionally, format the PCN with dashes for consistency
        $formattedPCN = preg_replace('/(\d{4})(\d{4})(\d{4})(\d{4})/', '$1-$2-$3-$4', $normalizedText);

        return [
            'isValid' => $headerFound && $pcnFound,
            'ocrText' => $ocrText,
        ];
    }
}
