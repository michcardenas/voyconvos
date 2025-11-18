@extends('layouts.app_dashboard')

@section('title', 'Detalles del Viaje')

@section('content')
<style>
    :root {
        --primary: #003366;
        --success: #00C853;
        --danger: #FF1744;
        --warning: #FFC107;
        --light: #f5f7fa;
        --vcv-primary: #003366;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', system-ui, sans-serif;
        background: var(--light);
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    /* Header de la página */
    .page-header {
        background: linear-gradient(135deg, #003366 0%, #0066CC 100%);
        padding: 2.5rem 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: 0 8px 32px rgba(0, 51, 102, 0.15);
    }

    .page-header h2 {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem 1rem;
        }

        .page-header h2 {
            font-size: 1.5rem;
        }

        .page-header p {
            font-size: 0.875rem;
        }
    }

    /* Current time */
    .current-time {
        background: white;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 51, 102, 0.08);
        text-align: center;
        font-weight: 600;
        color: var(--primary);
    }

    /* Card principal */
    .modern-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 51, 102, 0.1);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: 0 12px 40px rgba(0, 51, 102, 0.15);
        transform: translateY(-2px);
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary) 0%, #0066CC 100%);
        padding: 1.5rem 2rem;
        border-bottom: none;
        position: relative;
        overflow: hidden;
    }

    .card-header-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
        pointer-events: none;
    }

    .card-title-custom {
        color: white;
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.6;
        word-wrap: break-word;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .ruta-location {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        padding: 0.5rem 1rem;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .ruta-location:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }

    .ruta-arrow {
        font-size: 1.5rem;
        opacity: 0.8;
    }

    @media (max-width: 768px) {
        .card-title-custom {
            font-size: 0.9rem;
            gap: 0.5rem;
        }

        .ruta-location {
            padding: 0.4rem 0.75rem;
            font-size: 0.85rem;
        }

        .ruta-arrow {
            font-size: 1.2rem;
        }
    }

    @media (max-width: 480px) {
        .card-title-custom {
            flex-direction: column;
            align-items: flex-start;
        }

        .ruta-arrow {
            transform: rotate(90deg);
        }
    }

    .card-body-custom {
        padding: 2rem;
    }

    /* Grid de información del viaje */
    .trip-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        border: 2px solid #e0e7ff;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .info-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, var(--primary) 0%, #00BFFF 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .info-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 51, 102, 0.15);
        border-color: #00BFFF;
    }

    .info-item:hover::before {
        opacity: 1;
    }

    .info-item .icon {
        font-size: 2rem;
        flex-shrink: 0;
    }

    .info-item .content {
        flex: 1;
    }

    .info-item .label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item .value {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary);
    }

    /* Botón Iniciar Viaje */
    #iniciarViajeContainer {
        margin: 1.5rem 0;
        text-align: center;
        display: none;
    }

    #btnIniciarViaje {
        background: linear-gradient(135deg, var(--success) 0%, #69F0AE 100%);
        color: white;
        border: none;
        padding: 1.25rem 3rem;
        font-size: 1.2rem;
        font-weight: 700;
        border-radius: 16px;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(0, 200, 83, 0.3);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    #btnIniciarViaje:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(0, 200, 83, 0.4);
    }

    #btnIniciarViaje:active {
        transform: translateY(-1px);
    }

    #countdown {
        margin-top: 1rem;
        font-size: 0.95rem;
        color: #64748b;
        font-weight: 600;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: capitalize;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .status-badge.bg-success {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #15803d;
    }

    .status-badge.bg-primary {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #0369a1;
    }

    .status-badge.bg-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    .status-badge.bg-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .status-badge.bg-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    @media (max-width: 768px) {
        .status-badge {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
    }

    /* Botones modernos */
    .btn-modern {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .btn-sm.btn-modern {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-outline-primary.btn-modern {
        background: white;
        color: var(--primary);
        border-color: var(--primary);
    }

    .btn-outline-primary.btn-modern:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
    }

    .btn-success.btn-modern {
        background: linear-gradient(135deg, var(--success) 0%, #69F0AE 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 200, 83, 0.3);
    }

    .btn-success.btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 200, 83, 0.4);
    }

    .btn-danger.btn-modern {
        background: linear-gradient(135deg, var(--danger) 0%, #FF5252 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(255, 23, 68, 0.3);
    }

    .btn-danger.btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 23, 68, 0.4);
    }

    .btn-warning.btn-modern {
        background: linear-gradient(135deg, var(--warning) 0%, #FFD54F 100%);
        color: #000;
        border: none;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }

    .btn-warning.btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 193, 7, 0.4);
    }

    .btn-outline-danger.btn-modern {
        background: white;
        color: var(--danger);
        border-color: var(--danger);
    }

    .btn-outline-danger.btn-modern:hover {
        background: var(--danger);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 23, 68, 0.3);
    }

    /* Mapa */
    #map {
        height: 400px;
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 51, 102, 0.1);
    }

    /* Sección de pasajeros */
    .passengers-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 51, 102, 0.1);
        margin-bottom: 2rem;
    }

    .section-header {
        color: var(--primary);
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #e0e7ff;
    }

    .passenger-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e0e7ff;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .passenger-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 51, 102, 0.12);
        border-color: #00BFFF;
    }

    .passenger-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .passenger-name-clickable {
        color: var(--primary);
        font-size: 1.15rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-block;
        margin: 0;
    }

    .passenger-name-clickable:hover {
        color: #0066CC;
        transform: translateX(5px);
    }

    .passenger-meta {
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .rating-display {
        color: var(--warning);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .passenger-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    /* Badges de verificación */
    .badge.verification-mini {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge.verification-mini.verified {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #15803d;
    }

    .badge.verification-mini.not-verified {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    /* Sección de calificaciones */
    .ratings-section {
        margin-top: 1.5rem;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        border: 2px dashed #e0e7ff;
    }

    .ratings-title {
        color: var(--primary);
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .rating-item {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-left: 4px solid #00BFFF;
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .rating-header {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .rating-comment {
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }

    .rating-stars {
        color: var(--warning);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .no-rating {
        color: #94a3b8;
        font-style: italic;
        padding: 1rem;
        text-align: center;
        background: #f8fafc;
        border-radius: 8px;
    }

    /* Alertas modernas */
    .alert-modern {
        border-radius: 16px;
        padding: 1.25rem;
        border: 2px solid;
        font-weight: 500;
    }

    .alert-secondary.alert-modern {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-color: #cbd5e1;
        color: #475569;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        border-color: #ffe082;
        color: #92400e;
    }

    /* Área de acciones */
    .actions-area {
        margin-top: 2rem;
        text-align: center;
    }

    .btn-link.btn-modern {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-link.btn-modern:hover {
        color: #0066CC;
        transform: translateX(-5px);
    }

    /* Modal personalizado de confirmación */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-container {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.4s ease;
    }

    .modal-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #00C853 0%, #69F0AE 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }

    .modal-title {
        text-align: center;
        color: var(--primary);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .modal-message {
        text-align: center;
        color: #64748b;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .modal-highlight {
        color: var(--primary);
        font-weight: 700;
    }

    .modal-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .modal-btn {
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-btn-cancel {
        background: #f1f5f9;
        color: #475569;
    }

    .modal-btn-cancel:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .modal-btn-confirm {
        background: linear-gradient(135deg, var(--success) 0%, #69F0AE 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 200, 83, 0.3);
    }

    .modal-btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 200, 83, 0.4);
    }

    .modal-btn-confirm.loading {
        background: #94a3b8;
        cursor: not-allowed;
        position: relative;
    }

    .modal-btn-confirm.loading::after {
        content: "⏳";
        animation: spin 1s linear infinite;
    }

    /* Modal de Bootstrap mejorado */
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        border-radius: 20px 20px 0 0;
        border-bottom: 2px solid #e0e7ff;
        padding: 1.5rem 2rem;
    }

    .modal-header.bg-danger {
        background: linear-gradient(135deg, var(--danger) 0%, #FF5252 100%) !important;
    }

    .modal-header.bg-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #0066CC 100%) !important;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 2px solid #e0e7ff;
        padding: 1.5rem 2rem;
    }

    /* Profile photo en modal */
    .passenger-profile {
        text-align: center;
    }

    .profile-photo-section {
        margin-bottom: 2rem;
    }

    .profile-photo-container {
        width: 120px;
        height: 120px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid var(--primary);
        box-shadow: 0 8px 24px rgba(0, 51, 102, 0.2);
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-photo-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #94a3b8;
    }

    .rating-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        border-radius: 50px;
        font-weight: 600;
        color: #92400e;
    }

    .verification-status-container {
        display: flex;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .verification-badge {
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .verification-badge.verified {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #15803d;
    }

    .verification-badge.not-verified {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .passenger-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
    }

    .detail-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-size: 0.8rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-size: 1rem;
        color: var(--primary);
        font-weight: 600;
    }

    /* Animaciones */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
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

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .trip-info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .info-item {
            padding: 1rem;
        }

        .info-item .icon {
            font-size: 1.5rem;
        }

        .info-item .value {
            font-size: 1rem;
        }

        .card-body-custom {
            padding: 1.5rem;
        }

        .card-header-custom {
            padding: 1.25rem 1.5rem;
        }

        .passenger-details-grid {
            grid-template-columns: 1fr;
        }

        .passenger-actions {
            flex-direction: column;
        }

        .passenger-card {
            padding: 1.25rem;
        }

        .passengers-section {
            padding: 1.5rem;
        }

        .section-header {
            font-size: 1.1rem;
        }

        .modal-container {
            padding: 1.5rem;
        }

        .modal-buttons {
            flex-direction: column;
        }

        .modal-btn {
            width: 100%;
            justify-content: center;
        }

        #map {
            height: 300px;
        }

        .modern-card {
            border-radius: 16px;
        }

        #btnIniciarViaje {
            padding: 1rem 2rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 0.5rem;
        }

        .page-header h2 {
            font-size: 1.25rem;
        }

        .card-title-custom {
            font-size: 0.875rem;
        }

        .info-item .label {
            font-size: 0.75rem;
        }

        .info-item .value {
            font-size: 0.9rem;
        }

        .btn-modern {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }
    }
</style>

<div class="container py-4">
    <!-- Header de la página -->
    <div class="page-header">
        <h2>🛣️ Detalles del Viaje</h2>
        <p>Información completa sobre tu viaje y pasajeros</p>
    </div>

    <!-- Hora actual -->
    <div class="current-time" id="currentTime">
        <i class="fas fa-clock"></i> Cargando hora...
    </div>

    <!-- Card principal con detalles del viaje -->
    <div class="modern-card">
        <div class="card-header-custom">
            <h5 class="card-title-custom">
                <span class="ruta-location">
                    <i class="fas fa-map-marker-alt" style="color: #00C853;"></i>
                    @php
                        $origenParts = array_map('trim', explode(',', $viaje->origen_direccion));
                        $count = count($origenParts);
                        $origenCorta = $count >= 3 ? $origenParts[$count - 3] . ', ' . $origenParts[$count - 2] : $viaje->origen_direccion;
                        $origenCorta = preg_replace('/\b[A-Z]\d{4}\b\s*/i', '', $origenCorta);
                    @endphp
                    {{ $origenCorta }}
                </span>

                <span class="ruta-arrow">→</span>

                <span class="ruta-location">
                    <i class="fas fa-map-marker-alt" style="color: #FF1744;"></i>
                    @php
                        $destinoParts = array_map('trim', explode(',', $viaje->destino_direccion));
                        $count = count($destinoParts);
                        $destinoCorta = $count >= 3 ? $destinoParts[$count - 3] . ', ' . $destinoParts[$count - 2] : $viaje->destino_direccion;
                        $destinoCorta = preg_replace('/\b[A-Z]\d{4}\b\s*/i', '', $destinoCorta);
                    @endphp
                    {{ $destinoCorta }}
                </span>
            </h5>
        </div>
        <div class="card-body-custom">
            <div class="trip-info-grid">
                <div class="info-item">
                    <div class="icon">🗓</div>
                    <div class="content">
                        <div class="label">Fecha</div>
                        <div class="value">{{ $viaje->fecha_salida ? \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') : 'No definida' }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🕒</div>
                    <div class="content">
                        <div class="label">Hora</div>
                        <div class="value">{{ $viaje->hora_salida ? substr($viaje->hora_salida, 0, 5) : 'No definida' }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🎯</div>
                    <div class="content">
                        <div class="label">Distancia estimada</div>
                        <div class="value">{{ $viaje->distancia_km ?? '—' }} km</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🚗</div>
                    <div class="content">
                        <div class="label">Vehículo</div>
                        <div class="value">
                            @php
                                $marca = $viaje->registroConductor->marca_vehiculo ?? null;
                                $modelo = $viaje->registroConductor->modelo_vehiculo ?? null;
                            @endphp
                            {{ $viaje->vehiculo !== $marca ? ($viaje->vehiculo . ' - ') : '' }}
                            {{ $marca }} {{ $modelo }}
                        </div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">💰</div>
                    <div class="content">
                        <div class="label">Valor por persona</div>
                        <div class="value">${{ number_format($viaje->valor_persona, 2, ',', '.') }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🪑</div>
                    <div class="content">
                        <div class="label">Puestos disponibles</div>
                        <div class="value">{{ $viaje->puestos_disponibles }}</div>
                    </div>
                </div>
            </div>

            <!-- 🚀 BOTÓN INICIAR VIAJE -->
            <div id="iniciarViajeContainer">
                <button id="btnIniciarViaje"
                    onclick="mostrarModalConfirmacion({{ $viaje->id }})">
                    🚀 INICIAR VIAJE
                </button>
                <div id="countdown"></div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                <div>
                    <span class="label" style="font-weight: 600; color: var(--vcv-primary); font-size: 0.85rem; text-transform: uppercase; margin-right: 0.5rem;">📦 Estado:</span>
                    <span class="status-badge {{ $viaje->estado === 'Listo_para_iniciar' ? 'bg-success' : 'bg-primary' }} text-light">
                        {{ ucfirst(str_replace('_', ' ', $viaje->estado)) }}
                    </span>
                </div>

                @if($viaje->conductor_id === auth()->id())
                <form method="POST" action="{{ route('conductor.viaje.eliminar', $viaje->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-outline-danger btn-modern btn-cancelar-viaje">
                        ❌ Cancelar
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Mapa de la ruta -->
    <div class="modern-card">
        <div class="card-header-custom">
            <h5 class="card-title-custom">🗺️ Ruta del Viaje</h5>
        </div>
        <div class="card-body-custom">
            <div id="map"></div>
        </div>
    </div>

    <!-- Sección de pasajeros -->
    <div class="passengers-section">
        <h4 class="section-header">👥 Pasajeros Registrados ({{ $viaje->reservas->count() }})</h4>

        @if($viaje->reservas->count())
            <div class="table-responsive" style="margin-top: 1.5rem;">
                <table class="table table-hover align-middle" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th style="padding: 1rem; border: none; font-weight: 600;">Pasajero</th>
                            <th style="padding: 1rem; border: none; text-align: center; font-weight: 600;">Puestos</th>
                            <th style="padding: 1rem; border: none; text-align: center; font-weight: 600;">Estado</th>
                            <th style="padding: 1rem; border: none; text-align: center; font-weight: 600;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($viaje->reservas as $reserva)
                        <tr style="border-bottom: 1px solid #e5e7eb; cursor: pointer; transition: background 0.2s;"
                            onclick="showPassengerModal({{ $reserva->user->id }}, '{{ $reserva->user->name }}', '{{ $reserva->user->foto ? asset('storage/' . $reserva->user->foto) : '' }}', '{{ $reserva->user->email }}', '{{ $reserva->user->celular ?? 'No especificado' }}', '{{ $reserva->user->ciudad ?? 'No especificado' }}', {{ $reserva->user->calificacion ?? 0 }}, {{ $reserva->cantidad_puestos }}, {{ $reserva->user->verificado }})"
                            onmouseover="this.style.background='#f8fafc'"
                            onmouseout="this.style.background='white'">
                            <td style="padding: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($reserva->user->foto)
                                        <img src="{{ asset('storage/' . $reserva->user->foto) }}"
                                             alt="{{ $reserva->user->name }}"
                                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #e5e7eb;">
                                    @else
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                            {{ strtoupper(substr($reserva->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: #1f2937;">{{ $reserva->user->name }}</div>
                                        <div style="font-size: 0.8rem; color: #6b7280;">
                                            @if($reserva->user->verificado == 1)
                                                <i class="fas fa-shield-check" style="color: #10b981;"></i> Verificado
                                            @else
                                                <i class="fas fa-shield" style="color: #f59e0b;"></i> No verificado
                                            @endif
                                            @if($reserva->user->calificacion)
                                                • ⭐ {{ $reserva->user->calificacion }}/5
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td style="padding: 1rem; text-align: center;">
                                <div style="font-weight: 600; color: #1f2937;">
                                    <i class="fas fa-chair" style="color: #667eea;"></i> {{ $reserva->cantidad_puestos }}
                                </div>
                                @if($reserva->total)
                                    <div style="font-size: 0.8rem; color: #6b7280;">
                                        ${{ number_format($reserva->total, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>

                            <td style="padding: 1rem; text-align: center;" onclick="event.stopPropagation()">
                                @if($reserva->estado == 'pendiente_confirmacion')
                                    <span class="badge" style="background: #fef3c7; color: #92400e; padding: 0.5rem 0.75rem; font-weight: 600; border-radius: 8px;">
                                        ⏳ Pendiente
                                    </span>
                                @elseif($reserva->estado == 'pendiente_pago')
                                    <span class="badge" style="background: #dbeafe; color: #1e40af; padding: 0.5rem 0.75rem; font-weight: 600; border-radius: 8px;">
                                        💳 Esperando pago
                                    </span>
                                    @if($reserva->verificado_por_conductor)
                                        <div style="font-size: 0.7rem; color: #059669; margin-top: 0.25rem;">
                                            ✓ {{ \Carbon\Carbon::parse($reserva->updated_at)->format('d/m H:i') }}
                                        </div>
                                    @endif
                                @elseif($reserva->estado == 'confirmada')
                                    <span class="badge" style="background: #d1fae5; color: #065f46; padding: 0.5rem 0.75rem; font-weight: 600; border-radius: 8px;">
                                        ✅ Confirmada
                                    </span>
                                    @if($reserva->verificado_por_conductor)
                                        <div style="font-size: 0.7rem; color: #059669; margin-top: 0.25rem;">
                                            ✓ {{ \Carbon\Carbon::parse($reserva->updated_at)->format('d/m H:i') }}
                                        </div>
                                    @endif
                                @elseif($reserva->estado == 'cancelada_por_conductor')
                                    <span class="badge" style="background: #fee2e2; color: #991b1b; padding: 0.5rem 0.75rem; font-weight: 600; border-radius: 8px;">
                                        ❌ Cancelada
                                    </span>
                                @else
                                    <span class="badge" style="background: #f1f5f9; color: #475569; padding: 0.5rem 0.75rem; font-weight: 600; border-radius: 8px;">
                                        {{ ucfirst(str_replace('_', ' ', $reserva->estado)) }}
                                    </span>
                                @endif
                            </td>

                            <td style="padding: 1rem; text-align: center;" onclick="event.stopPropagation()">
                                <div style="display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;">
                                    @if($requiereVerificacion && $reserva->estado == 'pendiente_confirmacion')
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                style="padding: 0.4rem 1rem; border-radius: 8px; font-weight: 600;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#aprobarModal"
                                                onclick="setApprovalData({{ $reserva->id }}, '{{ $reserva->user->name }}', 'verificar')">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                style="padding: 0.4rem 1rem; border-radius: 8px; font-weight: 600;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rechazarModal"
                                                onclick="setRejectionData({{ $reserva->id }}, '{{ $reserva->user->name }}')">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    @elseif($requiereVerificacion && ($reserva->estado == 'pendiente_pago' || $reserva->estado == 'confirmada'))
                                        <button type="button"
                                                class="btn btn-sm btn-warning"
                                                style="padding: 0.4rem 1rem; border-radius: 8px; font-weight: 600;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rechazarModal"
                                                onclick="setRejectionData({{ $reserva->id }}, '{{ $reserva->user->name }}')">
                                            <i class="fas fa-ban"></i> Cancelar
                                        </button>
                                    @elseif($reserva->estado == 'cancelada_por_conductor')
                                        <span style="color: #6b7280; font-size: 0.85rem;">
                                            -
                                        </span>
                                    @else
                                        <span style="color: #059669; font-size: 0.85rem; font-weight: 600;">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-secondary alert-modern" style="margin-top: 1.5rem;">
                <i class="fas fa-info-circle"></i> Aún no hay pasajeros en este viaje.
            </div>
        @endif
    </div>

    <!-- Botón de regreso -->
    <div class="actions-area">
        <a href="{{ route('dashboard') }}" class="btn-link btn-modern">⬅️ Volver al dashboard</a>
    </div>
</div>

<!-- Modal de Aprobación -->
<div class="modal fade" id="aprobarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aprobar Pasajero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                </div>
                <h6 id="modalMessage" class="mb-3" style="font-size: 1.1rem; font-weight: 600;">¿Aprobar a este pasajero?</h6>
                <div style="background: #f0f9ff; padding: 1rem; border-radius: 12px; border-left: 4px solid #0ea5e9; margin-bottom: 1rem; text-align: left;">
                    <p class="mb-2" style="color: #0369a1; font-weight: 600; font-size: 0.95rem;">
                        <i class="fas fa-info-circle"></i> ¿Qué sucederá al aprobar?
                    </p>
                    <ul style="color: #0c4a6e; font-size: 0.9rem; margin: 0; padding-left: 1.5rem;">
                        <li>El pasajero recibirá una notificación de aprobación</li>
                        <li>Se le enviará un enlace para realizar el pago</li>
                        <li>La reserva pasará a estado <strong>"Pendiente de pago"</strong></li>
                        <li>Una vez que pague, se confirmará automáticamente</li>
                    </ul>
                </div>
                <p class="text-muted" style="font-size: 0.85rem; margin: 0;">
                    <i class="fas fa-shield-check"></i> Al aprobar, confirmas que revisaste el perfil del pasajero
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <form id="approvalForm" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="accion" value="verificar">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Sí, Aprobar Pasajero
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Rechazar -->
<div class="modal fade" id="rechazarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">❌ Rechazar Pasajero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalRejectionMessage">¿Estás seguro de rechazar a este pasajero?</p>
                <div class="alert alert-warning">
                    <small>⚠️ Esta acción no se puede deshacer. El pasajero será notificado del rechazo.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="rejectionForm" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="accion" value="rechazar">
                    <button type="submit" class="btn btn-danger">❌ Confirmar Rechazo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Información del Pasajero -->
<div class="modal fade" id="passengerInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary bg-opacity-10">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>
                    Información del Pasajero
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="passenger-profile">
                    <div class="profile-photo-section">
                        <div class="profile-photo-container">
                            <img id="passengerPhoto" src="" alt="Foto del pasajero" class="profile-photo">
                            <div id="noPhotoPlaceholder" class="no-photo-placeholder" style="display: none;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <h5 id="passengerName"></h5>
                        <div id="passengerRating" class="rating-badge"></div>
                    </div>
                    
                    <div class="verification-status-container">
                        <div id="verificationStatus" class="verification-badge"></div>
                    </div>
                    
                    <div class="passenger-details-grid">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Email</div>
                                <div class="detail-value" id="passengerEmail"></div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-phone text-success"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Teléfono</div>
                                <div class="detail-value" id="passengerPhone"></div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Ciudad</div>
                                <div class="detail-value" id="passengerCity"></div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-chair text-info"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Puestos reservados</div>
                                <div class="detail-value" id="passengerSeats"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar Viaje -->
<div class="modal fade" id="modalCancelarViaje" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">❌ Cancelar Viaje</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCancelarViaje" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>⚠️ Atención:</strong> Esta acción no se puede deshacer. El viaje será cancelado permanentemente.
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivoCancelacion" class="form-label">
                            <strong>Motivo de cancelación</strong> <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="motivoCancelacion" name="motivo_cancelacion" rows="4" 
                                  placeholder="Explica brevemente por qué cancelas este viaje..." required></textarea>
                        <div class="form-text">Este motivo será visible para los pasajeros que tenían reservas.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmarCancelacion" required>
                            <label class="form-check-label" for="confirmarCancelacion">
                                Confirmo que deseo cancelar este viaje
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i> Cancelar Viaje
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmar Inicio de Viaje -->
<div id="modalConfirmarViaje" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-icon">
            <i class="fas fa-route"></i>
        </div>
        
        <h2 class="modal-title">¿Iniciar el viaje ahora?</h2>
        
        <div class="modal-message">
            Estás a punto de iniciar el viaje. Serás dirigido a la pantalla para 
            <span class="modal-highlight">verificar qué pasajeros están presentes</span>.
            <br><br>
            ¿Estás listo para comenzar?
        </div>
        
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="cerrarModal()">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button class="modal-btn modal-btn-confirm" onclick="confirmarInicioViaje()">
                <i class="fas fa-check"></i> ¡Sí, iniciar!
            </button>
        </div>
    </div>
</div>

<!-- Google Maps -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initViajeDetalleMapa&v=3.55"></script>

<script>
// ========================================
// FUNCIONES DEL MAPA
// ========================================
function initViajeDetalleMapa() {
    try {
        const origen = {
            lat: parseFloat({{ $viaje->origen_lat }}),
            lng: parseFloat({{ $viaje->origen_lng }})
        };
        
        const destino = {
            lat: parseFloat({{ $viaje->destino_lat }}),
            lng: parseFloat({{ $viaje->destino_lng }})
        };

        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: origen,
            mapTypeId: 'roadmap',
            styles: [
                {
                    featureType: 'poi',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                }
            ]
        });

        const markerOrigen = new google.maps.Marker({
            position: origen,
            map: map,
            title: 'Origen: {{ addslashes($viaje->origen_direccion) }}',
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
            }
        });
        
        const markerDestino = new google.maps.Marker({
            position: destino,
            map: map,
            title: 'Destino: {{ addslashes($viaje->destino_direccion) }}',
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
            }
        });

        const bounds = new google.maps.LatLngBounds();
        bounds.extend(origen);
        bounds.extend(destino);
        map.fitBounds(bounds);

        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#003366',
                strokeWeight: 5
            }
        });
        
        directionsRenderer.setMap(map);

        directionsService.route({
            origin: origen,
            destination: destino,
            travelMode: google.maps.TravelMode.DRIVING
        }, function(response, status) {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
            }
        });

    } catch (error) {
        console.error('Error al inicializar el mapa:', error);
    }
}

window.initViajeDetalleMapa = initViajeDetalleMapa;

// ========================================
// FUNCIONES DE MODALES
// ========================================
function setApprovalData(reservaId, nombrePasajero, accion = 'verificar') {
    const form = document.getElementById('approvalForm');
    form.action = `/conductor/verificar-pasajero/${reservaId}`;
    document.getElementById('modalMessage').textContent = `¿Estás seguro de aprobar a ${nombrePasajero}?`;
}

function setRejectionData(reservaId, nombrePasajero) {
    const form = document.getElementById('rejectionForm');
    form.action = `/conductor/verificar-pasajero/${reservaId}`;
    document.getElementById('modalRejectionMessage').textContent = `¿Estás seguro de rechazar a ${nombrePasajero}?`;
}

function showPassengerModal(userId, name, photo, email, phone, city, rating, seats, userVerified = 0) {
    document.getElementById('passengerName').textContent = name;
    document.getElementById('passengerEmail').textContent = email;
    document.getElementById('passengerPhone').textContent = phone;
    document.getElementById('passengerCity').textContent = city;
    document.getElementById('passengerSeats').textContent = seats;
    
    const photoElement = document.getElementById('passengerPhoto');
    const placeholderElement = document.getElementById('noPhotoPlaceholder');
    
    if (photo && photo.trim() !== '') {
        photoElement.src = photo;
        photoElement.style.display = 'block';
        placeholderElement.style.display = 'none';
    } else {
        photoElement.style.display = 'none';
        placeholderElement.style.display = 'flex';
    }
    
    const ratingElement = document.getElementById('passengerRating');
    if (rating && rating > 0) {
        ratingElement.innerHTML = `<i class="fas fa-star text-warning"></i> ${rating}/5`;
        ratingElement.style.display = 'block';
    } else {
        ratingElement.innerHTML = '<span class="text-muted">Sin calificación</span>';
        ratingElement.style.display = 'block';
    }
    
    const verificationElement = document.getElementById('verificationStatus');
    verificationElement.className = 'verification-badge';
    
    if (parseInt(userVerified) === 1) {
        verificationElement.classList.add('verified');
        verificationElement.innerHTML = '<i class="fas fa-shield-check"></i> Usuario Verificado';
    } else {
        verificationElement.classList.add('not-verified');
        verificationElement.innerHTML = '<i class="fas fa-shield-exclamation"></i> Usuario No Verificado';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('passengerInfoModal'));
    modal.show();
}

// ========================================
// MODAL PERSONALIZADO INICIAR VIAJE
// ========================================
let viajeIdActual = null;

function mostrarModalConfirmacion(viajeId) {
    viajeIdActual = viajeId;
    const modal = document.getElementById('modalConfirmarViaje');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    const modal = document.getElementById('modalConfirmarViaje');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    viajeIdActual = null;
}

function confirmarInicioViaje() {
    if (!viajeIdActual) return;
    iniciarViaje(viajeIdActual);
    cerrarModal();
}

document.getElementById('modalConfirmarViaje').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModal();
    }
});

function iniciarViaje(viajeId) {
    const confirmBtn = document.querySelector('.modal-btn-confirm');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '';
    confirmBtn.classList.add('loading');
    
    const btn = document.getElementById('btnIniciarViaje');
    if (btn) {
        const btnOriginalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Iniciando...';
        btn.disabled = true;
    }

    fetch(`/conductor/viaje/${viajeId}/iniciar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect_url;
        } else {
            alert('Error al iniciar viaje: ' + data.message);
            confirmBtn.innerHTML = originalText;
            confirmBtn.classList.remove('loading');
            if (btn) {
                btn.innerHTML = btnOriginalText;
                btn.disabled = false;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al conectar con el servidor');
        confirmBtn.innerHTML = originalText;
        confirmBtn.classList.remove('loading');
        if (btn) {
            btn.innerHTML = btnOriginalText;
            btn.disabled = false;
        }
    });
}

// ========================================
// MODAL CANCELAR VIAJE
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const botonesCancelar = document.querySelectorAll('.btn-cancelar-viaje');
    const modal = new bootstrap.Modal(document.getElementById('modalCancelarViaje'));
    const form = document.getElementById('formCancelarViaje');
    
    botonesCancelar.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            const actionUrl = this.closest('form').action;
            form.action = actionUrl;
            modal.show();
        });
    });
    
    form.addEventListener('submit', function(e) {
        const motivo = document.getElementById('motivoCancelacion').value.trim();
        const confirmacion = document.getElementById('confirmarCancelacion').checked;
        
        if (!motivo || motivo.length < 10) {
            e.preventDefault();
            alert('El motivo debe tener al menos 10 caracteres');
            return false;
        }
        
        if (!confirmacion) {
            e.preventDefault();
            alert('Debes confirmar la cancelación');
            return false;
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Cancelando...';
    });

    // ========================================
    // RELOJ EN TIEMPO REAL
    // ========================================
    function actualizarReloj() {
        const ahora = new Date();
        const opciones = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        const horaActual = ahora.toLocaleString('es-AR', opciones);
        document.getElementById('currentTime').innerHTML = `<i class="fas fa-clock"></i> ${horaActual}`;
    }

    actualizarReloj();
    setInterval(actualizarReloj, 1000);

    // ========================================
    // SISTEMA DE BOTÓN INICIAR VIAJE
    // ========================================
    const fechaSalida = '{{ $viaje->fecha_salida }}';
    const horaSalida = '{{ $viaje->hora_salida }}';
    const estadoViaje = '{{ $viaje->estado }}';

    if (!fechaSalida || !horaSalida) {
        return;
    }

    function verificarBotonIniciar() {
        const ahora = new Date();
        const fechaFormateada = fechaSalida.split(' ')[0];
        const horaFormateada = horaSalida.substring(0, 8);
        const fechaHoraSalida = new Date(fechaFormateada + 'T' + horaFormateada);

        // Botón aparece 90 minutos antes de la salida
        const tiempoActivacion = new Date(fechaHoraSalida.getTime() - (90 * 60 * 1000)); // 90 minutos
        const tiempoMaximo = new Date(fechaHoraSalida.getTime() + (3 * 60 * 60 * 1000)); // 3 horas después

        const enRangoTiempo = ahora >= tiempoActivacion && ahora <= tiempoMaximo;
        const deberiaVisible = enRangoTiempo && estadoViaje !== 'iniciado';

        const container = document.getElementById('iniciarViajeContainer');
        const countdown = document.getElementById('countdown');
        const btnIniciar = document.getElementById('btnIniciarViaje');

        if (deberiaVisible) {
            container.style.display = 'block';

            const diff = fechaHoraSalida.getTime() - ahora.getTime();

            if (diff > 0) {
                // Aún no es la hora de salida - mostrar contador
                btnIniciar.style.display = 'none';
                const horas = Math.floor(diff / (1000 * 60 * 60));
                const minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const segundos = Math.floor((diff % (1000 * 60)) / 1000);

                countdown.innerHTML = `
                    <div style="background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
                                padding: 1.5rem;
                                border-radius: 16px;
                                border: 2px solid #ffe082;
                                box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);">
                        <div style="font-size: 0.9rem; color: #92400e; margin-bottom: 0.5rem; font-weight: 600;">
                            ⏳ El viaje podrá iniciarse en:
                        </div>
                        <div style="font-size: 2rem; font-weight: 700; color: #f57c00; font-family: 'Courier New', monospace;">
                            ${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}
                        </div>
                        <div style="font-size: 0.85rem; color: #92400e; margin-top: 0.5rem;">
                            Hora de salida programada: ${horaFormateada}
                        </div>
                    </div>
                `;
            } else {
                // Ya pasó la hora de salida - mostrar botón
                btnIniciar.style.display = 'inline-flex';
                const tiempoPasado = Math.abs(diff);
                const minutosPasados = Math.floor(tiempoPasado / (1000 * 60));

                if (minutosPasados < 180) {
                    countdown.innerHTML = `
                        <div style="color: #15803d; font-weight: 600; margin-top: 0.75rem; font-size: 0.95rem;">
                            <i class="fas fa-check-circle"></i> ¡Listo para iniciar!
                            ${minutosPasados > 0 ? `(${minutosPasados} min desde la hora programada)` : ''}
                        </div>
                    `;
                } else {
                    countdown.innerHTML = `
                        <div style="color: #991b1b; font-weight: 600; margin-top: 0.75rem; font-size: 0.95rem;">
                            <i class="fas fa-exclamation-triangle"></i> Tiempo de salida expirado (más de 3 horas)
                        </div>
                    `;
                }
            }
        } else {
            container.style.display = 'none';
        }
    }

    verificarBotonIniciar();
    setInterval(verificarBotonIniciar, 1000); // Actualizar cada segundo para contador más preciso
});
</script>

@endsection