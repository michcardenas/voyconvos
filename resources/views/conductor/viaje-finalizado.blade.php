@extends('layouts.app_dashboard')

@section('title', 'Viaje Finalizado')

@section('content')
<div class="viaje-finalizado-wrapper">
    <div class="container">
        <!-- Header de estado -->
        <div class="status-header finalizado">
            <h1>✅ Viaje Finalizado</h1>
            <div class="trip-info">
                <strong>{{ explode(',', $viaje->origen_direccion)[0] ?? 'Origen' }}</strong>
                →
                <strong>{{ explode(',', $viaje->destino_direccion)[0] ?? 'Destino' }}</strong>
            </div>
            <div class="status-badge finalizado">
                🏁 {{ ucfirst(str_replace('_', ' ', $viaje->estado)) }}
            </div>
            <div class="fecha-finalizacion">
                Finalizado: {{ $viaje->updated_at->format('d/m/Y H:i') }}
            </div>
        </div>

        <!-- Resumen del viaje -->
        <div class="resumen-viaje">
    <h3>📊 Resumen del Viaje</h3>
    <div class="stats-grid">
        <div class="stat-card success">
            <div class="stat-number">{{ $estadisticas['pasajeros_finalizados'] }}</div>
            <div class="stat-label">Pasajeros Finalizados</div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-number">{{ $estadisticas['total_pasajeros'] }}</div>
            <div class="stat-label">Total Pasajeros</div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-number">${{ number_format($estadisticas['ingresos_totales'], 0) }}</div>
            <div class="stat-label">Ingresos Totales</div>
        </div>
        
        <div class="stat-card primary">
            <div class="stat-number">{{ $viaje->distancia_km ?? '—' }} km</div>
            <div class="stat-label">Distancia Recorrida</div>
        </div>
    </div>

        <!-- Detalles del viaje -->
        <div class="detalles-viaje">
            <h3>🚗 Detalles del Viaje</h3>
            <div class="detalles-grid">
                <div class="detalle-item">
                    <strong>Fecha de Salida:</strong>
                    {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y H:i') }}
                </div>
                <div class="detalle-item">
                    <strong>Vehículo:</strong>
                    {{ $viaje->vehiculo }}
                    @if($viaje->conductor->registroConductor)
                        ({{ $viaje->conductor->registroConductor->marca_vehiculo ?? '' }} 
                         {{ $viaje->conductor->registroConductor->modelo_vehiculo ?? '' }})
                    @endif
                </div>
                <div class="detalle-item">
                    <strong>Valor por Persona:</strong>
                    ${{ number_format($viaje->valor_persona, 0) }}
                </div>
                <div class="detalle-item">
                    <strong>Puestos Totales:</strong>
                    {{ $viaje->puestos_totales }}
                </div>
                <div class="detalle-item">
                    <strong>Duración del Viaje:</strong>
                    {{ $estadisticas['duracion_viaje'] }}
                </div>
            </div>
        </div>

        <!-- Lista de pasajeros -->
   <!-- Lista de pasajeros CON CONTROL DE CALIFICACIONES -->
<div class="pasajeros-finalizados">
    <h3>👥 Pasajeros del Viaje</h3>
    
    @if($viaje->reservas->count() > 0)
        <div class="passenger-list">
            @foreach($viaje->reservas as $reserva)
                {{-- 🔢 Calcular el valor pagado correctamente --}}
                @php
                    $valorPagado = $reserva->valor_pagado ?? ($reserva->precio_por_persona * $reserva->cantidad_puestos);
                    $valorPorPersona = $reserva->precio_por_persona ?? $viaje->valor_persona ?? 0;
                    
                    // 🌟 Verificar si ya fue calificado por este conductor
                    $calificacionExistente = $reserva->calificaciones->first();
                    $yaCalificado = $calificacionExistente !== null;
                @endphp
                
                <div class="passenger-item-final {{ $reserva->estado }} {{ $yaCalificado ? 'ya-calificado' : '' }}">
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
                            @if($valorPagado > 0)
                                Total pagado: ${{ number_format($valorPagado, 0) }}
                                @if($reserva->cantidad_puestos > 1)
                                    <small style="color: #666;">({{ $reserva->cantidad_puestos }} × ${{ number_format($valorPorPersona, 0) }})</small>
                                @endif
                            @else
                                <span style="color: #dc3545;">Sin información de pago</span>
                            @endif
                        </div>
                        
                        {{-- 🔍 Información de códigos --}}
                        @if($reserva->notificado !== null)
                            <div class="codigo-info" style="font-size: 0.8rem; color: #666; margin-top: 0.25rem;">
                                Código: {{ $reserva->notificado ? 'Correcto ✅' : 'Incorrecto ⚠️' }}
                            </div>
                        @endif

                        {{-- 🌟 MOSTRAR CALIFICACIÓN EXISTENTE --}}
                        @if($yaCalificado)
                            <div class="calificacion-existente" style="margin-top: 0.5rem; padding: 0.5rem; background: #fff3cd; border-radius: 5px;">
                                <div class="estrellas-existente">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="estrella-mostrar {{ $i <= $calificacionExistente->calificacion ? 'activa' : '' }}">⭐</span>
                                    @endfor
                                    <small style="margin-left: 0.5rem; color: #856404;">
                                        ({{ $calificacionExistente->calificacion }}/5)
                                    </small>
                                </div>
                                @if($calificacionExistente->comentario)
                                    <div class="comentario-existente" style="font-size: 0.8rem; color: #856404; margin-top: 0.25rem; font-style: italic;">
                                        "{{ $calificacionExistente->comentario }}"
                                    </div>
                                @endif
                                <div class="fecha-calificacion" style="font-size: 0.7rem; color: #6c757d; margin-top: 0.25rem;">
                                    Calificado el {{ $calificacionExistente->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="passenger-status-final">
                        @if($reserva->estado === 'finalizado')
                            {{-- 🎯 Mostrar estado según código --}}
                            @if($reserva->notificado === 1)
                                <span class="badge-success">✅ Finalizado (código OK)</span>
                            @elseif($reserva->notificado === 0)
                                <span class="badge-warning">✅ Finalizado (sin código)</span>
                            @else
                                <span class="badge-success">✅ Finalizado</span>
                            @endif
                            
                            {{-- 🌟 BOTÓN CALIFICAR (solo si NO está calificado) --}}
                            @if(!$yaCalificado)
                                <button type="button" 
                                        class="btn-calificar" 
                                        onclick="abrirModalCalificar({{ $reserva->id }}, '{{ $reserva->user->name }}')"
                                        id="btn-calificar-{{ $reserva->id }}">
                                    ⭐ Calificar
                                </button>
                            @else
                                <span class="badge-calificado">⭐ Ya Calificado</span>
                            @endif
                        @else
                            <span class="badge-warning">⚠️ No finalizado</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- 📊 Resumen de calificaciones --}}
        @if($estadisticas['total_calificaciones'] > 0)
            <div class="resumen-calificaciones" style="margin-top: 2rem; padding: 1rem; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                <h5 style="color: #856404; margin-bottom: 1rem;">⭐ Resumen de Calificaciones</h5>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Total calificaciones:</strong><br>
                        {{ $estadisticas['total_calificaciones'] }} de {{ $estadisticas['pasajeros_finalizados'] }}
                    </div>
                    <div class="col-md-4">
                        <strong>Promedio:</strong><br>
                        {{ number_format($estadisticas['promedio_calificaciones'], 1) }}/5 ⭐
                    </div>
                    <div class="col-md-4">
                        <strong>Pendientes:</strong><br>
                        {{ $estadisticas['pasajeros_finalizados'] - $estadisticas['total_calificaciones'] }} pasajeros
                    </div>
                </div>
            </div>
        @endif
        
        {{-- 📊 Resumen de pagos (mismo código anterior) --}}
        <div class="resumen-pagos" style="margin-top: 2rem; padding: 1rem; background: #e8f5e8; border-radius: 8px; border-left: 4px solid #28a745;">
            <h5 style="color: #155724; margin-bottom: 1rem;">💰 Resumen de Pagos</h5>
            <div class="row">
                <div class="col-md-3">
                    <strong>Total recaudado:</strong><br>
                    ${{ number_format($estadisticas['ingresos_totales'], 0) }}
                </div>
                <div class="col-md-3">
                    <strong>Puestos vendidos:</strong><br>
                    {{ $estadisticas['puestos_vendidos'] }}
                </div>
                <div class="col-md-3">
                    <strong>Promedio por puesto:</strong><br>
                    ${{ $estadisticas['puestos_vendidos'] > 0 ? number_format($estadisticas['ingresos_totales'] / $estadisticas['puestos_vendidos'], 0) : 0 }}
                </div>
                <div class="col-md-3">
                    <strong>Valor esperado:</strong><br>
                    ${{ number_format($estadisticas['total_esperado'], 0) }}
                </div>
            </div>
        </div>
        
    @else
        <div class="text-center py-4">
            <p class="text-muted">No hubo pasajeros en este viaje.</p>
        </div>
    @endif
</div>

<!-- 🌟 MODAL PARA CALIFICAR PASAJERO -->
<div id="modalCalificarPasajero" class="modal-overlay-calificar">
    <div class="modal-container-calificar">
        <!-- Icono y título -->
        <div class="modal-icon-calificar">
            <i class="fas fa-star"></i>
        </div>
        
        <h2 class="modal-title-calificar">Calificar Pasajero</h2>
        
        <!-- Información del pasajero -->
        <div class="pasajero-info-modal">
            <div class="info-row">
                <strong id="nombrePasajeroCalificar">Nombre del pasajero</strong>
            </div>
            <div class="info-subtitle">
                ¿Cómo fue tu experiencia con este pasajero?
            </div>
        </div>
        
        <!-- Sistema de estrellas -->
        <div class="rating-section">
            <label class="rating-label">Calificación:</label>
            <div class="stars-container">
                <span class="star" data-rating="1">⭐</span>
                <span class="star" data-rating="2">⭐</span>
                <span class="star" data-rating="3">⭐</span>
                <span class="star" data-rating="4">⭐</span>
                <span class="star" data-rating="5">⭐</span>
            </div>
            <div class="rating-text" id="ratingText">Selecciona una calificación</div>
        </div>
        
        <!-- Campo de comentario -->
        <div class="comentario-section">
            <label for="comentarioCalificacion" class="comentario-label">
                💬 Comentario (Opcional)
            </label>
            <textarea 
                id="comentarioCalificacion" 
                class="comentario-textarea"
                placeholder="Escribe tu experiencia con este pasajero..."
                maxlength="500"
                rows="4"></textarea>
            <div class="comentario-help">
                <small id="contadorCaracteres">0/500 caracteres</small>
            </div>
        </div>
        
        <!-- Botones -->
        <div class="modal-buttons-calificar">
            <button class="modal-btn-cancel" onclick="cerrarModalCalificar()">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button class="modal-btn-confirm" onclick="confirmarCalificacion()" id="btnConfirmarCalificacion">
                <i class="fas fa-check"></i> Enviar Calificación
            </button>
        </div>
    </div>
</div>

        <!-- Observaciones (si las hay) -->
        @if($viaje->observaciones)
            <div class="observaciones-viaje">
                <h3>📝 Observaciones</h3>
                <p>{{ $viaje->observaciones }}</p>
            </div>
        @endif

        <!-- Acciones -->
        <div class="acciones-finalizadas">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                📊 Ir al Dashboard
            </a>
            
            <!-- <a href="{{ route('conductor.viaje.detalles', $viaje->id) }}" class="btn btn-outline-secondary">
                📋 Ver Detalles Completos
            </a> -->
            
            <button onclick="window.print()" class="btn btn-outline-info">
                🖨️ Imprimir Resumen
            </button>
        </div>
    </div>
</div>

<style>
    /* 🌟 Estilos para calificaciones existentes */
.calificacion-existente {
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: #fff3cd;
    border-radius: 5px;
    border-left: 3px solid #ffc107;
}

.estrellas-existente {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.estrella-mostrar {
    font-size: 1rem;
    margin-right: 2px;
}

.estrella-mostrar.activa {
    filter: grayscale(0%);
    opacity: 1;
}

.estrella-mostrar:not(.activa) {
    filter: grayscale(100%);
    opacity: 0.3;
}

.comentario-existente {
    font-size: 0.8rem;
    color: #856404;
    margin-top: 0.25rem;
    font-style: italic;
    line-height: 1.3;
}

.fecha-calificacion {
    font-size: 0.7rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

/* 🏷️ Badge para "Ya Calificado" */
.badge-calificado {
    background: linear-gradient(135deg, #6f42c1, #5a2d91);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    white-space: nowrap;
    margin-top: 0.5rem;
    display: inline-block;
    box-shadow: 0 2px 4px rgba(111, 66, 193, 0.3);
}

/* 🎨 Estilo especial para pasajeros ya calificados */
.passenger-item-final.ya-calificado {
    background: #f8f9ff;
    border-left-color: #6f42c1;
}

.passenger-item-final.ya-calificado .passenger-avatar {
    background: linear-gradient(135deg, #6f42c1, #5a2d91);
}

/* 📊 Resumen de calificaciones */
.resumen-calificaciones {
    margin-top: 2rem;
    padding: 1rem;
    background: #fff3cd;
    border-radius: 8px;
    border-left: 4px solid #ffc107;
}

.resumen-calificaciones h5 {
    color: #856404;
    margin-bottom: 1rem;
}

.resumen-calificaciones .row {
    display: flex;
    flex-wrap: wrap;
}

.resumen-calificaciones .col-md-4 {
    flex: 1;
    min-width: 200px;
    margin-bottom: 0.5rem;
    padding: 0.5rem;
}

/* 🔄 Animación para actualización en tiempo real */
@keyframes nuevaCalificacion {
    0% {
        background: #d1ecf1;
        transform: scale(1.02);
    }
    100% {
        background: #fff3cd;
        transform: scale(1);
    }
}

.calificacion-existente.nueva {
    animation: nuevaCalificacion 2s ease-out;
}

/* 📱 Responsive para calificaciones */
@media (max-width: 768px) {
    .resumen-calificaciones .row {
        flex-direction: column;
    }
    
    .resumen-calificaciones .col-md-4 {
        min-width: 100%;
        text-align: center;
    }
    
    .estrellas-existente {
        justify-content: center;
    }
    
    .badge-calificado {
        margin-top: 0.25rem;
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }
}

/* 🎯 Estados hover para elementos interactivos */
.btn-calificar:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
}

.badge-calificado:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(111, 66, 193, 0.4);
}

/* 💫 Efectos visuales mejorados */
.passenger-item-final {
    transition: all 0.3s ease;
}

.passenger-item-final:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.calificacion-existente {
    transition: all 0.3s ease;
}

.calificacion-existente:hover {
    background: #fff5d3;
    transform: translateX(2px);
}
        .viaje-finalizado-wrapper {
            min-height: 100vh;
            padding: 2rem 0;
        }

        .status-header.finalizado {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .status-badge.finalizado {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            display: inline-block;
            margin-top: 1rem;
        }

        .fecha-finalizacion {
            margin-top: 1rem;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .resumen-viaje, .detalles-viaje, .pasajeros-finalizados, .observaciones-viaje {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            border-left: 4px solid #007bff;
        }

        .stat-card.success { border-left-color: #28a745; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.info { border-left-color: #17a2b8; }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            color: #666;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }

        .detalles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .detalle-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 3px solid #007bff;
        }

        .passenger-item-final {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .passenger-item-final.presente.finalizado {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }

        .passenger-item-final.ausente {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
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
        }

        .passenger-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .passenger-details {
            flex: 1;
        }

        .passenger-details h6 {
            margin: 0 0 0.5rem 0;
            font-weight: bold;
            color: #333;
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

        .badge-success {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .badge-warning {
            background: #ffc107;
            color: #212529;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .acciones-finalizadas {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            background: transparent;
            color: #6c757d;
            border: 2px solid #6c757d;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
        }

        .btn-outline-info {
            background: transparent;
            color: #17a2b8;
            border: 2px solid #17a2b8;
        }

        .btn-outline-info:hover {
            background: #17a2b8;
            color: white;
        }

        @media print {
            .acciones-finalizadas {
                display: none;
            }
            
            .viaje-finalizado-wrapper {
                background: white;
            }
        }

        @media (max-width: 768px) {
            .acciones-finalizadas {
                flex-direction: column;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .detalles-grid {
                grid-template-columns: 1fr;
            }
        }
        .btn-calificar {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            box-shadow: 0 2px 10px rgba(255, 215, 0, 0.3);
        }

        .btn-calificar:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        }

        /* 🌟 ESTILOS PARA EL MODAL DE CALIFICACIÓN */
        .modal-overlay-calificar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .modal-overlay-calificar.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-container-calificar {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.8) translateY(50px);
            transition: all 0.3s ease;
        }

        .modal-overlay-calificar.show .modal-container-calificar {
            transform: scale(1) translateY(0);
        }

        .modal-icon-calificar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
        }

        .modal-title-calificar {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .pasajero-info-modal {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .info-subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            font-style: italic;
        }

        /* ⭐ SISTEMA DE ESTRELLAS */
        .rating-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .rating-label {
            display: block;
            margin-bottom: 1rem;
            font-weight: 600;
            color: #333;
        }

        .stars-container {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .star {
            font-size: 2rem;
            cursor: pointer;
            transition: all 0.2s ease;
            filter: grayscale(100%);
            opacity: 0.5;
        }

        .star:hover,
        .star.active {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.1);
        }

        .star.active {
            text-shadow: 0 0 10px #FFD700;
        }

        .rating-text {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* 💬 SECCIÓN DE COMENTARIO */
        .comentario-section {
            margin-bottom: 2rem;
        }

        .comentario-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .comentario-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.9rem;
            resize: vertical;
            transition: border-color 0.3s ease;
        }

        .comentario-textarea:focus {
            outline: none;
            border-color: #FFD700;
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
        }

        .comentario-help {
            text-align: right;
            margin-top: 0.5rem;
        }

        .comentario-help small {
            color: #666;
            font-size: 0.8rem;
        }

        /* 🔘 BOTONES DEL MODAL */
        .modal-buttons-calificar {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-btn-cancel,
        .modal-btn-confirm {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-btn-cancel {
            background: #6c757d;
            color: white;
        }

        .modal-btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .modal-btn-confirm {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            opacity: 0.5;
            cursor: not-allowed;
        }

        .modal-btn-confirm.enabled {
            opacity: 1;
            cursor: pointer;
        }

        .modal-btn-confirm.enabled:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        /* 📱 RESPONSIVE */
        @media (max-width: 768px) {
            .modal-container-calificar {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .modal-buttons-calificar {
                flex-direction: column;
            }
            
            .stars-container {
                gap: 0.3rem;
            }
            
            .star {
                font-size: 1.5rem;
            }
        }
</style>

<script>
let reservaIdCalificar = null;
let calificacionSeleccionada = 0;

// 🌟 Función para abrir el modal de calificación
function abrirModalCalificar(reservaId, nombrePasajero) {
    reservaIdCalificar = reservaId;
    calificacionSeleccionada = 0;
    
    // Actualizar información en el modal
    document.getElementById('nombrePasajeroCalificar').textContent = nombrePasajero;
    
    // Limpiar formulario
    document.getElementById('comentarioCalificacion').value = '';
    document.getElementById('contadorCaracteres').textContent = '0/500 caracteres';
    
    // Resetear estrellas
    document.querySelectorAll('.star').forEach(star => {
        star.classList.remove('active');
    });
    
    // Resetear texto de calificación
    document.getElementById('ratingText').textContent = 'Selecciona una calificación';
    
    // Deshabilitar botón de confirmar
    const btnConfirmar = document.getElementById('btnConfirmarCalificacion');
    btnConfirmar.classList.remove('enabled');
    
    // Mostrar modal
    const modal = document.getElementById('modalCalificarPasajero');
    modal.classList.add('show');
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
    
    console.log('Modal de calificación abierto para reserva:', reservaId, 'Pasajero:', nombrePasajero);
}

// 🚪 Función para cerrar el modal
function cerrarModalCalificar() {
    const modal = document.getElementById('modalCalificarPasajero');
    modal.classList.remove('show');
    
    // Restaurar scroll del body
    document.body.style.overflow = '';
    reservaIdCalificar = null;
    calificacionSeleccionada = 0;
}

// ⭐ Manejo de estrellas
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingTexts = ['', 'Muy malo', 'Malo', 'Regular', 'Bueno', 'Excelente'];
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            calificacionSeleccionada = rating;
            
            // Actualizar estrellas visuales
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
            
            // Actualizar texto
            document.getElementById('ratingText').textContent = ratingTexts[rating];
            
            // Habilitar botón de confirmar
            const btnConfirmar = document.getElementById('btnConfirmarCalificacion');
            btnConfirmar.classList.add('enabled');
            
            console.log('Calificación seleccionada:', rating);
        });
        
        // Efecto hover
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.style.filter = 'grayscale(0%)';
                    s.style.opacity = '1';
                } else {
                    s.style.filter = 'grayscale(100%)';
                    s.style.opacity = '0.5';
                }
            });
        });
    });
    
    // Restaurar estrellas al salir del hover
    document.querySelector('.stars-container').addEventListener('mouseleave', function() {
        stars.forEach((star, index) => {
            if (star.classList.contains('active')) {
                star.style.filter = 'grayscale(0%)';
                star.style.opacity = '1';
            } else {
                star.style.filter = 'grayscale(100%)';
                star.style.opacity = '0.5';
            }
        });
    });
    
    // Contador de caracteres
    const comentarioTextarea = document.getElementById('comentarioCalificacion');
    comentarioTextarea.addEventListener('input', function() {
        const caracteresUsados = this.value.length;
        document.getElementById('contadorCaracteres').textContent = `${caracteresUsados}/500 caracteres`;
    });
});

