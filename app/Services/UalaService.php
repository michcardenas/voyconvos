<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Uala\SDK as UalaSDK;

class UalaService
{
    private ?string $clientId;
    private ?string $clientSecret;
    private ?string $username;
    private bool $isDev;
    private UalaSDK $sdk;

    public function __construct()
    {
        // Obtener configuraciones
        $this->clientId = config('services.uala.client_id') ?? env('UALA_CLIENT_ID');
        $this->clientSecret = config('services.uala.client_secret') ?? env('UALA_CLIENT_SECRET');
        $this->username = config('services.uala.username') ?? env('UALA_USERNAME');
        $this->isDev = config('services.uala.is_dev', true); // true para staging

        // Validar configuraciones
        $this->validateConfig();

        // Crear instancia del SDK de Uala
        $this->initializeUalaSDK();
    }

    /**
     * Validar que todas las configuraciones necesarias estén presentes
     */
    private function validateConfig(): void
    {
        $requiredConfigs = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => $this->username,
        ];

        foreach ($requiredConfigs as $key => $value) {
            if (empty($value)) {
                throw new \Exception("Configuración de Uala faltante: {$key}. Verifica tu archivo .env");
            }
        }
    }

    /**
     * Inicializar el SDK oficial de Uala
     */
    private function initializeUalaSDK(): void
    {
        try {
            Log::info('=== INICIALIZANDO SDK UALA ===', [
                'username' => $this->username,
                'client_id' => $this->clientId,
                'is_dev' => $this->isDev
            ]);

            // Crear instancia del SDK según la documentación oficial
            $this->sdk = new UalaSDK(
                $this->username,
                $this->clientId,
                $this->clientSecret,
                $this->isDev
            );

            Log::info('=== SDK UALA INICIALIZADO EXITOSAMENTE ===');

        } catch (\Exception $e) {
            Log::error('=== ERROR INICIALIZANDO SDK UALA ===', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception("Error inicializando SDK de Uala: " . $e->getMessage());
        }
    }

    /**
     * Crear checkout usando el SDK oficial de Uala
     * Mantiene la misma interfaz que teníamos antes
     */
    public function createCheckout(array $checkoutData): array
    {
        try {
            Log::info('=== CREANDO ORDEN CON SDK UALA ===', [
                'checkout_data' => $checkoutData
            ]);

            // Según la documentación: createOrder(amount, description, callbackSuccess, callbackFail)
            $amount = $checkoutData['amount']['value'];
            $description = $checkoutData['description'];
            $callbackSuccess = $checkoutData['callback_urls']['success'];
            $callbackFail = $checkoutData['callback_urls']['failure'];

            Log::info('=== PARÁMETROS PARA CREAR ORDEN ===', [
                'amount' => $amount,
                'description' => $description,
                'callbackSuccess' => $callbackSuccess,
                'callbackFail' => $callbackFail
            ]);

            // Crear orden usando el SDK oficial
            $order = $this->sdk->createOrder($amount, $description, $callbackSuccess, $callbackFail);

            Log::info('=== ORDEN UALA CREADA EXITOSAMENTE ===', [
                'response' => $order
            ]);

            // Convertir respuesta al formato que espera nuestro código
            return $this->normalizeResponse($order);

        } catch (\Exception $e) {
            Log::error('=== ERROR CREANDO ORDEN CON SDK UALA ===', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'checkout_data' => $checkoutData
            ]);

            throw new \Exception("Error procesando orden con Uala SDK: " . $e->getMessage());
        }
    }

    /**
     * Normalizar respuesta del SDK al formato que espera nuestro código
     */
    private function normalizeResponse($order): array
    {
        // El SDK retorna un objeto, convertirlo a array si es necesario
        $orderArray = is_object($order) ? (array) $order : $order;

        Log::info('=== NORMALIZANDO RESPUESTA UALA ===', [
            'raw_response' => $orderArray
        ]);

        // Adaptar la respuesta del SDK al formato que espera nuestro código
        return [
            'id' => $orderArray['uuid'] ?? $orderArray['id'] ?? null,
            'payment_url' => $orderArray['checkoutUrl'] ?? $orderArray['checkout_url'] ?? null,
            'checkout_url' => $orderArray['checkoutUrl'] ?? $orderArray['checkout_url'] ?? null,
            'external_reference' => $orderArray['externalReference'] ?? $orderArray['external_reference'] ?? null,
            'status' => $orderArray['status'] ?? 'pending',
            'uuid' => $orderArray['uuid'] ?? null,
            // Incluir toda la respuesta original por si necesitamos algo más
            'original_response' => $orderArray
        ];
    }

    /**
     * Preparar datos de checkout desde una reserva
     * Mantiene la misma interfaz que teníamos antes
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
     * Obtener información de una orden (funcionalidad adicional del SDK)
     */
    public function getOrder(string $uuid): array
    {
        try {
            Log::info('=== OBTENIENDO ORDEN UALA ===', ['uuid' => $uuid]);
            
            $order = $this->sdk->getOrder($uuid);
            
            Log::info('=== ORDEN OBTENIDA EXITOSAMENTE ===', ['order' => $order]);
            
            return is_object($order) ? (array) $order : $order;
            
        } catch (\Exception $e) {
            Log::error('=== ERROR OBTENIENDO ORDEN UALA ===', [
                'message' => $e->getMessage(),
                'uuid' => $uuid
            ]);
            
            throw new \Exception("Error obteniendo orden de Uala: " . $e->getMessage());
        }
    }
}