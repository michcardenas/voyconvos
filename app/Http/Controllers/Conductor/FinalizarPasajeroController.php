<?php

namespace App\Http\Controllers\Conductor;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\UniversalMail;
use Illuminate\Support\Facades\Mail;

class FinalizarPasajeroController extends Controller
{
public function finalizar(Request $request, $reserva) 
{
    // 📝 Log básico
    Log::info('Iniciando finalización de pasajero', [
        'reserva_id' => $reserva, // Ahora es $reserva, no $reservaId
        'user_id' => auth()->id() ?? 'no_auth'
    ]);

    try {
        // 🔍 Buscar reserva
        $reservaModel = Reserva::find($reserva);
                
        if (!$reservaModel) {
            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ]);
        }

        // 🔄 Actualizar estado
        $reservaModel->estado = 'finalizado';
        $reservaModel->save();

        // 📧 ENVIAR EMAIL AL PASAJERO
        try {
            // Obtener datos para el email
            $viaje = $reservaModel->viaje;
            $pasajero = $reservaModel->user;
            $conductor = auth()->user();
            
            if ($pasajero && $pasajero->email && $viaje) {
                $fechaViaje = \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
                
                Mail::to($pasajero->email)->send(new UniversalMail(
                    $pasajero,
                    'Viaje finalizado - VoyConvos',
                    "Tu viaje con VoyConvos ha sido finalizado exitosamente.\n\n📍 Detalles del viaje:\n• Origen: {$viaje->origen}\n• Destino: {$viaje->destino}\n• Fecha: {$fechaViaje}\n• Conductor: {$conductor->name}\n\nTu viaje ha sido registrado como completado.\n\nGracias por viajar con VoyConvos y esperamos verte pronto en tu próximo viaje.",
                    'notificacion'
                ));
                
                Log::info('Email de finalización enviado', [
                    'reserva_id' => $reservaModel->id,
                    'pasajero_email' => $pasajero->email
                ]);
            }
            
        } catch (\Exception $emailError) {
            // Si falla el email, solo registrar pero continuar
            Log::error('Error enviando email de finalización: ' . $emailError->getMessage());
        }

