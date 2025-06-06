@extends('layouts.app')

@section('content')
<div class="confirmation-wrapper">
    <div class="container">
        <div class="confirmation-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1>¡Reserva Creada! 🎉</h1>
            <p class="success-message">Tu reserva ha sido registrada exitosamente</p>
            
            <div class="reservation-details">
                <h3>📋 Detalles de tu reserva</h3>
                
                <div class="detail-row">
                    <span class="label">Número de reserva:</span>
                    <span class="value">#{{ $reserva->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Estado:</span>
                    <span class="value status-{{ $reserva->estado }}">
                        @if($reserva->estado === 'pendiente_pago')
                            ⏳ Pendiente de Pago
                        @elseif($reserva->estado === 'confirmada')
                            ✅ Confirmada
                        @else
                            {{ ucfirst($reserva->estado) }}
                        @endif
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Viaje:</span>
                    <span class="value">{{ $reserva->viaje->origen_direccion }} → {{ $reserva->viaje->destino_direccion }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Fecha:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Puestos:</span>
                    <span class="value">{{ $reserva->cantidad_puestos }} {{ $reserva->cantidad_puestos == 1 ? 'pasajero' : 'pasajeros' }}</span>
                </div>
                
                @if($reserva->total)
                <div class="detail-row total">
                    <span class="label">Total:</span>
                    <span class="value">${{ number_format($reserva->total, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
            
            @if($reserva->estado === 'pendiente_pago')
            <div class="next-steps">
                <h4>🚀 Próximos pasos</h4>
                <p>Tu reserva está registrada pero <strong>pendiente de pago</strong>. 
                Completa el pago para confirmar tu reserva.</p>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('pasajero.procesar.pago', $reserva->id) }}" class="btn btn-pay">
                    <i class="fas fa-credit-card"></i>
                    Pagar con Mercado Pago
                    <span class="amount">${{ number_format($reserva->total, 0, ',', '.') }}</span>
                </a>
            </div>
            @else
            <div class="action-buttons">
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    Ir al Inicio
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.confirmation-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    padding: 2rem 0;
}

.confirmation-card {
    background: white;
    border-radius: 20px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: 0 auto;
}

.success-icon {
    font-size: 5rem;
    color: #4CAF50;
    margin-bottom: 1rem;
}

.confirmation-card h1 {
    color: #1F4E79;
    margin-bottom: 0.5rem;
}

.success-message {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.reservation-details {
    text-align: left;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin: 2rem 0;
}

.reservation-details h3 {
    color: #1F4E79;
    margin-bottom: 1rem;
    text-align: center;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row.total {
    font-weight: bold;
    font-size: 1.1rem;
    color: #1F4E79;
    background: #e3f2fd;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
}

.status-pendiente_pago {
    color: #ff9800;
    font-weight: bold;
}

.status-confirmada {
    color: #4CAF50;
    font-weight: bold;
}

.next-steps {
    background: #fff3cd;
    border-radius: 12px;
    padding: 1.5rem;
    margin: 2rem 0;
    text-align: left;
}

.next-steps h4 {
    color: #856404;
    margin-bottom: 1rem;
}

.next-steps p {
    color: #856404;
    margin: 0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 0.8rem 2rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-pay {
    background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
    color: white;
    font-size: 1.1rem;
    padding: 1rem 2rem;
    border-radius: 30px;
    font-weight: 700;
    box-shadow: 0 8px 25px rgba(0, 210, 255, 0.3);
}

.btn-pay:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0, 210, 255, 0.4);
    color: white;
    text-decoration: none;
}

.btn-pay .amount {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-weight: 800;
}

.btn-primary {
    background: #4CAF50;
    color: white;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .confirmation-card {
        margin: 1rem;
        padding: 2rem 1rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
        max-width: 280px;
    }
}
</style>
@endsection