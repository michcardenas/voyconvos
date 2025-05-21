@extends('layouts.app_admin')

@section('title', 'Gestión de Páginas')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">📄 Páginas disponibles</h1>

    @if($paginas->isEmpty())
        <p>No hay páginas registradas.</p>
    @else
        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Secciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paginas as $pagina)
                    <tr>
                        <td>{{ $pagina->id }}</td>
                        <td>{{ $pagina->nombre }}</td>
                        <td>
                            <a href="{{ route('configuracion.paginas.editar', $pagina->id) }}" class="btn btn-sm btn-outline-primary">
                                Editar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
