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

        // 🔥 DEBUG DEL SDK ANTES DE LA LLAMADA
        Log::info('=== ESTADO DEL SDK ANTES DE CREATEORDER ===', [
            'sdk_class' => get_class($this->sdk),
            'sdk_methods' => get_class_methods($this->sdk),
            'sdk_properties' => get_object_vars($this->sdk),
            'sdk_is_authenticated' => method_exists($this->sdk, 'isAuthenticated') ? $this->sdk->isAuthenticated() : 'method_not_exist'
        ]);

        // 🚨 CAPTURAR CUALQUIER OUTPUT O ERROR DEL SDK
        ob_start();
        $errorOutput = '';
        
        // Capturar errores que el SDK pueda no estar reportando
        set_error_handler(function($severity, $message, $file, $line) use (&$errorOutput) {
            $errorOutput .= "Error: $message in $file:$line\n";
        });

        try {
            // Crear orden usando el SDK oficial
            Log::info('=== LLAMANDO $this->sdk->createOrder() ===');
            $order = $this->sdk->createOrder($amount, $description, $callbackSuccess, $callbackFail);
            Log::info('=== LLAMADA A createOrder() COMPLETADA ===');
            
        } catch (\Throwable $sdkException) {
            Log::error('=== EXCEPCIÓN INTERNA DEL SDK ===', [
                'exception_class' => get_class($sdkException),
                'message' => $sdkException->getMessage(),
                'code' => $sdkException->getCode(),
                'file' => $sdkException->getFile(),
                'line' => $sdkException->getLine(),
                'trace' => $sdkException->getTraceAsString()
            ]);
            throw $sdkException;
        }

        // Restaurar error handler
        restore_error_handler();
        $capturedOutput = ob_get_clean();

        // Log cualquier output capturado
        if (!empty($capturedOutput)) {
            Log::info('=== OUTPUT CAPTURADO DEL SDK ===', ['output' => $capturedOutput]);
        }
        
        if (!empty($errorOutput)) {
            Log::info('=== ERRORES CAPTURADOS DEL SDK ===', ['errors' => $errorOutput]);
        }

        // 🔥 ANÁLISIS DETALLADO DE LA RESPUESTA
        Log::info('=== ANÁLISIS COMPLETO DEL OBJETO ORDER ===', [
            'order_is_null' => is_null($order),
            'order_type' => gettype($order),
            'order_class' => is_object($order) ? get_class($order) : 'not_object',
            'order_as_array' => (array) $order,
            'object_vars' => is_object($order) ? get_object_vars($order) : 'not_object',
            'order_json_encode' => json_encode($order),
            'order_var_dump' => var_export($order, true),
            'order_print_r' => print_r($order, true)
        ]);

        // 🚨 VERIFICAR SI EL SDK TIENE MÉTODOS PARA OBTENER ERRORES
        if (is_object($this->sdk)) {
            $possibleErrorMethods = ['getLastError', 'getError', 'getErrors', 'getLastResponse', 'getResponse'];
            foreach ($possibleErrorMethods as $method) {
                if (method_exists($this->sdk, $method)) {
                    try {
                        $errorInfo = $this->sdk->$method();
                        Log::info("=== SDK->$method() ===", ['result' => $errorInfo]);
                    } catch (\Exception $e) {
                        Log::info("=== ERROR CALLING SDK->$method() ===", ['error' => $e->getMessage()]);
                    }
                }
            }
        }

        // 🚨 VERIFICAR PROPIEDADES OCULTAS O PRIVADAS
        $reflection = new \ReflectionObject($order);
        $allProperties = $reflection->getProperties();
        $propertyDetails = [];
        
        foreach ($allProperties as $property) {
            $property->setAccessible(true);
            $propertyDetails[$property->getName()] = [
                'visibility' => $property->isPublic() ? 'public' : ($property->isProtected() ? 'protected' : 'private'),
                'value' => $property->getValue($order)
            ];
        }
        
        Log::info('=== PROPIEDADES DEL OBJETO ORDER (INCLUYENDO PRIVADAS) ===', $propertyDetails);

        // Si el objeto está vacío, intentar alternativas
        if (empty((array) $order)) {
            Log::error('=== OBJETO ORDER ESTÁ VACÍO - VERIFICANDO ALTERNATIVAS ===');
            
            // Verificar si el SDK almacena la respuesta en alguna propiedad
            $sdkReflection = new \ReflectionObject($this->sdk);
            $sdkProperties = $sdkReflection->getProperties();
            $sdkPropertyDetails = [];
            
            foreach ($sdkProperties as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($this->sdk);
                $sdkPropertyDetails[$property->getName()] = [
                    'type' => gettype($value),
                    'value' => is_object($value) ? get_class($value) : $value
                ];
            }
            
            Log::info('=== PROPIEDADES DEL SDK DESPUÉS DE createOrder ===', $sdkPropertyDetails);
        }

        // Proceder con normalización (aunque esté vacío, para mantener el flujo)
        return $this->normalizeResponse($order);

    } catch (\Exception $e) {
        Log::error('=== ERROR CREANDO ORDEN CON SDK UALA ===', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'checkout_data' => $checkoutData,
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        throw new \Exception("Error procesando orden con Uala SDK: " . $e->getMessage());
    }
}

    /**
     * Normalizar respuesta del SDK al formato que espera nuestro código
     */
  private function normalizeResponse($order): array
{
    // 🚨 DETECCIÓN ESPECÍFICA DE OBJETO VACÍO
    $isEmpty = false;
    if (is_object($order)) {
        $objectVars = get_object_vars($order);
        $isEmpty = empty($objectVars);
        
        Log::info('=== VERIFICANDO SI OBJETO ESTÁ VACÍO ===', [
            'is_empty' => $isEmpty,
            'object_vars_count' => count($objectVars),
            'object_vars' => $objectVars,
            'json_encode' => json_encode($order),
            'array_cast' => (array) $order
        ]);
    }

    if ($isEmpty) {
        Log::error('=== ⚠️  OBJETO ORDER COMPLETAMENTE VACÍO ===', [
            'possible_causes' => [
                '1. SDK credentials incorrectas',
                '2. API de Uala rechazó la petición silenciosamente',  
                '3. Error en la configuración del environment',
                '4. Bug en el SDK de Uala',
                '5. Parámetros inválidos en createOrder()'
            ],
            'recommendations' => [
                'Verificar credenciales en .env',
                'Revisar documentación oficial de Uala',
                'Contactar soporte técnico de Uala',
                'Probar con datos diferentes'
            ]
        ]);
        
        // Retornar respuesta normalizada vacía pero válida
        return [
            'id' => null,
            'uuid' => null,
            'payment_url' => null,
            'checkout_url' => null,
            'external_reference' => null,
            'status' => 'error',
            'order_number' => null,
            'amount' => null,
            'currency' => null,
            'original_response' => [
                'id' => null,
                'uuid' => null,
                'orderNumber' => null,
                'status' => 'empty_response_from_sdk',
                'amount' => null,
                'currency' => null,
                'refNumber' => null,
                'checkoutLink' => null,
                'successCallback' => null,
                'failedCallback' => null,
                'error' => 'SDK devolvió objeto vacío'
            ]
        ];
    }

    // Si no está vacío, continuar con análisis normal
    Log::info('=== ANÁLISIS COMPLETO DEL OBJETO ORDER ===', [
        'order_type' => gettype($order),
        'order_class' => is_object($order) ? get_class($order) : 'not_object',
        'order_as_array' => (array) $order,
        'object_vars' => is_object($order) ? get_object_vars($order) : 'not_object',
        'order_json_encode' => json_encode($order)
    ]);

    // Probar diferentes formas de acceder a las propiedades principales
    $possibleProperties = [
        'uuid', 'id', 'orderId', 'order_id', 'orderNumber', 'order_number',
        'status', 'state', 'orderStatus', 'order_status',
        'amount', 'total', 'value',
        'currency', 'curr',
        'refNumber', 'ref_number', 'reference'
    ];

    $foundProperties = [];
    foreach ($possibleProperties as $prop) {
        if (is_object($order) && property_exists($order, $prop)) {
            $foundProperties[$prop] = $order->$prop;
        }
    }

    Log::info('=== PROPIEDADES ENCONTRADAS EN ORDER ===', $foundProperties);

    // Manejo seguro del objeto links
    $checkoutLink = null;
    
    if (is_object($order) && property_exists($order, 'links')) {
        Log::info('=== ANÁLISIS DEL OBJETO LINKS ===', [
            'links_exists' => true,
            'links_type' => gettype($order->links),
            'links_class' => is_object($order->links) ? get_class($order->links) : 'not_object',
            'links_as_array' => (array) $order->links,
            'links_vars' => is_object($order->links) ? get_object_vars($order->links) : 'not_object'
        ]);

        if (is_object($order->links)) {
            $possibleLinkProps = [
                'checkoutLink', 'checkout_link', 'paymentUrl', 'payment_url', 
                'checkout', 'payment', 'url', 'link', 'success', 'failed'
            ];
            
            $foundLinkProps = [];
            foreach ($possibleLinkProps as $linkProp) {
                if (property_exists($order->links, $linkProp)) {
                    $foundLinkProps[$linkProp] = $order->links->$linkProp;
                }
            }
            
            Log::info('=== PROPIEDADES ENCONTRADAS EN LINKS ===', $foundLinkProps);
            
            $checkoutLink = $order->links->checkoutLink 
                         ?? $order->links->checkout_link 
                         ?? $order->links->paymentUrl 
                         ?? $order->links->payment_url 
                         ?? $order->links->url 
                         ?? $order->links->link 
                         ?? null;
        }
    } else {
        Log::info('=== OBJETO LINKS NO ENCONTRADO ===', [
            'links_exists' => false,
            'order_has_links_property' => is_object($order) && property_exists($order, 'links')
        ]);
    }

    // Buscar checkout link también en el objeto principal
    if (empty($checkoutLink) && is_object($order)) {
        $checkoutLink = $order->checkoutLink 
                     ?? $order->checkout_link 
                     ?? $order->paymentUrl 
                     ?? $order->payment_url 
                     ?? $order->url 
                     ?? $order->link 
                     ?? null;
        
        if (!empty($checkoutLink)) {
            Log::info('=== CHECKOUT LINK ENCONTRADO EN OBJETO PRINCIPAL ===', ['link' => $checkoutLink]);
        }
    }

    // Extraer otros campos
    $orderId = null;
    $status = 'pending';
    $amount = null;
    $currency = null;
    $refNumber = null;
    $orderNumber = null;

    if (is_object($order)) {
        $orderId = $order->uuid ?? $order->id ?? $order->orderId ?? $order->order_id ?? null;
        $status = $order->status ?? $order->state ?? $order->orderStatus ?? 'pending';
        $amount = $order->amount ?? $order->total ?? $order->value ?? null;
        $currency = $order->currency ?? $order->curr ?? null;
        $refNumber = $order->refNumber ?? $order->ref_number ?? $order->reference ?? null;
        $orderNumber = $order->orderNumber ?? $order->order_number ?? $orderId ?? null;
    }

    Log::info('=== VALORES EXTRAÍDOS PARA NORMALIZACIÓN ===', [
        'orderId' => $orderId,
        'status' => $status,
        'checkoutLink' => $checkoutLink,
        'amount' => $amount,
        'currency' => $currency,
        'refNumber' => $refNumber,
        'orderNumber' => $orderNumber,
        'has_checkout_link' => !empty($checkoutLink)
    ]);

    // Crear respuesta normalizada
    $normalizedData = [
        'id' => $orderId,
        'uuid' => $orderId,
        'payment_url' => $checkoutLink,
        'checkout_url' => $checkoutLink,
        'external_reference' => $refNumber,
        'status' => strtolower($status),
        'order_number' => $orderNumber,
        'amount' => $amount,
        'currency' => $currency,
        'original_response' => [
            'id' => $orderId,
            'uuid' => $orderId,
            'orderNumber' => $orderNumber,
            'status' => $status,
            'amount' => $amount,
            'currency' => $currency,
            'refNumber' => $refNumber,
            'checkoutLink' => $checkoutLink,
            'successCallback' => is_object($order) && isset($order->links) && is_object($order->links) 
                               ? ($order->links->success ?? null) 
                               : null,
            'failedCallback' => is_object($order) && isset($order->links) && is_object($order->links) 
                              ? ($order->links->failed ?? null) 
                              : null
        ]
    ];

    Log::info('=== RESPUESTA NORMALIZADA ===', $normalizedData);

    // 🚨 VALIDACIÓN CRÍTICA
    if (empty($normalizedData['payment_url'])) {
        Log::error('=== ❌ ERROR: NO SE ENCONTRÓ URL DE PAGO ===', [
            'normalizedData' => $normalizedData,
            'original_order_dump' => var_export($order, true),
            'object_analysis' => [
                'is_empty_object' => $isEmpty,
                'has_properties' => !empty(get_object_vars($order)),
                'property_count' => count(get_object_vars($order))
            ]
        ]);
    } else {
        Log::info('=== ✅ URL DE PAGO ENCONTRADA EXITOSAMENTE ===', [
            'payment_url' => $normalizedData['payment_url']
        ]);
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
     * Obtener información de una orden
     */
    public function getOrder(string $uuid): array
    {
        try {
            Log::info('=== OBTENIENDO ORDEN UALA ===', ['uuid' => $uuid]);
            
            $order = $this->sdk->getOrder($uuid);
            
            Log::info('=== ORDEN OBTENIDA EXITOSAMENTE ===', [
                'uuid' => $order->uuid ?? 'N/A',
                'status' => $order->status ?? 'N/A'
            ]);
            
            return $this->normalizeResponse($order);
            
        } catch (\Exception $e) {
            Log::error('=== ERROR OBTENIENDO ORDEN UALA ===', [
                'message' => $e->getMessage(),
                'uuid' => $uuid
            ]);
            
            throw new \Exception("Error obteniendo orden de Uala: " . $e->getMessage());
        }
    }
}