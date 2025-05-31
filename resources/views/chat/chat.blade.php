@extends('layouts.app_dashboard')

@section('content')
<div class="container py-4">
    <h3 class="text-vcv mb-4">💬 Chat de viaje</h3>

    <div class="card">
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            @forelse ($mensajes as $mensaje)
                <div class="mb-3">
                    <strong>{{ $mensaje->emisor_id === auth()->id() ? 'Tú' : 'Otro usuario' }}:</strong>
                    <p class="mb-0">{{ $mensaje->mensaje }}</p>
                    <small class="text-muted">{{ $mensaje->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <hr>
            @empty
                <p class="text-muted">No hay mensajes aún.</p>
            @endforelse
        </div>
    </div>

    <form action="{{ route('chat.enviar', $viaje->id) }}" method="POST" class="mt-3">
        @csrf
        <div class="input-group">
            <input type="text" name="mensaje" class="form-control" placeholder="Escribe un mensaje..." required>
            <button class="btn btn-primary" type="submit">Enviar</button>
        </div>
    </form>

    <a href="{{ url()->previous() }}" class="btn btn-link mt-3">← Volver</a>
</div>
@endsection
