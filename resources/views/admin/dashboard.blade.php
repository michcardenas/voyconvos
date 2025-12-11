@extends('layouts.app_admin')

@section('content')
<style>
    /* Variables de color */
    :root {
        --primary-color: #00304b;
        --primary-light: #1a4d70;
        --primary-dark: #001d2e;
        --success-color: #10b981;
        --info-color: #3b82f6;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .dashboard-container {
        max-width: 100%;
        width: 100%;
        margin: 0 auto;
        padding-top: 100px;
        padding-bottom: 60px;
        padding-left: 40px;
        padding-right: 40px;
    }

    @media (min-width: 1400px) {
        .dashboard-container {
            padding-left: 80px;
            padding-right: 80px;
        }
    }

    .dashboard-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 40px;
        text-align: center;
        letter-spacing: -0.5px;
    }

    .card-stats {
        min-width: 280px;
        flex: 1;
        max-width: 320px;
        border: none;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        box-shadow: var(--shadow-md);
        overflow: hidden;
        position: relative;
    }

    .card-stats::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        transition: width 0.3s ease;
    }

    .card-stats.border-left-primary::before {
        background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-light) 100%);
    }

    .card-stats.border-left-success::before {
        background: linear-gradient(180deg, var(--success-color) 0%, #059669 100%);
    }

    .card-stats.border-left-info::before {
        background: linear-gradient(180deg, var(--info-color) 0%, #2563eb 100%);
    }

    .card-stats.border-left-warning::before {
        background: linear-gradient(180deg, var(--warning-color) 0%, #d97706 100%);
    }

    .card-stats.border-left-danger::before {
        background: linear-gradient(180deg, var(--danger-color) 0%, #dc2626 100%);
    }

    .card-stats:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
    }

    .card-stats:hover::before {
        width: 100%;
        opacity: 0.05;
    }

    .stat-label {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
        opacity: 0.9;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--gray-700);
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-subtext {
        font-size: 0.8rem;
        color: var(--gray-600);
        font-weight: 500;
    }

    .stat-icon {
        opacity: 0.15;
        transition: all 0.3s ease;
    }

    .card-stats:hover .stat-icon {
        opacity: 0.25;
        transform: scale(1.1);
    }

    .cards-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 24px;
        margin-bottom: 48px;
    }

    .center-button {
        display: flex;
        justify-content: center;
        margin-top: 48px;
        margin-bottom: 48px;
    }

    .btn-view-users {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        color: white;
        font-weight: 600;
        padding: 14px 32px;
        border-radius: 12px;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: var(--shadow-md);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }

    .btn-view-users:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        color: #fff;
    }
    .table th, .table td {
        vertical-align: middle;
    }

    /* Mejoras de tabla */
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        color: white !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 12px;
        border: none;
    }

    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background-color: var(--gray-50);
        transform: scale(1.01);
        box-shadow: var(--shadow-sm);
    }

    /* Badges mejorados */
    .badge {
        padding: 6px 14px;
        font-size: 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .badge-warning {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        color: white;
    }

    .badge-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
    }

    .badge-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        color: white;
    }

    .badge-info {
        background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
        color: white;
    }

    .badge-danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        color: white;
    }

    .badge-secondary {
        background: linear-gradient(135deg, var(--gray-600) 0%, var(--gray-700) 100%);
        color: white;
    }

    .badge-outline-primary {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        font-weight: 700;
    }

/* 🔥 ESTILOS CORREGIDOS PARA PAGINACIÓN */
.pagination {
    margin: 0;
    padding: 0;
    justify-content: center !important;
    align-items: center;
}

.pagination .page-item {
    margin: 0 2px;
}

.pagination .page-link {
    font-size: 0.875rem !important;
    padding: 0.5rem 0.75rem !important;
    border: 1px solid #dee2e6;
    border-radius: 6px !important;
    color: #00304b !important;
    background-color: #fff;
    transition: all 0.3s ease;
    min-width: 40px;
    text-align: center;
    line-height: 1.2;
}

.pagination .page-link:hover {
    background-color: #f8f9fa;
    border-color: #00304b;
    color: #00304b !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 48, 75, 0.1);
}

