@extends('layouts.app_dashboard')

@section('title', 'Viaje en Curso')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-success: #28a745;
        --vcv-danger: #dc3545;
        --vcv-warning: #ffc107;
        --vcv-info: #17a2b8;
    }

    .en-curso-wrapper {
        background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 50%, #e3f2fd 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .status-header {
        background: linear-gradient(135deg, var(--vcv-success), #20c997);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        position: relative;
        overflow: hidden;
    }

    .status-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .status-header h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2.5rem;
        font-weight: 700;
        position: relative;
        z-index: 2;
    }

    .trip-info {
        font-size: 1.2rem;
        opacity: 0.95;
        position: relative;
        z-index: 2;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        font-weight: 600;
        margin-top: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-top: 4px solid;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-card.success { border-top-color: var(--vcv-success); }
    .stat-card.info { border-top-color: var(--vcv-info); }
    .stat-card.warning { border-top-color: var(--vcv-warning); }
    .stat-card.primary { border-top-color: var(--vcv-primary); }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: var(--vcv-primary);
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .passengers-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        color: var(--vcv-primary);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(31, 78, 121, 0.1);
    }

    .passenger-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
    }

    .passenger-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 4px solid;
        transition: all 0.3s ease;
    }

    .passenger-item.presente {
        border-left-color: var(--vcv-success);
        background: rgba(40, 167, 69, 0.05);
    }

    .passenger-item.ausente {
        border-left-color: var(--vcv-danger);
        background: rgba(220, 53, 69, 0.05);
        opacity: 0.7;
    }

    .passenger-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--vcv-primary), #4a90e2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .passenger-avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .passenger-details h6 {
        margin: 0 0 0.25rem 0;
        color: var(--vcv-primary);
        font-weight: 600;
    }

    .passenger-meta {
        color: #666;
        font-size: 0.85rem;
    }

    .status-icon {
        margin-left: auto;
        font-size: 1.2rem;
    }

    .actions-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .btn-finalizar {
        background: linear-gradient(135deg, var(--vcv-danger), #e74c3c);
        border: none;
        color: white;
        padding: 1rem 3rem;
        border-radius: 25px;
        font-size: 1.2rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }

    .btn-finalizar:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
    }

    .btn-finalizar:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .time-display {
        background: rgba(31, 78, 121, 0.1);
        border-radius: 10px;
        padding: 1rem;
        margin: 1rem 0;
        text-align: center;
        font-family: 'Courier New', monospace;
        font-size: 1.1rem;
        color: var(--vcv-primary);
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .status-header h1 {
            font-size: 2rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .passenger-list {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="en-curso-wrapper">
    <div class="container">
        <!-- Header de estado -->
        <div class="status-header">
            <h1>🚗 Viaje en Curso</h1>
            <div class="trip-info">
                <strong>{{ explode(',', $viaje->origen_direccion)[0] ?? 'Origen' }}</strong>
                →
                <strong>{{ explode(',', $viaje->destino_direccion)[0] ?? 'Destino' }}</strong>
            </div>
            <div class="status-badge">
                ✅ {{ ucfirst(str_replace('_', ' ', $viaje->estado)) }}
            </div>
        </div>

        <!-- Tiempo transcurrido -->
        <div class="time-display" id="tiempoTranscurrido">
            ⏱️ Tiempo de viaje: Calculando...
        </div>

        <!-- Estadísticas del viaje -->
        <div class="stats-grid">
            <div class="stat-card success">
                <div class="stat-number">{{ $estadisticas['presentes'] }}</div>
                <div class="stat-label">Pasajeros Presentes</div>
            </div>
            
            <div class="stat-card info">
                <div class="stat-number">{{ $estadisticas['puestos_ocupados'] }}</div>
                <div class="stat-label">Puestos Ocupados</div>
            </div>
            
            <div class="stat-card warning">
                <div class="stat-number">${{ number_format($estadisticas['ingresos_reales'], 0) }}</div>
                <div class="stat-label">Ingresos del Viaje</div>
            </div>
            
            <div class="stat-card primary">
                <div class="stat-number">{{ $viaje->distancia_km ?? '—' }} km</div>
                <div class="stat-label">Distancia Total</div>
            </div>
        </div>

        <!-- Lista de pasajeros -->
        <div class="passengers-section">
            <h3 class="section-title">👥 Pasajeros en el Viaje</h3>
            
            @if($viaje->reservas->count() > 0)
                <div class="passenger-list">
                    @foreach($viaje->reservas as $reserva)
                        <div class="passenger-item {{ $reserva->asistencia }}">
                            <div class="passenger-avatar">
                                @if($reserva->user->foto)
                                    <img src="{{ asset('storage/' . $reserva->user->foto) }}" alt="{{ $reserva->user->name }}">
                                @else
                                    {{ substr($reserva->user->name, 0, 1) }}
                                @endif
                            </div>
                            
                            <div class="passenger-details">
                                <h6>{{ $reserva->user->name }}</h6>
                                <div class="passenger-meta">
                                    {{ $reserva->cantidad_puestos }} puesto{{ $reserva->cantidad_puestos > 1 ? 's' : '' }}
                                    @if($reserva->user->celular)
                                        • {{ $reserva->user->celular }}
                                    @endif
                                </div>
                            </div>
                            
                            <div class="status-icon">
                                @if($reserva->asistencia === 'presente')
                                    <span style="color: var(--vcv-success);">✅</span>
                                @else
                                    <span style="color: var(--vcv-danger);">❌</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">No hay pasajeros verificados en este viaje.</p>
                </div>
            @endif
        </div>

        <!-- Acciones -->
        <div class="actions-section">
            <h4 style="color: var(--vcv-primary); margin-bottom: 1rem;">🏁 Finalizar Viaje</h4>
            <p class="text-muted mb-3">
                Cuando llegues al destino, finaliza el viaje para completar el proceso.
            </p>
            
            <button id="btnFinalizarViaje" 
                    class="btn-finalizar"
                    onclick="finalizarViaje({{ $viaje->id }})">
                🏁 FINALIZAR VIAJE
            </button>
            
            <div class="mt-3">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    📊 Ir al Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// ⏱️ Contador de tiempo transcurrido
document.addEventListener('DOMContentLoaded', function() {
    const inicioViaje = new Date('{{ $estadisticas["hora_inicio"] }}');
    
    function actualizarTiempo() {
        const ahora = new Date();
        const diff = ahora - inicioViaje;
        
        const horas = Math.floor(diff / (1000 * 60 * 60));
        const minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((diff % (1000 * 60)) / 1000);
        
        document.getElementById('tiempoTranscurrido').innerHTML = 
            `⏱️ Tiempo de viaje: ${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
    }
    
    // Actualizar cada segundo
    actualizarTiempo();
    setInterval(actualizarTiempo, 1000);
});

// 🏁 Función para finalizar viaje
function finalizarViaje(viajeId) {
    if (confirm('¿Estás seguro de finalizar el viaje?\n\nEsta acción marcará el viaje como completado y no se puede deshacer.')) {
        const btn = document.getElementById('btnFinalizarViaje');
        const originalText = btn.innerHTML;
        
        // Mostrar loading
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Finalizando...';
        btn.disabled = true;

        fetch(`/conductor/viaje/${viajeId}/finalizar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('¡Viaje finalizado exitosamente!');
                window.location.href = data.redirect_url;
            } else {
                alert('Error al finalizar viaje: ' + data.message);
                // Restaurar botón
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al conectar con el servidor');
            // Restaurar botón
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
}
</script>
@endsection