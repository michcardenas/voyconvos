@extends('layouts.app_dashboard') 

@section('title', 'Configuración')

@section('content')
<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <a href="{{ route('configuracion.paginas') }}" class="text-decoration-none">
            <div class="p-4 rounded-4 text-white text-center h-100" style="background-color: #1F4E79; transition: 0.3s;">
                <i class="fas fa-edit fa-2x mb-2"></i>
                <h5 class="fw-semibold mb-0">Edición de Páginas</h5>
            </div>
        </a>
    </div>

    <div class="col-md-6 mb-4">
        <a href="{{ route('configuracion.seo') }}" class="text-decoration-none">
            <div class="p-4 rounded-4 text-white text-center h-100" style="background-color: #4CAF50; transition: 0.3s;">
                <i class="fas fa-search fa-2x mb-2"></i>
                <h5 class="fw-semibold mb-0">SEO</h5>
            </div>
        </a>
    </div>
</div>


@endsection
