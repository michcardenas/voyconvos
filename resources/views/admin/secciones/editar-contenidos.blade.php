@extends('layouts.app_dashboard')

@section('title', 'Editar Contenidos')

@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container py-5" style="max-width: 800px;">
    <h2 class="fw-bold mb-4 text-primary">
        ✏️ Editar contenidos de: <span class="text-dark">{{ $seccion->slug }}</span>
    </h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.secciones.actualizar-contenidos', $seccion->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach($seccion->contenidos as $contenido)
            <div class="mb-4">
                <label class="form-label">{{ ucfirst(str_replace('_', ' ', $contenido->clave)) }}</label>

                @if(Str::contains($contenido->clave, ['background', 'imagen', 'img']))
                    @if($contenido->valor)
                        <div class="mb-2">
                            <img src="{{ asset($contenido->valor) }}" alt="Vista previa" style="max-width: 100%; border-radius: 6px;">
                        </div>
                    @endif
                    <input type="file" name="valor_{{ $contenido->id }}" class="form-control" accept="image/*">
                @elseif(Str::contains($contenido->clave, ['texto', 'descripcion', 'slogan']))
                    <textarea name="valor_{{ $contenido->id }}" class="form-control" rows="4">{{ $contenido->valor }}</textarea>
                @else
                    <input type="text" name="valor_{{ $contenido->id }}" class="form-control" value="{{ $contenido->valor }}">
                @endif
            </div>
        @endforeach

        <div class="d-flex justify-content-between">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">← Volver</a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection
