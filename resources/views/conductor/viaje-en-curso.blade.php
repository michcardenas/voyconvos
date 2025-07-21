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
        justify-content: space-between;
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
    
/* Ajustes para el contenedor de acciones del pasajero */
.passenger-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Botón para finalizar pasajero individual */
.btn-finalizar-pasajero {
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
    white-space: nowrap;
}

.btn-finalizar-pasajero:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
}

.btn-finalizar-pasajero:active {
    transform: translateY(0);
}

/* Badge para pasajeros ya finalizados */
.badge-finalizado {
    background: #e8f5e8;
    color: #28a745;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 1px solid #d4edda;
}

/* Modal overlay */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

/* Modal container */
.modal-container {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    border-radius: 20px;
    padding: 2rem;
    max-width: 450px;
    width: 90%;
    box-shadow: 
        0 20px 60px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.1);
    transform: scale(0.8) translateY(30px);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.modal-overlay.show .modal-container {
    transform: scale(1) translateY(0);
}

/* Línea decorativa */
.modal-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ff6b6b, #ee5a52, #ff8a80);
}

/* Icono del modal */
.modal-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
    animation: pulse 2s infinite;
}

.modal-icon i {
    font-size: 2.5rem;
    color: white;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Título del modal */
.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1.5rem;
    line-height: 1.3;
}

/* Información del pasajero en el modal */
.passenger-info-modal {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid #ff6b6b;
}

.info-row {
    margin: 0.5rem 0;
}

.info-row:first-child {
    font-size: 1.1rem;
    color: #2c3e50;
}

.info-row:last-child {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Mensaje del modal */
.modal-message {
    color: #6c757d;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.modal-highlight {
    color: #ff6b6b;
    font-weight: 600;
}

/* Botones del modal */
.modal-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.modal-btn {
    padding: 12px 30px;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 120px;
    position: relative;
    overflow: hidden;
}

/* Botón confirmar (rojo) */
.modal-btn-confirm-red {
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    color: white;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
}

.modal-btn-confirm-red:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.5);
}

/* Botón cancelar */
.modal-btn-cancel {
    background: #f8f9fa;
    color: #6c757d;
    border: 2px solid #e9ecef;
}

.modal-btn-cancel:hover {
    background: #e9ecef;
    color: #495057;
    transform: translateY(-2px);
}

/* Estado de loading */
.modal-btn.loading {
    pointer-events: none;
    opacity: 0.8;
}

.modal-btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 480px) {
    .passenger-actions {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }
    
    .btn-finalizar-pasajero {
        padding: 6px 12px;
        font-size: 0.8rem;
    }
    
    .modal-container {
        margin: 1rem;
        padding: 1.5rem;
    }

    .modal-buttons {
        flex-direction: column;
    }

    .modal-btn {
        width: 100%;
    }
}


.codigo-section {
    margin: 1.5rem 0 2rem 0;
    text-align: left;
}

.codigo-label {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.75rem;
    font-size: 1rem;
    text-align: center;
}

.codigo-input {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 1.2rem;
    font-weight: 600;
    text-align: center;
    letter-spacing: 2px;
    text-transform: uppercase;
    background: #f8f9fa;
    transition: all 0.3s ease;
    outline: none;
    font-family: 'Courier New', monospace;
}

.codigo-input:focus {
    border-color: #ff6b6b;
    background: white;
    box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
    transform: scale(1.02);
}

.codigo-input::placeholder {
    color: #adb5bd;
    font-weight: normal;
    letter-spacing: normal;
    text-transform: none;
    font-family: inherit;
}

.codigo-help {
    text-align: center;
    margin-top: 0.5rem;
}

.codigo-help small {
    color: #6c757d;
    font-size: 0.85rem;
    font-style: italic;
}

/* Efectos visuales cuando el input está vacío/lleno */
.codigo-input:valid {
    border-color: #28a745;
    background: #f8fff8;
}

.codigo-input:invalid:not(:placeholder-shown) {
    border-color: #dc3545;
    background: #fff8f8;
}

/* Animación del botón cuando el código está listo */
.modal-btn-confirm-red:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.modal-btn-confirm-red.ready {
    animation: pulse-ready 2s infinite;
}

@keyframes pulse-ready {
    0%, 100% { 
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
    }
    50% { 
        box-shadow: 0 6px 25px rgba(255, 107, 107, 0.6);
    }
}

