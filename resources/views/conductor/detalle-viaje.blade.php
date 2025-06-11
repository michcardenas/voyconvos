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
                    <input type="time" id="horaSalida" class="form-control">
                </div>
                <div class="form-group">
                    <label for="puestosDisponibles" class="form-label">🪑 Puestos Disponibles</label>
                    <input type="number" id="puestosDisponibles" class="form-control" min="1" placeholder="Ej: 3">
                </div>
            </div>
    <input type="hidden" id="totalPorPasajero" name="total_por_pasajero" value="">

          <div class="form-group">
    <label for="valorCobrado" class="form-label">💵 Valor total a cobrar (manual)</label>
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
// TODO EL JAVASCRIPT ORIGINAL EXACTO - SIN CAMBIOS
const viaje = JSON.parse(localStorage.getItem('ultimoViaje'));

if (viaje) {
    document.getElementById('origenDireccion').textContent = viaje.origen.direccion;
    document.getElementById('destinoDireccion').textContent = viaje.destino.direccion;
    document.getElementById('distanciaKm').textContent = viaje.distancia+'km';
    document.getElementById('vehiculoTipo').textContent = viaje.vehiculo;
    document.getElementById('valorCalculado').textContent = parseFloat(viaje.costo).toFixed(2);
    document.getElementById('fechaViaje').value = viaje.fecha;
}
function calcularValorPorPersona() {
    const valorTotal = parseFloat(document.getElementById("valorCobrado").value);
    const puestos = parseInt(document.getElementById("puestosTotales").value);
    const costoServicio = {{ $costo_servicio }};
    const maxPorcentaje = {{ $nomasde }};
    
    const valorEstimado = document.getElementById('valorCalculado').textContent = parseFloat(viaje.costo).toFixed(2); // Puedes pasarlo desde el controlador
    const maxValorPermitido = valorEstimado * (1 + (maxPorcentaje / 100));
    
    const inputValor = document.getElementById("valorCobrado");
    const textoElemento = document.getElementById("textoValorPersona");
    const botonAgendar = document.getElementById("botonAgendarContainer");
    const inputHiddenTotal = document.getElementById("totalPorPasajero"); // 👈 NUEVO
    
    // Validar que el valor ingresado no exceda el límite
    if (!isNaN(valorTotal) && valorTotal > maxValorPermitido) {
        // Marcar en rojo y bloquear
        inputValor.style.borderColor = "#dc3545";
        inputValor.style.backgroundColor = "#ffe6e6";
        
        // OCULTAR EL BOTÓN
        botonAgendar.style.display = "none";
        
        // LIMPIAR EL INPUT HIDDEN 👈 NUEVO
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
    
    if (!isNaN(valorTotal) && puestos > 0) {
        // Restablecer estilos normales del input
        inputValor.style.borderColor = "#28a745";
        inputValor.style.backgroundColor = "#e6ffe6";
        
        // MOSTRAR EL BOTÓN
        botonAgendar.style.display = "block";
        
        const valorPersona = valorTotal / puestos;
        const costoServicioPesos = (valorPersona * costoServicio) / 100;
        const valorFinal = valorPersona + costoServicioPesos; // 👈 ESTE ES EL VALOR QUE QUEREMOS
        
        // GUARDAR EN INPUT HIDDEN 👈 NUEVO
        inputHiddenTotal.value = valorFinal.toFixed(2);
        
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
                • <strong>Total por pasajero: ${valorFinalFormateado}</strong>
                <br><small style="color: #6c757d;">✅ Valor dentro del límite permitido</small>
            </div>`;
    } else {
        // Si no hay valores válidos, ocultar el botón y limpiar hidden
        botonAgendar.style.display = "none";
        inputHiddenTotal.value = ""; // 👈 NUEVO
        
        // Restablecer estilos
        inputValor.style.borderColor = "";
        inputValor.style.backgroundColor = "";
        
        textoElemento.innerHTML = '';
    }
}
    
    if (!isNaN(valorTotal) && puestos > 0) {
        // Restablecer estilos normales del input
        inputValor.style.borderColor = "#28a745";
        inputValor.style.backgroundColor = "#e6ffe6";
        
        // MOSTRAR EL BOTÓN
        botonAgendar.style.display = "block";
        botonAgendar.style.opacity = "0";
        setTimeout(() => {
            botonAgendar.style.opacity = "1";
        }, 10);
        
        const valorPersona = valorTotal / puestos;
        const costoServicioPesos = (valorPersona * costoServicio) / 100;
        const valorFinal = valorPersona + costoServicioPesos;
        
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
                • <strong>Total por pasajero: ${valorFinalFormateado}</strong>
                <br><small style="color: #6c757d;">✅ Valor dentro del límite permitido</small>
            </div>`;
    } else {
        // Si no hay valores válidos, ocultar el botón
        botonAgendar.style.transition = "opacity 0.3s ease";
        botonAgendar.style.opacity = "0";
        setTimeout(() => {
            botonAgendar.style.display = "none";
        }, 300);
        
        // Restablecer estilos
        inputValor.style.borderColor = "";
        inputValor.style.backgroundColor = "";
        
        textoElemento.innerHTML = '';
    }

function calcularCosto() {
    console.log("🔢 Calculando costo...");
    
    // 🔍 Obtener valores de los inputs
    const costoTotalInput = document.getElementById("costo") || document.getElementById("costoTotal") || document.getElementById("costo_total");
    const puestosInput = document.getElementById("puestosTotales") || document.getElementById("puestos_totales") || document.getElementById("capacidad");
    
    if (!costoTotalInput) {
        console.error("❌ No se encontró el input del costo total");
        return;
    }
    
    if (!puestosInput) {
        console.error("❌ No se encontró el input de puestos totales");
        return;
    }
    
    // 📊 Obtener valores
    const costoTotal = parseFloat(costoTotalInput.value) || 0;
    const puestos = parseInt(puestosInput.value) || 0;
    
    console.log("📊 Valores:", { costoTotal, puestos });
    
    // ✅ Calcular valor por persona
    if (costoTotal > 0 && puestos > 0) {
        const valorPersona = costoTotal / puestos;
        
        // 💰 Formatear como moneda colombiana
        const valorFormateado = valorPersona.toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
        
        // 📝 Mostrar resultado
        document.getElementById("valor_persona").value = valorFormateado;
        
        console.log("✅ Calculado:", {
            valorPersona: valorPersona,
            valorFormateado: valorFormateado
        });
    } else {
        // ❌ Limpiar si no hay valores válidos
        document.getElementById("valor_persona").value = "";
        console.log("⚠️ Valores inválidos para calcular");
    }
}

// 🚀 Auto-calcular cuando cambien los valores
document.addEventListener('DOMContentLoaded', function() {
    console.log("🔄 Configurando event listeners...");
    
    // 📍 Buscar inputs posibles
    const costoInputs = [
        document.getElementById("costo"),
        document.getElementById("costoTotal"), 
        document.getElementById("costo_total")
    ].filter(input => input !== null);
    
    const puestosInputs = [
        document.getElementById("puestosTotales"),
        document.getElementById("puestos_totales"),
        document.getElementById("capacidad")
    ].filter(input => input !== null);
    
    // ⚡ Agregar event listeners
    costoInputs.forEach(input => {
        input.addEventListener('input', calcularCosto);
        input.addEventListener('change', calcularCosto);
        console.log("✅ Event listener agregado a:", input.id);
    });
    
    puestosInputs.forEach(input => {
        input.addEventListener('input', calcularCosto);
        input.addEventListener('change', calcularCosto);
        console.log("✅ Event listener agregado a:", input.id);
    });
    
    // 🔄 Calcular inicial
    setTimeout(calcularCosto, 500);
});


function guardarInfoConductor() {
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

    // ✅ Actualizar el input oculto con el valor calculado
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
        if (data.success) {
            document.getElementById("mensaje-exito").style.display = 'block';
            setTimeout(() => window.location.href = "{{ route('dashboard') }}", 1500);
        } else {
            document.getElementById("mensaje-error").style.display = 'block';
        }
    })
    .catch(err => {
        console.error(err);
        document.getElementById("mensaje-error").style.display = 'block';
    });
}

</script>
@endsection