        Log::info('Pasajero finalizado', [
            'reserva_id' => $reservaModel->id,
            'estado' => $reservaModel->estado
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pasajero finalizado exitosamente'
        ]);

    } catch (\Exception $e) {
        Log::error('Error al finalizar pasajero', [
            'error' => $e->getMessage(),
            'reserva_id' => $reserva
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

  public function finalizarPasajero(Request $request, $reservaId)
{
    try {
        // 🔍 Buscar la reserva con el viaje relacionado
        $reserva = Reserva::with(['viaje', 'user'])
            ->where('id', $reservaId)
            ->first();

        if (!$reserva) {
            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        // 🛡️ Verificar permisos: que sea el conductor del viaje
        if ($reserva->viaje->conductor_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para finalizar este pasajero'
            ], 403);
        }

        // ✅ Verificar que el viaje esté en curso
        if ($reserva->viaje->estado !== 'en_curso') {
            return response()->json([
                'success' => false,
                'message' => 'El viaje debe estar en curso para finalizar pasajeros'
            ], 400);
        }

        // 🔒 Verificar que el pasajero no esté ya finalizado
        if ($reserva->estado === 'finalizado') {
            return response()->json([
                'success' => false,
                'message' => 'Este pasajero ya ha sido finalizado'
            ], 400);
        }

        // 🔐 Validar código de confirmación
        $codigoConfirmacion = $request->input('codigo_confirmacion', '');
        $codigoEsCorrecto = $this->validarCodigoConfirmacion($codigoConfirmacion, $reservaId);

        // 📝 Preparar datos según si el código es correcto
        $datosActualizacion = [
            'estado' => 'finalizado',           // ✅ Siempre finalizado
            'asistencia' => 'presente',         // ✅ Siempre presente (asistió)
            'notificado' => $codigoEsCorrecto ? 1 : 0,  // 🎯 1 si código correcto, 0 si no
            'verificado_por_conductor' => true,
            'fecha_verificacion' => now(),
            'updated_at' => now()
        ];

        // 🔄 Actualizar en base de datos
        DB::beginTransaction();

        try {
            $reserva->update($datosActualizacion);

            // 📊 Log para seguimiento
            Log::info('Pasajero finalizado', [
                'reserva_id' => $reservaId,
                'viaje_id' => $reserva->viaje_id,
                'user_id' => $reserva->user_id,
                'conductor_id' => auth()->id(),
                'codigo_usado' => $codigoConfirmacion,
                'codigo_correcto' => $codigoEsCorrecto,
                'notificado' => $codigoEsCorrecto ? 1 : 0,
                'timestamp' => now()
            ]);

            // 📧 ENVIAR EMAILS - PASAJERO FINALIZADO
            try {
                $viaje = $reserva->viaje;
                $pasajero = $reserva->user;
                $conductor = auth()->user();
                $fechaViaje = \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
                
                if ($codigoEsCorrecto) {
                    // EMAIL CUANDO EL CÓDIGO ES CORRECTO
                    Mail::to($pasajero->email)->send(new UniversalMail(
                        $pasajero,
                        '¡Viaje completado exitosamente! - VoyConvos',
                        "¡Felicidades! Has completado exitosamente tu viaje con VoyConvos.\n\n📍 Detalles del viaje:\n• Origen: {$viaje->origen}\n• Destino: {$viaje->destino}\n• Fecha: {$fechaViaje}\n• Conductor: {$conductor->name}\n\n✅ Código de confirmación validado correctamente.\n\nTu viaje ha sido registrado como completado. Esperamos que hayas tenido una excelente experiencia.\n\n¡Gracias por viajar con VoyConvos! Te esperamos en tu próximo viaje.",
                        'notificacion'
                    ));
                } else {
                    // EMAIL CUANDO EL CÓDIGO NO ES CORRECTO PERO SE FINALIZA
                    Mail::to($pasajero->email)->send(new UniversalMail(
                        $pasajero,
                        'Viaje finalizado - VoyConvos',
                        "Tu viaje con VoyConvos ha sido finalizado por el conductor.\n\n📍 Detalles del viaje:\n• Origen: {$viaje->origen}\n• Destino: {$viaje->destino}\n• Fecha: {$fechaViaje}\n• Conductor: {$conductor->name}\n\nTu viaje ha sido registrado como completado. Si tienes alguna consulta sobre este viaje, no dudes en contactarnos.\n\nGracias por viajar con VoyConvos.",
                        'general'
                    ));
                }
                
                Log::info('Email de finalización enviado', [
                    'reserva_id' => $reservaId,
                    'pasajero_email' => $pasajero->email,
                    'codigo_correcto' => $codigoEsCorrecto
                ]);
                
            } catch (\Exception $emailError) {
                // Si fallan los emails, solo registrar pero continuar
                Log::error('Error enviando email de finalización: ' . $emailError->getMessage());
            }

            DB::commit();

            // 🎉 Preparar mensaje según resultado del código
            if ($codigoEsCorrecto) {
                $mensaje = "✅ {$reserva->user->name} finalizado exitosamente con código correcto";
            } else {
                $mensaje = "✅ {$reserva->user->name} finalizado, pero el código no fue correcto";
            }

            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'data' => [
                    'reserva_id' => $reservaId,
                    'pasajero_nombre' => $reserva->user->name,
                    'codigo_correcto' => $codigoEsCorrecto,
                    'notificado' => $codigoEsCorrecto ? 1 : 0,
                    'estado' => 'finalizado',
                    'asistencia' => 'presente',
                    'fecha_finalizacion' => $reserva->fecha_verificacion
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    } catch (\Exception $e) {
        Log::error('Error al finalizar pasajero', [
            'reserva_id' => $reservaId,
            'conductor_id' => auth()->id(),
            'error' => $e->getMessage(),
            'linea' => $e->getLine(),
            'archivo' => $e->getFile()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor al finalizar pasajero'
        ], 500);
    }
}

    /**
     * Validar código de confirmación contra el ID de reserva
     *
     * @param string $codigo
     * @param int $reservaId
     * @return bool
     */
    private function validarCodigoConfirmacion($codigo, $reservaId)
    {
        // Si no viene código, considerar incorrecto
        if (empty($codigo)) {
            return false;
        }

        // 🔧 Limpiar código: quitar ceros de la izquierda
        $codigoLimpio = ltrim($codigo, '0');
        
        // Si queda vacío después de quitar ceros, convertir a "0"
        if (empty($codigoLimpio)) {
            $codigoLimpio = '0';
        }

        // 🔍 Convertir a entero para comparar
        $codigoNumerico = (int) $codigoLimpio;
        $reservaIdNumerico = (int) $reservaId;

        $esValido = $codigoNumerico === $reservaIdNumerico;

        Log::info('Validación de código finalizar pasajero', [
            'codigo_original' => $codigo,
            'codigo_limpio' => $codigoLimpio,
            'codigo_numerico' => $codigoNumerico,
            'reserva_id' => $reservaIdNumerico,
            'es_valido' => $esValido
        ]);

        return $esValido;
    }
}