.pagination .page-item.active .page-link {
    background-color: #00304b !important;
    border-color: #00304b !important;
    color: #fff !important;
    font-weight: 600;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d !important;
    background-color: #f8f9fa;
    border-color: #dee2e6;
    cursor: not-allowed;
}

/* 🔥 FLECHAS DE NAVEGACIÓN MÁS PEQUEÑAS */
.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    font-size: 0.7rem !important;
    padding: 0.5rem !important;
    width: 40px !important;
    height: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Ocultar el texto original completamente */
.pagination .page-item:first-child .page-link *,
.pagination .page-item:last-child .page-link * {
    display: none !important;
}

/* Reemplazar con flechas pequeñas */
.pagination .page-item:first-child .page-link {
    font-family: Arial, sans-serif !important;
}

.pagination .page-item:first-child .page-link::after {
    content: "◀" !important;
    font-size: 0.7rem !important;
    display: block !important;
}

.pagination .page-item:last-child .page-link::after {
    content: "▶" !important;
    font-size: 0.7rem !important;
    display: block !important;
}

/* Asegurar que el texto original no aparezca */
.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    color: transparent !important;
    overflow: hidden !important;
}

.pagination .page-item:first-child .page-link::after,
.pagination .page-item:last-child .page-link::after {
    color: #00304b !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
}

.pagination .page-item:first-child .page-link:hover::after,
.pagination .page-item:last-child .page-link:hover::after {
    color: #00304b !important;
}

.pagination .page-item.active:first-child .page-link::after,
.pagination .page-item.active:last-child .page-link::after {
    color: #fff !important;
}

/* 🔥 RESPONSIVE PARA MÓVILES */
@media (max-width: 768px) {
    .pagination {
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .pagination .page-link {
        padding: 0.4rem 0.6rem !important;
        font-size: 0.8rem !important;
        min-width: 35px;
    }
}

/* Cursor y hover effects */
.cursor-pointer {
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.cursor-pointer:hover {
    background-color: var(--gray-50) !important;
    transform: scale(1.005);
    box-shadow: var(--shadow-md);
}

.cursor-pointer::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.cursor-pointer:hover::after {
    opacity: 0.03;
}

/* Bordes izquierdos personalizados */
.border-left-primary {
    border-left: 0.25rem solid var(--color-principal) !important;
}

.border-left-success {
    border-left: 0.25rem solid var(--color-complementario) !important;
}

.border-left-info {
    border-left: 0.25rem solid var(--color-google-blue) !important;
}

.border-left-warning {
    border-left: 0.25rem solid var(--color-google-yellow) !important;
}

.border-left-danger {
    border-left: 0.25rem solid var(--color-google-red) !important;
}

.border-left-secondary {
    border-left: 0.25rem solid var(--color-neutro-oscuro) !important;
}

/* Mejoras para las tarjetas principales */
.card {
    border-radius: 16px;
    border: none;
    transition: all 0.3s ease;
    background-color: white;
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.card.shadow {
    box-shadow: var(--shadow-lg);
}

.card-header {
    background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
    border-bottom: 3px solid var(--primary-color);
    border-radius: 16px 16px 0 0 !important;
    color: var(--primary-color);
    font-weight: 700;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h6 {
    margin: 0;
    font-size: 1.1rem;
    letter-spacing: 0.3px;
}

.card-body {
    padding: 24px;
}

/* Badges personalizados */
.badge-primary {
    background-color: var(--color-principal) !important;
    color: white;
}

.badge-success {
    background-color: var(--color-complementario) !important;
    color: white;
}

.badge-info {
    background-color: var(--color-google-blue) !important;
    color: white;
}

.badge-warning {
    background-color: var(--color-google-yellow) !important;
    color: var(--color-neutro-oscuro);
    font-weight: 600;
}

.badge-danger {
    background-color: var(--color-google-red) !important;
    color: white;
}

.badge-secondary {
    background-color: var(--color-neutro-oscuro) !important;
    color: white;
}

.badge-outline-primary {
    border: 2px solid var(--color-principal);
    color: var(--color-principal);
    background-color: transparent;
    font-weight: 600;
}

/* Colores de texto personalizados */
.text-primary {
    color: var(--color-principal) !important;
}

.text-success {
    color: var(--color-complementario) !important;
}

.text-info {
    color: var(--color-google-blue) !important;
}

.text-warning {
    color: var(--color-google-yellow) !important;
}

.text-danger {
    color: var(--color-google-red) !important;
}

/* Botones mejorados con gradientes */
.btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    border: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    padding: 10px 20px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
    border: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    padding: 10px 20px;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-info {
    background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
    border: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    padding: 10px 20px;
}

.btn-info:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
    border: none;
    color: white;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    padding: 10px 20px;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
    border: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    padding: 10px 20px;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
    border-radius: 8px;
}

.btn-group .btn {
    margin: 0 2px;
}

/* Tabla mejorada */
.table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(31, 78, 121, 0.1);
}

.table-primary th {
    background-color: var(--color-principal) !important;
    color: white !important;
    border: none;
    font-weight: 600;
    padding: 15px 12px;
}

.table-hover tbody tr:hover {
    background-color: var(--color-azul-claro);
    color: var(--color-principal);
}

/* Títulos mejorados */
h2, h4 {
    color: var(--primary-color) !important;
    font-weight: 700;
    letter-spacing: -0.5px;
    position: relative;
    padding-bottom: 12px;
}

h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-light) 100%);
    border-radius: 2px;
}

