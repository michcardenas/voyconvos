@extends('layouts.app')

@section('content')
<style>
    /* ============================================
       RESET ESPECÍFICO PARA ESTA VISTA
       ============================================ */
    #viajes-disponibles-page * {
        box-sizing: border-box;
    }

    /* ============================================
       VARIABLES Y ESTILOS BASE (del dashboard)
       ============================================ */
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
    }

    /* ============================================
       WRAPPER PRINCIPAL - Estilo Dashboard
       ============================================ */
    #viajes-disponibles-page {
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.03) 0%, #FCFCFD 50%, rgba(76, 175, 80, 0.02) 100%);
        min-height: 100vh;
        padding: 6rem 0 3rem 0; /* Padding top para el header fijo */
        position: relative;
    }

    #viajes-disponibles-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(76, 175, 80, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(31, 78, 121, 0.03) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    #viajes-disponibles-page .container {
        position: relative;
        z-index: 1;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    /* ============================================
       PAGE HEADER - Estilo Dashboard
       ============================================ */
    #viajes-disponibles-page .page-header {
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.95) 0%, rgba(76, 175, 80, 0.85) 100%),
                    url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=1600') center/cover;
        color: white !important;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
        min-height: 120px;
        display: flex;
        align-items: center;
    }

    #viajes-disponibles-page .page-header::before {
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

    #viajes-disponibles-page .page-header h2 {
        margin: 0 !important;
        font-weight: 700;
        font-size: 2rem;
        position: relative;
        z-index: 2;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        color: white !important;
    }

    /* ============================================
       FILTROS - Estilo Dashboard Search Box
       ============================================ */
    #viajes-disponibles-page .filter-container {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(31, 78, 121, 0.1);
    }

    #viajes-disponibles-page .filter-form {
        margin: 0;
    }

    #viajes-disponibles-page .filters-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: end;
    }

    #viajes-disponibles-page .filter-group {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 180px;
    }

    #viajes-disponibles-page .filter-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--vcv-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    #viajes-disponibles-page .filter-label i {
        color: var(--vcv-primary);
    }

    #viajes-disponibles-page .filter-select,
    #viajes-disponibles-page input[type="date"].filter-select {
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        color: var(--vcv-dark);
        font-weight: 500;
        width: 100%;
        font-family: 'Poppins', sans-serif;
    }

    #viajes-disponibles-page .filter-select:focus,
    #viajes-disponibles-page input[type="date"].filter-select:focus {
        outline: none;
        border-color: var(--vcv-primary);
        box-shadow: 0 0 0 3px rgba(31, 78, 121, 0.1);
    }

    #viajes-disponibles-page .clear-filter {
        padding: 0.875rem 2rem;
        background: #dc3545;
        color: white !important;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        white-space: nowrap;
    }

    #viajes-disponibles-page .clear-filter:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        color: white !important;
    }

    /* ============================================
       FILTROS ACTIVOS - Estilo Dashboard
       ============================================ */
    #viajes-disponibles-page .active-filters {
        background: linear-gradient(135deg, var(--vcv-primary), rgba(31, 78, 121, 0.9));
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.2);
    }

    #viajes-disponibles-page .active-filters-header {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    #viajes-disponibles-page .active-filters-header strong {
        color: white;
    }

    #viajes-disponibles-page .filters-tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        width: 100%;
    }

    #viajes-disponibles-page .filter-tag {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    /* ============================================
       RESUMEN DE RESULTADOS - Estilo Dashboard
       ============================================ */
    #viajes-disponibles-page .results-summary {
        background: rgba(76, 175, 80, 0.05);
        border-radius: 12px;
        padding: 1.25rem 2rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--vcv-accent);
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.1);
    }

    #viajes-disponibles-page .results-content {
        margin: 0;
    }

    #viajes-disponibles-page .results-text {
        margin: 0;
        color: var(--vcv-dark);
        font-weight: 500;
        font-size: 1rem;
    }

    #viajes-disponibles-page .results-count {
        color: var(--vcv-accent);
        font-weight: 700;
        font-size: 1.1rem;
    }

    #viajes-disponibles-page .results-icon {
        margin-right: 0.5rem;
    }

    #viajes-disponibles-page .filter-indicator {
        color: var(--vcv-primary);
        font-weight: 600;
    }

    #viajes-disponibles-page .no-results-suggestion {
        margin: 0.75rem 0 0 0;
        font-size: 0.875rem;
    }

    #viajes-disponibles-page .no-results-suggestion a {
        color: var(--vcv-primary);
        font-weight: 600;
        text-decoration: none;
    }

    #viajes-disponibles-page .no-results-suggestion a:hover {
        text-decoration: underline;
    }

    /* ============================================
       GRID DE CARDS
       ============================================ */
    #viajes-disponibles-page .row {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
        margin: 0;
    }

    #viajes-disponibles-page .col-lg-4,
    #viajes-disponibles-page .col-md-6 {
        width: 100%;
        padding: 0;
    }

    /* ============================================
       CARDS DE VIAJES - Estilo Dashboard
       ============================================ */
    #viajes-disponibles-page .trip-card {
        background: white;
        border-radius: 16px;
        padding: 0;
        border: 1px solid rgba(31, 78, 121, 0.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    #viajes-disponibles-page .trip-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        border-color: rgba(31, 78, 121, 0.2);
    }

    /* ============================================
       HEADER DE TARJETA - Estilo Dashboard Hero
       ============================================ */
    #viajes-disponibles-page .trip-header {
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.95) 0%, rgba(76, 175, 80, 0.85) 100%);
        color: white !important;
        padding: 1.5rem 1.5rem;
        position: relative;
        overflow: hidden;
    }

    #viajes-disponibles-page .trip-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    #viajes-disponibles-page .route-display {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
        position: relative;
        z-index: 2;
        color: white !important;
    }

    #viajes-disponibles-page .route-city {
        flex: 1;
        text-align: center;
        color: white !important;
    }

    #viajes-disponibles-page .route-arrow {
        margin: 0 1rem;
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.9);
    }

    #viajes-disponibles-page .trip-duration {
        text-align: center;
        font-size: 0.85rem;
        opacity: 0.95;
        margin-top: 0.5rem;
        position: relative;
        z-index: 2;
        color: white !important;
    }

    /* ============================================
       BODY DE TARJETA
       ============================================ */
    #viajes-disponibles-page .trip-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    #viajes-disponibles-page .trip-details {
        flex: 1;
    }

    #viajes-disponibles-page .detail-row {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.5rem 0;
    }

    #viajes-disponibles-page .detail-row:last-child {
        margin-bottom: 0;
    }

    #viajes-disponibles-page .detail-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1rem;
        flex-shrink: 0;
    }

    #viajes-disponibles-page .detail-icon.date {
        background: linear-gradient(135deg, var(--vcv-light) 0%, rgba(31, 78, 121, 0.1) 100%);
        color: var(--vcv-primary);
    }

    #viajes-disponibles-page .detail-icon.time {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(76, 175, 80, 0.05) 100%);
        color: var(--vcv-accent);
    }

    #viajes-disponibles-page .detail-icon.driver {
        background: linear-gradient(135deg, var(--vcv-light) 0%, rgba(221, 242, 254, 0.5) 100%);
        color: var(--vcv-primary);
    }

    #viajes-disponibles-page .detail-icon.seats {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%);
        color: #f57c00;
    }

    #viajes-disponibles-page .driver-row {
        background: rgba(31, 78, 121, 0.03);
        border-radius: 12px;
        padding: 1rem !important;
        margin-bottom: 1.2rem !important;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    #viajes-disponibles-page .driver-avatar {
        width: 50px;
        height: 50px;
        margin-right: 1rem;
        position: relative;
        flex-shrink: 0;
    }

    #viajes-disponibles-page .driver-photo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--vcv-primary);
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);
    }

    #viajes-disponibles-page .driver-photo-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--vcv-primary), rgba(31, 78, 121, 0.8));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        border: 3px solid var(--vcv-primary);
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);
    }

    #viajes-disponibles-page .driver-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
        flex-wrap: wrap;
    }

    #viajes-disponibles-page .stars {
        display: flex;
        gap: 0.15rem;
    }

    #viajes-disponibles-page .stars i {
        font-size: 0.85rem;
        color: #ffc107;
    }

    #viajes-disponibles-page .stars .far {
        color: rgba(255, 193, 7, 0.3);
    }

    #viajes-disponibles-page .rating-value {
        font-weight: 600;
        color: var(--vcv-primary);
        font-size: 0.875rem;
    }

    #viajes-disponibles-page .rating-count {
        color: rgba(58, 58, 58, 0.6);
        font-size: 0.75rem;
    }

    #viajes-disponibles-page .verified-badge {
        display: inline-block;
        margin-left: 0.5rem;
        color: var(--vcv-accent);
        font-size: 0.95rem;
    }

    #viajes-disponibles-page .verified-badge i {
        filter: drop-shadow(0 1px 2px rgba(76, 175, 80, 0.3));
    }

    #viajes-disponibles-page .experience-badge {
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
        padding: 0.25rem 0.65rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid rgba(31, 78, 121, 0.2);
    }

    #viajes-disponibles-page .driver-no-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    #viajes-disponibles-page .no-rating-text {
        font-size: 0.8rem;
        color: rgba(58, 58, 58, 0.6);
    }

    #viajes-disponibles-page .detail-content {
        flex: 1;
    }

    #viajes-disponibles-page .detail-label {
        font-size: 0.8rem;
        color: rgba(58, 58, 58, 0.7);
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    #viajes-disponibles-page .detail-value {
        color: var(--vcv-dark);
        font-weight: 600;
        font-size: 0.95rem;
    }

    /* ============================================
       SECCIÓN DE PRECIO - Estilo Dashboard
       ============================================ */
    #viajes-disponibles-page .price-section {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.08) 0%, rgba(76, 175, 80, 0.03) 100%);
        border-radius: 12px;
        padding: 1.25rem;
        margin: 1.25rem 0;
        text-align: center;
        border: 2px solid rgba(76, 175, 80, 0.2);
    }

    #viajes-disponibles-page .price-amount {
        font-size: 2rem;
        font-weight: 700;
        color: var(--vcv-accent);
        margin: 0;
    }

    #viajes-disponibles-page .price-label {
        font-size: 0.85rem;
        color: rgba(58, 58, 58, 0.7);
        margin: 0.25rem 0 0 0;
        font-weight: 500;
    }

    #viajes-disponibles-page .seats-available {
        display: inline-block;
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid rgba(76, 175, 80, 0.3);
    }

    /* ============================================
       ACCIONES - Estilo Dashboard Buttons
       ============================================ */
    #viajes-disponibles-page .trip-actions {
        padding: 0 1.5rem 1.5rem;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    #viajes-disponibles-page .btn-custom {
        border: none;
        border-radius: 10px;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        flex: 1;
        min-width: 120px;
        cursor: pointer;
    }

    #viajes-disponibles-page .btn-custom.primary {
        background: var(--vcv-primary);
        color: white !important;
    }

    #viajes-disponibles-page .btn-custom.primary:hover {
        background: #173d61;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(31, 78, 121, 0.3);
        color: white !important;
    }

    #viajes-disponibles-page .btn-custom.success {
        background: var(--vcv-accent);
        color: white !important;
    }

    #viajes-disponibles-page .btn-custom.success:hover {
        background: #45a049;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(76, 175, 80, 0.3);
        color: white !important;
    }

    #viajes-disponibles-page .btn-custom.outline {
        background: rgba(31, 78, 121, 0.05);
        color: var(--vcv-primary) !important;
        border: 2px solid rgba(31, 78, 121, 0.3);
    }

    #viajes-disponibles-page .btn-custom.outline:hover {
        background: var(--vcv-primary);
        color: white !important;
        border-color: var(--vcv-primary);
        transform: translateY(-2px);
    }

    #viajes-disponibles-page .btn-custom.small {
        padding: 0.6rem 1.2rem;
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }

    /* ============================================
       VERIFICACIÓN - Estilo Dashboard Alerts
       ============================================ */
    #viajes-disponibles-page .verification-message {
        margin-bottom: 1rem;
        width: 100%;
    }

    #viajes-disponibles-page .verification-alert {
        display: flex;
        align-items: flex-start;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border: 2px solid;
    }

    #viajes-disponibles-page .verification-alert.pending {
        background: rgba(255, 193, 7, 0.1);
        border-color: rgba(255, 193, 7, 0.3);
        color: #856404;
    }

    #viajes-disponibles-page .verification-alert.incomplete {
        background: rgba(220, 53, 69, 0.1);
        border-color: rgba(220, 53, 69, 0.3);
        color: #721c24;
    }

    #viajes-disponibles-page .verification-alert .alert-icon {
        margin-right: 0.75rem;
        font-size: 1.1rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
    }

    #viajes-disponibles-page .verification-alert .alert-content {
        flex: 1;
    }

    #viajes-disponibles-page .verification-alert .alert-title {
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
    }

    #viajes-disponibles-page .verification-alert .alert-text {
        font-size: 0.8rem;
        margin: 0 0 0.75rem 0;
        opacity: 0.95;
    }

    #viajes-disponibles-page .disabled-actions {
        display: flex;
        gap: 0.75rem;
        opacity: 0.6;
        width: 100%;
    }

    #viajes-disponibles-page .btn-custom.disabled {
        background-color: #e9ecef !important;
        color: #6c757d !important;
        border-color: #dee2e6 !important;
        cursor: not-allowed !important;
        pointer-events: none;
    }

    #viajes-disponibles-page .btn-custom.outline.disabled {
        background-color: transparent !important;
        color: #6c757d !important;
        border-color: #dee2e6 !important;
    }

    /* ============================================
       EMPTY STATE - Estilo Dashboard
       ============================================ */
    #viajes-disponibles-page .empty-state {
        background: white;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        border: 1px solid rgba(31, 78, 121, 0.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    #viajes-disponibles-page .empty-state i {
        font-size: 4.5rem;
        color: rgba(31, 78, 121, 0.3);
        margin-bottom: 1.5rem;
    }

    #viajes-disponibles-page .empty-state h4 {
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    #viajes-disponibles-page .empty-state p {
        color: rgba(58, 58, 58, 0.7);
        margin: 0;
        font-size: 1rem;
        line-height: 1.6;
    }

    /* ============================================
       ANIMACIONES - del Dashboard
       ============================================ */
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(10px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    /* ============================================
       RESPONSIVE - Estilo Dashboard
       ============================================ */
    @media (max-width: 768px) {
        #viajes-disponibles-page {
            padding: 5rem 0 2rem 0;
        }

        #viajes-disponibles-page .container {
            padding: 0 0.75rem;
        }
        
        #viajes-disponibles-page .page-header {
            padding: 1.5rem;
            min-height: 100px;
        }
        
        #viajes-disponibles-page .page-header h2 {
            font-size: 1.5rem;
        }
        
        #viajes-disponibles-page .filter-container {
            padding: 1.5rem;
        }

        #viajes-disponibles-page .filters-row {
            flex-direction: column;
        }

        #viajes-disponibles-page .filter-group {
            width: 100%;
            min-width: 100%;
        }

        #viajes-disponibles-page .clear-filter {
            width: 100%;
            justify-content: center;
        }

        #viajes-disponibles-page .active-filters {
            padding: 1.25rem 1.5rem;
        }

        #viajes-disponibles-page .filter-tag {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }

        #viajes-disponibles-page .results-summary {
            padding: 1rem 1.5rem;
        }

        #viajes-disponibles-page .row {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        #viajes-disponibles-page .trip-card {
            margin-bottom: 0;
        }
        
        #viajes-disponibles-page .trip-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        #viajes-disponibles-page .btn-custom {
            width: 100%;
            margin: 0;
        }

        #viajes-disponibles-page .disabled-actions {
            flex-direction: column;
        }
        
        #viajes-disponibles-page .route-display {
            font-size: 0.95rem;
        }
        
        #viajes-disponibles-page .route-arrow {
            margin: 0 0.5rem;
        }

        #viajes-disponibles-page .driver-avatar {
            width: 45px;
            height: 45px;
        }

        #viajes-disponibles-page .driver-photo,
        #viajes-disponibles-page .driver-photo-placeholder {
            width: 45px;
            height: 45px;
        }

        #viajes-disponibles-page .driver-rating {
            gap: 0.3rem;
        }

        #viajes-disponibles-page .stars {
            gap: 0.1rem;
        }

        #viajes-disponibles-page .stars i {
            font-size: 0.75rem;
        }

        #viajes-disponibles-page .verification-alert {
            padding: 0.875rem 1rem;
            font-size: 0.8rem;
        }
        
        #viajes-disponibles-page .verification-alert .alert-title {
            font-size: 0.8rem;
        }
        
        #viajes-disponibles-page .verification-alert .alert-text {
            font-size: 0.75rem;
        }

        #viajes-disponibles-page .empty-state {
            padding: 3rem 1.5rem;
        }

        #viajes-disponibles-page .empty-state i {
            font-size: 3.5rem;
        }

        #viajes-disponibles-page .empty-state h4 {
            font-size: 1.3rem;
        }

        #viajes-disponibles-page .empty-state p {
            font-size: 0.9rem;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        #viajes-disponibles-page .row {
            grid-template-columns: repeat(2, 1fr);
        }

        #viajes-disponibles-page .filters-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }

        #viajes-disponibles-page .filter-group:last-child {
            grid-column: span 2;
        }
    }

    @media (min-width: 1025px) {
        #viajes-disponibles-page .row {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>

<div id="viajes-disponibles-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h2> Viajes Disponibles</h2>
        </div>

        <!-- Filtros -->
        <div class="filter-container">
            <form method="GET" action="{{ route('pasajero.viajes.disponibles') }}" class="filter-form" id="filterForm">
                <div class="filters-row">
                    <!-- Filtro por Ciudad Origen -->
                    <div class="filter-group">
                        <label for="ciudad_origen" class="filter-label">
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            <span class="label-text">Ciudad origen</span>
                        </label>
                        <select name="ciudad_origen" 
                                id="ciudad_origen" 
                                class="filter-select" 
                                onchange="this.form.submit()"
                                aria-label="Seleccionar ciudad de origen">
                            <option value="">Todas las ciudades</option>
                            @foreach($ciudadesOrigen as $ciudad)
                                <option value="{{ $ciudad }}" 
                                        {{ request('ciudad_origen') == $ciudad ? 'selected' : '' }}>
                                    {{ $ciudad }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Ciudad Destino -->
                    <div class="filter-group">
                        <label for="ciudad_destino" class="filter-label">
                            <i class="fas fa-flag-checkered" aria-hidden="true"></i>
                            <span class="label-text">Ciudad destino</span>
                        </label>
                        <select name="ciudad_destino" 
                                id="ciudad_destino" 
                                class="filter-select" 
                                onchange="this.form.submit()"
                                aria-label="Seleccionar ciudad de destino">
                            <option value="">Todas las ciudades</option>
                            @foreach($ciudadesDestino as $ciudad)
                                <option value="{{ $ciudad }}" 
                                        {{ request('ciudad_destino') == $ciudad ? 'selected' : '' }}>
                                    {{ $ciudad }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Fecha -->
                    <div class="filter-group">
                        <label for="fecha_salida" class="filter-label">
                            <i class="fas fa-calendar" aria-hidden="true"></i>
                            <span class="label-text">Fecha salida</span>
                        </label>
                        <input type="date" 
                               name="fecha_salida" 
                               id="fecha_salida" 
                               class="filter-select" 
                               value="{{ request('fecha_salida') }}"
                               min="{{ date('Y-m-d') }}"
                               onchange="this.form.submit()"
                               aria-label="Seleccionar fecha de salida">
                    </div>

                    <!-- Filtro por Puestos -->
                    <div class="filter-group">
                        <label for="puestos_minimos" class="filter-label">
                            <i class="fas fa-chair" aria-hidden="true"></i>
                            <span class="label-text">Puestos mínimos</span>
                        </label>
                        <select name="puestos_minimos"
                                id="puestos_minimos"
                                class="filter-select"
                                onchange="this.form.submit()"
                                aria-label="Seleccionar número mínimo de puestos">
                            <option value="">Todos</option>
                            <option value="1" {{ request('puestos_minimos') == '1' ? 'selected' : '' }}>1+</option>
                            <option value="2" {{ request('puestos_minimos') == '2' ? 'selected' : '' }}>2+</option>
                            <option value="3" {{ request('puestos_minimos') == '3' ? 'selected' : '' }}>3+</option>
                            <option value="4" {{ request('puestos_minimos') == '4' ? 'selected' : '' }}>4+</option>
                        </select>
                    </div>

                    <!-- Filtro por Ordenamiento -->
                    <div class="filter-group">
                        <label for="ordenar" class="filter-label">
                            <i class="fas fa-sort" aria-hidden="true"></i>
                            <span class="label-text">Ordenar por</span>
                        </label>
                        <select name="ordenar"
                                id="ordenar"
                                class="filter-select"
                                onchange="this.form.submit()"
                                aria-label="Ordenar resultados">
                            <option value="fecha" {{ request('ordenar', 'fecha') == 'fecha' ? 'selected' : '' }}>Fecha (próxima)</option>
                            <option value="cercania" {{ request('ordenar') == 'cercania' ? 'selected' : '' }}>Cercanía</option>
                            <option value="precio" {{ request('ordenar') == 'precio' ? 'selected' : '' }}>Precio (menor)</option>
                        </select>
                    </div>

                    <!-- Botón limpiar -->
                    @if(request()->hasAny(['puestos_minimos', 'ciudad_origen', 'ciudad_destino', 'fecha_salida']))
                        <div class="filter-group">
                            <label class="filter-label" style="opacity: 0; pointer-events: none;" aria-hidden="true">
                                <span>Acciones</span>
                            </label>
                            <a href="{{ route('pasajero.viajes.disponibles') }}" 
                               class="clear-filter"
                               role="button"
                               aria-label="Limpiar todos los filtros">
                                <i class="fas fa-times" aria-hidden="true"></i>
                                <span class="clear-text">Limpiar</span>
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Filtros Activos -->
        @if(request()->hasAny(['puestos_minimos', 'ciudad_origen', 'ciudad_destino', 'fecha_salida']))
            <div class="active-filters" role="region" aria-label="Filtros aplicados">
                <div class="active-filters-header">
                    <strong>Filtros aplicados:</strong>
                </div>
                <div class="filters-tags-container">
                    @if(request('ciudad_origen'))
                        <span class="filter-tag" role="status">
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            <span>Origen: {{ request('ciudad_origen') }}</span>
                        </span>
                    @endif
                    @if(request('ciudad_destino'))
                        <span class="filter-tag" role="status">
                            <i class="fas fa-flag-checkered" aria-hidden="true"></i>
                            <span>Destino: {{ request('ciudad_destino') }}</span>
                        </span>
                    @endif
                    @if(request('fecha_salida'))
                        <span class="filter-tag" role="status">
                            <i class="fas fa-calendar" aria-hidden="true"></i>
                            <span>Fecha: {{ \Carbon\Carbon::parse(request('fecha_salida'))->format('d/m/Y') }}</span>
                        </span>
                    @endif
                    @if(request('puestos_minimos'))
                        <span class="filter-tag" role="status">
                            <i class="fas fa-chair" aria-hidden="true"></i>
                            <span>{{ request('puestos_minimos') }}+ puestos</span>
                        </span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Resumen de Resultados -->
        <div class="results-summary" role="status" aria-live="polite">
            <div class="results-content">
                <p class="results-text">
                    <span class="results-icon">🔍</span>
                    Se encontraron 
                    <span class="results-count">{{ $viajesDisponibles->count() }}</span> 
                    {{ $viajesDisponibles->count() == 1 ? 'viaje disponible' : 'viajes disponibles' }}
                    @if(request()->hasAny(['puestos_minimos', 'ciudad_origen', 'ciudad_destino', 'fecha_salida']))
                        <span class="filter-indicator">con los filtros aplicados</span>
                    @endif
                </p>
                @if($viajesDisponibles->count() == 0 && request()->hasAny(['puestos_minimos', 'ciudad_origen', 'ciudad_destino', 'fecha_salida']))
                    <p class="no-results-suggestion">
                        <small>💡 Intenta <a href="{{ route('pasajero.viajes.disponibles') }}">quitar algunos filtros</a> para ver más opciones</small>
                    </p>
                @endif
            </div>
        </div>

        <!-- Grid de Viajes -->
        @if($viajesDisponibles->isEmpty())
            <div class="empty-state">
                <i class="fas fa-car-side"></i>
                <h4>No hay viajes disponibles</h4>
                <p>Por el momento no hay viajes programados.<br>¡Vuelve más tarde para encontrar tu próximo destino!</p>
            </div>
        @else
            <div class="row">
                @foreach($viajesDisponibles as $viaje)
                    <div class="col-lg-4 col-md-6">
                        <div class="trip-card">
                            <!-- Trip Header -->
                            <div class="trip-header">
                                <div class="route-display">
                                    <div class="route-city">{{ explode(',', $viaje->origen_direccion)[0] ?? $viaje->origen_direccion }}</div>
                                    <div class="route-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                    <div class="route-city">{{ explode(',', $viaje->destino_direccion)[0] ?? $viaje->destino_direccion }}</div>
                                </div>
                                <div class="trip-duration">
                                    {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('M d, Y') }}
                                    @if($viaje->hora_salida)
                                        • {{ \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') }}
                                    @endif
                                    @if(request('ordenar') == 'cercania' && isset($viaje->distancia_km) && $viaje->distancia_km < 999999)
                                        • 📍 {{ number_format($viaje->distancia_km, 1) }} km
                                    @endif
                                </div>
                            </div>

                            <!-- Trip Body -->
                            <div class="trip-body">
                                <div class="trip-details">
                                    <!-- Date -->
                                    <div class="detail-row">
                                        <div class="detail-icon date">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Fecha de salida</div>
                                            <div class="detail-value">{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</div>
                                        </div>
                                    </div>

                                    <!-- Time -->
                                    <div class="detail-row">
                                        <div class="detail-icon time">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Hora de salida</div>
                                            <div class="detail-value">
                                                @if($viaje->hora_salida)
                                                    {{ \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') }}
                                                @else
                                                    Por definir
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Driver -->
                                    <div class="detail-row driver-row">
                                        <div class="driver-avatar">
                                            @if($viaje->conductor)
                                                <img src="{{ $viaje->conductor->foto ? asset('storage/' . $viaje->conductor->foto) : asset('img/usuario.png') }}" 
                                                    alt="{{ $viaje->conductor->name }}" 
                                                    class="driver-photo">
                                            @else
                                                <div class="driver-photo-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Conductor</div>
                                            <div class="detail-value">
                                                {{ $viaje->conductor?->name ?? 'No disponible' }}
                                                @if($viaje->conductor && ($viaje->conductor->verificado ?? ($viaje->conductor->calificacion_promedio ?? 0) >= 4.5))
                                                    <span class="verified-badge">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            @if($viaje->conductor)
                                                @php
                                                    $tieneCalificaciones = ($viaje->conductor->total_calificaciones ?? 0) > 0;
                                                    $rating = $viaje->conductor->calificacion_promedio ?? 0;
                                                @endphp
                                                
                                                @if($tieneCalificaciones && $rating > 0)
                                                    <div class="driver-rating">
                                                        @php
                                                            $fullStars = floor($rating);
                                                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                                        @endphp
                                                        <div class="stars">
                                                            @for($i = 1; $i <= $fullStars; $i++)
                                                                <i class="fas fa-star"></i>
                                                            @endfor
                                                            @if($hasHalfStar)
                                                                <i class="fas fa-star-half-alt"></i>
                                                            @endif
                                                            @for($i = 1; $i <= $emptyStars; $i++)
                                                                <i class="far fa-star"></i>
                                                            @endfor
                                                        </div>
                                                        <span class="rating-value">{{ number_format($rating, 1) }}</span>
                                                        <span class="rating-count">({{ $viaje->conductor->total_calificaciones }})</span>
                                                        @if($viaje->conductor->experiencia_anos ?? false)
                                                            <span class="experience-badge">{{ $viaje->conductor->experiencia_anos }}+ años</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="driver-no-rating">
                                                        <span class="no-rating-text"></span>
                                                        @if($viaje->conductor->experiencia_anos ?? false)
                                                            <span class="experience-badge">{{ $viaje->conductor->experiencia_anos }}+ años</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Available Seats -->
                                    <div class="detail-row">
                                        <div class="detail-icon seats">
                                            <i class="fas fa-chair"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Puestos disponibles</div>
                                            <div class="detail-value">
                                                <span class="seats-available">{{ $viaje->puestos_disponibles }} disponibles</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Section -->
                                <div class="price-section">
                                    <p class="price-amount">${{ number_format($viaje->valor_persona ?? 5200, 2, ',', '.') }}</p>
                                    <p class="price-label">por persona</p>
                                </div>
                            </div>

                            <!-- Trip Actions -->
                            <div class="trip-actions">
                                @if($estadoVerificacion['puede_acceder'])
                                    <!-- Usuario verificado -->
                                    <a href="{{ route('pasajero.confirmar.mostrar', $viaje->id) }}" class="btn-custom primary">
                                        <i class="fas fa-info-circle"></i>
                                        Detalles
                                    </a>

                                    <a href="{{ route('chat.ver', $reserva->viaje_id ?? $viaje->id) }}" class="btn-custom outline">
                                        <i class="fas fa-comments"></i>
                                        Chat
                                    </a>
                                @else
                                    <!-- Usuario no verificado -->
                                    <div class="verification-message">
                                        @if($estadoVerificacion['mensaje']['tipo'] === 'pendiente')
                                            <div class="verification-alert pending">
                                                <div class="alert-icon">
                                                    <i class="{{ $estadoVerificacion['mensaje']['icono'] }}"></i>
                                                </div>
                                                <div class="alert-content">
                                                    <h5 class="alert-title">{{ $estadoVerificacion['mensaje']['titulo'] }}</h5>
                                                    <p class="alert-text">{{ $estadoVerificacion['mensaje']['texto'] }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="verification-alert incomplete">
                                                <div class="alert-icon">
                                                    <i class="{{ $estadoVerificacion['mensaje']['icono'] }}"></i>
                                                </div>
                                                <div class="alert-content">
                                                    <h5 class="alert-title">{{ $estadoVerificacion['mensaje']['titulo'] }}</h5>
                                                    <p class="alert-text">{{ $estadoVerificacion['mensaje']['texto'] }}</p>
                                                    @if(isset($estadoVerificacion['mensaje']['boton']))
                                                        <a href="{{ route($estadoVerificacion['mensaje']['boton']['ruta']) }}" 
                                                           class="btn-custom primary small">
                                                            <i class="fas fa-edit"></i>
                                                            {{ $estadoVerificacion['mensaje']['boton']['texto'] }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Botones deshabilitados -->
                                    <div class="disabled-actions">
                                        <button class="btn-custom primary disabled" disabled title="Completa tu verificación para acceder">
                                            <i class="fas fa-info-circle"></i>
                                            Detalles
                                        </button>

                                        <button class="btn-custom outline disabled" disabled title="Completa tu verificación para acceder">
                                            <i class="fas fa-comments"></i>
                                            Chat
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection