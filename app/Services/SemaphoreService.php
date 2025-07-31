<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SemaphoreService {
    protected $apiKey;
    protected $baseOtpUrl;

    public function __construct(){
        $this->apiKey = config('services.semaphore.key');
        $this->baseOtpUrl = 'https://api.semaphore.co/api/v4/otp'; 
    }

    public function sendOtp($number, $message, $customOtp = null){
        $payload  =  [
            'apikey' => $this->apiKey,
            'number' => $number,
            'message' => $message,
        ];

        if ($customOtp) {
            $payload['code'] = $customOtp; 
        }

        $response = Http::asForm()->post($this->baseOtpUrl, $payload);

        return $response->json();
    }
}