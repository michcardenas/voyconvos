@extends('layouts.app_dashboard')

@section('title', 'Detalle del Viaje')

@section('content')
<style>
    /* Variables para mantener consistencia */
    :root {
        --vcv-primary: #003366;
        --vcv-primary-light: #004080;
        --vcv-info: #00BFFF;
        --vcv-success: #28a745;
        --vcv-warning: #ffc107;
        --vcv-danger: #dc3545;
        --border-color: rgba(0, 51, 102, 0.1);
        --shadow-card: 0 4px 12px rgba(0, 51, 102, 0.1);
        --shadow-soft: 0 2px 8px rgba(0, 51, 102, 0.08);
        --border-radius: 12px;
        --transition: all 0.3s ease;
    }

    /* Contenedor principal */
    .trip-detail-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Header principal */
    .trip-header {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, var(--vcv-primary-light) 100%);
        color: white;
        padding: 2rem;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
        text-align: center;
        margin-bottom: 0;
    }

    .trip-header h2 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 600;
        letter-spacing: -0.02em;
    }

    .trip-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 1rem;
    }

    /* Contenedor de contenido */
    .trip-content {
        background: white;
        border-radius: 0 0 var(--border-radius) var(--border-radius);
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    /* Secciones */
    .trip-section {
        padding: 2rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .trip-section:last-child {
        border-bottom: none;
    }

    .section-title {
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Lista de información de ruta */
    .route-info {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .route-info li {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .route-info li:last-child {
        border-bottom: none;
    }

    .route-info li strong {
        color: var(--vcv-primary);
        font-weight: 600;
        min-width: 100px;
    }

    .route-info li span {
        color: #495057;
        font-weight: 500;
    }

    /* Valor estimado destacado */
    .estimated-value {
        background: linear-gradient(135deg, var(--vcv-success) 0%, #20c997 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-soft);
    }

    .estimated-value .amount {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .estimated-value .label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 0.5rem;
    }

    /* Formularios mejorados */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: var(--transition);
        background-color: #fff;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--vcv-info);
        box-shadow: 0 0 0 3px rgba(0, 191, 255, 0.1);
    }

    .form-control:read-only {
        background-color: #f8f9fa;
        color: var(--vcv-primary);
        font-weight: 600;
    }

    /* Grid para formularios */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    /* Botón principal */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--vcv-success) 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
        display: block;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
    }

    .btn-primary-custom:active {
        transform: translateY(0);
    }

    /* Mensajes de estado */
    .message {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-top: 1.5rem;
        text-align: center;
        font-weight: 500;
        display: none;
    }

    .message-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .message-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    /* Área de botón centrada */
    .button-area {
        text-align: center;
        padding: 2rem;
        background: #f8f9fa;
    }

    /* Cards para organizar mejor la información */
    .info-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--vcv-info);
    }

    .value-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        padding: 1.5rem;
        border: 2px solid var(--border-color);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .trip-detail-container {
            margin: 1rem;
        }
        
        .trip-header {
            padding: 1.5rem;
        }
        
        .trip-header h2 {
            font-size: 1.5rem;
        }
        
        .trip-section {
            padding: 1.5rem;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
        
        .estimated-value .amount {
            font-size: 1.8rem;
        }
    }

    /* Animaciones sutiles */
    .trip-content {
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Iconos en los labels */
    .form-label::before {
        margin-right: 0.5rem;
    }
</style>

<div class="trip-detail-container">
    <!-- Header mejorado -->
    <div class="trip-header">
        <h2>🗺️ Detalles de ruta</h2>
        <p>Configura los detalles de tu viaje y compártelo con otros viajeros</p>
    </div>

    <div class="trip-content">
        <!-- Información de Ruta -->
        <div class="trip-section">
            <h4 class="section-title">📍 Información de Ruta</h4>
            <div class="info-card">
                <ul id="infoRuta" class="route-info">
                    <li><strong>Origen:</strong> <span id="origenDireccion"></span></li>
                    <li><strong>Destino:</strong> <span id="destinoDireccion"></span></li>
                    <li><strong>Distancia:</strong> <span id="distanciaKm"></span> </li>
                    <li><strong>Vehículo:</strong> <span id="vehiculoTipo"></span></li>
                </ul>
            </div>
        </div>

        <!-- Valor Estimado y por Persona -->
        <div class="trip-section">
            <h4 class="section-title">💰 Valor Estimado del Viaje</h4>
            
            <div class="estimated-value">
                <div class="amount">$<span id="valorCalculado">0.00</span></div>
                <div class="label">Costo estimado total del viaje</div>
            </div>

            <div class="value-card">
           
            <div class="form-group">
                <label for="puestosTotales" class="form-label">👥 Puestos totales (incluyendo conductor)</label>
                <input type="number" 
                    id="puestosTotales" 
                    class="form-control" 
                    value="{{ $registroConductor ? $registroConductor->numero_puestos : 4 }}" 
                    readonly >
            </div>
                            
                <div class="form-group">
                    <label for="valor_persona" class="form-label">💸 Valor por persona estimado</label>
                    <input type="text" id="valor_persona" class="form-control" readonly placeholder="Se calculará automáticamente">
                </div>
            </div>
        </div>

        <!-- Detalles del viaje -->
        <div class="trip-section">
            <h4 class="section-title">🕒 Detalles del viaje</h4>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="fechaViaje" class="form-label">📅 Fecha del viaje</label>
                    <input type="date" id="fechaViaje" class="form-control">
                </div>
               <div class="form-group">
    <label for="horaSalida" class="form-label">⏰ Hora de Salida</label>
    <input type="time" id="horaSalida" name="hora_salida" class="form-control" step="60">
</div>

                <div class="form-group">
                    <label for="puestosDisponibles" class="form-label">🪑 Puestos Disponibles</label>
                    <input type="number" id="puestosDisponibles" class="form-control" min="1" placeholder="Ej: 3">
                </div>
            </div>
    <input type="hidden" id="totalPorPasajero" name="total_por_pasajero" value="">

          <div class="form-group">
    <label for="valorCobrado" class="form-label">💵 Valor total a cobrar del viaje </label>
    <input type="number"
           id="valorCobrado"
           class="form-control"
           placeholder="El valor que recibira despues de concluir su viaje, este valor sera divido por todos los pasajeros"
           onchange="calcularValorPorPersona()">
</div>

<!-- Aquí se mostrará el resultado o error -->
<div id="textoValorPersona"></div>
        </div>

        <!-- Botón de acción -->
        <div class="button-area" id="botonAgendarContainer">
            <button class="btn-primary-custom" onclick="guardarInfoConductor()">
                🚗 Agendar viaje
            </button>
        </div>

        <!-- Mensajes -->
        <div id="mensaje-exito" class="message message-success">
            ✅ ¡Viaje guardado exitosamente!
        </div>

        <div id="mensaje-error" class="message message-error">
            ❌ Error al guardar el viaje. Intenta nuevamente.
        </div>
    </div>
</div>

<script>
// Cargar datos del viaje desde localStorage
const viaje = JSON.parse(localStorage.getItem('ultimoViaje'));

if (viaje) {
    document.getElementById('origenDireccion').textContent = viaje.origen.direccion;
    document.getElementById('destinoDireccion').textContent = viaje.destino.direccion;
    document.getElementById('distanciaKm').textContent = viaje.distancia + 'km';
    document.getElementById('vehiculoTipo').textContent = viaje.vehiculo;
    document.getElementById('valorCalculado').textContent = parseFloat(viaje.costo).toFixed(2);
    
    // ✅ CORREGIR: Asegurar formato de fecha correcto
    if (viaje.fecha) {
        document.getElementById('fechaViaje').value = viaje.fecha;
    }
}

// ✅ FUNCIÓN PRINCIPAL - SIN DUPLICADOS
function calcularValorPorPersona() {
    const valorTotal = parseFloat(document.getElementById("valorCobrado").value) || 0;
    const puestos = parseInt(document.getElementById("puestosTotales").value) || 0;
    const costoServicio = {{ $costo_servicio }};
    const maxPorcentaje = {{ $nomasde }};
    
    console.log("🔍 Calculando:", { valorTotal, puestos, costoServicio, maxPorcentaje });
    
    // Obtener elementos del DOM
    const valorEstimado = parseFloat(document.getElementById('valorCalculado').textContent) || 0;
    const maxValorPermitido = valorEstimado * (1 + (maxPorcentaje / 100));
    
    const inputValor = document.getElementById("valorCobrado");
    const textoElemento = document.getElementById("textoValorPersona");
    const botonAgendar = document.getElementById("botonAgendarContainer");
    const inputHiddenTotal = document.getElementById("totalPorPasajero");
    
    console.log("💰 Valores base:", { valorEstimado, maxValorPermitido });
    
    // Validar límite máximo
    if (valorTotal > 0 && valorTotal > maxValorPermitido) {
        console.log("❌ Valor excede límite");
        
        inputValor.style.borderColor = "#dc3545";
        inputValor.style.backgroundColor = "#ffe6e6";
        botonAgendar.style.display = "none";
        inputHiddenTotal.value = "";
        
        textoElemento.innerHTML = `
            <div style="color: #dc3545; font-weight: bold; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;">
                ⚠️ Error: El valor ingresado ($${valorTotal.toLocaleString('es-CO')}) excede el máximo permitido<br>
                • Estimado: $${valorEstimado.toLocaleString('es-CO')}<br>
                • Máximo permitido (+${maxPorcentaje}%): $${maxValorPermitido.toLocaleString('es-CO')}<br>
                <small>🚫 No puede agendar el viaje con este valor</small>
            </div>`;
        return;
    }
    
    // ✅ SIEMPRE ACTUALIZAR valor_persona SI HAY PUESTOS (aunque no haya valor cobrado)
    if (puestos > 0 && viaje && viaje.costo) {
        const costoEstimado = parseFloat(viaje.costo);
        const valorPersonaEstimado = costoEstimado / puestos;
        
        const valorPersonaInput = document.getElementById("valor_persona");
        if (valorPersonaInput) {
            valorPersonaInput.value = valorPersonaEstimado.toLocaleString('es-CO', {
                style: 'currency',
                currency: 'COP'
            });
            console.log("💸 Valor por persona estimado actualizado:", valorPersonaInput.value);
        }
    }
    
    // Cálculo completo con valor cobrado
    if (valorTotal > 0 && puestos > 0) {
        console.log("✅ Calculando valores finales con valor cobrado");
        
        inputValor.style.borderColor = "#28a745";
        inputValor.style.backgroundColor = "#e6ffe6";
        botonAgendar.style.display = "block";
        
        const valorPersona = valorTotal / puestos;
        const costoServicioPesos = (valorPersona * costoServicio) / 100;
        const valorFinal = valorPersona + costoServicioPesos;
        
        console.log("💸 Cálculo final:", { valorPersona, costoServicioPesos, valorFinal });
        
        // Guardar en input hidden
        inputHiddenTotal.value = valorFinal.toFixed(2);
        
        // Mostrar desglose
        const valorPersonaFormateado = valorPersona.toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP'
        });
        
        const costoServicioFormateado = costoServicioPesos.toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP'
        });
        
        const valorFinalFormateado = valorFinal.toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP'
        });
        
    textoElemento.innerHTML = `
    <div style="color: #155724; background-color: #d4edda; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px;">
        💡 <strong>Cálculo por pasajero:</strong><br>
        • Valor base: <strong>${valorPersonaFormateado}</strong><br>
        • Costo servicio (${costoServicio}%): <strong>${costoServicioFormateado}</strong><br>
        • <strong>Total por pasajero: ${valorFinalFormateado}</strong><br>
        <hr>
        💰 <strong>Costo total del viaje + costo servicio:</strong> ${ (valorFinal * puestos).toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP'
        }) }
        <br><small style="color: #6c757d;">✅ Valor dentro del límite permitido</small>
    </div>`;

    } else if (puestos > 0 && viaje && viaje.costo && valorTotal === 0) {
        // ✅ CASO: Solo hay puestos, no hay valor cobrado - mostrar estimado
        console.log("📊 Mostrando solo valor estimado");
        
        botonAgendar.style.display = "none";
        inputHiddenTotal.value = "";
        inputValor.style.borderColor = "";
        inputValor.style.backgroundColor = "";
        textoElemento.innerHTML = `
            <div style="color: #6c757d; background-color: #f8f9fa; padding: 10px; border: 1px solid #e9ecef; border-radius: 5px;">
                💡 <strong>Valor estimado por persona:</strong> ${(parseFloat(viaje.costo) / puestos).toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP'
                })}<br>
                <small>Ingresa el valor que cobrarás para ver el cálculo completo</small>
            </div>`;
    } else {
        console.log("⚠️ Valores insuficientes para calcular");
        
        // Limpiar todo si no hay valores válidos
        botonAgendar.style.display = "none";
        inputHiddenTotal.value = "";
        inputValor.style.borderColor = "";
        inputValor.style.backgroundColor = "";
        textoElemento.innerHTML = '';
        
        // Solo limpiar valor_persona si no hay puestos válidos
        if (puestos <= 0) {
            const valorPersonaInput = document.getElementById("valor_persona");
            if (valorPersonaInput) {
                valorPersonaInput.value = "";
            }
        }
    }
}

