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

// Login con Google
Route::get('/login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Página principal
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Configuración general
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');

    // Vista de páginas dinámicas (usando el controlador)
    Route::get('/configuracion/paginas', [ConfiguracionController::class, 'index'])
        ->name('configuracion.paginas');

    // Vista SEO (ejemplo estático)
    Route::get('/configuracion/seo', function () {
        return 'Vista: Configuración SEO';
    })->name('configuracion.seo');
});

// Zona administrativa
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

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

// Rutas conductores
Route::get('/conductor/gestion', function () {
    return view('conductor.gestion');
})->name('conductor.gestion');
Route::post('/conductor/destino', [DestinoController::class, 'store'])->name('conductor.destino.store');
Route::get('/conductor/estimar-ruta', [\App\Http\Controllers\Conductor\RutaController::class, 'estimar']);



// // Panel de usuario
// Route::get('/panel', [\App\Http\Controllers\UsuarioController::class, 'index'])->name('usuario.panel');

// Autenticación por defecto
require __DIR__.'/auth.php';
