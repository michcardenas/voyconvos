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

    public function __construct()
    {
        // Obtener configuraciones
        $this->clientId = config('services.uala.client_id') ?? env('UALA_CLIENT_ID');
        $this->clientSecret = config('services.uala.client_secret') ?? env('UALA_CLIENT_SECRET');
        $this->username = config('services.uala.username') ?? env('UALA_USERNAME');
        $this->isDev = config('services.uala.is_dev', true); // true para staging

        // Validar configuraciones
        $this->validateConfig();

        // Configurar el SDK de Uala
        $this->setupUalaSDK();
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
     * Configurar el SDK oficial de Uala
     */
    private function setupUalaSDK(): void
    {
        try {
            Log::info('=== CONFIGURANDO SDK UALA ===', [
                'username' => $this->username,
                'client_id' => $this->clientId,
                'is_dev' => $this->isDev
            ]);

            UalaSDK::setUp([
                'userName' => $this->username,
                'clientId' => $this->clientId,
                'clientSecret' => $this->clientSecret,
                'isDev' => $this->isDev, // true para staging, false para producción
            ]);

            Log::info('=== SDK UALA CONFIGURADO EXITOSAMENTE ===');

        } catch (\Exception $e) {
            Log::error('=== ERROR CONFIGURANDO SDK UALA ===', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception("Error configurando SDK de Uala: " . $e->getMessage());
        }
    }

    /**
     * Crear checkout usando el SDK oficial de Uala
     * Mantiene la misma interfaz que teníamos antes
     */
    public function createCheckout(array $checkoutData): array
    {
        try {
            // Convertir datos al formato del SDK
            $orderData = $this->prepareOrderDataForSDK($checkoutData);
            
            Log::info('=== CREANDO ORDEN CON SDK UALA ===', [
                'order_data' => $orderData
            ]);

            // Crear orden usando el SDK oficial
            $order = UalaSDK::createOrder($orderData);

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
     * Preparar datos para el SDK oficial de Uala
     */
    private function prepareOrderDataForSDK(array $checkoutData): array
    {
        return [
            'amount' => $checkoutData['amount']['value'],
            'description' => $checkoutData['description'],
            'externalReference' => $checkoutData['external_reference'] ?? null,
            'callbackSuccess' => $checkoutData['callback_urls']['success'],
            'callbackFail' => $checkoutData['callback_urls']['failure'],
            // El SDK puede tener otros campos, verificar documentación
        ];
    }

    /**
     * Normalizar respuesta del SDK al formato que espera nuestro código
     */
    private function normalizeResponse($order): array
    {
        // Adaptar la respuesta del SDK al formato que espera nuestro código
        return [
            'id' => $order['uuid'] ?? $order['id'] ?? null,
            'payment_url' => $order['checkoutUrl'] ?? $order['checkout_url'] ?? null,
            'checkout_url' => $order['checkoutUrl'] ?? $order['checkout_url'] ?? null,
            'external_reference' => $order['externalReference'] ?? $order['external_reference'] ?? null,
            'status' => $order['status'] ?? 'pending',
            'uuid' => $order['uuid'] ?? null,
            // Incluir toda la respuesta original por si necesitamos algo más
            'original_response' => $order
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
            
            $order = UalaSDK::getOrder($uuid);
            
            Log::info('=== ORDEN OBTENIDA EXITOSAMENTE ===', ['order' => $order]);
            
            return $order;
            
        } catch (\Exception $e) {
            Log::error('=== ERROR OBTENIENDO ORDEN UALA ===', [
                'message' => $e->getMessage(),
                'uuid' => $uuid
            ]);
            
            throw new \Exception("Error obteniendo orden de Uala: " . $e->getMessage());
        }
    }

    /**
     * Obtener lista de órdenes (funcionalidad adicional del SDK)
     */
    public function getOrders(array $params = []): array
    {
        try {
            Log::info('=== OBTENIENDO LISTA DE ÓRDENES UALA ===', ['params' => $params]);
            
            $orders = UalaSDK::getOrders($params);
            
            Log::info('=== ÓRDENES OBTENIDAS EXITOSAMENTE ===');
            
            return $orders;
            
        } catch (\Exception $e) {
            Log::error('=== ERROR OBTENIENDO ÓRDENES UALA ===', [
                'message' => $e->getMessage(),
                'params' => $params
            ]);
            
            throw new \Exception("Error obteniendo órdenes de Uala: " . $e->getMessage());
        }
    }
}