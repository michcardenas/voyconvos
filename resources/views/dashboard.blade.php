@extends('layouts.app_dashboard')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
    }

    .dashboard-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgb(13 111 201 / 68%) 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
    }

    .dashboard-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(76, 175, 80, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(31, 78, 121, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(221, 242, 254, 0.3) 0%, transparent 50%);
        pointer-events: none;
    }

    .container {
        position: relative;
        z-index: 1;
    }

    .welcome-section {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, rgba(31, 78, 121, 0.9) 50%, rgba(58, 58, 58, 0.8) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 6px 20px rgba(31, 78, 121, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }

    .welcome-section h2 {
        margin: 0;
        font-weight: 600;
        font-size: 2rem;
        position: relative;
        z-index: 2;
        color: white !important;
    }

    .welcome-section p {
        margin: 0.5rem 0 0 0;
        opacity: 0.95;
        font-size: 1rem;
        position: relative;
        z-index: 2;
    }

    .stats-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 12px 12px 0 0;
    }

    .stats-card.primary::before {
        background: var(--vcv-primary);
    }

    .stats-card.success::before {
        background: var(--vcv-accent);
    }

    .stats-card.info::before {
        background: rgba(31, 78, 121, 0.6);
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        border-color: rgba(31, 78, 121, 0.15);
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .stats-icon.primary {
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
    }

    .stats-icon.success {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
    }

    .stats-icon.info {
        background: rgba(221, 242, 254, 0.8);
        color: var(--vcv-primary);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .stats-label {
        color: white;
        font-weight: 600;
        margin: 0;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section-header {
        background: white;
        padding: 1.2rem 1.8rem;
        border-radius: 12px;
        margin-bottom: 1.2rem;
        border-left: 3px solid var(--vcv-primary);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .section-header h4 {
        margin: 0;
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.1rem;
    }

    .section-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .section-title {
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.3rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(31, 78, 121, 0.1);
    }

    .filtros-container {
        background: rgba(221, 242, 254, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.1);
        box-shadow: 0 2px 6px rgba(31, 78, 121, 0.05);
    }

    .filtros-container .form-control,
    .filtros-container .form-select {
        border-radius: 8px;
        border: 1px solid rgba(31, 78, 121, 0.2);
        padding: 0.6rem 0.8rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background-color: white;
    }

    .filtros-container .form-control:focus,
    .filtros-container .form-select:focus {
        border-color: var(--vcv-primary);
        box-shadow: 0 0 0 0.2rem rgba(31, 78, 121, 0.25);
        transform: translateY(-1px);
    }

    .filtros-container .btn-primary {
        background: var(--vcv-primary);
        border-color: var(--vcv-primary);
        border-radius: 8px;
        font-weight: 600;
        padding: 0.6rem 1.5rem;
        transition: all 0.3s ease;
    }

    .filtros-container .btn-primary:hover {
        background: rgba(31, 78, 121, 0.9);
        border-color: rgba(31, 78, 121, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(31, 78, 121, 0.3);
    }

    .filtros-container .btn-outline-secondary {
        border-color: rgba(31, 78, 121, 0.3);
        color: var(--vcv-primary);
        border-radius: 8px;
        font-weight: 500;
        padding: 0.6rem 1.5rem;
    }

    .filtros-container .btn-outline-secondary:hover {
        background-color: rgba(31, 78, 121, 0.1);
        border-color: var(--vcv-primary);
        color: var(--vcv-primary);
        transform: translateY(-1px);
    }

    .filtros-activos {
        background: white;
        border-radius: 8px;
        padding: 0.8rem 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--vcv-primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .filtros-activos .badge {
        margin: 0.2rem 0.3rem 0.2rem 0;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        margin-bottom: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .table {
        margin: 0;
        border: none;
    }

    .table thead th {
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.9), rgba(31, 78, 121, 0.8));
        color: white;
        border: none;
        padding: 1rem;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .table tbody tr {
        border-bottom: 1px solid rgba(31, 78, 121, 0.08);
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.7);
    }

    .table tbody tr:hover {
        background: rgba(221, 242, 254, 0.4);
    }

    .table tbody td {
        padding: 1rem;
        border: none;
        vertical-align: middle;
        color: var(--vcv-dark);
    }

    .btn-custom {
        border: none;
        border-radius: 20px;
        padding: 0.4rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
        margin: 0.2rem;
        font-size: 0.85rem;
    }

    .btn-custom.primary {
        background: var(--vcv-primary);
        color: white;
    }

    .btn-custom.primary:hover {
        background: rgba(31, 78, 121, 0.9);
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(31, 78, 121, 0.2);
        color: white;
    }

    .btn-custom.outline {
        background: transparent;
        border: 1px solid rgba(31, 78, 121, 0.3);
        color: var(--vcv-primary);
    }

    .btn-custom.outline:hover {
        background: rgba(31, 78, 121, 0.05);
        border-color: var(--vcv-primary);
        color: var(--vcv-primary);
    }

    .btn-custom.accent {
        background: var(--vcv-accent);
        color: white;
    }

    .btn-custom.accent:hover {
        background: rgba(76, 175, 80, 0.9);
        transform: translateY(-1px);
        color: white;
    }

    .trips-counter {
        font-size: 2rem;
        font-weight: 700;
        color: var(--vcv-primary);
        margin-bottom: 1rem;
    }

    .badge-modern {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.2px;
    }

    .btn-modern {
        border-radius: 6px;
        font-weight: 500;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .btn-outline-primary.btn-modern {
        border-color: var(--vcv-primary);
        color: var(--vcv-primary);
    }

    .btn-outline-primary.btn-modern:hover {
        background-color: var(--vcv-primary);
        border-color: var(--vcv-primary);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(31, 78, 121, 0.2);
    }

    .ratings-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .passenger-link {
        color: var(--vcv-primary);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .passenger-link:hover {
        color: var(--vcv-accent);
        text-decoration: underline;
    }

    .alert-modern {
        border: none;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        border-left: 4px solid var(--vcv-accent);
        background: rgba(76, 175, 80, 0.05);
    }

    .action-buttons {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        text-align: center;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    /* Override para cards de estadísticas del conductor */
    .stats-cards .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .stats-cards .card.bg-vcv-primary {
        background: var(--vcv-primary) !important;
    }

    .stats-cards .card.bg-success {
        background: var(--vcv-accent) !important;
    }

    .stats-cards .card.bg-secondary {
        background: rgba(31, 78, 121, 0.6) !important;
    }

    .stats-cards .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    .stats-cards .card-body {
        padding: 1.5rem;
        text-align: center;
        position: relative;
    }

    .stats-cards .card-title {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        opacity: 0.9;
    }
     .action-buttons-enhanced {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(221, 242, 254, 0.3) 100%);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(31, 78, 121, 0.1);
            border: 1px solid rgba(31, 78, 121, 0.08);
            position: relative;
            overflow: hidden;
        }

        .action-buttons-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .btn-destacado {
            background: linear-gradient(135deg, #1F4E79 0%, #4CAF50 50%, #1F4E79 100%);
            background-size: 200% 200%;
            color: white;
            padding: 1.2rem 2rem;
            border-radius: 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 8px 25px rgba(31, 78, 121, 0.3);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
            min-width: 280px;
            animation: pulse-glow 2s infinite;
        }

        .btn-destacado::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-destacado:hover::before {
            opacity: 1;
        }

        .btn-destacado:hover {
            background-position: 100% 0;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 35px rgba(31, 78, 121, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-destacado:active {
            transform: translateY(-1px) scale(1.01);
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 8px 25px rgba(31, 78, 121, 0.3);
            }
            50% {
                box-shadow: 0 8px 35px rgba(76, 175, 80, 0.4);
            }
        }

        .btn-icon {
            font-size: 2rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-5px);
            }
            60% {
                transform: translateY(-3px);
            }
        }

        .btn-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .btn-text strong {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .btn-text small {
            font-size: 0.85rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .btn-arrow {
            font-size: 1.2rem;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .btn-destacado:hover .btn-arrow {
            transform: translateX(5px);
        }

        .btn-ayuda {
            background: rgba(255, 255, 255, 0.9);
            color: #1F4E79;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            border: 2px solid rgba(31, 78, 121, 0.2);
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .btn-ayuda:hover {
            background: #1F4E79;
            color: white;
            border-color: #1F4E79;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(31, 78, 121, 0.2);
            text-decoration: none;
        }

        .btn-ayuda i {
            transition: transform 0.3s ease;
        }

        .btn-ayuda:hover i {
            transform: scale(1.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .action-buttons-enhanced {
                padding: 1.5rem;
            }
            
            .btn-destacado {
                min-width: auto;
                width: 100%;
                text-align: left;
                padding: 1rem 1.5rem;
            }
            
            .btn-icon {
                font-size: 1.5rem;
            }
            
            .btn-text strong {
                font-size: 1rem;
            }
            
            .btn-text small {
                font-size: 0.8rem;
            }
            
            .d-flex.gap-3 {
                flex-direction: column;
                gap: 1rem !important;
            }
            
            .btn-ayuda {
                width: 100%;
                justify-content: center;
                padding: 0.7rem 1rem;
            }
        }

        @media (max-width: 576px) {
            .action-buttons-enhanced {
                padding: 1rem;
                margin: 0 -10px;
            }
        }
       

    /* Responsive similar al pasajero */
    @media (max-width: 768px) {
        .welcome-section {
            padding: 1.5rem;
        }
        
        .welcome-section h2 {
            font-size: 1.8rem;
        }
        
        .table-responsive {
            border-radius: 15px;
        }
        
        .trips-counter {
            font-size: 1.5rem;
        }
        
        .section-container {
            padding: 1rem;
        }
        
        .filtros-container {
            padding: 1rem;
        }
        
        .filtros-container .row > div {
            margin-bottom: 0.75rem;
        }
        
        .table td,
        .table th {
            font-size: 12px;
            white-space: nowrap;
            padding: 0.5rem 0.3rem;
        }

        .table td form {
            display: inline-block;
            margin-top: 4px;
        }

        .table ul {
            padding-left: 15px;
            font-size: 11px;
        }
        
        .btn-modern {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
        }
        
        .badge-modern {
            font-size: 0.65rem;
            padding: 0.3rem 0.6rem;
        }
        
        .d-flex.gap-3 {
            flex-direction: column;
            gap: 0.5rem !important;
        }
        
        .filtros-container .row {
            flex-direction: column;
        }
        
        .filtros-activos .badge {
            display: inline-block;
            margin: 0.2rem 0.2rem;
            font-size: 0.7rem;
        }
    }
    .btn-verificacion-pendiente {
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    border-radius: 15px;
    padding: 1rem 1.5rem;
    text-decoration: none;
    color: #6c757d;
    transition: all 0.3s ease;
    cursor: not-allowed;
    opacity: 0.8;
    min-width: 280px;
}

.btn-verificacion-pendiente .btn-icon {
    font-size: 2rem;
    margin-right: 1rem;
    background: #ffc107;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-verificacion-pendiente .btn-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    text-align: left;
}

.btn-verificacion-pendiente .btn-text strong {
    font-size: 1.1rem;
    color: #495057;
    margin-bottom: 0.25rem;
}

.btn-verificacion-pendiente .btn-text small {
    color: #6c757d;
    font-size: 0.875rem;
}

.btn-verificacion-pendiente .btn-status {
    font-size: 1.5rem;
    margin-left: 1rem;
    color: #dc3545;
}

.btn-destacado {
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-radius: 15px;
    padding: 1rem 1.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    min-width: 280px;
}

.btn-destacado:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
    color: white;
    text-decoration: none;
}

.btn-destacado .btn-icon {
    font-size: 2rem;
    margin-right: 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-destacado .btn-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    text-align: left;
}

.btn-destacado .btn-text strong {
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

.btn-destacado .btn-text small {
    opacity: 0.9;
    font-size: 0.875rem;
}

.btn-destacado .btn-arrow {
    font-size: 1.5rem;
    margin-left: 1rem;
    transition: transform 0.3s ease;
}

.btn-destacado:hover .btn-arrow {
    transform: translateX(5px);
}

.alert {
    border-radius: 12px;
}

.fs-4 {
    font-size: 1.5rem !important;
}
</style>

<div class="dashboard-wrapper">
    <div class="container py-4">
        <div class="welcome-section">
            <h2>👋 Bienvenido, {{ auth()->user()->name ?? 'Invitado' }}</h2>
            <p>Gestiona tus viajes y conecta con otros viajeros de forma segura</p>
        </div>
          @auth
@role('conductor')
 <div class="action-buttons-enhanced mb-4">
    <div class="d-flex gap-3 flex-wrap justify-content-center align-items-center">
        
        {{-- Verificar si el usuario está verificado --}}
        @if(auth()->user()->verificado == 1)
            {{-- Usuario verificado - Mostrar botón normal --}}
            <a href="{{ route('conductor.gestion') }}" class="btn-destacado">
                <span class="btn-icon">🚗</span>
                <span class="btn-text">
                    <strong>Agendar nuevo viaje</strong>
                    <small>Conecta con más pasajeros</small>
                </span>
                <span class="btn-arrow">→</span>
            </a>
        @else
            {{-- Usuario no verificado - Mostrar mensaje --}}
            <div class="btn-verificacion-pendiente">
                <span class="btn-icon">⏳</span>
                <span class="btn-text">
                    <strong>Cuenta en proceso de verificación</strong>
                    <small>Podrás agendar viajes cuando tu cuenta sea verificada</small>
                </span>
                <span class="btn-status">🔒</span>
            </div>
        @endif
        
        <a href="{{ route('contacto.formulario') }}" class="btn-ayuda">
            <i class="fas fa-question-circle me-2"></i>
            ¿Necesitas ayuda?
        </a>
    </div>
</div>

{{-- Alerta adicional para usuarios no verificados --}}
@if(auth()->user()->verificado == 0)
<div class="alert alert-warning border-0 shadow-sm mb-4">
    <div class="d-flex align-items-start">
        <i class="fas fa-clock text-warning me-3 fs-4"></i>
        <div class="flex-grow-1">
            <h6 class="alert-heading mb-2">
                <i class="fas fa-shield-alt me-2"></i>
                Verificación de Cuenta Pendiente
            </h6>
            <p class="mb-3">
                Tu cuenta está siendo revisada por nuestro equipo de seguridad. 
                Este proceso puede tomar entre 24 a 48 horas hábiles.
            </p>
            
           
        </div>
    </div>
</div>
@endif

@endrole
    @endauth
        <!-- Cards de estadísticas -->
        <div class="row g-4 mb-4 stats-cards">
            <div class="col-md-4">
                <div class="card text-white bg-vcv-primary shadow-soft stats-card primary">
                    <div class="card-body">
                        <div class="stats-icon primary">
                            <i class="fas fa-route"></i>
                        </div>
                        <p class="stats-number">{{ $totalViajes ?? 0 }}</p>
                        <p class="stats-label">Total de Viajes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success shadow-soft stats-card success">
                    <div class="card-body">
                        <div class="stats-icon success">
                            <i class="fas fa-clock"></i>
                        </div>
                        <p class="stats-number">{{ $viajesProximos ?? 0 }}</p>
                        <p class="stats-label">Próximos Viajes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary shadow-soft stats-card info">
                    <div class="card-body">
                        <div class="stats-icon info">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <p class="stats-number">{{ $viajesRealizados ?? 0 }}</p>
                        <p class="stats-label">Viajes Realizados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de próximos viajes -->
        <div class="section-container">
            <h4 class="section-title">🚍 Tus próximos viajes</h4>

            <!-- Filtros simples mejorados -->
            <div class="filtros-container">
                <form method="GET" action="{{ route('dashboard') }}" class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label for="estado" class="form-label text-muted small">Estado del viaje</label>
                        <select name="estado" id="estado" class="form-select">
                            <option value="todos" {{ $filtros['estado'] == 'todos' ? 'selected' : '' }}>Todos los estados</option>
                            <option value="pendiente" {{ $filtros['estado'] == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmado" {{ $filtros['estado'] == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                            <option value="en_proceso" {{ $filtros['estado'] == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="ocupado_total" {{ $filtros['estado'] == 'ocupado_total' ? 'selected' : '' }}>Ocupado Total</option>
                            <option value="completado" {{ $filtros['estado'] == 'completado' ? 'selected' : '' }}>Completado</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="fecha_desde" class="form-label text-muted small">Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ $filtros['fecha_desde'] }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="fecha_hasta" class="form-label text-muted small">Hasta</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ $filtros['fecha_hasta'] }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="buscar" class="form-label text-muted small">Buscar</label>
                        <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Origen, destino..." value="{{ $filtros['buscar'] }}">
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex flex-column justify-content-end">
                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fas fa-search me-1"></i> Filtrar
                        </button>
                        @if(array_filter($filtros))
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Limpiar
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Mostrar filtros activos -->
            @if(array_filter($filtros))
            <div class="filtros-activos">
                <small class="text-muted fw-bold">Filtros aplicados: </small>
                @if($filtros['estado'] != 'todos')
                    <span class="badge bg-primary">{{ ucfirst($filtros['estado']) }}</span>
                @endif
                @if($filtros['fecha_desde'])
                    <span class="badge bg-info">Desde: {{ \Carbon\Carbon::parse($filtros['fecha_desde'])->format('d/m/Y') }}</span>
                @endif
                @if($filtros['fecha_hasta'])
                    <span class="badge bg-info">Hasta: {{ \Carbon\Carbon::parse($filtros['fecha_hasta'])->format('d/m/Y') }}</span>
                @endif
                @if($filtros['buscar'])
                    <span class="badge bg-warning text-dark">"{{ $filtros['buscar'] }}"</span>
                @endif
            </div>
            @endif

            <!-- <div class="d-flex align-items-center mb-3">
                <p class="trips-counter me-3">
                    {{ $viajesProximos ?? 0 }}
                </p>
                @if($reservasNoVistas > 0)
                <span class="badge bg-success badge-modern">🔔 {{ $reservasNoVistas }} nuevas reservas</span>
                @endif
            </div> -->

            @if(isset($viajesProximosList) && count($viajesProximosList) > 0)
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Fecha de viaje</th>
                                <th>Hora</th>
                                <th>Ocupación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($viajesProximosList as $viaje)
                            <tr>
                                <td>
                                    <div class="text-truncate" style="max-width: 120px;" title="{{ $viaje->origen_direccion }}">
                                        {{ $viaje->origen_direccion }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 120px;" title="{{ $viaje->destino_direccion }}">
                                        {{ $viaje->destino_direccion }}
                                    </div>
                                </td>
                                <td>{{ $viaje->hora_salida ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') ?? '—' }}</td>
                                <td>
                                    <span class="fw-bold">{{ $viaje->puestos_disponibles }} / {{ $viaje->reservas->sum('cantidad_puestos') }}</span>
                                    @if($viaje->reservas->count() > 0)
                                    <ul class="mt-1 mb-0 list-unstyled small">
                                        @foreach ($viaje->reservas as $reserva)
                                        <li>
                                            <a href="{{ route('chat.ver', $reserva->viaje_id) }}" class="passenger-link">
                                                {{ $reserva->user->name }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </td>
                              
                                <td>
                                    @if($viaje->estado === 'ocupado_total')
                                        <span class="badge bg-danger text-white badge-modern">
                                            🚫 {{ ucfirst(str_replace('_', ' ', $viaje->estado)) }}
                                        </span>
                                    @else
                                        <span class="badge bg-primary text-white badge-modern">{{ ucfirst($viaje->estado) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if($viaje->estado !== 'cancelado')
                                        <a href="{{ route('conductor.viaje.detalle', $viaje->id) }}" class="btn btn-sm btn-outline-primary btn-modern">
                                            👁 Ver detalles
                                        </a>
                                        @if($viaje->conductor_id === auth()->id())
                                       
                                        @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="alert alert-info alert-modern text-center">
                @if(array_filter($filtros))
                    <h6>No se encontraron viajes con los filtros aplicados</h6>
                    <p class="mb-2">Intenta ajustar los criterios de búsqueda</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">Ver todos los viajes</a>
                @else
                    <h6>No tienes viajes registrados</h6>
                    <p class="mb-2">¡Comienza creando tu primer viaje!</p>
                    @auth
                        @role('conductor')
                        <a href="{{ route('conductor.gestion') }}" class="btn btn-primary btn-sm">Crear viaje</a>
                        @endrole
                    @endauth
                @endif
            </div>
            @endif
        </div>

        <!-- Nueva sección de calificaciones del conductor -->
        <div class="ratings-section">
            <h4 class="section-title">⭐ Calificaciones como Conductor</h4>
            <!-- Contenido futuro de calificaciones -->
        </div>

 

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de fechas
    const fechaDesde = document.getElementById('fecha_desde');
    const fechaHasta = document.getElementById('fecha_hasta');
    
    if (fechaDesde && fechaHasta) {
        fechaDesde.addEventListener('change', function() {
            if (this.value && fechaHasta.value && this.value > fechaHasta.value) {
                fechaHasta.value = this.value;
            }
            fechaHasta.min = this.value;
        });
        
        fechaHasta.addEventListener('change', function() {
            if (this.value && fechaDesde.value && this.value < fechaDesde.value) {
                fechaDesde.value = this.value;
            }
            fechaDesde.max = this.value;
        });
    }

    // Auto-submit en cambio de estado (opcional)
    const selectEstado = document.getElementById('estado');
    if (selectEstado) {
        selectEstado.addEventListener('change', function() {
            // Opcional: auto-submit cuando cambie el estado
            // this.form.submit();
        });
    }

    // Loading state para el botón de filtrar
    const formFiltros = document.querySelector('form[action*="dashboard"]');
    if (formFiltros) {
        formFiltros.addEventListener('submit', function() {
            const btnSubmit = this.querySelector('button[type="submit"]');
            if (btnSubmit) {
                btnSubmit.classList.add('loading');
                btnSubmit.disabled = true;
            }
        });
    }
});
</script>
@endsection