.section-title {
    font-size: 1.75rem;
    margin-top: 60px;
    margin-bottom: 32px;
}

/* Estadísticas cards mejoradas */
.stat-value {
    color: var(--color-principal);
    font-weight: 700;
    font-size: 1.8rem;
}

.stat-label {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
}
.badge-puestos {
    background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
    color: white !important;
    font-weight: 700;
    padding: 10px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    box-shadow: var(--shadow-sm);
    letter-spacing: 0.3px;
}
/* Efectos de animación */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive mejoras */
@media (max-width: 1024px) {
    .dashboard-container {
        padding-left: 25px;
        padding-right: 25px;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding-left: 20px;
        padding-right: 20px;
    }

    .dashboard-title {
        font-size: 1.75rem;
    }

    .stat-value {
        font-size: 1.5rem;
    }

    .card-stats {
        min-width: 100%;
        max-width: 100%;
    }

    .cards-row {
        gap: 16px;
    }

    .cursor-pointer:hover {
        transform: none;
    }

    .card:hover {
        transform: none;
    }

    .card-stats:hover {
        transform: translateY(-4px);
    }

    .btn-view-users {
        padding: 12px 24px;
        font-size: 0.9rem;
    }

    h2::after {
        width: 60px;
    }

    .table-responsive {
        border-radius: 8px;
        box-shadow: var(--shadow-sm);
    }

    .table thead th {
        font-size: 0.7rem;
        padding: 12px 8px;
    }

    .table tbody td {
        font-size: 0.8rem;
        padding: 10px 8px;
    }
}

@media (max-width: 576px) {
    .dashboard-container {
        padding-top: 80px;
        padding-left: 15px;
        padding-right: 15px;
    }

    .table-responsive {
        font-size: 0.75rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        min-width: 800px;
    }

    .table th, .table td {
        padding: 8px 6px;
        white-space: nowrap;
    }

    .badge {
        font-size: 0.65rem;
        padding: 4px 8px;
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 0.75rem;
    }
}

/* Sombras personalizadas */
.shadow {
    box-shadow: 0 4px 15px rgba(31, 78, 121, 0.12) !important;
}

.shadow-sm {
    box-shadow: 0 2px 8px rgba(31, 78, 121, 0.08) !important;
}

/* Bordes redondeados consistentes */
.rounded {
    border-radius: 12px !important;
}

/* Texto muted mejorado */
.text-muted {
    color: var(--gray-600) !important;
    font-size: 0.9rem;
}

/* Background general */
body {
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
}

/* Mejora de iconos */
.fa, .fas, .far {
    transition: all 0.3s ease;
}

/* Efecto de pulso sutil en iconos de estadísticas */
@keyframes pulse-subtle {
    0%, 100% {
        opacity: 0.15;
    }
    50% {
        opacity: 0.25;
    }
}

.stat-icon {
    animation: pulse-subtle 3s ease-in-out infinite;
}

