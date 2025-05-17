<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/voyconvos_admin.css') }}">
    

    <title>@yield('title', 'Panel de Administración')</title>
</head>
<body class="bg-gray-100 font-sans antialiased">

    {{-- Navbar --}}
    {{-- Navegación principal --}}
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div>
                <a href="{{ url('/dashboard') }}" class="text-lg font-bold text-gray-800">VoyConVos</a>
            </div>
            <ul class="flex space-x-4">
                <li>
                    <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-indigo-600">
                        Gestión de Usuarios
                    </a>
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

</body>
</html>
