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
.badge-finalizado {
    background: #28a745;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    white-space: nowrap;
}

.badge-finalizado-sin-codigo {
    background: #ffc107;
    color: #212529;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    white-space: nowrap;
    border: 2px solid #e0a800;
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
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
    border-left: 4px solid #007bff;
}

.passenger-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Pasajero finalizado */
.passenger-item.finalizado {
    background: #d4edda;
    border-left-color: #28a745;
}

.passenger-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 1rem;
    flex-shrink: 0;
}

.passenger-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.passenger-details {
    flex: 1;
    min-width: 0; /* Para que funcione el overflow */
}

.passenger-details h6 {
    margin: 0 0 0.5rem 0;
    font-weight: bold;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.passenger-meta {
    color: #666;
    font-size: 0.9rem;
}

.passenger-payment {
    color: #28a745;
    font-weight: bold;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

/* 🔥 ESTILOS PARA CONTROLES INLINE */
.passenger-actions-inline {
    display: flex;
    align-items: center;
    margin-left: 1rem;
    flex-shrink: 0;
}

.finalizado-container {
    display: flex;
    align-items: center;
}

.badge-finalizado {
    background: #28a745;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    white-space: nowrap;
}

.finalizacion-controls {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: white;
    padding: 0.5rem;
    border-radius: 8px;
    border: 2px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.codigo-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.codigo-label-inline {
    font-size: 0.8rem;
    font-weight: 600;
    color: #495057;
    white-space: nowrap;
}

.codigo-input-inline {
    width: 70px;
    padding: 0.4rem 0.6rem;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-family: monospace;
    font-size: 0.9rem;
    text-align: center;
    font-weight: bold;
    transition: all 0.3s ease;
}

.codigo-input-inline:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.codigo-input-inline::placeholder {
    color: #28a745;
    font-weight: bold;
}

.btn-finalizar-inline {
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    color: white;
    border: none;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    box-shadow: 0 2px 4px rgba(255, 107, 107, 0.3);
}

.btn-finalizar-inline:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 107, 107, 0.4);
}

.btn-finalizar-inline:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* 📱 RESPONSIVE DESIGN */
@media (max-width: 768px) {
    .passenger-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .passenger-actions-inline {
        width: 100%;
        justify-content: center;
        margin-left: 0;
    }
    
    .finalizacion-controls {
        flex-direction: column;
        gap: 0.5rem;
        width: 100%;
        align-items: stretch;
    }
    
    .codigo-container {
        justify-content: center;
    }
    
    .codigo-input-inline {
        width: 80px;
    }
}

@media (max-width: 480px) {
    .finalizacion-controls {
        padding: 0.75rem;
    }
    
    .codigo-label-inline {
        font-size: 0.9rem;
    }
    
    .codigo-input-inline {
        width: 90px;
        padding: 0.5rem;
        font-size: 1rem;
    }
    
    .btn-finalizar-inline {
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
    }
}

/* 🎯 ANIMACIONES DE ESTADO */
@keyframes finalizando {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

.passenger-item.finalizando {
    animation: finalizando 1s infinite;
}

.passenger-item.finalizando .finalizacion-controls {
    pointer-events: none;
    opacity: 0.7;
}

/* ✨ EFECTOS VISUALES MEJORADOS */
.finalizacion-controls:hover {
    border-color: #007bff;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.codigo-input-inline.codigo-correcto {
    border-color: #28a745;
    background-color: #f8fff9;
}

.codigo-input-inline.codigo-incorrecto {
    border-color: #dc3545;
    background-color: #fff8f8;
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
                <div class="passenger-item" id="pasajero-{{ $reserva->id }}">
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
                        <div class="passenger-payment">
                            Pagó: ${{ number_format($reserva->valor_pagado ?? 0, 0) }}
                        </div>
                    </div>
                                         
                    <div class="passenger-actions-inline">
                        @if($reserva->estado === 'finalizado')
                            {{-- 🎯 PASAJERO YA FINALIZADO --}}
                            <div class="finalizado-container">
                                <span class="badge-finalizado">✅ Finalizado</span>
                            </div>
                        @else
                            {{-- 🔥 CONTROLES INLINE PARA FINALIZAR --}}
                            <div class="finalizacion-controls">
                                <div class="codigo-container">
                                    <label for="codigo-{{ $reserva->id }}" class="codigo-label-inline">
                                         Código:
                                    </label>
                                    <input 
                                        type="text" 
                                        id="codigo-{{ $reserva->id }}" 
                                        class="codigo-input-inline"
                                        placeholder="{{ str_pad($reserva->id, 4, '0', STR_PAD_LEFT) }}"
                                        maxlength="4"
                                        autocomplete="off"
                                        autocapitalize="characters"
                                        title="Código esperado: {{ str_pad($reserva->id, 4, '0', STR_PAD_LEFT) }}">
                                </div>
                                
                                <button type="button" 
                                        class="btn-finalizar-inline" 
                                        onclick="finalizarPasajeroDirecto({{ $reserva->id }}, '{{ $reserva->user->name }}')"
                                        id="btn-finalizar-{{ $reserva->id }}">
                                     Finalizar
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4">
            <p class="text-muted">No hay pasajeros en este viaje.</p>
        </div>
    @endif
</div>
        <!-- Acciones -->
        <div class="actions-section">
            <h4 style="color: var(--vcv-primary); margin-bottom: 1rem;"> Finalizar Viaje</h4>
            <p class="text-muted mb-3">
                Cuando llegues al destino, finaliza el viaje para completar el proceso.
            </p>
            
            <button id="btnFinalizarViaje" 
                    class="btn-finalizar"
                    onclick="finalizarViaje({{ $viaje->id }})">
                 FINALIZAR VIAJE
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
    // ✅ Verificar que existe la hora de inicio
    const horaInicioElement = '{{ $estadisticas["hora_inicio"] ?? "" }}';
    
    if (!horaInicioElement) {
        console.warn('No se encontró hora de inicio, usando hora actual');
        document.getElementById('tiempoTranscurrido').innerHTML = '⏱️ Tiempo de viaje: --:--:--';
        return;
    }
    
    const inicioViaje = new Date(horaInicioElement);
    
    function actualizarTiempo() {
        const ahora = new Date();
        const diff = ahora - inicioViaje;
        
        if (diff < 0) {
            document.getElementById('tiempoTranscurrido').innerHTML = '⏱️ Tiempo de viaje: 00:00:00';
            return;
        }
        
        const horas = Math.floor(diff / (1000 * 60 * 60));
        const minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((diff % (1000 * 60)) / 1000);
        
        document.getElementById('tiempoTranscurrido').innerHTML = 
            `⏱️ Tiempo de viaje: ${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
    }
    
    // Actualizar inmediatamente y luego cada segundo
    actualizarTiempo();
    setInterval(actualizarTiempo, 1000);
    
    console.log('✅ Contador de tiempo iniciado desde:', inicioViaje);
});

// 🏁 Función para finalizar viaje completo
function finalizarViaje(viajeId) {
    if (!viajeId) {
        alert('Error: ID de viaje no válido');
        return;
    }
    
    const mensaje = '¿Estás seguro de finalizar el viaje?\n\n' +
                   'Esta acción marcará el viaje como completado y no se puede deshacer.\n' +
                   'Todos los pasajeros restantes también serán finalizados automáticamente.';
    
    if (confirm(mensaje)) {
        const btn = document.getElementById('btnFinalizarViaje');
        
        if (!btn) {
            alert('Error: No se encontró el botón de finalizar');
            return;
        }
        
        const originalText = btn.innerHTML;
        
        // 🎨 Mostrar loading
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Finalizando...';
        btn.disabled = true;
        
        // 🌐 Realizar petición
        fetch(`/conductor/conductor/viaje/${viajeId}/finalizar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': obtenerTokenCSRF()
            }
        })
        .then(response => {
            console.log('Finalizar viaje - Status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Finalizar viaje - Response:', data);
            
            if (data.success) {
                alert('¡Viaje finalizado exitosamente!');
                
                // Redireccionar o recargar según la respuesta
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.location.reload();
                }
            } else {
                alert('Error al finalizar viaje: ' + (data.message || 'Error desconocido'));
                restaurarBoton(btn, originalText);
            }
        })
        .catch(error => {
            console.error('Error al finalizar viaje:', error);
            alert('Error de conexión al finalizar el viaje');
            restaurarBoton(btn, originalText);
        });
    }
}