// 📝 Función para confirmar calificación (temporal)
// 📝 Función para confirmar calificación (MEJORADA)
function confirmarCalificacion() {
    if (calificacionSeleccionada === 0) {
        alert('Por favor selecciona una calificación');
        return;
    }

    const comentario = document.getElementById('comentarioCalificacion').value.trim();
    const btn = document.getElementById('btnConfirmarCalificacion');
    const textoOriginal = btn.innerHTML;

    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
    btn.disabled = true;

    // ✅ Obtener token del meta tag
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!token) {
        alert('Error: Token de seguridad no encontrado');
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
        return;
    }

    fetch(`/conductor/calificar-pasajero/${reservaIdCalificar}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            calificacion: calificacionSeleccionada,
            comentario: comentario
        })
    })
    .then(response => {
        // 🔍 Capturar tanto respuestas exitosas como errores
        return response.json().then(data => {
            return { data, status: response.status };
        });
    })
    .then(({ data, status }) => {
        console.log('Response data:', data, 'Status:', status);
        
        if (data.success) {
            // ✅ ÉXITO: Calificación enviada
            alert('¡Calificación enviada exitosamente!');
            cerrarModalCalificar();
            
            // 🎨 Actualizar visualmente el botón de calificar
            actualizarVistaCalificacion(reservaIdCalificar, data.data);
            
        } else {
            // ❌ ERROR: Manejar diferentes tipos de errores
            if (data.codigo === 'YA_CALIFICADO') {
                alert('⚠️ Ya has calificado a este pasajero anteriormente');
                cerrarModalCalificar();
                
                // 🔄 Recargar página para actualizar vista
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        }
        
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
    });
}

// 🎨 Función para actualizar vista después de calificar
function actualizarVistaCalificacion(reservaId, calificacionData) {
    const btnCalificar = document.getElementById(`btn-calificar-${reservaId}`);
    
    if (btnCalificar) {
        // Reemplazar botón con badge de "Ya Calificado"
        btnCalificar.outerHTML = '<span class="badge-calificado">⭐ Ya Calificado</span>';
        
        // Opcional: Agregar la calificación al DOM
        const pasajeroItem = btnCalificar.closest('.passenger-item-final');
        if (pasajeroItem && calificacionData) {
            const pasajeroDetails = pasajeroItem.querySelector('.passenger-details');
            if (pasajeroDetails) {
                // Crear elemento de calificación
                const estrellas = '⭐'.repeat(calificacionData.calificacion) + '☆'.repeat(5 - calificacionData.calificacion);
                const calificacionHTML = `
                    <div class="calificacion-existente" style="margin-top: 0.5rem; padding: 0.5rem; background: #d1ecf1; border-radius: 5px;">
                        <div class="estrellas-existente">
                            ${estrellas} (${calificacionData.calificacion}/5)
                        </div>
                        ${calificacionData.comentario ? `<div class="comentario-existente" style="font-size: 0.8rem; color: #0c5460; margin-top: 0.25rem; font-style: italic;">"${calificacionData.comentario}"</div>` : ''}
                        <div class="fecha-calificacion" style="font-size: 0.7rem; color: #6c757d; margin-top: 0.25rem;">
                            Recién calificado
                        </div>
                    </div>
                `;
                pasajeroDetails.insertAdjacentHTML('beforeend', calificacionHTML);
            }
        }
    }
    
    console.log(`✅ Vista actualizada para reserva ${reservaId}`);
}

// 🌟 Función para abrir modal - verificar si ya está calificado
function abrirModalCalificar(reservaId, nombrePasajero) {
    // 🔍 Verificar si el botón aún existe (no fue calificado)
    const btnCalificar = document.getElementById(`btn-calificar-${reservaId}`);
    
    if (!btnCalificar) {
        alert('Este pasajero ya ha sido calificado anteriormente');
        return;
    }
    
    reservaIdCalificar = reservaId;
    calificacionSeleccionada = 0;
    
    // Actualizar información en el modal
    document.getElementById('nombrePasajeroCalificar').textContent = nombrePasajero;
    
    // Limpiar formulario
    document.getElementById('comentarioCalificacion').value = '';
    document.getElementById('contadorCaracteres').textContent = '0/500 caracteres';
    
    // Resetear estrellas
    document.querySelectorAll('.star').forEach(star => {
        star.classList.remove('active');
    });
    
    // Resetear texto de calificación
    document.getElementById('ratingText').textContent = 'Selecciona una calificación';
    
    // Deshabilitar botón de confirmar
    const btnConfirmar = document.getElementById('btnConfirmarCalificacion');
    btnConfirmar.classList.remove('enabled');
    
    // Mostrar modal
    const modal = document.getElementById('modalCalificarPasajero');
    modal.classList.add('show');
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
    
    console.log('Modal de calificación abierto para reserva:', reservaId, 'Pasajero:', nombrePasajero);
}
// Cerrar modal al hacer click fuera
document.getElementById('modalCalificarPasajero').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalCalificar();
    }
});

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalCalificar();
    }
});
</script>
@endsection