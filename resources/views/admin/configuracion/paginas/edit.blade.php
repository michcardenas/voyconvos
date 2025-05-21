@extends('layouts.app_dashboard') 

@section('title', 'Configuración')

@section('content')
<div style="max-width: 960px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.05);">
    <h2 class="fw-bold mb-4" style="color: #1F4E79;">
        📚 Secciones de la página
    </h2>

    @if($pagina->secciones->isEmpty())
        <div class="alert alert-warning">No hay secciones registradas para esta página.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle" style="border-radius: 8px; overflow: hidden;">
                <thead class="table-light">
                    <tr style="background-color: #e3f2fd;">
                        <th style="width: 60px;" class="text-center">#</th>
                        <th>Nombre de la Sección</th>
                        <th style="width: 180px;" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagina->secciones as $seccion)
                        <tr>
                            <td class="text-center">{{ $seccion->id }}</td>
                            <td class="text-capitalize">{{ $seccion->slug }}</td>
                            <td class="text-center">
                                @if($seccion->contenidos->count())
    <a href="{{ route('admin.secciones.editar-contenidos', $seccion->slug) }}"
       class="btn btn-outline-primary btn-sm">
        <i class="fas fa-pen"></i> Editar contenidos
    </a>
@else
    <span class="badge bg-secondary">Sin contenido</span>
@endif



                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
