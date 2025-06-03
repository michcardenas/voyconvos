@extends('layouts.app_dashboard')

@section('title', 'Detalle del Viaje')

@section('content')
<div style="max-width: 900px; margin: 60px auto; padding: 40px 30px; background: white; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); font-family: 'Segoe UI', sans-serif;">
    <h2 style="color: #1F4E79; text-align: center; margin-bottom: 30px;">🗺️ Detalles de ruta</h2>

    {{-- Información de Ruta --}}
    <div style="margin-bottom: 30px;">
        <h4 style="color: #1F4E79;">📍 Información de Ruta</h4>
        <ul id="infoRuta" style="list-style: none; padding-left: 0;">
            <li><strong>Origen:</strong> <span id="origenDireccion"></span></li>
            <li><strong>Destino:</strong> <span id="destinoDireccion"></span></li>
            <li><strong>Distancia:</strong> <span id="distanciaKm"></span> km</li>
            <li><strong>Vehículo:</strong> <span id="vehiculoTipo"></span></li>
        </ul>
    </div>

    {{-- Valor Estimado y por Persona --}}
    <div style="margin-bottom: 30px;">
        <h4 style="color: #1F4E79;">💰 Valor Estimado del Viaje</h4>
        <div style="font-size: 22px; font-weight: bold; color: green;">
            $<span id="valorCalculado">0.00</span>
        </div>
        <div style="margin-top: 10px;">
            <label for="puestosTotales">👥 Puestos totales (incluyendo conductor)</label>
            <input type="number" id="puestosTotales" class="form-control" style="margin-top: 5px; padding: 10px; border: 1px solid #ccc; border-radius: 8px;" min="1" placeholder="Ej: 4" onchange="calcularCosto()">
        </div>
        <div style="margin-top: 10px;">
            <label for="valor_persona">💸 Valor por persona</label>
            <input type="text" id="valor_persona" class="form-control" readonly style="margin-top: 5px; font-weight: bold; background-color: #f5f5f5;">
        </div>
    </div>

    {{-- Detalles del viaje --}}
    <div style="margin-bottom: 30px;">
        <h4 style="color: #1F4E79;">🕒 Detalles del viaje</h4>
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div style="flex: 1;">
                <label for="fechaViaje">📅 Fecha del viaje</label>
                <input type="date" id="fechaViaje" class="form-control" style="padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
            </div>
            <div style="flex: 1;">
                <label for="horaSalida">⏰ Hora de Salida</label>
                <input type="time" id="horaSalida" class="form-control" style="padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
            </div>
            <div style="flex: 1;">
                <label for="puestosDisponibles">🪑 Puestos Disponibles</label>
                <input type="number" id="puestosDisponibles" class="form-control" style="padding: 10px; border: 1px solid #ccc; border-radius: 8px;" min="1">
            </div>
        </div>

        <div style="margin-top: 20px;">
            <label for="valorCobrado">💵 Valor total a cobrar (manual)</label>
            <input type="number" id="valorCobrado" class="form-control" style="padding: 10px; border: 1px solid #ccc; border-radius: 8px;" placeholder="Ej: 35000">
        </div>
    </div>

    {{-- Botón --}}
    <div style="text-align: center;">
        <button class="btn btn-success" onclick="guardarInfoConductor()" style="padding: 12px 25px; font-weight: bold; border-radius: 10px;">Agendar viaje</button>
    </div>

    <div id="mensaje-exito" style="display: none; margin-top: 20px; padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 10px; text-align: center;">
        ✅ ¡Viaje guardado exitosamente!
    </div>

    <div id="mensaje-error" style="display: none; margin-top: 20px; padding: 15px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 10px; text-align: center;">
        ❌ Error al guardar el viaje. Intenta nuevamente.
    </div>
</div>

<script>
const viaje = JSON.parse(localStorage.getItem('ultimoViaje'));

if (viaje) {
    document.getElementById('origenDireccion').textContent = viaje.origen.direccion;
    document.getElementById('destinoDireccion').textContent = viaje.destino.direccion;
    document.getElementById('distanciaKm').textContent = viaje.distancia;
    document.getElementById('vehiculoTipo').textContent = viaje.vehiculo;
    document.getElementById('valorCalculado').textContent = parseFloat(viaje.costo).toFixed(2);
    document.getElementById('fechaViaje').value = viaje.fecha;
}

function calcularCosto() {
    const costoTotal = parseFloat(viaje.costo);
    const puestos = parseInt(document.getElementById("puestosTotales").value);

    if (!isNaN(costoTotal) && puestos > 0) {
        const valorPersona = costoTotal / puestos;
        document.getElementById("valor_persona").value = valorPersona.toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP'
        });
    }
}

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