/* Divider decorativo */
.divider {
    height: 2px;
    background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
    margin: 40px 0;
    opacity: 0.2;
}

/* Tabla responsive mejorada */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin: 0;
}

.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: var(--primary-light);
}
</style>

<div class="dashboard-container">
    <h1 class="dashboard-title">📊 Dashboard Administrativo</h1>

    <div class="cards-row">
        <!-- Total Usuarios -->
        <div class="card card-stats border-left-primary shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-primary">Total Usuarios</div>
                    <div class="stat-value">{{ $totalUsuarios }}</div>
                </div>
                <i class="fas fa-users fa-2x stat-icon text-primary"></i>
            </div>
        </div>

        <!-- Conductores -->
        <div class="card card-stats border-left-success shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-success">Conductores</div>
                    <div class="stat-value">{{ $conductores }}</div>
                    <div class="stat-subtext">{{ $conductoresVerificados }} verificados</div>
                </div>
                <i class="fas fa-car fa-2x stat-icon text-success"></i>
            </div>
        </div>

        <!-- Pasajeros -->
        <div class="card card-stats border-left-info shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-info">Pasajeros</div>
                    <div class="stat-value">{{ $pasajeros }}</div>
                    <div class="stat-subtext">{{ $pasajerosVerificados }} verificados</div>
                </div>
                <i class="fas fa-user-friends fa-2x stat-icon text-info"></i>
            </div>
        </div>

        <!-- Sin Verificar -->
        <div class="card card-stats border-left-warning shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-warning">Sin Verificar</div>
                    <div class="stat-value">{{ $totalSinVerificar }}</div>
                    <div class="stat-subtext text-danger">¡Requiere atención!</div>
                </div>
                <i class="fas fa-exclamation-triangle fa-2x stat-icon text-warning"></i>
            </div>
        </div>
    </div>

    <!-- Botones centrados -->
    <div class="center-button">
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ url('/admin/users') }}" class="btn-view-users">
                👥 Ver Usuarios
            </a>
            <a href="{{ route('admin.gestor-pagos') }}" class="btn-view-users" style="background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);">
                💳 Gestor de Pagos
            </a>
        </div>
    </div>
</div>

<!-- Tarjetas de viajes ACTUALIZADAS -->
<h2 class="text-center section-title">🚌 Estadísticas de Viajes</h2>
<div class="cards-row">
    <div class="card card-stats border-left-primary shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-primary">Total Viajes</div>
                <div class="stat-value">{{ $viajesTotales }}</div>
            </div>
            <i class="fas fa-route fa-2x stat-icon text-primary"></i>
        </div>
    </div>

    <div class="card card-stats border-left-success shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-success">Viajes Activos</div>
                <div class="stat-value">{{ $viajesActivos }}</div>
                <div class="stat-subtext">En curso o por realizar</div>
            </div>
            <i class="fas fa-check-circle fa-2x stat-icon text-success"></i>
        </div>
    </div>

    {{-- ✨ NUEVA TARJETA: Viajes Finalizados Automáticamente --}}
    <div class="card card-stats border-left-warning shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-warning">Finalizados sin realizar viaje </div>
                <div class="stat-value">{{ $viajesFinalizadosAutomaticamente }}</div>
                <div class="stat-subtext">Pasaron +24h de la fecha</div>
            </div>
            <i class="fas fa-clock fa-2x stat-icon text-warning"></i>
        </div>
    </div>

    <div class="card card-stats border-left-danger shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-danger">Viajes Inactivos</div>
                <div class="stat-value">{{ $viajesInactivos }}</div>
                <div class="stat-subtext">Cancelados por el conductor</div>
            </div>
            <i class="fas fa-times-circle fa-2x stat-icon text-danger"></i>
        </div>
    </div>
</div>

{{-- ✨ NUEVA SECCIÓN: Tabla de viajes finalizados automáticamente (opcional) --}}
@if($viajesFinalizadosAutomaticamente > 0)

    
    {{-- Mostrar algunos ejemplos --}}
    @if(isset($viajesFinalizadosDetalles) && $viajesFinalizadosDetalles->count() > 0)
  
    
    @if($viajesFinalizadosDetalles->count() > 5)
    <small class="text-muted">
        Y {{ $viajesFinalizadosDetalles->count() - 5 }} viajes más...
    </small>
    @endif
    @endif
