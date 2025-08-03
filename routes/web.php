<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PaginaController;
use App\Http\Controllers\Admin\SeccionController;
use App\Http\Controllers\Admin\ContenidoController;
use App\Http\Controllers\Conductor\DestinoController;
use App\Http\Controllers\Conductor\ConductorController;
use App\Http\Controllers\Conductor\RutaController;
use App\Http\Controllers\Conductor\RegistroVehiculoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pasajero\DashboardController as PasajeroDashboard;
use App\Http\Controllers\pasajero\DashboardPasajeroController;
use App\Http\Controllers\Pasajero\ReservaPasajeroController;
use App\Http\Controllers\Pasajero\ChatPasajeroController;
use App\Http\Controllers\Conductor\ViajeController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TerminosController;
use App\Http\Controllers\PoliticasController;
use App\Http\Controllers\SobreNosotrosPublicoController;
use App\Http\Controllers\ComoFuncionaPublicoController;
use MercadoPago\SDK;
use App\Http\Controllers\Admin\ConfiguracionAdminController;
use App\Http\Controllers\InicioController;


// Login con Google
Route::get('/login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Página principal
Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [InicioController::class, 'index'])->name('inicio');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::post('/conductor/calificar-conductor/{reserva}', [
    \App\Http\Controllers\Conductor\ConductorController::class, 
    'calificarConductor'
])
->name('conductor.calificar.conductor');
// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
     Route::get('/pasajero/registro', [ProfileController::class, 'registroForm'])->name('pasajero.registro.form');
    Route::post('/pasajero/registro', [ProfileController::class, 'registroStore'])->name('pasajero.registro.store');
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //EDITAR USUARIOS
    Route::get('/perfil/editar', [ProfileController::class, 'editarUsuario'])->name('perfil.editar.usuario');
    Route::get('/perfil/conductor', [ProfileController::class, 'editarUsuario'])->name('conductor.perfil.edit');
    Route::put('/conductor', [ProfileController::class, 'update'])->name('conductor.perfil.update');

    Route::get('/perfil/pasajero', [ProfileController::class, 'editarUsuario'])->name('pasajero.perfil.edit');

    Route::put('/conductor/perfil/actualizar', [ProfileController::class, 'actualizarPerfil'])->name('conductor.perfil.update');
    Route::put('/pasajero/perfil/actualizar', [ProfileController::class, 'actualizarPerfilPasajero'])->name('pasajero.perfil.update');



    // Configuración general
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');

    // Vista de páginas dinámicas (usando el controlador)s
    Route::get('/configuracion/paginas', [ConfiguracionController::class, 'index'])
        ->name('configuracion.paginas');

    // Vista SEO (ejemplo estático)
    Route::get('/configuracion/seo',[ConfiguracionController::class, 'seo'])->name('configuracion.seo');});

     // Página principal SEO
    Route::get('/configuracion/seo', [ConfiguracionController::class, 'seo'])->name('configuracion.seo');
    
    // Operaciones AJAX SEO
    Route::post('/configuracion/seo/obtener', [ConfiguracionController::class, 'obtenerMetadatos'])->name('configuracion.seo.obtener');
    Route::post('/configuracion/seo/guardar', [ConfiguracionController::class, 'guardarMetadatos'])->name('configuracion.seo.guardar');
    Route::delete('/configuracion/seo/eliminar', [ConfiguracionController::class, 'eliminarMetadatos'])->name('configuracion.seo.eliminar');
    Route::post('/configuracion/seo/previsualizar', [ConfiguracionController::class, 'previsualizarMetadatos'])->name('configuracion.seo.previsualizar');
Route::post('conductor/finalizar-pasajero/{reservaId}', [
        App\Http\Controllers\Conductor\FinalizarPasajeroController::class, 
        'finalizarPasajero'
    ])->name('conductor.finalizar-pasajero');

// Zona administrativa
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

  Route::get('/dashboard', [PaginaController::class, 'dashboard'])->name('dashboard');
       // Ruta para ver detalle del viaje
    Route::get('/viajes/{viaje}/detalle', [ConfiguracionAdminController::class, 'detalleViaje'])
        ->name('viajes.detalle');
    
    // Ruta para editar viaje (opcional)
    Route::get('/viajes/{viaje}/editar', [ConfiguracionAdminController::class, 'editarViaje'])
        ->name('viajes.editar');
    
    // Ruta para ver todos los viajes
    Route::get('/viajes/todos', [ConfiguracionAdminController::class, 'todosLosViajes'])
        ->name('viajes.todos');

    Route::resource('users', UserController::class);
    Route::resource('paginas', PaginaController::class);
    Route::resource('secciones', SeccionController::class);
    Route::resource('contenidos', ContenidoController::class)->only(['edit', 'update']);
});

// Ruta para mostrar secciones de una página específica
Route::get('admin/secciones/{pagina}', [SeccionController::class, 'index'])->name('secciones.index');
Route::get('/admin/secciones/{slug}/editar', [SeccionController::class, 'editarContenido'])
    ->name('admin.secciones.editarContenido');
