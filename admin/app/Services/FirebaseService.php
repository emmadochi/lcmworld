<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    /**
     * Send a Push Notification via Firebase Cloud Messaging HTTP v1 API.
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendNotification(string $topic, string $title, string $body, array $data = []): bool
    {
        $credentialsPath = storage_path('app/firebase-service-account.json');

        if (!file_exists($credentialsPath)) {
            Log::warning('Firebase service account file missing: ' . $credentialsPath);
            return false;
        }

        try {
            $credentials = json_decode(file_get_contents($credentialsPath), true);
            $projectId = $credentials['project_id'] ?? null;

            if (!$projectId) {
                Log::error('Firebase project_id missing in service account file');
                return false;
            }

            $accessToken = $this->getAccessToken($credentials);
            if (!$accessToken) {
                return false;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $response = Http::withToken($accessToken)
                ->post($url, [
                    'message' => [
                        'topic' => $topic,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'data' => $data,
                    ]
                ]);

            if ($response->successful()) {
                Log::info("FCM Notification sent to topic {$topic}");
                return true;
            } else {
                Log::error("FCM Notification failed: " . $response->body());
                return false;
            }

        } catch (\Exception $e) {
            Log::error("FCM Exception: " . $e->getMessage());
            return false;
        }
    }

    private function getAccessToken($credentials)
    {
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $now = time();
        $payload = json_encode([
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signatureInput = $base64UrlHeader . '.' . $base64UrlPayload;

        openssl_sign($signatureInput, $signature, $credentials['private_key'], 'sha256WithRSAEncryption');
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $signatureInput . '.' . $base64UrlSignature;

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        Log::error('Failed to get FCM OAuth token: ' . $response->body());
        return null;
    }
}
