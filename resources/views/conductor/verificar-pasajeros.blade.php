@extends('layouts.app_dashboard')

@section('title', 'Verificar Pasajeros')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-success: #28a745;
        --vcv-danger: #dc3545;
        --vcv-warning: #ffc107;
        --vcv-light: #f8f9fa;
    }

    .verification-wrapper {
        background: linear-gradient(135deg, #e3f2fd 0%, #f5f5f5 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .header-card {
        background: linear-gradient(135deg, var(--vcv-primary), #2c5a7a);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(31, 78, 121, 0.3);
    }

    .header-card h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .trip-info {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .verification-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .instructions {
        background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
        border-left: 4px solid var(--vcv-success);
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .instructions h4 {
        color: var(--vcv-success);
        margin-bottom: 0.5rem;
    }

    .passenger-item {
        background: var(--vcv-light);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .passenger-item.selected-presente {
        border-color: var(--vcv-success);
        background: rgba(40, 167, 69, 0.1);
    }

    .passenger-item.selected-ausente {
        border-color: var(--vcv-danger);
        background: rgba(220, 53, 69, 0.1);
    }

    .passenger-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .passenger-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .passenger-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--vcv-primary), #4a90e2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .passenger-avatar img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .passenger-details h5 {
        margin: 0 0 0.25rem 0;
        color: var(--vcv-primary);
        font-weight: 600;
    }

    .passenger-meta {
        color: #666;
        font-size: 0.9rem;
    }

    .attendance-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-attendance {
        padding: 0.75rem 1.5rem;
        border: 2px solid;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .btn-presente {
        border-color: var(--vcv-success);
        color: var(--vcv-success);
    }

    .btn-presente.active {
        background: var(--vcv-success);
        color: white;
    }

    .btn-ausente {
        border-color: var(--vcv-danger);
        color: var(--vcv-danger);
    }

    .btn-ausente.active {
        background: var(--vcv-danger);
        color: white;
    }

    .summary-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-top: 4px solid var(--vcv-primary);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .summary-item {
        text-align: center;
        padding: 1rem;
        background: var(--vcv-light);
        border-radius: 8px;
    }

    .summary-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.25rem;
    }

    .summary-label {
        font-size: 0.9rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--vcv-success), #20c997);
        border: none;
        color: white;
        padding: 1rem 3rem;
        border-radius: 25px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 1rem;
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .passenger-header {
            flex-direction: column;
            gap: 1rem;
        }

        .attendance-buttons {
            width: 100%;
        }

        .btn-attendance {
            flex: 1;
        }
    }
</style>

<div class="verification-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="header-card">
            <h1>🚗 Verificar Pasajeros</h1>
            <div class="trip-info">
                <strong>{{ explode(',', $viaje->origen_direccion)[0] ?? 'Origen' }}</strong>
                →
                <strong>{{ explode(',', $viaje->destino_direccion)[0] ?? 'Destino' }}</strong>
                <br>
                <small>{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }} - {{ $viaje->hora_salida }}</small>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="verification-card">
            <div class="instructions">
                <h4>📋 Instrucciones</h4>
                <p>Marca cada pasajero como <strong>Presente</strong> si subió al vehículo o <strong>Ausente</strong> si no se presentó. Una vez verificados todos, podrás continuar con el viaje.</p>
            </div>

            @if($viaje->reservas->count() > 0)
                <form action="{{ route('conductor.viaje.procesar-asistencia', $viaje->id) }}" method="POST" id="asistenciaForm">
                    @csrf
                    
                    <!-- Lista de pasajeros -->
                    @foreach($viaje->reservas as $reserva)
                        <div class="passenger-item" data-reserva="{{ $reserva->id }}">
                            <div class="passenger-header">
                                <div class="passenger-info">
                                    <div class="passenger-avatar">
                                        @if($reserva->user->foto)
                                            <img src="{{ asset('storage/' . $reserva->user->foto) }}" alt="{{ $reserva->user->name }}">
                                        @else
                                            {{ substr($reserva->user->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="passenger-details">
                                        <h5>{{ $reserva->user->name }}</h5>
                                        <div class="passenger-meta">
                                            {{ $reserva->cantidad_puestos }} puesto{{ $reserva->cantidad_puestos > 1 ? 's' : '' }}
                                            @if($reserva->user->celular)
                                                • {{ $reserva->user->celular }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="attendance-buttons">
                                    <input type="hidden" name="asistencias[{{ $reserva->id }}]" value="" required>
                                    
                                    <button type="button" 
                                            class="btn-attendance btn-presente" 
                                            onclick="marcarAsistencia({{ $reserva->id }}, 'presente')">
                                        ✅ Presente
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn-attendance btn-ausente" 
                                            onclick="marcarAsistencia({{ $reserva->id }}, 'ausente')">
                                        ❌ Ausente
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Resumen -->
                    <div class="summary-section">
                        <h4 class="text-center mb-3">📊 Resumen de Verificación</h4>
                        <div class="summary-grid">
                            <div class="summary-item">
                                <div class="summary-number" id="totalPasajeros">{{ $viaje->reservas->sum('cantidad_puestos') }}</div>
                                <div class="summary-label">Total Reservados</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-number text-success" id="presentesCount">0</div>
                                <div class="summary-label">Presentes</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-number text-danger" id="ausentesCount">0</div>
                                <div class="summary-label">Ausentes</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-number text-warning" id="pendientesCount">{{ $viaje->reservas->count() }}</div>
                                <div class="summary-label">Sin Verificar</div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" id="btnContinuar" disabled>
                            🚀 Continuar Viaje
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-5">
                    <h4>😔 No hay pasajeros confirmados</h4>
                    <p>No hay reservas confirmadas para este viaje.</p>
                    <a href="{{ route('conductor.viaje.detalle', $viaje->id) }}" class="btn btn-secondary">
                        ← Volver a detalles
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
let asistencias = {};
const totalReservas = {{ $viaje->reservas->count() }};

function marcarAsistencia(reservaId, estado) {
    // Actualizar estado
    asistencias[reservaId] = estado;
    
    // Actualizar input hidden
    document.querySelector(`input[name="asistencias[${reservaId}]"]`).value = estado;
    
    // Actualizar visual del item
    const item = document.querySelector(`[data-reserva="${reservaId}"]`);
    item.classList.remove('selected-presente', 'selected-ausente');
    item.classList.add(`selected-${estado}`);
    
    // Actualizar botones
    const botones = item.querySelectorAll('.btn-attendance');
    botones.forEach(btn => btn.classList.remove('active'));
    item.querySelector(`.btn-${estado}`).classList.add('active');
    
    // Actualizar contadores
    actualizarContadores();
    
    // Verificar si se puede continuar
    verificarContinuar();
}

function actualizarContadores() {
    let presentes = 0;
    let ausentes = 0;
    let pendientes = totalReservas;
    
    @foreach($viaje->reservas as $reserva)
        if (asistencias[{{ $reserva->id }}]) {
            pendientes--;
            if (asistencias[{{ $reserva->id }}] === 'presente') {
                presentes += {{ $reserva->cantidad_puestos }};
            } else {
                ausentes += {{ $reserva->cantidad_puestos }};
            }
        }
    @endforeach
    
    document.getElementById('presentesCount').textContent = presentes;
    document.getElementById('ausentesCount').textContent = ausentes;
    document.getElementById('pendientesCount').textContent = pendientes;
}

function verificarContinuar() {
    const todosMarcados = Object.keys(asistencias).length === totalReservas;
    const btnContinuar = document.getElementById('btnContinuar');
    
    btnContinuar.disabled = !todosMarcados;
    
    if (todosMarcados) {
        btnContinuar.innerHTML = '🚀 Continuar Viaje';
    } else {
        btnContinuar.innerHTML = `⏳ Verifica ${totalReservas - Object.keys(asistencias).length} pasajero(s) más`;
    }
}

// Confirmación antes de enviar
document.getElementById('asistenciaForm').addEventListener('submit', function(e) {
    const presentes = Object.values(asistencias).filter(a => a === 'presente').length;
    const ausentes = Object.values(asistencias).filter(a => a === 'ausente').length;
    
    if (!confirm(`¿Confirmar verificación?\n\nPresentes: ${presentes}\nAusentes: ${ausentes}\n\nEsta acción no se puede deshacer.`)) {
        e.preventDefault();
    }
});
</script>
@endsection