/* Responsive para móviles */
@media (max-width: 480px) {
    .codigo-input {
        padding: 12px 16px;
        font-size: 1.1rem;
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
                                         
                    <div class="passenger-actions">
                        <div class="status-icon">
                            @if($reserva->asistencia === 'presente')
                                <span style="color: var(--vcv-success);">✅</span>
                            @else
                                <span style="color: var(--vcv-danger);">❌</span>
                            @endif
                        </div>
                        
                        <!-- 🔥 BOTÓN NUEVO para finalizar pasajero -->
                        @if($reserva->asistencia === 'presente' && $reserva->estado !== 'finalizado')
                            <button type="button" 
                                    class="btn-finalizar-pasajero" 
                                    onclick="abrirModalFinalizarPasajero({{ $reserva->id }}, '{{ $reserva->user->name }}', {{ $reserva->cantidad_puestos }})">
                                🏁 Finalizar
                            </button>
                        @elseif($reserva->estado === 'finalizado')
                            <span class="badge-finalizado">✅ Finalizado</span>
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

<!-- 🎯 MODAL PARA FINALIZAR PASAJERO -->
<div id="modalFinalizarPasajero" class="modal-overlay">
    <div class="modal-container">
        <!-- Icono y título -->
        <div class="modal-icon" style="background: linear-gradient(135deg, #ff6b6b, #ee5a52);">
            <i class="fas fa-map-marker-alt"></i>
        </div>
                 
        <h2 class="modal-title">¿Finalizar viaje del pasajero?</h2>
                 
        <!-- Información del pasajero -->
        <div class="passenger-info-modal">
            <div class="info-row">
                <strong id="pasajeroNombre">Nombre del pasajero</strong>
            </div>
            <div class="info-row">
                <span id="pasajeroPuestos">0</span> puesto(s) • Llegó a su destino
            </div>
        </div>
                 
        <!-- Mensaje -->
        <div class="modal-message">
            El pasajero ha llegado a su destino y se bajará del vehículo.
            <br><br>
            <span class="modal-highlight">Solicita el código de confirmación al pasajero:</span>
        </div>

        <!-- 🔥 NUEVO: Campo de código -->
        <!-- 🔥 SECCIÓN DE CÓDIGO ACTUALIZADA -->
<div class="codigo-section">
    <label for="codigoConfirmacion" class="codigo-label">
        🔐 Código de Confirmación (Opcional)
    </label>
    <input 
        type="text" 
        id="codigoConfirmacion" 
        class="codigo-input"
        placeholder="Ej: 0025"
        maxlength="4"
        autocomplete="off"
        autocapitalize="characters">
    <div class="codigo-help">
        <small>
            El código del pasajero aparecerá aquí<br>
            <em style="color: #666;">Si no coincide, se mostrará advertencia pero se finalizará igual</em>
        </small>
    </div>
</div>
                 
        <!-- Botones -->
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="cerrarModalPasajero()">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button class="modal-btn modal-btn-confirm-red" onclick="confirmarFinalizarPasajero()" id="btnConfirmarFinalizar">
                <i class="fas fa-check"></i> Sí, finalizar
            </button>
        </div>
    </div>
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

        // ✅ URL CORREGIDA con doble conductor (basándome en tu patrón anterior)
        fetch(`/conductor/conductor/viaje/${viajeId}/finalizar`, {
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
let reservaIdActual = null;

// Función para abrir el modal de finalizar pasajero
function abrirModalFinalizarPasajero(reservaId, nombrePasajero, cantidadPuestos) {
    reservaIdActual = reservaId;
    
    // 🎯 Generar código esperado (4 dígitos con ceros a la izquierda)
    const codigoEsperado = reservaId.toString().padStart(4, '0');
    
    // Actualizar información en el modal
    document.getElementById('pasajeroNombre').textContent = nombrePasajero;
    document.getElementById('pasajeroPuestos').textContent = cantidadPuestos;
    
    // 🔥 ACTUALIZAR: Mostrar el código esperado en el modal
    const codigoHelp = document.querySelector('.codigo-help small');
    if (codigoHelp) {
        codigoHelp.innerHTML = `El código del pasajero es: <strong>${codigoEsperado}</strong><br>
                               <em>Nota: Si no coincide, se mostrará una advertencia pero se finalizará igual</em>`;
    }
    
    // Limpiar el campo de código
    document.getElementById('codigoConfirmacion').value = '';
    
    // Mostrar modal
    const modal = document.getElementById('modalFinalizarPasajero');
    modal.classList.add('show');
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
    
    console.log('Modal abierto para reserva:', reservaId, 'Código esperado:', codigoEsperado, 'Pasajero:', nombrePasajero);
}
// Función para cerrar el modal
function cerrarModalPasajero() {
    const modal = document.getElementById('modalFinalizarPasajero');
    modal.classList.remove('show');
    
    // Restaurar scroll del body
    document.body.style.overflow = '';
    reservaIdActual = null;
}

// Función para confirmar finalización del pasajero
function confirmarFinalizarPasajero() {
    if (!reservaIdActual) return;
    
    console.log('=== INICIANDO FINALIZACIÓN ===');
    console.log('Reserva ID:', reservaIdActual);
    
    const codigoConfirmacion = document.getElementById('codigoConfirmacion').value.trim();
    console.log('Código ingresado:', codigoConfirmacion);
    
    const btnConfirmar = document.getElementById('btnConfirmarFinalizar');
    const textoOriginal = btnConfirmar.innerHTML;
    
    // Mostrar loading
    btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Finalizando...';
    btnConfirmar.disabled = true;
    
    // 🌐 Nueva URL más simple
const url = `/conductor/conductor/finalizar-pasajero/${reservaIdActual}`;
    console.log('URL:', url);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            codigo_confirmacion: codigoConfirmacion
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // 🔍 CAPTURAR EL TEXTO REAL que devuelve el servidor
        return response.text().then(text => {
            console.log('=== RESPUESTA DEL SERVIDOR ===');
            console.log(text);
            console.log('=== FIN RESPUESTA ===');
            
            // Si la respuesta parece JSON, parsearla
            if (text.startsWith('{') || text.startsWith('[')) {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('JSON inválido: ' + text.substring(0, 100));
                }
            } else {
                // Si no es JSON, mostrar el HTML recibido
                throw new Error('Servidor devolvió HTML: ' + text.substring(0, 200));
            }
        });
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            alert(data.message);
            cerrarModalPasajero();
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
            btnConfirmar.innerHTML = textoOriginal;
            btnConfirmar.disabled = false;
        }
    })
    .catch(error => {
        console.error('=== ERROR COMPLETO ===', error);
        alert('Error al conectar: ' + error.message);
        btnConfirmar.innerHTML = textoOriginal;
        btnConfirmar.disabled = false;
    });
}
// Cerrar modal al hacer click fuera
document.getElementById('modalFinalizarPasajero').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalPasajero();
    }
});

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalPasajero();
    }
});
</script>
@endsection