</div>
@endif

{{-- Continúa con el resto de la vista... --}}
<!-- Tabla de Viajes -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">🚀 Viajes Creados Recientemente</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Conductor</th>
                        <th>Ruta</th>
                        <th>Fecha/Hora</th>
                        <th>Puestos</th>
                        <th>Valor</th>
                        <th>Estado</th>
                        <th>Reservas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($viajes as $viaje)
                        @php
                            // ✨ NUEVO: Verificar si el viaje debe mostrarse como finalizado
                            $fechaLimite = \Carbon\Carbon::now()->subHours(24);
                            
                            try {
                                // Obtener solo la fecha (sin hora) de fecha_salida
                                $fechaSoloFecha = \Carbon\Carbon::parse($viaje->fecha_salida)->format('Y-m-d');
                                
                                $fechaViaje = $viaje->hora_salida 
                                    ? \Carbon\Carbon::parse($fechaSoloFecha . ' ' . $viaje->hora_salida)
                                    : \Carbon\Carbon::parse($fechaSoloFecha)->endOfDay();
                                    
                                $estaFinalizado = $fechaViaje->addHours(24)->isPast() && 
                                                 in_array($viaje->estado, ['pendiente', 'confirmado', 'en_proceso', 'completado']);
                            } catch (\Exception $e) {
                                $estaFinalizado = false;
                            }
                        @endphp
                        
                        <tr class="align-middle text-center cursor-pointer {{ $estaFinalizado ? 'table-warning' : '' }}" 
                            onclick="window.location='{{ route('admin.viajes.detalle', $viaje->id) }}'"
                            @if($estaFinalizado) title="Viaje finalizado automáticamente - Pasaron más de 24h" @endif>
                            <td>
                                <strong>{{ $viaje->conductor->name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $viaje->conductor->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="text-left">
                                    <strong>🅰️ {{ Str::limit($viaje->origen_direccion ?? 'N/A', 25) }}</strong><br>
                                    <small class="text-muted">⬇️</small><br>
                                    <strong>🅱️ {{ Str::limit($viaje->destino_direccion ?? 'N/A', 25) }}</strong>
                                </div>
                            </td>
                            <td>
                                @if($viaje->fecha_salida)
                                    <strong>{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</strong><br>
                                @else
                                    <strong>N/A</strong><br>
                                @endif
                                
                                @if($viaje->hora_salida)
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') }}</small>
                                @else
                                    <small class="text-muted">N/A</small>
                                @endif
                                
                                {{-- ✨ NUEVO: Indicador de finalización automática --}}
                                @if($estaFinalizado)
                                    <br><small class="text-danger">
                                        <i class="fas fa-clock"></i> Finalizado auto
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-puestos">{{ $viaje->puestos_disponibles ?? 0 }}/{{ $viaje->puestos_totales ?? 0 }}</span>
                            </td>
                            <td>
                                <strong>${{ number_format($viaje->valor_cobrado ?? 0, 0, ',', '.') }}</strong><br>
                                <small class="text-muted">total del viaje</small>
                            </td>
                            <td>
                                @if($estaFinalizado)
                                    {{-- ✨ NUEVO: Mostrar estado finalizado --}}
                                    <span class="badge badge-success">✅ Finalizado</span>
                                    <br><small class="text-muted">({{ ucfirst($viaje->estado) }})</small>
                                @else
                                    {{-- Estado original --}}
                                    @switch($viaje->estado)
                                        @case('pendiente')
                                            <span class="badge badge-warning">⏳ Pendiente</span>
                                            @break
                                        @case('confirmado')
                                        @case('activo')
                                            <span class="badge badge-success">✅ Confirmado</span>
                                            @break
                                        @case('en_curso')
                                            <span class="badge badge-primary">🚌 En Curso</span>
                                            @break
                                        @case('completado')
                                            <span class="badge badge-secondary">✔️ Completado</span>
                                            @break
                                        @case('cancelado')
                                        @case('inactivo')
                                            <span class="badge badge-danger">❌ Cancelado</span>
                                            @break
                                        @case('listo_para_iniciar')
                                            <span class="badge badge-info">🚀 Listo</span>
                                            @break
                                        @default
                                            <span class="badge badge-light">{{ ucfirst($viaje->estado ?? 'N/A') }}</span>
                                    @endswitch
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-outline-primary">{{ $viaje->reservas->count() }} reserva(s)</span>
                            </td>
                            <td onclick="event.stopPropagation();">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.viajes.detalle', $viaje->id) }}" class="btn btn-sm btn-info" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($viaje->reservas->count() == 0)
                                        <form action="{{ route('admin.viajes.eliminar', $viaje->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este viaje? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Viaje">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-secondary" disabled title="No se puede eliminar: tiene reservas">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay viajes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 🔥 PAGINACIÓN CORREGIDA CON ESTILOS FORZADOS -->
        @if($viajes->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <div style="display: flex; align-items: center; gap: 5px;">
                    @if ($viajes->onFirstPage())
                        <span style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #ddd; border-radius: 4px; color: #ccc; font-size: 12px; background: #f8f9fa;">‹</span>
                    @else
                        <a href="{{ $viajes->previousPageUrl() }}" style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #00304b; border-radius: 4px; color: #00304b; text-decoration: none; font-size: 12px; background: #fff; transition: all 0.2s;" onmouseover="this.style.background='#00304b'; this.style.color='#fff';" onmouseout="this.style.background='#fff'; this.style.color='#00304b';">‹</a>
                    @endif

                    @foreach ($viajes->getUrlRange(1, $viajes->lastPage()) as $page => $url)
                        @if ($page == $viajes->currentPage())
                            <span style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #00304b; border-radius: 4px; color: #fff; font-size: 12px; background: #00304b; font-weight: 600;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #dee2e6; border-radius: 4px; color: #00304b; text-decoration: none; font-size: 12px; background: #fff; transition: all 0.2s;" onmouseover="this.style.background='#f8f9fa'; this.style.borderColor='#00304b';" onmouseout="this.style.background='#fff'; this.style.borderColor='#dee2e6';">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($viajes->hasMorePages())
                        <a href="{{ $viajes->nextPageUrl() }}" style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #00304b; border-radius: 4px; color: #00304b; text-decoration: none; font-size: 12px; background: #fff; transition: all 0.2s;" onmouseover="this.style.background='#00304b'; this.style.color='#fff';" onmouseout="this.style.background='#fff'; this.style.color='#00304b';">›</a>
                    @else
                        <span style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #ddd; border-radius: 4px; color: #ccc; font-size: 12px; background: #f8f9fa;">›</span>
                    @endif
                </div>
            </nav>
        </div>
        @endif
    </div>
</div>


<div class="mt-5">
    <h2 class="text-center section-title">🧾 Últimas Reservas Realizadas</h2>
    
    <div class="table-responsive">
        <table class="table table-hover shadow-sm rounded">
            <thead class="table-primary text-center">
                <tr>
                    <th>Pasajero</th>
                    <th>Conductor</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Fecha Reserva</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservasRecientes as $reserva)
                    <tr class="align-middle text-center">
                        <td>{{ $reserva->pasajero->name ?? 'Desconocido' }}</td>
                        <td>{{ $reserva->viaje->conductor->name ?? 'Desconocido' }}</td>
                        <td>{{ Str::limit($reserva->viaje->origen_direccion ?? 'N/A', 25) }}</td>
                        <td>{{ Str::limit($reserva->viaje->destino_direccion ?? 'N/A', 25) }}</td>
                        <td>{{ optional($reserva->fecha_reserva)->format('d M Y, H:i') ?? 'N/D' }}</td>
                        <td>
                            @switch($reserva->estado)
                                @case('confirmada')
                                    <span class="badge bg-success">Confirmada</span>
                                    @break
                                @case('pendiente')
                                @case('pendiente_pago')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                    @break
                                @case('cancelada')
                                @case('fallida')
                                    <span class="badge bg-danger">{{ ucfirst($reserva->estado) }}</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ ucfirst($reserva->estado) }}</span>
                            @endswitch
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay reservas recientes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection