<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExternalApiController extends Controller
{
    public function submitAssessment(Request $request)
    {
        $timestamp = now()->timestamp * 1000;

        $nonce = Str::random(16);

        $secretKey = 'ABC123xyz';
        $signatureString = $nonce . $timestamp . $secretKey;
        $apiSignature = hash('sha256', $signatureString);

        $payload = [
            'timestamp' => $timestamp
        ];

        try {
            $response = Http::withHeaders([
                'X-Nonce' => $nonce,
                'X-API-Signature' => $apiSignature,
            ])->post('https://unisync.alphagames.my.id/api/assessment', $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'data' => $response->json()
                ]);
            } else {
                Log::error('API Request Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'status' => false,
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
