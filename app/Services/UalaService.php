<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class UalaService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $username;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.uala.base_url');
        $this->clientId = config('services.uala.client_id');
        $this->clientSecret = config('services.uala.client_secret');
        $this->username = config('services.uala.username');
        $this->timeout = config('services.uala.timeout', 30);

        // Validar que las configuraciones estén presentes
        $this->validateConfig();
    }

    /**
     * Validar que todas las configuraciones necesarias estén presentes
     */
    private function validateConfig(): void
    {
        $requiredConfigs = [
            'base_url' => $this->baseUrl,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => $this->username,
        ];

        foreach ($requiredConfigs as $key => $value) {
            if (empty($value)) {
                throw new \Exception("Configuración de Uala faltante: {$key}");
            }
        }
    }

    /**
     * Obtener token de acceso de Uala con cache
     */
    public function getAccessToken(): string
    {
        // Intentar obtener el token del cache primero
        $cacheKey = 'uala_access_token';
        $cachedToken = Cache::get($cacheKey);

        if ($cachedToken) {
            Log::info('=== UALA TOKEN DESDE CACHE ===');
            return $cachedToken;
        }

        // Si no hay token en cache, solicitar uno nuevo
        return $this->requestNewAccessToken();
    }

    /**
     * Solicitar un nuevo token de acceso
     */
    private function requestNewAccessToken(): string
    {
        try {
            Log::info('=== SOLICITANDO NUEVO TOKEN UALA ===', [
                'url' => $this->baseUrl . '/v2/api/auth/token',
                'client_id' => $this->clientId,
                'username' => $this->username,
            ]);

            $response = Http::timeout($this->timeout)
                ->post($this->baseUrl . '/v2/api/auth/token', [
                    'username' => $this->username,
                    'client_id' => $this->clientId,
                    'client_secret_id' => $this->clientSecret,
                    'grant_type' => 'client_credentials'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $accessToken = $data['access_token'];
                $expiresIn = $data['expires_in'] ?? 86400; // 24 horas por defecto

                // Guardar en cache por un tiempo menor al de expiración (90% del tiempo)
                $cacheTime = (int) ($expiresIn * 0.9);
                Cache::put('uala_access_token', $accessToken, now()->addSeconds($cacheTime));

                Log::info('=== TOKEN UALA OBTENIDO EXITOSAMENTE ===', [
                    'expires_in' => $expiresIn,
                    'cached_for_seconds' => $cacheTime,
                ]);

                return $accessToken;
            }

            // Error en la respuesta
            $errorBody = $response->body();
            Log::error('=== ERROR OBTENIENDO TOKEN UALA ===', [
                'status' => $response->status(),
                'response' => $errorBody,
                'headers' => $response->headers(),
            ]);

            throw new \Exception("Error obteniendo token de Uala (HTTP {$response->status()}): {$errorBody}");

        } catch (\Exception $e) {
            Log::error('=== EXCEPCIÓN AL OBTENER TOKEN UALA ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // Limpiar cache en caso de error
            Cache::forget('uala_access_token');
            
            throw new \Exception("Error de conexión con Uala: " . $e->getMessage());
        }
    }

    /**
     * Crear checkout en Uala
     */
    public function createCheckout(array $checkoutData): array
    {
        try {
            $accessToken = $this->getAccessToken();
            
            Log::info('=== CREANDO CHECKOUT UALA ===', [
                'checkout_data' => $checkoutData,
                'endpoint' => $this->baseUrl . '/v2/api/checkout',
            ]);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . '/v2/api/checkout', $checkoutData);

            if ($response->successful()) {
                $checkoutResponse = $response->json();
                
                Log::info('=== CHECKOUT UALA CREADO EXITOSAMENTE ===', [
                    'response' => $checkoutResponse,
                ]);

                return $checkoutResponse;
            }

            // Error en la respuesta
            $errorBody = $response->body();
            Log::error('=== ERROR CREANDO CHECKOUT UALA ===', [
                'status' => $response->status(),
                'response' => $errorBody,
                'headers' => $response->headers(),
                'request_data' => $checkoutData,
            ]);

            throw new \Exception("Error creando checkout en Uala (HTTP {$response->status()}): {$errorBody}");

        } catch (\Exception $e) {
            Log::error('=== EXCEPCIÓN CREANDO CHECKOUT UALA ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'checkout_data' => $checkoutData,
            ]);

            throw new \Exception("Error procesando checkout con Uala: " . $e->getMessage());
        }
    }

    /**
     * Preparar datos de checkout desde una reserva
     */
    public function prepareCheckoutData($reserva, $viaje): array
    {
        return [
            'amount' => [
                'value' => (float) $reserva->total,
                'currency' => 'ARS'
            ],
            'description' => substr(
                "Viaje de " . ($viaje->origen_direccion ?? 'origen') . " a " . ($viaje->destino_direccion ?? 'destino'),
                0,
                255
            ),
            'external_reference' => "RESERVA_" . $reserva->id,
            'callback_urls' => [
                'success' => route('pasajero.pago.success', $reserva->id),
                'failure' => route('pasajero.pago.failure', $reserva->id),
                'pending' => route('pasajero.pago.pending', $reserva->id),
            ],
            'payer' => [
                'email' => auth()->user()->email,
                'name' => auth()->user()->name,
            ],
            'items' => [
                [
                    'name' => "Reserva de {$reserva->cantidad_puestos} puesto(s)",
                    'quantity' => (int) $reserva->cantidad_puestos,
                    'unit_price' => (float) $reserva->precio_por_persona,
                ]
            ],
            'metadata' => [
                'viaje_id' => $viaje->id,
                'reserva_id' => $reserva->id,
                'user_id' => auth()->id(),
            ]
        ];
    }

    /**
     * Limpiar cache de token (útil para testing o troubleshooting)
     */
    public function clearTokenCache(): void
    {
        Cache::forget('uala_access_token');
        Log::info('=== CACHE DE TOKEN UALA LIMPIADO ===');
    }
}