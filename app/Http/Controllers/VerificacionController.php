<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\RegistroConductor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class VerificacionController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        return view('verificacion.unified-form', compact('user'));
    }




    /**
     * Procesar la verificación de documentos
     */
   public function store(Request $request)
{
    // Validación: DNI obligatorio + documentos conductor opcionales
    $validated = $request->validate([
        'dni' => ['required', 'string', 'max:20'],
        'dni_foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        'dni_foto_atras' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        'licencia' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        'cedula' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        'cedula_verde' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        'seguro' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
    ], [
        'dni.required' => 'El número de documento es obligatorio',
        'dni_foto.required' => 'La foto frontal del documento es obligatoria',
        'dni_foto_atras.required' => 'La foto trasera del documento es obligatoria',
        '*.image' => 'El archivo debe ser una imagen',
        '*.mimes' => 'Solo se aceptan imágenes JPG, JPEG o PNG',
        '*.max' => 'La imagen no debe superar los 5MB',
    ]);

    $user = Auth::user();

    \DB::beginTransaction();
    try {
        // ==========================================
        // 1. GUARDAR DNI (OBLIGATORIO)
        // ==========================================

        // Eliminar fotos antiguas de DNI si existen
        if ($user->dni_foto && Storage::disk('public')->exists($user->dni_foto)) {
            Storage::disk('public')->delete($user->dni_foto);
        }
        if ($user->dni_foto_atras && Storage::disk('public')->exists($user->dni_foto_atras)) {
            Storage::disk('public')->delete($user->dni_foto_atras);
        }

        // Guardar nuevas fotos de DNI
        $dniFotoPath = $this->processAndSaveImage(
            $request->file('dni_foto'),
            'documentos/dni',
            'dni_frontal_' . $user->id
        );

        $dniFotoAtrasPath = $this->processAndSaveImage(
            $request->file('dni_foto_atras'),
            'documentos/dni',
            'dni_trasero_' . $user->id
        );

        // Actualizar usuario con DNI y fotos
        $user->update([
            'dni' => $validated['dni'],
            'dni_foto' => $dniFotoPath,
            'dni_foto_atras' => $dniFotoAtrasPath,
            'verificado' => false, // Pendiente de revisión por admin
        ]);

        // Asignar rol de pasajero (todos pueden ser pasajeros)
        if (!$user->hasRole('pasajero')) {
            $user->assignRole('pasajero');
        }

        // ==========================================
        // 2. GUARDAR DOCUMENTOS DE CONDUCTOR (OPCIONAL)
        // ==========================================

        $documentosConductorSubidos = [];
        $hasDriverDocs = $request->hasFile('licencia') ||
                         $request->hasFile('cedula') ||
                         $request->hasFile('cedula_verde') ||
                         $request->hasFile('seguro');

        if ($hasDriverDocs) {
            // Crear directorio para documentos de conductor
            if (!Storage::disk('public')->exists('documentos/conductor')) {
                Storage::disk('public')->makeDirectory('documentos/conductor');
            }

            // Obtener o crear registro de conductor
            $registroConductor = $user->registroConductor;
            if (!$registroConductor) {
                $registroConductor = new \App\Models\RegistroConductor([
                    'user_id' => $user->id,
                    // Valores temporales hasta que el usuario complete los datos del vehículo
                    'marca_vehiculo' => 'Pendiente',
                    'modelo_vehiculo' => 'Pendiente',
                    'anio_vehiculo' => date('Y'),
                    'patente' => 'PENDIENTE',
                    'numero_puestos' => 4, // Valor por defecto temporal
                    'estado_verificacion' => 'pendiente', // Valores permitidos: pendiente, aprobado, rechazado
                    'estado_registro' => 'incompleto', // Valores permitidos: incompleto, completo
                ]);
            }

            // Procesar y guardar documentos opcionales
            if ($request->hasFile('licencia')) {
                // Eliminar anterior si existe
                if ($registroConductor->licencia && Storage::disk('public')->exists($registroConductor->licencia)) {
                    Storage::disk('public')->delete($registroConductor->licencia);
                }
                $registroConductor->licencia = $this->processAndSaveImage(
                    $request->file('licencia'),
                    'documentos/conductor',
                    'licencia_' . $user->id
                );
                $documentosConductorSubidos[] = 'licencia';
            }

            if ($request->hasFile('cedula')) {
                if ($registroConductor->cedula && Storage::disk('public')->exists($registroConductor->cedula)) {
                    Storage::disk('public')->delete($registroConductor->cedula);
                }
                $registroConductor->cedula = $this->processAndSaveImage(
                    $request->file('cedula'),
                    'documentos/conductor',
                    'cedula_' . $user->id
                );
                $documentosConductorSubidos[] = 'cédula';
            }

            if ($request->hasFile('cedula_verde')) {
                if ($registroConductor->cedula_verde && Storage::disk('public')->exists($registroConductor->cedula_verde)) {
                    Storage::disk('public')->delete($registroConductor->cedula_verde);
                }
                $registroConductor->cedula_verde = $this->processAndSaveImage(
                    $request->file('cedula_verde'),
                    'documentos/conductor',
                    'cedula_verde_' . $user->id
                );
                $documentosConductorSubidos[] = 'cédula verde';
            }

            if ($request->hasFile('seguro')) {
                if ($registroConductor->seguro && Storage::disk('public')->exists($registroConductor->seguro)) {
                    Storage::disk('public')->delete($registroConductor->seguro);
                }
                $registroConductor->seguro = $this->processAndSaveImage(
                    $request->file('seguro'),
                    'documentos/conductor',
                    'seguro_' . $user->id
                );
                $documentosConductorSubidos[] = 'seguro';
            }

            $registroConductor->save();
        }

        \DB::commit();

        // Mensaje de éxito personalizado
        if (!empty($documentosConductorSubidos)) {
            $docsText = implode(', ', $documentosConductorSubidos);
            $message = "✅ DNI y documentos de conductor ({$docsText}) guardados correctamente. Para completar tu registro como conductor, agrega los datos de tu vehículo desde tu perfil.";
        } else {
            $message = '✅ Identidad verificada correctamente. Tu cuenta será revisada en 24-48 horas.';
        }

        return redirect()->route('hibrido.dashboard')
            ->with('success', $message);

    } catch (\Exception $e) {
        \DB::rollback();

        // Log del error con más detalles
        \Log::error('Error al procesar verificación', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()
            ->withInput()
            ->withErrors(['error' => 'Hubo un error al procesar tus documentos: ' . $e->getMessage()]);
    }
}

    /**
     * Procesar y guardar imagen optimizada
     */
    private function processAndSaveImage($file, $directory, $filename)
    {
        if (!$file) {
            throw new \Exception('No se recibió ningún archivo');
        }

        // Generar nombre único con timestamp
        $extension = $file->getClientOriginalExtension();
        $fullFilename = $filename . '_' . time() . '.' . $extension;
        $path = $directory . '/' . $fullFilename;

        // Intentar optimizar con Intervention Image si está disponible
        try {
            // Verificar si Intervention Image está instalado
            if (class_exists(\Intervention\Image\Facades\Image::class)) {
                $image = Image::make($file)
                    ->resize(1200, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode($extension, 85);

                Storage::disk('public')->put($path, $image);
            } else {
                // Si no está Intervention Image, guardar directamente
                $file->storeAs($directory, $fullFilename, 'public');
            }
        } catch (\Exception $e) {
            // Fallback: guardar sin optimización
            \Log::warning('No se pudo optimizar la imagen, guardando sin optimización: ' . $e->getMessage());
            $file->storeAs($directory, $fullFilename, 'public');
        }

        return $path;
    }

    /**
     * Omitir verificación (opcional)
     */
    public function skip()
    {
        return redirect()->route('dashboard')
            ->with('warning', 'Has omitido la verificación. Puedes completarla más tarde desde tu perfil.');
    }
}