Route::get('/admin/secciones/{slug}/editar-contenidos', [\App\Http\Controllers\Admin\SeccionController::class, 'editarContenidos'])->name('admin.secciones.editar-contenidos');
Route::put('/admin/secciones/{slug}/actualizar-contenidos', [\App\Http\Controllers\Admin\SeccionController::class, 'actualizarContenidos'])->name('admin.secciones.actualizar-contenidos');
Route::get('/configuracion/paginas', function () {
    $paginas = \App\Models\Pagina::all();
    return view('admin.configuracion.paginas.index', compact('paginas'));
})->name('configuracion.paginas');

// Ruta para editar una página desde la configuración
Route::get('/configuracion/paginas/{pagina}/editar', [PaginaController::class, 'editar'])
    ->middleware(['auth'])
    ->name('configuracion.paginas.editar');
    
// Contacto público
Route::get('/contacto', [ContactoController::class, 'mostrarFormulario'])->name('contacto.formulario');
Route::post('/contacto', [ContactoController::class, 'enviarFormulario'])->name('contacto.enviar');

//gestion
Route::get('/admin/gestion', [ConfiguracionAdminController::class, 'index'])->name('admin.gestion');
Route::get('/admin/gestion/create', [ConfiguracionAdminController::class, 'create'])->name('admin.gestion.create');
Route::post('/admin/gestion', [ConfiguracionAdminController::class, 'store'])->name('admin.gestion.store');
Route::get('/admin/gestor-pagos', [ConfiguracionAdminController::class, 'gestorPagos'])
    ->name('admin.gestor-pagos')
    ->middleware(['auth']); 
// Rutas conductores
Route::post('/conductor/destino', [DestinoController::class, 'store'])->name('conductor.destino.store');
Route::get('/conductor/estimar-ruta', [\App\Http\Controllers\Conductor\RutaController::class, 'estimar']);
Route::get('/conductor/detalle-viaje', [RutaController::class, 'detalle'])->name('detalle.viaje');
Route::post('/conductor/guardar-viaje', [RutaController::class, 'store'])->name('conductor.viaje.store');
Route::get('/conductor/gestion', [ConductorController::class, 'gestion'])->name('conductor.gestion');

// editar datos del conductor
Route::get('/conductor/completar-registro', [RegistroVehiculoController::class, 'form'])->name('conductor.registro.form');
Route::post('/conductor/completar-registro', [RegistroVehiculoController::class, 'store'])->name('conductor.registro.store');
Route::post('/conductor/registro', [RegistroVehiculoController::class, 'store'])->name('conductor.registro.store');

// Dashboard de pasajero
Route::get('/pasajero/dashboard', [ReservaPasajeroController::class, 'misReservas'])->name('pasajero.dashboard');
Route::post('/conductor/viaje/{viaje}/iniciar', [ConductorController::class, 'iniciarViaje'])
    ->name('conductor.viaje.iniciar');

Route::post('/webhook/uala-bis', [ReservaPasajeroController::class, 'handleUalaWebhook'])->name('uala.webhook');
Route::get('/conductor/viaje/{viaje}/detalles-finalizados', [App\Http\Controllers\Conductor\ConductorController::class, 'verViajeFinalizados'])
    ->name('conductor.viaje.detalles.finalizados');
    Route::post('/conductor/calificar-pasajero/{reserva}', [App\Http\Controllers\Conductor\ConductorController::class, 'calificar'])
    ->name('conductor.calificar.pasajero');
Route::prefix('conductor')->name('conductor.')->group(function () {
    
    // ✅ ESTA RUTA DEBE EXISTIR EXACTAMENTE ASÍ:
    Route::get('/viaje/{viaje}/en-curso', [App\Http\Controllers\Conductor\ConductorController::class, 'viajeEnCurso'])
        ->name('viaje.en-curso');
        
    // Las demás rutas...
    Route::post('/viaje/{viaje}/iniciar', [App\Http\Controllers\Conductor\ConductorController::class, 'iniciarViaje'])
        ->name('viaje.iniciar');
        Route::post('/conductor/finalizar-pasajero/{reserva}', [App\Http\Controllers\Conductor\FinalizarPasajeroController::class, 'finalizar'])
     ->name('conductor.finalizar.pasajero');
     
     Route::post('/conductor/viaje/{viaje}/finalizar', [App\Http\Controllers\Conductor\ConductorController::class, 'finalizarViaje'])
    ->name('conductor.viaje.finalizar');
     Route::get('/conductor/test-finalizar', [App\Http\Controllers\Conductor\FinalizarPasajeroController::class, 'test']);

    Route::get('/viaje/{viaje}/verificar-pasajeros', [App\Http\Controllers\Conductor\ConductorController::class, 'verificarPasajeros'])
        ->name('viaje.verificar-pasajeros');
        
    Route::post('/viaje/{viaje}/procesar-asistencia', [App\Http\Controllers\Conductor\ConductorController::class, 'procesarAsistencia'])
        ->name('viaje.procesar-asistencia');
});
// Ver detalles de una reserva
Route::get('/pasajero/reserva/{reserva}/detalles', [ReservaPasajeroController::class, 'verDetalles'])->name('pasajero.reserva.detalles');