// ✅ FUNCIÓN PARA CALCULAR VALOR INICIAL BÁSICO
function calcularValorInicialPorPersona() {
    if (!viaje || !viaje.costo) {
        console.log("⚠️ No hay datos de viaje para calcular valor inicial");
        return;
    }
    
    const puestosPorDefecto = 4; // Valor por defecto típico
    const costoTotal = parseFloat(viaje.costo);
    const valorPersonaInicial = costoTotal / puestosPorDefecto;
    
    console.log("💰 Calculando valor inicial:", { costoTotal, puestosPorDefecto, valorPersonaInicial });
    
    const valorPersonaInput = document.getElementById("valor_persona");
    if (valorPersonaInput) {
        valorPersonaInput.value = valorPersonaInicial.toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP'
        });
        console.log("✅ Valor inicial actualizado:", valorPersonaInput.value);
    }
}

// ✅ INICIALIZACIÓN AL CARGAR LA PÁGINA
document.addEventListener('DOMContentLoaded', function() {

    
    console.log("🚀 Página cargada, inicializando...");
    
    // 1. CALCULAR VALOR INICIAL INMEDIATAMENTE
    if (viaje && viaje.costo) {
        console.log("📊 Datos del viaje disponibles:", viaje);
        calcularValorInicialPorPersona();
    }
    
    // 2. CONFIGURAR EVENTOS PARA RECALCULAR
    const valorCobradoInput = document.getElementById("valorCobrado");
    const puestosTotalesInput = document.getElementById("puestosTotales");
    
    if (valorCobradoInput) {
        valorCobradoInput.addEventListener('input', calcularValorPorPersona);
        valorCobradoInput.addEventListener('change', calcularValorPorPersona);
    }
    
    if (puestosTotalesInput) {
        puestosTotalesInput.addEventListener('input', calcularValorPorPersona);
        puestosTotalesInput.addEventListener('change', calcularValorPorPersona);
        
        // ✅ TAMBIÉN RECALCULAR CUANDO CAMBIEN LOS PUESTOS (sin valor cobrado)
        puestosTotalesInput.addEventListener('input', function() {
            const puestos = parseInt(this.value) || 0;
            if (puestos > 0 && viaje && viaje.costo) {
                const costoTotal = parseFloat(viaje.costo);
                const valorPersona = costoTotal / puestos;
                
                const valorPersonaInput = document.getElementById("valor_persona");
                if (valorPersonaInput) {
                    valorPersonaInput.value = valorPersona.toLocaleString('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    });
                    console.log("🔄 Valor por persona actualizado:", valorPersonaInput.value);
                }
            }
        });
    }
    
    // 3. EJECUTAR CÁLCULO COMPLETO SI YA HAY VALORES
    setTimeout(() => {
        const valorCobrado = document.getElementById("valorCobrado")?.value;
        const puestosTotales = document.getElementById("puestosTotales")?.value;
        
        if (valorCobrado && puestosTotales) {
            console.log("🔄 Ejecutando cálculo completo con valores existentes");
            calcularValorPorPersona();
        }
    }, 200);



     const puestosTotales = document.getElementById('puestosTotales');
    const puestosDisponibles = document.getElementById('puestosDisponibles');
    
    // Función de validación
    function validarPuestosDisponibles() {
        const totalPuestos = parseInt(puestosTotales.value);
        const disponibles = parseInt(puestosDisponibles.value);
        
        // Si no hay valor, no validar
        if (!disponibles || isNaN(disponibles)) {
            puestosDisponibles.classList.remove('is-invalid');
            return;
        }
        
        // Validar que disponibles sea menor que total
        if (disponibles >= totalPuestos) {
            // Agregar clase de error
            puestosDisponibles.classList.add('is-invalid');
            
            // Mostrar mensaje de error
            mostrarMensajeError();
            
            // Ajustar automáticamente al máximo permitido
            puestosDisponibles.value = totalPuestos - 1;
            
            // Remover error después del ajuste
            setTimeout(() => {
                puestosDisponibles.classList.remove('is-invalid');
            }, 2000);
        } else {
            // Remover clase de error si está válido
            puestosDisponibles.classList.remove('is-invalid');
            ocultarMensajeError();
        }
    }
    
    // Función para mostrar mensaje de error
    function mostrarMensajeError() {
        // Buscar si ya existe el mensaje
        let mensajeExistente = document.getElementById('errorPuestos');
        
        if (!mensajeExistente) {
            // Crear mensaje de error
            const mensaje = document.createElement('div');
            mensaje.id = 'errorPuestos';
            mensaje.className = 'text-danger mt-1 small';
            mensaje.innerHTML = '⚠️ Los puestos disponibles deben ser menores que los puestos totales';
            
            // Insertar después del input
            puestosDisponibles.parentNode.appendChild(mensaje);
        }
    }
    
    // Función para ocultar mensaje de error
    function ocultarMensajeError() {
        const mensaje = document.getElementById('errorPuestos');
        if (mensaje) {
            mensaje.remove();
        }
    }
    
    // Escuchar cambios en puestos disponibles
    puestosDisponibles.addEventListener('input', validarPuestosDisponibles);
    puestosDisponibles.addEventListener('blur', validarPuestosDisponibles);
    
    // También establecer el máximo permitido dinámicamente
    puestosDisponibles.addEventListener('focus', function() {
        const totalPuestos = parseInt(puestosTotales.value);
        if (totalPuestos > 0) {
            puestosDisponibles.setAttribute('max', totalPuestos - 1);
        }
    });
});

// ✅ FUNCIÓN PARA GUARDAR INFO DEL CONDUCTOR
function guardarInfoConductor() {
    console.log("💾 Guardando información del conductor...");
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const puestosTotales = parseInt(document.getElementById('puestosTotales').value || 0);
    const valorCobrado = parseFloat(document.getElementById('valorCobrado').value || 0);
    const puestosDisponibles = parseInt(document.getElementById('puestosDisponibles').value || 0);
    const horaSalida = document.getElementById('horaSalida').value;
    const fecha = document.getElementById('fechaViaje').value;

    if (!horaSalida || !fecha || puestosTotales <= 0 || puestosDisponibles <= 0 || valorCobrado <= 0) {
        alert("⚠️ Completa todos los campos antes de continuar.");
        return;
    }

    const valorPorPersona = valorCobrado / puestosTotales;
    document.getElementById("totalPorPasajero").value = valorPorPersona.toFixed(2);

    const body = {
        origen_direccion: viaje.origen.direccion,
        origen_lat: parseFloat(viaje.origen.coords.split(',')[0]),
        origen_lng: parseFloat(viaje.origen.coords.split(',')[1]),
        destino_direccion: viaje.destino.direccion,
        destino_lat: parseFloat(viaje.destino.coords.split(',')[0]),
        destino_lng: parseFloat(viaje.destino.coords.split(',')[1]),
        distancia_km: parseFloat(viaje.distancia),
        vehiculo: viaje.vehiculo,
        valor_estimado: parseFloat(viaje.costo),
        valor_persona: valorPorPersona,
        puestos_totales: puestosTotales,
        valor_cobrado: valorCobrado,
        hora_salida: horaSalida,
        fecha_salida: fecha,
        puestos_disponibles: puestosDisponibles,
        activo: true
    };

    console.log("📤 Enviando datos:", body);

    fetch("{{ route('conductor.viaje.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token
        },
        body: JSON.stringify(body)
    })
    .then(res => res.json())
    .then(data => {
        console.log("📥 Respuesta del servidor:", data);
        if (data.success) {
            document.getElementById("mensaje-exito").style.display = 'block';
            setTimeout(() => window.location.href = "{{ route('dashboard') }}", 1500);
        } else {
            document.getElementById("mensaje-error").style.display = 'block';
        }
    })
    .catch(err => {
        console.error("❌ Error:", err);
        document.getElementById("mensaje-error").style.display = 'block';
    });
}
</script>
@endsection