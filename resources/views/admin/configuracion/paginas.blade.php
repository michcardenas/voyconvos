@extends('layouts.app_admin')

@section('title', 'Edición de Páginas')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-4">Edición de Páginas</h1>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Página</th>
                    <th>Editar</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paginas as $pagina)
                <tr>
                    <td>{{ $pagina->nombre }}</td>
                    <td>
                        <a href="{{ route('configuracion.paginas.editar', $pagina->id) }}" class="btn btn-primary">Editar</a>

                    </td>
                    <td>
                        <a href="{{ $pagina->ruta }}" target="_blank" class="btn btn-sm btn-outline-primary">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
