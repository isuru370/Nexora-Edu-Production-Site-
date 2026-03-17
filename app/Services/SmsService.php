<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected string $baseUrl;
    protected string $userId;
    protected string $apiKey;
    protected string $senderId;

    public function __construct()
    {
        $this->baseUrl = 'https://smsapi.chatbiz.net/v1';
        $this->userId = config('services.sms.user_id');
        $this->apiKey = config('services.sms.api_key');
        $this->senderId = config('services.sms.sender_id');
    }

    // Send Single SMS
    public function sendSms($recipient, $message)
    {
        try {
            $recipient = $this->formatNumber($recipient);

            $response = Http::timeout(10)->get("{$this->baseUrl}/send", [
                'user_id' => $this->userId,
                'api_key' => $this->apiKey,
                'sender_id' => $this->senderId,
                'recipient_contact_no' => $recipient,
                'message' => $message,
            ]);

            // Debug return (remove later if needed)
            return [
                'http_status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    // Bulk SMS
    public function sendBulkSms($numbers, $message, $campaign = 'LaravelCampaign')
    {
        try {
            $formattedNumbers = [];

            foreach ($numbers as $number) {
                $formattedNumbers[] = $this->formatNumber($number);
            }

            $response = Http::asForm()->timeout(20)->post("{$this->baseUrl}/bulk/", [
                'user_id' => $this->userId,
                'api_key' => $this->apiKey,
                'sender_id' => $this->senderId,
                'campaign_name' => $campaign,
                'message' => $message,
                'recipient_contact_no' => implode(',', $formattedNumbers),
            ]);

            return $response->json();
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    // Send OTP
    public function sendOtp($number)
    {
        $otp = random_int(100000, 999999);

        $message = "Your verification code is: {$otp}";

        $response = $this->sendSms($number, $message);

        return [
            'otp' => $otp,
            'sms_response' => $response,
        ];
    }

    // Get Balance
    public function getBalance()
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/getBalance/", [
                'user_id' => $this->userId,
                'api_key' => $this->apiKey,
            ]);

            return $response->json();
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    // Format Sri Lanka numbers
    private function formatNumber($number)
    {
        $number = preg_replace('/\s+/', '', trim($number));

        if (str_starts_with($number, '0')) {
            return '94' . substr($number, 1);
        }

        if (str_starts_with($number, '+94')) {
            return substr($number, 1);
        }

        return $number;
    }
}