// Mostrar viajes disponibles
Route::get('/pasajero/viajes-disponibles', [ReservaPasajeroController::class, 'mostrarViajesDisponibles'])->name('pasajero.viajes.disponibles');

// Paso 1: Mostrar formulario de confirmación
Route::get('/pasajero/reservar/{viaje}', [ReservaPasajeroController::class, 'mostrarConfirmacion'])->name('pasajero.confirmar.mostrar');

// Paso 2: Mostrar resumen con GET (cantidad y total)
Route::get('/pasajero/reserva/resumen/{viaje}', [ReservaPasajeroController::class, 'mostrarResumen'])->name('pasajero.reserva.resumen');

// Paso 3: Confirmar reserva y guardar
Route::post('/pasajero/reservar/{viaje}', [ReservaPasajeroController::class, 'reservar'])->name('pasajero.reservar');
Route::get('/reserva/{viaje}/confirmada', [ReservaPasajeroController::class, 'confirmada'])->name('pasajero.reserva.confirmada');
Route::get('/reserva/{viaje}/fallida', [ReservaPasajeroController::class, 'fallida'])->name('pasajero.reserva.fallida');
Route::get('/reserva/{viaje}/pendiente', [ReservaPasajeroController::class, 'pendiente'])->name('pasajero.reserva.pendiente');
Route::get('/reserva/confirmacion/{reserva}', [ReservaPasajeroController::class, 'confirmacionReserva'])->name('pasajero.reserva.confirmacion');


// // Confirmación final
// Route::get('/pasajero/reserva-confirmada/{viaje}', [ReservaPasajeroController::class, 'confirmacion'])->name('pasajero.reserva.confirmada');

// Chat pasajero con conductor
Route::get('/chat/{viaje}', [\App\Http\Controllers\ChatController::class, 'verChat'])->name('chat.ver');
Route::post('/chat/{viaje}', [\App\Http\Controllers\ChatController::class, 'enviarMensaje'])->name('chat.enviar');

// Eliminacion de viaje
Route::delete('/viajes/{viaje}/eliminar', [\App\Http\Controllers\Conductor\ViajeController::class, 'eliminar'])->name('conductor.viaje.eliminar');

// detalles del viaje conductor
Route::get('/viajes/{viaje}/detalle', [ViajeController::class, 'detalle'])->name('conductor.viaje.detalle');
Route::get('/conductor/viajes/{viaje}', [ConductorController::class, 'verViaje'])->name('conductor.viaje.detalles');
Route::post('/conductor/verificar-pasajero/{reserva}', [ConductorController::class, 'verificarPasajero'])
    ->name('conductor.verificar-pasajero')
    ->middleware('auth');

// Mostrar formulario calificación
Route::get('/pasajero/reserva/{reserva}/calificar', [CalificacionController::class, 'formularioPasajero'])
        ->name('pasajero.calificar.formulario');

// Guardar calificación calificación
Route::post('/pasajero/reserva/{reserva}/calificar', [CalificacionController::class, 'guardarCalificacionPasajero'])
        ->name('pasajero.calificar.guardar');

// seccion preguntas frecuentes
Route::get('/preguntas-frecuentes', [FaqController::class, 'index'])->name('faq.index');

// Terminos y condiciones
Route::get('/terminos-y-condiciones', [TerminosController::class, 'index'])->name('terminos.index');

// Políticas de privacidad
Route::get('/politica-de-privacidad', [PoliticasController::class, 'index'])->name('politicas.index');

// sobre nosotros
Route::get('/sobre-nosotros', [SobreNosotrosPublicoController::class, 'index'])->name('sobre-nosotros');

// Cómo funciona
Route::get('/como-funciona', [ComoFuncionaPublicoController::class, 'index'])->name('como-funciona');

Route::middleware(['auth'])->group(function () {
    // Ruta para procesar pago
    Route::get('/reserva/{reserva}/pagar', [ReservaPasajeroController::class, 'procesarPago'])->name('pasajero.procesar.pago');
    
    // Callbacks de Mercado Pago
    Route::get('/pago/success/{reserva}', [ReservaPasajeroController::class, 'pagoSuccess'])->name('pasajero.pago.success');
    Route::get('/pago/failure/{reserva}', [ReservaPasajeroController::class, 'pagoFailure'])->name('pasajero.pago.failure');
    Route::get('/pago/pending/{reserva}', [ReservaPasajeroController::class, 'pagoPending'])->name('pasajero.pago.pending');
});






// // Panel de usuario
// Route::get('/panel', [\App\Http\Controllers\UsuarioController::class, 'index'])->name('usuario.panel');

// Autenticación por defecto
require __DIR__.'/auth.php';
