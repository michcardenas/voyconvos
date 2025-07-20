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

            // DEBUGGING PROFUNDO - Ver exactamente qué devuelve
            Log::info('=== DEBUGGING RESPUESTA RAW DEL SDK ===', [
                'type' => gettype($order),
                'is_object' => is_object($order),
                'is_array' => is_array($order),
                'is_string' => is_string($order),
                'class_name' => is_object($order) ? get_class($order) : 'N/A',
                'var_dump' => var_export($order, true),
                'json_encode' => json_encode($order),
                'print_r' => print_r($order, true)
            ]);

            // Si es un objeto, mostrar sus propiedades
            if (is_object($order)) {
                $reflection = new \ReflectionObject($order);
                $properties = [];
                foreach ($reflection->getProperties() as $property) {
                    $property->setAccessible(true);
                    try {
                        $properties[$property->getName()] = $property->getValue($order);
                    } catch (\Exception $e) {
                        $properties[$property->getName()] = 'Error: ' . $e->getMessage();
                    }
                }
                
                Log::info('=== PROPIEDADES DEL OBJETO ORDEN ===', [
                    'properties' => $properties,
                    'public_vars' => get_object_vars($order)
                ]);
            }

            // Intentar diferentes métodos de acceso a los datos
            $extractedData = $this->extractOrderData($order);

            Log::info('=== DATOS EXTRAÍDOS DE LA ORDEN ===', [
                'extracted_data' => $extractedData
            ]);

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
     * Intentar extraer datos de la orden usando diferentes métodos
     */
    private function extractOrderData($order): array
    {
        $data = [];

        try {
            // Método 1: Tratar como array
            if (is_array($order)) {
                $data['as_array'] = $order;
            }

            // Método 2: Tratar como objeto con propiedades públicas
            if (is_object($order)) {
                $data['object_vars'] = get_object_vars($order);
                
                // Método 3: Intentar acceder a propiedades comunes
                $commonProps = ['uuid', 'id', 'checkoutUrl', 'checkout_url', 'url', 'link', 'status', 'externalReference'];
                foreach ($commonProps as $prop) {
                    if (isset($order->$prop)) {
                        $data['property_' . $prop] = $order->$prop;
                    }
                }

                // Método 4: Si tiene método toArray()
                if (method_exists($order, 'toArray')) {
                    $data['to_array'] = $order->toArray();
                }

                // Método 5: Si tiene método getData()
                if (method_exists($order, 'getData')) {
                    $data['get_data'] = $order->getData();
                }

                // Método 6: Si tiene método getAttributes()
                if (method_exists($order, 'getAttributes')) {
                    $data['get_attributes'] = $order->getAttributes();
                }
            }

            // Método 7: Convertir a string y ver si es JSON
            $stringRep = (string) $order;
            if (!empty($stringRep) && $stringRep !== '[]') {
                $data['string_representation'] = $stringRep;
                $decodedJson = json_decode($stringRep, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['decoded_json'] = $decodedJson;
                }
            }

        } catch (\Exception $e) {
            $data['extraction_error'] = $e->getMessage();
        }

        return $data;
    }

    /**
     * Normalizar respuesta del SDK al formato que espera nuestro código
     */
    private function normalizeResponse($order): array
    {
        // Extraer datos usando el método mejorado
        $extractedData = $this->extractOrderData($order);

        Log::info('=== NORMALIZANDO RESPUESTA UALA ===', [
            'raw_response' => $order,
            'extracted_data' => $extractedData
        ]);

        // Intentar encontrar los datos en diferentes lugares
        $normalizedData = [
            'id' => null,
            'payment_url' => null,
            'checkout_url' => null,
            'external_reference' => null,
            'status' => 'pending',
            'uuid' => null,
            'original_response' => $order,
            'extracted_data' => $extractedData
        ];

        // Si encontramos datos extraídos, usarlos
        if (!empty($extractedData)) {
            foreach ($extractedData as $method => $data) {
                if (is_array($data)) {
                    // Buscar campos conocidos
                    if (isset($data['uuid'])) $normalizedData['uuid'] = $data['uuid'];
                    if (isset($data['id'])) $normalizedData['id'] = $data['id'];
                    if (isset($data['checkoutUrl'])) $normalizedData['payment_url'] = $data['checkoutUrl'];
                    if (isset($data['checkout_url'])) $normalizedData['checkout_url'] = $data['checkout_url'];
                    if (isset($data['url'])) $normalizedData['payment_url'] = $data['url'];
                    if (isset($data['link'])) $normalizedData['payment_url'] = $data['link'];
                    if (isset($data['status'])) $normalizedData['status'] = $data['status'];
                    if (isset($data['externalReference'])) $normalizedData['external_reference'] = $data['externalReference'];
                }
            }
        }

        return $normalizedData;
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