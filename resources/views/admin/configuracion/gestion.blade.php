@extends('layouts.app_admin')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Gestión de Configuración Admin</h1>

    {{-- Botón de Nuevo --}}
    <div class="mb-4">
        <a href="{{ route('admin.gestion.create') }}"
           class="inline-block bg-indigo-600 text-black px-4 py-2 rounded hover:bg-indigo-700 transition">
            ➕ Nueva Configuración
        </a>
    </div>

    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Nombre</th>
                <th class="border px-4 py-2">Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($configuraciones as $config)
                <tr>
                    <td class="border px-4 py-2">{{ $config->id_configuracion }}</td>
                    <td class="border px-4 py-2">{{ $config->nombre }}</td>
                    <td class="border px-4 py-2">{{ $config->valor }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
