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
    // Validación básica (siempre requerida)
    $rules = [
        'dni' => ['required', 'string', 'max:20'],
        'dni_foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        'dni_foto_atras' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
    ];

    $messages = [
        'dni.required' => 'El número de documento es obligatorio',
        'dni_foto.required' => 'La foto frontal del documento es obligatoria',
        'dni_foto_atras.required' => 'La foto trasera del documento es obligatoria',
        '*.image' => 'El archivo debe ser una imagen',
        '*.mimes' => 'Solo se aceptan imágenes JPG, JPEG o PNG',
        '*.max' => 'La imagen no debe superar los 5MB',
    ];

    // Si es conductor, agregar validaciones adicionales
    if ($request->has('es_conductor') && $request->es_conductor) {
        $rules = array_merge($rules, [
            'marca_vehiculo' => ['required', 'string', 'max:100'],
            'modelo_vehiculo' => ['required', 'string', 'max:100'],
            'anio_vehiculo' => ['required', 'integer', 'min:2012', 'max:' . date('Y')],
            'patente' => ['required', 'string', 'max:20'],
            'numero_puestos' => ['required', 'integer', 'min:2', 'max:50'],
            'consumo_por_galon' => ['required', 'numeric', 'min:1'],
            'licencia' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
            'cedula' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
            'cedula_verde' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ]);

        $messages = array_merge($messages, [
            'marca_vehiculo.required' => 'La marca del vehículo es obligatoria',
            'modelo_vehiculo.required' => 'El modelo del vehículo es obligatorio',
            'anio_vehiculo.required' => 'El año del vehículo es obligatorio',
            'anio_vehiculo.min' => 'El vehículo debe ser del año 2012 o posterior',
            'patente.required' => 'La patente es obligatoria',
            'numero_puestos.required' => 'El número de puestos es obligatorio',
            'numero_puestos.min' => 'Mínimo 2 puestos',
            'consumo_por_galon.required' => 'El consumo por galón es obligatorio',
            'licencia.required' => 'La licencia de conducir es obligatoria para conductores',
            'cedula.required' => 'La cédula de identidad es obligatoria para conductores',
            'cedula_verde.required' => 'La cédula verde del vehículo es obligatoria para conductores',
        ]);
    } else {
        // Documentos opcionales (no se guardarán en registro_conductores)
        $rules = array_merge($rules, [
            'licencia' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
            'cedula_verde' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
            'seguro' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ]);
    }

    $validated = $request->validate($rules, $messages);

    $user = Auth::user();

    \DB::beginTransaction();
    try {
        // ==========================================
        // 1. PROCESAR Y GUARDAR DNI (OBLIGATORIO)
        // ==========================================
        
        // Eliminar fotos antiguas de DNI si existen
        if ($user->dni_foto) {
            Storage::disk('public')->delete($user->dni_foto);
        }
        if ($user->dni_foto_atras) {
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

        // Actualizar usuario con datos básicos
        $user->update([
            'dni' => $validated['dni'],
            'dni_foto' => $dniFotoPath,
            'dni_foto_atras' => $dniFotoAtrasPath,
            'verificado' => false, // Pendiente de revisión
        ]);

        // ==========================================
        // 2. PROCESAR DOCUMENTOS DE CONDUCTOR
        // ==========================================
        
        if ($request->has('es_conductor') && $request->es_conductor) {
            // Obtener o crear registro de conductor
            $registroConductor = $user->registroConductor ?: new \App\Models\RegistroConductor(['user_id' => $user->id]);

            // Eliminar documentos antiguos si existen
            if ($registroConductor->exists) {
                if ($registroConductor->licencia) {
                    Storage::disk('public')->delete($registroConductor->licencia);
                }
                if ($registroConductor->cedula) {
                    Storage::disk('public')->delete($registroConductor->cedula);
                }
                if ($registroConductor->cedula_verde) {
                    Storage::disk('public')->delete($registroConductor->cedula_verde);
                }
            }

            // Procesar y guardar documentos del conductor
            $licenciaPath = $this->processAndSaveImage(
                $request->file('licencia'),
                'documentos/conductor',
                'licencia_' . $user->id
            );

            $cedulaPath = $this->processAndSaveImage(
                $request->file('cedula'),
                'documentos/conductor',
                'cedula_' . $user->id
            );

            $cedulaVerdePath = $this->processAndSaveImage(
                $request->file('cedula_verde'),
                'documentos/conductor',
                'cedula_verde_' . $user->id
            );

            // Actualizar o crear registro de conductor
            $registroConductor->fill([
                'marca_vehiculo' => $validated['marca_vehiculo'],
                'modelo_vehiculo' => $validated['modelo_vehiculo'],
                'anio_vehiculo' => $validated['anio_vehiculo'],
                'patente' => strtoupper($validated['patente']),
                'numero_puestos' => $validated['numero_puestos'],
                'consumo_por_galon' => $validated['consumo_por_galon'],
                'licencia' => $licenciaPath,
                'cedula' => $cedulaPath,
                'cedula_verde' => $cedulaVerdePath,
                'estado_verificacion' => 'pendiente',
                'estado_registro' => 'pendiente',
            ]);

            $registroConductor->save();

            // Asignar rol de conductor
            if (!$user->hasRole('conductor')) {
                $user->assignRole('conductor');
            }

            $message = '¡Documentos de conductor enviados! Revisaremos tu información en 24-48 horas. 🚗';
            
        } else {
            // ==========================================
            // 3. SOLO VERIFICACIÓN BÁSICA (NO CONDUCTOR)
            // ==========================================
            
            // ⚠️ CAMBIO IMPORTANTE: No guardamos documentos opcionales
            // porque requieren datos completos del vehículo
            
            $optionalDocsUploaded = [];
            
            if ($request->hasFile('licencia')) {
                $optionalDocsUploaded[] = 'licencia';
            }
            if ($request->hasFile('cedula_verde')) {
                $optionalDocsUploaded[] = 'cédula verde';
            }
            if ($request->hasFile('seguro')) {
                $optionalDocsUploaded[] = 'seguro';
            }
            
            if (!empty($optionalDocsUploaded)) {
                // Informar al usuario que necesita registrarse como conductor
                $message = '✓ DNI verificado correctamente. Para subir ' . implode(', ', $optionalDocsUploaded) . 
                          ', debes registrarte como conductor con los datos de tu vehículo desde tu perfil. 🚗';
            } else {
                $message = '¡Documentos básicos enviados! Tu cuenta será verificada en 24-48 horas. 🎉';
            }
        }

        // Asignar rol de pasajero (todos son pasajeros)
        if (!$user->hasRole('pasajero')) {
            $user->assignRole('pasajero');
        }

        \DB::commit();

        return redirect()->route('hibrido.dashboard')
            ->with('success', $message);

    } catch (\Exception $e) {
        \DB::rollback();
        
        // Log del error
        \Log::error('Error al procesar verificación: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return back()
            ->withInput()
            ->withErrors(['error' => 'Hubo un error al procesar tus documentos. Por favor, intenta nuevamente.']);
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