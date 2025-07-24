<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/voyconvos_admin.css') }}">
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- FontAwesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Meta viewport --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <title>@yield('title', 'Panel de Administración')</title>
</head>
<body class="bg-gray-100 font-sans antialiased">

    {{-- Navbar --}}
    {{-- Navegación principal --}}
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div>
                <a href="{{ url('admin/dashboard') }}" class="text-lg font-bold text-gray-800">VoyConVos</a>
            </div>
            <ul class="flex space-x-4">
                <li>
                @role('admin')
                    <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-indigo-600">
                        Gestión de Usuarios
                    </a>

                      <a href="{{ route('admin.gestion') }}" class="text-gray-700 hover:text-indigo-600">
                        Gestión de admin
                    </a>
                    <a href="{{ route('configuracion.index') }}" class="text-gray-700 hover:text-indigo-600">
                            Configuración
                        </a>
                @endrole

                </li>
                {{-- Agrega más enlaces aquí si quieres --}}
            </ul>
        </div>
    </nav>

    {{-- Header opcional --}}
    @hasSection('header')
        <header class="bg-white shadow p-4 mb-4">
            <div class="container mx-auto">
                @yield('header')
            </div>
        </header>
    @endif

    {{-- Contenido principal --}}
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>
    @stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
