@extends('layouts.app')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-info: #00a8e1;
        --vcv-bg: #FCFCFD;
        --vcv-gradient-primary: linear-gradient(135deg, #1F4E79 0%, #2d5f8d 100%);
        --vcv-gradient-accent: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
        --vcv-gradient-info: linear-gradient(135deg, #00a8e1 0%, #33b9e8 100%);
        --vcv-gradient-hero: linear-gradient(135deg, rgba(31, 78, 121, 0.95) 0%, rgba(76, 175, 80, 0.85) 100%);
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f1f5f9 100%);
        background-attachment: fixed;
    }

    /* Dashboard Wrapper */
    .dashboard-wrapper {
        position: relative;
        min-height: 100vh;
        padding: 2rem 0 4rem 0;
    }

    /* Animated Background Overlay */
    .dashboard-wrapper::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(31, 78, 121, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(76, 175, 80, 0.03) 0%, transparent 50%);
        animation: subtleShift 20s ease infinite;
        pointer-events: none;
        z-index: 0;
    }

    @keyframes subtleShift {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .container {
        position: relative;
        z-index: 1;
    }

    /* Hero Section - Elegante con Glassmorphism sutil */
    .hero-elegant {
        background: var(--vcv-gradient-hero),
                    url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=1600') center/cover;
        border-radius: 20px;
        padding: 3.5rem 3rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 40px rgba(31, 78, 121, 0.15);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .hero-elegant::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        transform: rotate(45deg);
        animation: elegantShine 4s infinite;
    }

    @keyframes elegantShine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-elegant h2 {
        color: white;
        font-size: 2.8rem;
        font-weight: 800;
        margin: 0 0 0.75rem 0;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.5px;
    }

    .hero-elegant p {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.15rem;
        margin: 0;
        font-weight: 500;
        opacity: 0.95;
    }

    /* Quick Actions - Cards Elegantes */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
        animation: fadeInUp 0.6s ease-out 0.1s both;
    }

    .action-elegant {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: block;
    }

    .action-elegant::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--vcv-gradient-primary);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .action-elegant:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(31, 78, 121, 0.15);
        border-color: var(--vcv-primary);
    }

    .action-elegant:hover::before {
        transform: scaleX(1);
    }

    .action-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.1) 0%, rgba(31, 78, 121, 0.05) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        transition: all 0.3s ease;
    }

    .action-elegant:hover .action-icon {
        transform: scale(1.1) rotate(5deg);
        background: var(--vcv-gradient-primary);
    }

    .action-icon i {
        font-size: 1.75rem;
        color: var(--vcv-primary);
        transition: color 0.3s ease;
    }

    .action-elegant:hover .action-icon i {
        color: white;
    }

    .action-elegant h5 {
        color: var(--vcv-dark);
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .action-elegant p {
        color: #64748b;
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Stats Cards - Estilo Profesional */
    .stats-professional {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    .stat-elegant {
        background: white;
        border-radius: 18px;
        padding: 2.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(31, 78, 121, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-elegant::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 18px 18px 0 0;
    }

    .stat-elegant.primary::before {
        background: var(--vcv-gradient-primary);
    }

    .stat-elegant.success::before {
        background: var(--vcv-gradient-accent);
    }

    .stat-elegant.info::before {
        background: var(--vcv-gradient-info);
    }

    .stat-elegant:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(31, 78, 121, 0.12);
    }

    .stat-icon-elegant {
        width: 75px;
        height: 75px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease;
    }

    .stat-elegant:hover .stat-icon-elegant {
        transform: scale(1.05);
    }

    .stat-icon-elegant.primary {
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.1) 0%, rgba(31, 78, 121, 0.05) 100%);
    }

    .stat-icon-elegant.success {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(76, 175, 80, 0.05) 100%);
    }

    .stat-icon-elegant.info {
        background: linear-gradient(135deg, rgba(0, 168, 225, 0.1) 0%, rgba(0, 168, 225, 0.05) 100%);
    }

    .stat-icon-elegant i {
        font-size: 2rem;
    }

    .stat-icon-elegant.primary i {
        color: var(--vcv-primary);
    }

    .stat-icon-elegant.success i {
        color: var(--vcv-accent);
    }

    .stat-icon-elegant.info i {
        color: var(--vcv-info);
    }

    .stat-number-elegant {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.5rem;
        color: var(--vcv-primary);
    }

    .stat-label-elegant {
        color: #64748b;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
    }

    /* Section Header - Elegante */
    .section-elegant {
        background: white;
        border-radius: 18px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(31, 78, 121, 0.08);
        animation: fadeInUp 0.6s ease-out 0.3s both;
    }

    .section-elegant h4 {
        color: var(--vcv-primary);
        font-weight: 800;
        font-size: 1.5rem;
        margin: 0 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-elegant h4 i {
        color: var(--vcv-primary);
    }

    /* Filters - Estilo Profesional */
    .filters-elegant {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(31, 78, 121, 0.1);
        margin-bottom: 1.5rem;
    }

    .filter-elegant {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        border: 2px solid transparent;
        background: rgba(31, 78, 121, 0.04);
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
    }

    .filter-elegant:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.15);
        border-color: var(--vcv-primary);
        background: rgba(31, 78, 121, 0.08);
    }

    .filter-elegant.active {
        background: var(--vcv-gradient-primary);
        color: white;
        border-color: transparent;
        box-shadow: 0 6px 20px rgba(31, 78, 121, 0.25);
    }

    .filter-elegant .badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .filter-elegant.active .badge {
        background: rgba(255, 255, 255, 0.25);
    }

    /* Table - Elegante y Moderna */
    .table-elegant-wrapper {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
        animation: fadeInUp 0.6s ease-out 0.4s both;
        width: 100%;
    }

    .table-elegant {
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table-elegant thead th {
        background: var(--vcv-gradient-primary);
        color: white;
        padding: 1.5rem 1.25rem;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .table-elegant tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(31, 78, 121, 0.08);
    }

    .table-elegant tbody tr:hover {
        background: linear-gradient(90deg, rgba(221, 242, 254, 0.3) 0%, rgba(221, 242, 254, 0.1) 100%);
        transform: scale(1.005);
    }

    .table-elegant tbody td {
        padding: 1.5rem 1.25rem;
        border: none;
        color: var(--vcv-dark);
        font-weight: 500;
    }

    /* Badges - Elegantes con gradientes sutiles */
    .badge-elegant {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .badge-elegant.warning {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
    }

    .badge-elegant.info {
        background: linear-gradient(135deg, #00a8e1 0%, #33b9e8 100%);
        color: white;
    }

    .badge-elegant.success {
        background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
        color: white;
    }

    .badge-elegant.danger {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        color: white;
    }

    /* Action Buttons - Profesionales */
    .btn-elegant {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        border: 2px solid;
        background: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .btn-elegant::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .btn-elegant i {
        position: relative;
        z-index: 1;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-elegant:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .btn-elegant:hover::before {
        opacity: 1;
    }

    .btn-elegant:hover i {
        color: white;
    }

    .btn-elegant.info {
        border-color: var(--vcv-info);
    }

    .btn-elegant.info i {
        color: var(--vcv-info);
    }

    .btn-elegant.info::before {
        background: var(--vcv-gradient-info);
    }

    .btn-elegant.pay {
        border-color: var(--vcv-accent);
    }

    .btn-elegant.pay i {
        color: var(--vcv-accent);
    }

    .btn-elegant.pay::before {
        background: var(--vcv-gradient-accent);
    }

    .btn-elegant.chat {
        border-color: var(--vcv-primary);
    }

    .btn-elegant.chat i {
        color: var(--vcv-primary);
    }

    .btn-elegant.chat::before {
        background: var(--vcv-gradient-primary);
    }

    /* Empty State - Elegante */
    .empty-elegant {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(31, 78, 121, 0.08);
        animation: fadeInUp 0.6s ease-out;
    }

    .empty-icon-elegant {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.1) 0%, rgba(31, 78, 121, 0.05) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        animation: gentleFloat 3s ease-in-out infinite;
    }

    @keyframes gentleFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }

    .empty-icon-elegant i {
        font-size: 3.5rem;
        color: var(--vcv-primary);
        opacity: 0.7;
    }

    .empty-elegant h5 {
        color: var(--vcv-dark);
        font-weight: 800;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .empty-elegant p {
        color: #64748b;
        margin-bottom: 2rem;
        font-size: 1.05rem;
        line-height: 1.6;
    }

    .btn-primary-elegant {
        padding: 1rem 2.5rem;
        border-radius: 12px;
        background: var(--vcv-gradient-primary);
        color: white;
        border: none;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(31, 78, 121, 0.25);
        font-size: 1rem;
    }

    .btn-primary-elegant:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(31, 78, 121, 0.35);
        color: white;
    }

    /* Ratings Section - Elegante */
    .ratings-elegant {
        background: white;
        border-radius: 18px;
        padding: 2.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
        animation: fadeInUp 0.6s ease-out 0.5s both;
    }

    .rating-summary-elegant {
        background: var(--vcv-gradient-accent);
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .rating-summary-elegant::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: rotate(45deg);
        animation: elegantShine 4s infinite;
    }

    .rating-score-elegant {
        font-size: 4rem;
        font-weight: 900;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .rating-stars-elegant {
        font-size: 2rem;
        margin: 1rem 0;
        position: relative;
        z-index: 1;
    }

    .rating-count-elegant {
        opacity: 0.95;
        font-size: 1.1rem;
        position: relative;
        z-index: 1;
        font-weight: 600;
    }

    .rating-card-elegant {
        background: var(--vcv-bg);
        border-radius: 14px;
        padding: 1.75rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--vcv-accent);
        transition: all 0.3s ease;
        border: 1px solid rgba(31, 78, 121, 0.06);
    }

    .rating-card-elegant:hover {
        transform: translateX(8px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.12);
    }

    /* Pagination Elegant */
    .pagination {
        gap: 0.5rem;
    }

    .page-link {
        border-radius: 10px;
        border: 2px solid rgba(31, 78, 121, 0.1);
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        color: var(--vcv-primary);
        background: white;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: var(--vcv-gradient-primary);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
    }

    .page-item.active .page-link {
        background: var(--vcv-gradient-primary);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.25);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-elegant {
            padding: 2.5rem 2rem;
        }

        .hero-elegant h2 {
            font-size: 2rem;
        }

        .stat-number-elegant {
            font-size: 2.5rem;
        }

        .actions-grid {
            grid-template-columns: 1fr;
        }

        .stats-professional {
            grid-template-columns: 1fr;
        }

        .filters-elegant {
            justify-content: center;
        }

        .table-elegant-wrapper {
            overflow-x: auto;
        }
    }
</style>

<div class="dashboard-wrapper">
    <div class="container">
        <!-- Hero Section -->
        <div class="hero-elegant">
            <div class="hero-content">
                <h2>👋 Bienvenido, {{ auth()->user()->name ?? 'Viajero' }}</h2>
                <p>Gestiona tus viajes y revisa tu actividad de forma elegante y eficiente</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="actions-grid">
            <a href="{{ route('pasajero.viajes.disponibles') }}" class="action-elegant">
                <div class="action-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h5>Buscar Viajes</h5>
                <p>Encuentra el viaje perfecto que se ajuste a tu ruta y horario</p>
            </a>

            <a href="{{ route('conductor.gestion') }}" class="action-elegant">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h5>Publicar Viaje</h5>
                <p>Comparte tu ruta y ahorra en combustible mientras conoces gente</p>
            </a>

            <a href="{{ route('pasajero.dashboard', ['vista' => 'historial', 'estado' => 'todos']) }}" class="action-elegant">
                <div class="action-icon">
                    <i class="fas fa-history"></i>
                </div>
                <h5>Ver Historial</h5>
                <p>Revisa todos tus viajes anteriores y actividad completa</p>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-professional">
            <div class="stat-elegant primary">
                <div class="stat-icon-elegant primary">
                    <i class="fas fa-route"></i>
                </div>
                <div class="stat-number-elegant">{{ $totalViajes ?? 0 }}</div>
                <div class="stat-label-elegant">Total de Viajes</div>
            </div>

            <div class="stat-elegant success">
                <div class="stat-icon-elegant success">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number-elegant">{{ $viajesProximos ?? 0 }}</div>
                <div class="stat-label-elegant">Próximos Viajes</div>
            </div>

            <div class="stat-elegant info">
                <div class="stat-icon-elegant info">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number-elegant">{{ $viajesRealizados ?? 0 }}</div>
                <div class="stat-label-elegant">Viajes Realizados</div>
            </div>
        </div>

        <!-- Reservas Section -->
        <div class="section-elegant">
            <h4><i class="fas fa-list-alt"></i>Tus Reservas</h4>

            <!-- Vista Selector -->
            <div class="filters-elegant" style="border-bottom: 3px solid rgba(31, 78, 121, 0.15); padding-bottom: 1.5rem; margin-bottom: 2rem;">
                <a href="{{ route('pasajero.dashboard', ['vista' => 'proximos', 'estado' => request('estado', 'todos')]) }}"
                   class="filter-elegant {{ ($tipoVista ?? 'proximos') === 'proximos' ? 'active' : '' }}"
                   style="font-size: 1.05rem; padding: 1rem 2rem;">
                    <i class="fas fa-calendar-check"></i>
                    <span style="font-weight: 800;">Próximos Viajes</span>
                </a>
                <a href="{{ route('pasajero.dashboard', ['vista' => 'historial', 'estado' => request('estado', 'todos')]) }}"
                   class="filter-elegant {{ ($tipoVista ?? 'proximos') === 'historial' ? 'active' : '' }}"
                   style="font-size: 1.05rem; padding: 1rem 2rem;">
                    <i class="fas fa-history"></i>
                    <span style="font-weight: 800;">Historial Completo</span>
                </a>
            </div>

            <!-- Estado Filters -->
            <div class="filters-elegant">
                <a href="{{ route('pasajero.dashboard', ['vista' => $tipoVista ?? 'proximos', 'estado' => 'todos']) }}"
                   class="filter-elegant {{ ($estadoFiltro ?? 'todos') === 'todos' ? 'active' : '' }}">
                    <i class="fas fa-th"></i>
                    Todos
                    @if(isset($estadisticas))
                        <span class="badge">{{ $reservas->total() ?? $reservas->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('pasajero.dashboard', ['vista' => $tipoVista ?? 'proximos', 'estado' => 'activos']) }}"
                   class="filter-elegant {{ ($estadoFiltro ?? '') === 'activos' ? 'active' : '' }}">
                    <i class="fas fa-fire"></i>
                    Activos
                    @if(isset($estadisticas))
                        <span class="badge">{{ $estadisticas['activos'] }}</span>
                    @endif
                </a>
                <a href="{{ route('pasajero.dashboard', ['vista' => $tipoVista ?? 'proximos', 'estado' => 'pendiente_confirmacion']) }}"
                   class="filter-elegant {{ ($estadoFiltro ?? '') === 'pendiente_confirmacion' ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    Esperando
                    @if(isset($estadisticas))
                        <span class="badge">{{ $estadisticas['pendiente_confirmacion'] }}</span>
                    @endif
                </a>
                <a href="{{ route('pasajero.dashboard', ['vista' => $tipoVista ?? 'proximos', 'estado' => 'pendiente_pago']) }}"
                   class="filter-elegant {{ ($estadoFiltro ?? '') === 'pendiente_pago' ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    Por Pagar
                    @if(isset($estadisticas))
                        <span class="badge">{{ $estadisticas['pendiente_pago'] }}</span>
                    @endif
                </a>
                <a href="{{ route('pasajero.dashboard', ['vista' => $tipoVista ?? 'proximos', 'estado' => 'confirmada']) }}"
                   class="filter-elegant {{ ($estadoFiltro ?? '') === 'confirmada' ? 'active' : '' }}">
                    <i class="fas fa-check-double"></i>
                    Confirmadas
                    @if(isset($estadisticas))
                        <span class="badge">{{ $estadisticas['confirmada'] }}</span>
                    @endif
                </a>
                <a href="{{ route('pasajero.dashboard', ['vista' => $tipoVista ?? 'proximos', 'estado' => 'cancelados']) }}"
                   class="filter-elegant {{ ($estadoFiltro ?? '') === 'cancelados' ? 'active' : '' }}">
                    <i class="fas fa-times-circle"></i>
                    Cancelados
                    @if(isset($estadisticas))
                        <span class="badge">{{ $estadisticas['cancelados'] }}</span>
                    @endif
                </a>
            </div>
        </div>

        @if(isset($reservas) && $reservas->count() > 0)
            <div class="table-elegant-wrapper">
                <div class="table-responsive">
                    <table class="table table-elegant">
                        <thead>
                            <tr>
                                <th>RUTA</th>
                                <th class="d-none d-md-table-cell">FECHA</th>
                                <th>ESTADO</th>
                                <th class="d-none d-lg-table-cell">PUESTOS</th>
                                <th class="d-none d-md-table-cell">TOTAL</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $reserva)
                                @if($reserva->viaje)
                                <tr>
                                    <!-- RUTA -->
                                    <td>
                                        @php
                                            $acortarProvincia = function($texto) {
                                                $reemplazos = [
                                                    'Cdad. Autónoma de Buenos Aires' => 'CABA',
                                                    'Ciudad Autónoma de Buenos Aires' => 'CABA',
                                                    'Autonomous City of Buenos Aires' => 'CABA',
                                                    'Provincia de Buenos Aires' => 'Bs.As.',
                                                    'Buenos Aires Province' => 'Bs.As.',
                                                ];
                                                return str_replace(array_keys($reemplazos), array_values($reemplazos), $texto);
                                            };

                                            $origenParts = array_map('trim', explode(',', $reserva->viaje->origen_direccion ?? 'Origen'));
                                            $count = count($origenParts);
                                            $origenCorta = $count >= 3 ? $origenParts[$count - 3] . ', ' . $origenParts[$count - 2] : ($reserva->viaje->origen_direccion ?? 'Origen');
                                            $origenCorta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $origenCorta);
                                            $origenCorta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $origenCorta);
                                            $origenCorta = preg_replace('/\s+/', ' ', $origenCorta);
                                            $origenCorta = preg_replace('/,\s*,/', ',', $origenCorta);
                                            $origenCorta = trim($origenCorta, ' ,');
                                            $origenCorta = $acortarProvincia($origenCorta);

                                            $destinoParts = array_map('trim', explode(',', $reserva->viaje->destino_direccion ?? 'Destino'));
                                            $count = count($destinoParts);
                                            $destinoCorta = $count >= 3 ? $destinoParts[$count - 3] . ', ' . $destinoParts[$count - 2] : ($reserva->viaje->destino_direccion ?? 'Destino');
                                            $destinoCorta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $destinoCorta);
                                            $destinoCorta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $destinoCorta);
                                            $destinoCorta = preg_replace('/\s+/', ' ', $destinoCorta);
                                            $destinoCorta = preg_replace('/,\s*,/', ',', $destinoCorta);
                                            $destinoCorta = trim($destinoCorta, ' ,');
                                            $destinoCorta = $acortarProvincia($destinoCorta);
                                        @endphp
                                        <div style="max-width: 200px;">
                                            <strong class="d-block" style="font-weight: 700;">{{ Str::limit($origenCorta, 25) }}</strong>
                                            <small class="text-muted">
                                                <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i>
                                                {{ Str::limit($destinoCorta, 25) }}
                                            </small>
                                        </div>
                                    </td>

                                    <!-- FECHA -->
                                    <td class="d-none d-md-table-cell">
                                        @if($reserva->viaje->fecha_salida)
                                            <strong class="d-block" style="font-weight: 700;">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') }}</strong>
                                            @if($reserva->viaje->hora_salida)
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($reserva->viaje->hora_salida)->format('H:i') }}</small>
                                            @else
                                                <small class="text-muted">Hora no definida</small>
                                            @endif
                                        @else
                                            <span class="text-muted">No definida</span>
                                        @endif
                                    </td>
                                    
                                    <!-- ESTADO -->
                                    <td>
                                        @switch($reserva->estado)
                                            @case('pendiente')
                                                <span class="badge-elegant warning">⏰ Pendiente</span>
                                                @break
                                            @case('pendiente_pago')
                                                <span class="badge-elegant info">💳 Por Pagar</span>
                                                @break
                                            @case('pendiente_confirmacion')
                                                <span class="badge-elegant warning">🕐 Esperando</span>
                                                @break
                                            @case('confirmada')
                                                <span class="badge-elegant success">✅ Confirmado</span>
                                                @break
                                            @case('cancelada')
                                            @case('cancelada_por_conductor')
                                                <span class="badge-elegant danger">❌ Cancelado</span>
                                                @break
                                            @case('fallida')
                                                <span class="badge-elegant danger">⚠️ Fallido</span>
                                                @break
                                            @case('completada')
                                                <span class="badge-elegant success">🎉 Completado</span>
                                                @break
                                            @default
                                                <span class="badge-elegant info">{{ ucfirst($reserva->estado) }}</span>
                                        @endswitch
                                    </td>
                                    
                                    <!-- PUESTOS -->
                                    <td class="d-none d-lg-table-cell">
                                        <span class="badge-elegant info">
                                            {{ $reserva->cantidad_puestos ?? 1 }} <i class="fas fa-user"></i>
                                        </span>
                                    </td>
                                    
                                    <!-- TOTAL -->
                                    <td class="d-none d-md-table-cell">
                                        <strong style="font-size: 1.2rem; font-weight: 800; color: var(--vcv-accent);">
                                            ${{ number_format($reserva->total ?? 0, 0) }}
                                        </strong>
                                    </td>
                                    
                                    <!-- ACCIONES -->
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('pasajero.reserva.detalles', $reserva->id) }}" 
                                               class="btn-elegant info" 
                                               title="Ver detalles">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            
                                            @if($reserva->estado === 'pendiente_pago' && $reserva->mp_init_point)
                                                <a href="{{ $reserva->mp_init_point }}" 
                                                   class="btn-elegant pay" 
                                                   title="Proceder al pago">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                            @endif
                                            
                                            @if($reserva->estado === 'confirmada')
                                                <a href="{{ route('chat.ver', $reserva->viaje_id) }}" 
                                                   class="btn-elegant chat" 
                                                   title="Abrir Chat">
                                                    <i class="fas fa-comments"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($reservas, 'links'))
                    <div class="d-flex justify-content-center mt-4 p-3">
                        {{ $reservas->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="empty-elegant">
                <div class="empty-icon-elegant">
                    <i class="fas fa-inbox"></i>
                </div>
                <h5>No hay reservas</h5>
                <p>
                    @switch($estadoFiltro ?? 'todos')
                        @case('activos')
                            No tienes reservas activas. ¡Busca tu próximo viaje!
                            @break
                        @case('pendiente_confirmacion')
                            No tienes reservas esperando confirmación del conductor.
                            @break
                        @case('pendiente_pago')
                            No tienes reservas pendientes de pago.
                            @break
                        @case('confirmada')
                            No tienes reservas confirmadas.
                            @break
                        @case('cancelados')
                            No tienes reservas canceladas.
                            @break
                        @case('completada')
                            No tienes viajes completados.
                            @break
                        @default
                            No tienes ninguna reserva aún.
                    @endswitch
                </p>
                @if(in_array($estadoFiltro ?? 'todos', ['activos', 'todos']))
                    <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn-primary-elegant">
                        <i class="fas fa-search"></i>
                        Buscar viajes
                    </a>
                @endif
            </div>
        @endif

        <!-- Calificaciones Section -->
        <div class="section-elegant">
            <h4><i class="fas fa-star"></i>Calificaciones que has recibido como pasajero</h4>
        </div>
        
        <div class="ratings-elegant">
            @php
                $comentariosRecibidos = collect();
                $calificacionesRecibidas = null;
                
                if(isset($calificacionesDetalle)) {
                    $comentariosRecibidos = $calificacionesDetalle
                        ->where('usuario_calificado_id', auth()->id())
                        ->where('tipo', 'conductor_a_pasajero');
                        
                    if($comentariosRecibidos->count() > 0) {
                        $calificacionesRecibidas = (object) [
                            'total_calificaciones' => $comentariosRecibidos->count(),
                            'promedio_calificacion' => $comentariosRecibidos->avg('calificacion')
                        ];
                    }
                }
                
                if(isset($misCalificaciones) && isset($misCalificaciones['conductor_a_pasajero'])) {
                    $calificacionesRecibidas = $misCalificaciones['conductor_a_pasajero'];
                }
            @endphp
            
            @if($calificacionesRecibidas && $calificacionesRecibidas->total_calificaciones > 0)
                <div class="rating-summary-elegant">
                    <div class="rating-score-elegant">{{ number_format($calificacionesRecibidas->promedio_calificacion, 1) }}</div>
                    <div class="rating-stars-elegant">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($calificacionesRecibidas->promedio_calificacion))
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="rating-count-elegant">
                        Basado en {{ $calificacionesRecibidas->total_calificaciones }} calificación(es)
                    </p>
                </div>

                @if($comentariosRecibidos->count() > 0)
                    <h5 class="mb-4" style="color: var(--vcv-primary); font-weight: 800; font-size: 1.2rem;">Comentarios de conductores</h5>
                    
                    @foreach($comentariosRecibidos->take(5) as $comentario)
                        <div class="rating-card-elegant">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div style="color: #ffc107; margin-bottom: 0.5rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $comentario->calificacion)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2" style="font-weight: 800; color: var(--vcv-dark);">{{ $comentario->calificacion }}/5</span>
                                    </div>
                                    <small style="color: #64748b; font-weight: 600;">
                                        De conductor: <strong style="color: var(--vcv-dark);">{{ $comentario->nombre_conductor ?? 'Conductor' }}</strong>
                                    </small>
                                </div>
                                <small style="color: #94a3b8; font-weight: 600;">
                                    {{ \Carbon\Carbon::parse($comentario->fecha_calificacion)->format('d/m/Y') }}
                                </small>
                            </div>
                            
                            @if($comentario->comentario)
                                <p style="color: var(--vcv-dark); font-style: italic; margin-bottom: 1rem; font-weight: 500;">
                                    "{{ $comentario->comentario }}"
                                </p>
                            @endif
                            
                            @if($comentario->origen_direccion && $comentario->destino_direccion)
                                @php
                                    $acortarProvinciaCalif = function($texto) {
                                        $reemplazos = [
                                            'Cdad. Autónoma de Buenos Aires' => 'CABA',
                                            'Ciudad Autónoma de Buenos Aires' => 'CABA',
                                            'Autonomous City of Buenos Aires' => 'CABA',
                                            'Provincia de Buenos Aires' => 'Bs.As.',
                                            'Buenos Aires Province' => 'Bs.As.',
                                        ];
                                        return str_replace(array_keys($reemplazos), array_values($reemplazos), $texto);
                                    };

                                    $origenCalifParts = array_map('trim', explode(',', $comentario->origen_direccion));
                                    $countCalif = count($origenCalifParts);
                                    $origenCalifCorta = $countCalif >= 3 ? $origenCalifParts[$countCalif - 3] . ', ' . $origenCalifParts[$countCalif - 2] : $comentario->origen_direccion;
                                    $origenCalifCorta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $origenCalifCorta);
                                    $origenCalifCorta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $origenCalifCorta);
                                    $origenCalifCorta = preg_replace('/\s+/', ' ', $origenCalifCorta);
                                    $origenCalifCorta = preg_replace('/,\s*,/', ',', $origenCalifCorta);
                                    $origenCalifCorta = trim($origenCalifCorta, ' ,');
                                    $origenCalifCorta = $acortarProvinciaCalif($origenCalifCorta);

                                    $destinoCalifParts = array_map('trim', explode(',', $comentario->destino_direccion));
                                    $countCalif = count($destinoCalifParts);
                                    $destinoCalifCorta = $countCalif >= 3 ? $destinoCalifParts[$countCalif - 3] . ', ' . $destinoCalifParts[$countCalif - 2] : $comentario->destino_direccion;
                                    $destinoCalifCorta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $destinoCalifCorta);
                                    $destinoCalifCorta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $destinoCalifCorta);
                                    $destinoCalifCorta = preg_replace('/\s+/', ' ', $destinoCalifCorta);
                                    $destinoCalifCorta = preg_replace('/,\s*,/', ',', $destinoCalifCorta);
                                    $destinoCalifCorta = trim($destinoCalifCorta, ' ,');
                                    $destinoCalifCorta = $acortarProvinciaCalif($destinoCalifCorta);
                                @endphp
                                <small style="color: #64748b; font-weight: 600;">
                                    <i class="fas fa-route me-1"></i>
                                    Viaje: {{ Str::limit($origenCalifCorta, 20) }} → {{ Str::limit($destinoCalifCorta, 20) }}
                                </small>
                            @endif
                        </div>
                    @endforeach

                    @if($comentariosRecibidos->count() > 5)
                        <div class="text-center mt-4">
                            <button class="btn-primary-elegant" onclick="toggleAllReceivedRatings()" style="border: none; cursor: pointer;">
                                <i class="fas fa-chevron-down" id="toggle-received-icon"></i>
                                <span id="toggle-received-text">Ver todas las calificaciones ({{ $comentariosRecibidos->count() }})</span>
                            </button>
                        </div>
                        
                        <div id="all-received-ratings" style="display: none;" class="mt-3">
                            @foreach($comentariosRecibidos->skip(5) as $comentario)
                                <div class="rating-card-elegant">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <div style="color: #ffc107; margin-bottom: 0.5rem;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $comentario->calificacion)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2" style="font-weight: 800; color: var(--vcv-dark);">{{ $comentario->calificacion }}/5</span>
                                            </div>
                                            <small style="color: #64748b; font-weight: 600;">
                                                De conductor: <strong style="color: var(--vcv-dark);">{{ $comentario->nombre_conductor ?? 'Conductor' }}</strong>
                                            </small>
                                        </div>
                                        <small style="color: #94a3b8; font-weight: 600;">
                                            {{ \Carbon\Carbon::parse($comentario->fecha_calificacion)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    
                                    @if($comentario->comentario)
                                        <p style="color: var(--vcv-dark); font-style: italic; margin-bottom: 1rem; font-weight: 500;">
                                            "{{ $comentario->comentario }}"
                                        </p>
                                    @endif
                                    
                                    @if($comentario->origen_direccion && $comentario->destino_direccion)
                                        @php
                                            $acortarProvinciaCalif2 = function($texto) {
                                                $reemplazos = [
                                                    'Cdad. Autónoma de Buenos Aires' => 'CABA',
                                                    'Ciudad Autónoma de Buenos Aires' => 'CABA',
                                                    'Autonomous City of Buenos Aires' => 'CABA',
                                                    'Provincia de Buenos Aires' => 'Bs.As.',
                                                    'Buenos Aires Province' => 'Bs.As.',
                                                ];
                                                return str_replace(array_keys($reemplazos), array_values($reemplazos), $texto);
                                            };

                                            $origenCalif2Parts = array_map('trim', explode(',', $comentario->origen_direccion));
                                            $countCalif2 = count($origenCalif2Parts);
                                            $origenCalif2Corta = $countCalif2 >= 3 ? $origenCalif2Parts[$countCalif2 - 3] . ', ' . $origenCalif2Parts[$countCalif2 - 2] : $comentario->origen_direccion;
                                            $origenCalif2Corta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $origenCalif2Corta);
                                            $origenCalif2Corta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $origenCalif2Corta);
                                            $origenCalif2Corta = preg_replace('/\s+/', ' ', $origenCalif2Corta);
                                            $origenCalif2Corta = preg_replace('/,\s*,/', ',', $origenCalif2Corta);
                                            $origenCalif2Corta = trim($origenCalif2Corta, ' ,');
                                            $origenCalif2Corta = $acortarProvinciaCalif2($origenCalif2Corta);

                                            $destinoCalif2Parts = array_map('trim', explode(',', $comentario->destino_direccion));
                                            $countCalif2 = count($destinoCalif2Parts);
                                            $destinoCalif2Corta = $countCalif2 >= 3 ? $destinoCalif2Parts[$countCalif2 - 3] . ', ' . $destinoCalif2Parts[$countCalif2 - 2] : $comentario->destino_direccion;
                                            $destinoCalif2Corta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $destinoCalif2Corta);
                                            $destinoCalif2Corta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $destinoCalif2Corta);
                                            $destinoCalif2Corta = preg_replace('/\s+/', ' ', $destinoCalif2Corta);
                                            $destinoCalif2Corta = preg_replace('/,\s*,/', ',', $destinoCalif2Corta);
                                            $destinoCalif2Corta = trim($destinoCalif2Corta, ' ,');
                                            $destinoCalif2Corta = $acortarProvinciaCalif2($destinoCalif2Corta);
                                        @endphp
                                        <small style="color: #64748b; font-weight: 600;">
                                            <i class="fas fa-route me-1"></i>
                                            Viaje: {{ Str::limit($origenCalif2Corta, 20) }} → {{ Str::limit($destinoCalif2Corta, 20) }}
                                        </small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            @else
                <div class="empty-elegant">
                    <div class="empty-icon-elegant">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h5>Aún no has recibido calificaciones como pasajero</h5>
                    <p>Completa un viaje para recibir calificaciones de los conductores.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleAllReceivedRatings() {
    const allRatings = document.getElementById('all-received-ratings');
    const toggleText = document.getElementById('toggle-received-text');
    const toggleIcon = document.getElementById('toggle-received-icon');
    
    if (allRatings && toggleText && toggleIcon) {
        if (allRatings.style.display === 'none' || allRatings.style.display === '') {
            allRatings.style.display = 'block';
            toggleText.textContent = 'Ocultar calificaciones';
            toggleIcon.className = 'fas fa-chevron-up';
        } else {
            allRatings.style.display = 'none';
            toggleText.textContent = 'Ver todas las calificaciones ({{ $comentariosRecibidos->count() }})';
            toggleIcon.className = 'fas fa-chevron-down';
        }
    }
}
</script>
@endsection