function finalizarPasajeroDirecto(reservaId, nombrePasajero) {
    console.log('=== FINALIZANDO PASAJERO DIRECTO ===');
    console.log('Reserva ID:', reservaId);
    console.log('Pasajero:', nombrePasajero);
    
    // 🔍 Obtener elementos
    const codigoInput = document.getElementById(`codigo-${reservaId}`);
    const btnFinalizar = document.getElementById(`btn-finalizar-${reservaId}`);
    const pasajeroItem = document.getElementById(`pasajero-${reservaId}`);
    
    // ✅ Validar elementos
    if (!codigoInput || !btnFinalizar || !pasajeroItem) {
        alert('Error: No se encontraron los elementos necesarios');
        console.error('Elementos faltantes:', { codigoInput, btnFinalizar, pasajeroItem });
        return;
    }
    
    // 📝 Obtener datos
    const codigoIngresado = codigoInput.value.trim();
    const codigoEsperado = reservaId.toString().padStart(4, '0');
    
    console.log('Código ingresado:', codigoIngresado);
    console.log('Código esperado:', codigoEsperado);
    
    // 🎨 Aplicar efectos visuales
    const textoOriginal = btnFinalizar.innerHTML;
    btnFinalizar.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btnFinalizar.disabled = true;
    pasajeroItem.classList.add('finalizando');
    
    // 🌐 URL CORREGIDA - sin doble conductor
    const url = `/conductor/finalizar-pasajero/${reservaId}`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': obtenerTokenCSRF()
        },
        body: JSON.stringify({
            codigo_confirmacion: codigoIngresado
        })
    })
    .then(response => {
        console.log('Finalizar pasajero - Status:', response.status);
        
        return response.text().then(text => {
            console.log('=== RESPUESTA DEL SERVIDOR ===');
            console.log(text.substring(0, 500) + (text.length > 500 ? '...' : ''));
            
            // 🔍 Intentar parsear como JSON
            if (text.trim().startsWith('{') || text.trim().startsWith('[')) {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error(`JSON inválido: ${e.message}`);
                }
            } else {
                throw new Error(`Servidor devolvió HTML en lugar de JSON. Inicio: ${text.substring(0, 100)}`);
            }
        });
    })
    .then(data => {
        console.log('Finalizar pasajero - Response:', data);
        
        if (data.success) {
            // 🎉 Mostrar resultado según código
            mostrarExitoPasajero(reservaId, nombrePasajero, data.data.codigo_correcto, data.message);
        } else {
            alert('Error: ' + (data.message || 'Error desconocido'));
            restaurarEstadoPasajero(reservaId, textoOriginal);
        }
    })
    .catch(error => {
        console.error('Error al finalizar pasajero:', error);
        alert('Error de conexión: ' + error.message);
        restaurarEstadoPasajero(reservaId, textoOriginal);
    });
}

// 🎉 Función para mostrar éxito al finalizar pasajero (ACTUALIZADA)
function mostrarExitoPasajero(reservaId, nombrePasajero, codigoCorrecto, mensaje) {
    const pasajeroItem = document.getElementById(`pasajero-${reservaId}`);
    const actionsContainer = pasajeroItem.querySelector('.passenger-actions-inline');
    
    // 📢 Mostrar mensaje de éxito
    alert(mensaje);
    
    // 🎨 Actualizar interfaz
    const badgeClass = codigoCorrecto ? 'badge-finalizado' : 'badge-finalizado-sin-codigo';
    const badgeText = codigoCorrecto ? '✅ Finalizado' : '✅ Finalizado (sin código)';
    
    actionsContainer.innerHTML = `
        <div class="finalizado-container">
            <span class="${badgeClass}">${badgeText}</span>
        </div>
    `;
    
    // 🏷️ Actualizar clases
    pasajeroItem.classList.remove('finalizando');
    pasajeroItem.classList.add('finalizado');
    
    console.log(`✅ Pasajero ${nombrePasajero} finalizado - Código correcto: ${codigoCorrecto}`);
}

// 🎉 Función para mostrar éxito al finalizar pasajero
function mostrarExitoPasajero(reservaId, nombrePasajero, codigoIngresado, codigoEsperado, mensaje) {
    const pasajeroItem = document.getElementById(`pasajero-${reservaId}`);
    const actionsContainer = pasajeroItem.querySelector('.passenger-actions-inline');
    
    // 🔍 Verificar coincidencia de código
    const codigoCoincide = codigoIngresado === codigoEsperado;
    let mensajeCompleto = mensaje || `✅ ${nombrePasajero} ha sido finalizado`;
    
    if (codigoIngresado && !codigoCoincide) {
        mensajeCompleto += `\n⚠️ Código no coincidía (esperado: ${codigoEsperado}, ingresado: ${codigoIngresado})`;
    }
    
    // 📢 Mostrar mensaje de éxito
    alert(mensajeCompleto);
    
    // 🎨 Actualizar interfaz
    actionsContainer.innerHTML = `
        <div class="finalizado-container">
            <span class="badge-finalizado">✅ Finalizado</span>
        </div>
    `;
    
    // 🏷️ Actualizar clases
    pasajeroItem.classList.remove('finalizando');
    pasajeroItem.classList.add('finalizado');
    
    console.log(`✅ Pasajero ${nombrePasajero} finalizado exitosamente`);
}

// 🔄 Función para restaurar estado del pasajero en caso de error
function restaurarEstadoPasajero(reservaId, textoOriginal) {
    const btnFinalizar = document.getElementById(`btn-finalizar-${reservaId}`);
    const pasajeroItem = document.getElementById(`pasajero-${reservaId}`);
    
    if (btnFinalizar) {
        btnFinalizar.innerHTML = textoOriginal;
        btnFinalizar.disabled = false;
    }
    
    if (pasajeroItem) {
        pasajeroItem.classList.remove('finalizando');
    }
    
    console.log(`🔄 Estado restaurado para pasajero ${reservaId}`);
}

// 🔄 Función para restaurar botón genérico
function restaurarBoton(btn, textoOriginal) {
    if (btn) {
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
    }
}

// 🛡️ Función helper para obtener token CSRF
function obtenerTokenCSRF() {
    const token = document.querySelector('meta[name="csrf-token"]');
    
    if (!token) {
        console.error('❌ Token CSRF no encontrado en el meta tag');
        throw new Error('Token de seguridad no encontrado');
    }
    
    return token.getAttribute('content');
}

// 🎯 Validación en tiempo real del código (MEJORADA)
document.addEventListener('DOMContentLoaded', function() {
    const codigoInputs = document.querySelectorAll('.codigo-input-inline');
    
    console.log(`📝 Inicializando ${codigoInputs.length} inputs de código`);
    
    codigoInputs.forEach((input, index) => {
        console.log(`Configurando input ${index + 1}:`, input.id);
        
        // 📝 Validación en tiempo real
        input.addEventListener('input', function() {
            // Solo números
            let valor = this.value.replace(/\D/g, '');
            
            // Máximo 4 dígitos
            if (valor.length > 4) {
                valor = valor.slice(0, 4);
            }
            
            this.value = valor;
            
            // 🎨 Validación visual
            const reservaId = this.id.split('-')[1];
            const codigoEsperado = reservaId.toString().padStart(4, '0');
            
            // Limpiar clases anteriores
            this.classList.remove('codigo-correcto', 'codigo-incorrecto');
            
            if (valor.length === 4) {
                if (valor === codigoEsperado) {
                    this.classList.add('codigo-correcto');
                } else {
                    this.classList.add('codigo-incorrecto');
                }
            }
        });
        
        // ⌨️ Enter para finalizar
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                
                const reservaId = this.id.split('-')[1];
                const btnFinalizar = document.getElementById(`btn-finalizar-${reservaId}`);
                
                if (btnFinalizar && !btnFinalizar.disabled) {
                    btnFinalizar.click();
                } else {
                    console.log('Botón de finalizar no disponible o deshabilitado');
                }
            }
        });
        
        // 🎯 Focus automático en mobile
        input.addEventListener('focus', function() {
            // En móviles, hacer scroll hacia el elemento
            if (window.innerWidth <= 768) {
                setTimeout(() => {
                    this.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }, 300);
            }
        });
    });
    
    console.log('✅ Validación de códigos configurada correctamente');
});
</script>
@endsection