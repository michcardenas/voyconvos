@extends('layouts.app_dashboard')

@section('title', 'Detalle de tu reserva')

@section('content')
@php
    // Función para acortar nombres de provincias
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

    // Procesar origen
    $origenParts = array_map('trim', explode(',', $reserva->viaje->origen_direccion));
    $count = count($origenParts);
    $origenCorta = $count >= 3 ? $origenParts[$count - 3] . ', ' . $origenParts[$count - 2] : $reserva->viaje->origen_direccion;
    $origenCorta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $origenCorta);
    $origenCorta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $origenCorta);
    $origenCorta = preg_replace('/\s+/', ' ', $origenCorta);
    $origenCorta = preg_replace('/,\s*,/', ',', $origenCorta);
    $origenCorta = trim($origenCorta, ' ,');
    $origenCorta = $acortarProvincia($origenCorta);

    // Procesar destino
    $destinoParts = array_map('trim', explode(',', $reserva->viaje->destino_direccion));
    $count = count($destinoParts);
    $destinoCorta = $count >= 3 ? $destinoParts[$count - 3] . ', ' . $destinoParts[$count - 2] : $reserva->viaje->destino_direccion;
    $destinoCorta = preg_replace('/\b[A-Z]?\d{4}[A-Z]{0,3}\b\s*/i', '', $destinoCorta);
    $destinoCorta = preg_replace('/\b\w*\d{3,}\w*\b\s*/i', '', $destinoCorta);
    $destinoCorta = preg_replace('/\s+/', ' ', $destinoCorta);
    $destinoCorta = preg_replace('/,\s*,/', ',', $destinoCorta);
    $destinoCorta = trim($destinoCorta, ' ,');
    $destinoCorta = $acortarProvincia($destinoCorta);
@endphp
<style>
/* ===============================================
   🎨 VARIABLES CSS
   =============================================== */
:root {
    --vcv-primary: #1F4E79;
    --vcv-light: #DDF2FE;
    --vcv-dark: #3A3A3A;
    --vcv-accent: #4CAF50;
    --vcv-bg: #FCFCFD;
}

/* ===============================================
   📐 LAYOUT PRINCIPAL
   =============================================== */
.details-wrapper {
    background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(31, 78, 121, 0.03) 100%);
    min-height: 100vh;
    padding: 2rem 0;
    position: relative;
}

.details-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(76, 175, 80, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(31, 78, 121, 0.05) 0%, transparent 50%);
    pointer-events: none;
}

.details-wrapper .container {
    max-width: 900px !important;
    margin: 0 auto !important;
    padding: 0 1.5rem !important;
}

.container {
    position: relative;
    z-index: 1;
}

/* ===============================================
   📄 HEADER Y TARJETAS PRINCIPALES
   =============================================== */
.page-header {
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
    margin-top: 5rem;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    background: radial-gradient(circle, rgba(76, 175, 80, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.page-header h2 {
    margin: 0;
    font-weight: 600;
    font-size: 1.8rem;
    position: relative;
    z-index: 2;
}

.info-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 1.8rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(31, 78, 121, 0.12);
    box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(31, 78, 121, 0.12);
}

/* ===============================================
   🎯 COMPONENTES DE TARJETAS
   =============================================== */
.card-header-custom {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 0.8rem;
    border-bottom: 2px solid rgba(31, 78, 121, 0.1);
}

.card-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.1rem;
}

.card-icon.route {
    background: rgba(31, 78, 121, 0.1);
    color: var(--vcv-primary);
}

.card-icon.driver {
    background: rgba(76, 175, 80, 0.1);
    color: var(--vcv-accent);
}

.card-icon.booking {
    background: rgba(221, 242, 254, 0.8);
    color: var(--vcv-primary);
}

.card-title {
    margin: 0;
    color: var(--vcv-primary);
    font-weight: 600;
    font-size: 1.1rem;
}

/* ===============================================
   📊 GRILLA DE INFORMACIÓN
   =============================================== */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.info-item {
    background: rgba(221, 242, 254, 0.3);
    padding: 1rem;
    border-radius: 8px;
    border-left: 3px solid var(--vcv-primary);
    transition: all 0.2s ease;
}

.info-item:hover {
    background: rgba(221, 242, 254, 0.5);
    border-left-color: var(--vcv-accent);
}

.info-label {
    font-weight: 600;
    color: var(--vcv-dark);
    font-size: 0.85rem;
    margin-bottom: 0.3rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    color: var(--vcv-primary);
    font-weight: 500;
    font-size: 1rem;
}

/* ===============================================
   🏷️ BADGES Y ESTADOS
   =============================================== */
.status-badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-confirmado {
    background: rgba(76, 175, 80, 0.1);
    color: var(--vcv-accent);
    border: 1px solid rgba(76, 175, 80, 0.3);
}

.status-pendiente {
    background: rgba(255, 193, 7, 0.1);
    color: #f57c00;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-pendiente_pago {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
    animation: pulse-payment 2s infinite;
}

.status-cancelado {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

.rating-status {
    background: rgba(76, 175, 80, 0.1);
    border: 1px solid rgba(76, 175, 80, 0.3);
    color: var(--vcv-accent);
    padding: 1rem;
    border-radius: 10px;
    margin: 1rem 0;
    text-align: center;
    font-weight: 600;
}

/* ===============================================
   🗺️ MAPA Y RUTA
   =============================================== */
.map-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(31, 78, 121, 0.12);
    box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
}

.map-header {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.map-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(31, 78, 121, 0.1);
    color: var(--vcv-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.1rem;
}

.map-title {
    margin: 0;
    color: var(--vcv-primary);
    font-weight: 600;
    font-size: 1.1rem;
}

#mapa {
    width: 100%;
    height: 400px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.route-summary {
    background: rgba(31, 78, 121, 0.05);
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border: 1px solid rgba(31, 78, 121, 0.1);
}

.route-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.route-item:last-child {
    margin-bottom: 0;
}

.route-marker {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin-right: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
    color: white;
}

.route-marker.origin {
    background: var(--vcv-accent);
}

.route-marker.destination {
    background: #dc3545;
}

.route-text {
    color: var(--vcv-dark);
    font-weight: 500;
}

/* ===============================================
   🎬 SECCIÓN DE ACCIONES Y BOTONES
   =============================================== */
.action-section {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid rgba(31, 78, 121, 0.12);
    box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
    text-align: center;
}

.btn-custom {
    border: none;
    border-radius: 25px;
    padding: 0.8rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    margin: 0.5rem;
    font-size: 0.9rem;
}

.btn-custom.primary {
    background: var(--vcv-primary);
    color: white;
}

.btn-custom.primary:hover {
    background: rgba(31, 78, 121, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(31, 78, 121, 0.3);
    color: white;
}

.btn-custom.accent {
    background: var(--vcv-accent);
    color: white;
}

.btn-custom.accent:hover {
    background: rgba(76, 175, 80, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(76, 175, 80, 0.3);
    color: white;
}

.btn-custom.secondary {
    background: rgba(58, 58, 58, 0.1);
    color: var(--vcv-dark);
    border: 1px solid rgba(58, 58, 58, 0.3);
}

.btn-custom.secondary:hover {
    background: var(--vcv-dark);
    color: white;
    transform: translateY(-2px);
}

/* ===============================================
   💳 BOTONES DE PAGO
   =============================================== */
.btn-pay {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    padding: 12px 30px;
    font-weight: bold;
    font-size: 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-pay:hover {
    background: linear-gradient(135deg, #218838, #1ea085);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn-pay:active {
    transform: translateY(0);
}

.btn-pay i {
    margin-right: 8px;
}

.payment-button-container {
    border-top: 1px solid #eee;
    padding-top: 15px;
}

/* ===============================================
   📞 BOTONES DE CONTACTO
   =============================================== */
.contact-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.rating-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4) !important;
}

/* ===============================================
   🌟 MODAL DE CALIFICACIÓN - OVERLAY Y CONTENEDOR
   =============================================== */
.modal-overlay-calificar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    backdrop-filter: blur(8px);
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    padding: 1rem;
    box-sizing: border-box;
    overflow-y: auto;
}

.modal-overlay-calificar.show {
    display: flex;
    opacity: 1;
    visibility: visible;
}

.modal-container-calificar {
    background: white;
    border-radius: 24px;
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(255, 255, 255, 0.05);
    max-width: 520px;
    width: 100%;
    max-height: calc(100vh - 2rem);
    overflow-y: auto;
    transform: scale(0.8) translateY(60px);
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    position: relative;
    margin: auto;
    display: flex;
    flex-direction: column;
}

.modal-overlay-calificar.show .modal-container-calificar {
    transform: scale(1) translateY(0);
}

/* ===============================================
   📋 MODAL - HEADER
   =============================================== */
.modal-header-calificar {
    position: relative;
    padding: 2rem 2rem 1rem;
    text-align: center;
    background: linear-gradient(135deg, #ffc107, #ffb700);
    color: #212529;
}

.modal-close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    color: #212529;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.3s ease;
}

.modal-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.modal-icon-calificar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: #212529;
    font-size: 1.8rem;
    backdrop-filter: blur(10px);
}

.modal-title-calificar {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #212529;
}

/* ===============================================
   📝 MODAL - BODY Y CONTENIDO
   =============================================== */
.modal-body-calificar {
    padding: 2rem;
    flex: 1;
    overflow-y: auto;
}

.conductor-info-modal {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 16px;
    border: 1px solid #dee2e6;
}

.conductor-avatar {
    flex-shrink: 0;
}

.conductor-photo-modal {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #ffc107;
}

.conductor-photo-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(45deg, #6c757d, #495057);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.conductor-name-section strong {
    display: block;
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 0.25rem;
}

.info-subtitle {
    color: #6c757d;
    font-size: 0.9rem;
    font-style: italic;
}

/* ===============================================
   ⭐ MODAL - SISTEMA DE ESTRELLAS
   =============================================== */
.rating-section {
    text-align: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #fff;
    border-radius: 16px;
    border: 2px solid #f8f9fa;
}

.rating-label {
    display: block;
    margin-bottom: 1rem;
    font-weight: 600;
    color: #333;
    font-size: 1rem;
}

.stars-container {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.star-conductor {
    font-size: 2.2rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    filter: grayscale(100%) brightness(0.7);
    opacity: 0.6;
    user-select: none;
}

.star-conductor:hover,
.star-conductor.active {
    filter: grayscale(0%) brightness(1);
    opacity: 1;
    transform: scale(1.15);
}

.star-conductor.active {
    filter: drop-shadow(0 0 8px #FFD700);
}

.rating-text {
    color: #6c757d;
    font-size: 0.95rem;
    font-weight: 500;
    min-height: 20px;
}

/* ===============================================
   💬 MODAL - CAMPO DE COMENTARIO
   =============================================== */
.comentario-section {
    margin-bottom: 2rem;
}

.comentario-label {
    display: block;
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: #333;
    font-size: 1rem;
}

.comentario-textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-family: inherit;
    font-size: 0.95rem;
    resize: vertical;
    transition: all 0.3s ease;
    background: #fafbfc;
}

.comentario-textarea:focus {
    outline: none;
    border-color: #ffc107;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.1);
}

.comentario-help {
    text-align: right;
    margin-top: 0.5rem;
}

.comentario-help small {
    color: #6c757d;
    font-size: 0.8rem;
}

/* ===============================================
   🔘 MODAL - FOOTER Y BOTONES
   =============================================== */
.modal-footer-calificar {
    padding: 1.5rem 2rem 2rem;
    display: flex;
    gap: 1rem;
    justify-content: center;
    background: #f8f9fa;
}

.modal-btn-cancel,
.modal-btn-confirm {
    padding: 0.875rem 1.75rem;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
    min-width: 140px;
    justify-content: center;
}

.modal-btn-cancel {
    background: #6c757d;
    color: white;
}

.modal-btn-cancel:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
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
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
}
.conductor-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.conductor-section-title {
    margin-bottom: 15px;
    color: #333;
    text-align: center;
}

.trust-badge {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    padding: 12px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 20px;
    font-size: 0.95em;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.conductor-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.conductor-photo-container {
    text-align: center;
}

.conductor-photo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #007bff;
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.conductor-photo-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(45deg, #6c757d, #495057);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    box-shadow: 0 4px 12px rgba(108,117,125,0.3);
}

.conductor-info-item {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.conductor-info-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.85em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.conductor-info-value {
    color: #333;
    font-size: 1.15em;
    font-weight: 500;
}

.conductor-phone-link {
    color: #007bff;
    text-decoration: none;
    font-size: 1.1em;
    font-weight: 500;
}

.conductor-pending-info {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    border-left: 4px solid #ffc107;
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.conductor-pending-info i {
    font-size: 1.5em;
    color: #856404;
    margin-top: 5px;
}

.pending-info-content {
    text-align: left;
    color: #856404;
}

.pending-info-title {
    font-weight: 600;
    margin-bottom: 5px;
}

.pending-info-text {
    font-size: 0.9em;
}

.contact-buttons {
    margin-top: 20px;
}

.contact-btn {
    padding: 12px;
    font-weight: 600;
    border-radius: 10px;
    border-width: 2px;
    transition: all 0.3s ease;
    text-align: center;
}

.rating-btn {
    padding: 15px 30px;
    font-weight: 600;
    border-radius: 10px;
    border-width: 2px;
    transition: all 0.3s ease;
    min-width: 250px;
    text-align: center;
    background: linear-gradient(135deg, #ffc107, #ffb700);
    border: none;
    color: #212529;
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.rating-completed {
    border-radius: 10px;
    padding: 15px;
    margin: 0;
    min-width: 250px;
    text-align: center;
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    border: 1px solid #c3e6cb;
    color: #155724;
    font-weight: 500;
}

.payment-note {
    color: #6c757d;
    margin-top: 10px;
    font-size: 0.9em;
    margin-bottom: 0;
}

.map-display {
    height: 400px;
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
    border: 2px solid #ddd;
}

.map-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    color: #666;
}

.map-loading-content {
    text-align: center;
}

.map-loading-content i {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.action-title {
    margin-bottom: 1rem;
    color: var(--vcv-primary);
    font-weight: 600;
}

/* CORREGIR PROBLEMAS DEL MODAL */
.modal-overlay-calificar {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 999999 !important; /* Z-index muy alto */
    background: rgba(0, 0, 0, 0.8) !important;
    backdrop-filter: blur(8px) !important;
}

.modal-container-calificar {
    position: relative !important;
    z-index: 1000000 !important;
}

/* Asegurar que el modal esté por encima de todo */
body.modal-open {
    overflow: hidden !important;
    position: fixed !important;
    width: 100% !important;
}

/* ===============================================
   🎭 ANIMACIONES
   =============================================== */
@keyframes pulse-payment {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.8) translateY(60px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

@keyframes starGlow {
    0%, 100% { 
        filter: drop-shadow(0 0 8px #FFD700); 
    }
    50% { 
        filter: drop-shadow(0 0 12px #FFD700); 
    }
}

.star-conductor.active {
    animation: starGlow 2s ease-in-out infinite;
}

/* ===============================================
   📱 MEDIA QUERIES - RESPONSIVE
   =============================================== */

/* Pantallas medianas y pequeñas */
@media (max-width: 768px) {
    .details-wrapper {
        padding: 1rem 0;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .btn-custom {
        width: 100%;
        margin: 0.3rem 0;
    }

    /* Modal responsive en móviles */
    .modal-overlay-calificar {
        padding: 0.5rem;
        align-items: flex-start;
        padding-top: 2rem;
    }
    
    .modal-container-calificar {
        width: 100%;
        margin: 0;
        max-height: calc(100vh - 1rem);
        border-radius: 20px;
        min-height: auto;
    }
    
    .modal-header-calificar,
    .modal-body-calificar {
        padding: 1.5rem;
    }
    
    .modal-footer-calificar {
        padding: 1rem 1.5rem 1.5rem;
        flex-direction: column;
        flex-shrink: 0;
    }
    
    .conductor-info-modal {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
        padding: 1rem;
    }
    
    .stars-container {
        gap: 0.25rem;
    }
    
    .star-conductor {
        font-size: 1.8rem;
    }
    
    .modal-btn-cancel,
    .modal-btn-confirm {
        min-width: 100%;
    }
    
    .modal-title-calificar {
        font-size: 1.3rem;
    }
    
    .modal-icon-calificar {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}

/* Pantallas con altura reducida */
@media (max-height: 600px) {
    .modal-overlay-calificar {
        align-items: flex-start;
        padding-top: 1rem;
    }
    
    .modal-container-calificar {
        max-height: calc(100vh - 2rem);
    }
    
    .modal-header-calificar {
        padding: 1.5rem 2rem 1rem;
    }
    
    .modal-body-calificar {
        padding: 1rem 2rem;
    }
    
    .modal-footer-calificar {
        padding: 1rem 2rem 1.5rem;
    }
    
    .conductor-info-modal {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .rating-section {
        padding: 1rem;
        margin-bottom: 1rem;
    }
}
main {
       min-height: 5px;
}
</style>

<div class="details-wrapper">
    <div class="container">
        <!-- ===============================================
             📋 HEADER DE LA PÁGINA
             =============================================== -->
        <div class="page-header">
            <h2>📋 Detalles de tu reserva</h2>
        </div>

        <!-- ===============================================
             🚗 INFORMACIÓN DEL VIAJE
             =============================================== -->
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-icon route">
                    <i class="fas fa-route"></i>
                </div>
                <h5 class="card-title">Información del Viaje</h5>
            </div>
            
            <!-- Resumen de la ruta -->
            <div class="route-summary">
                <div class="route-item">
                    <div class="route-marker origin">A</div>
                    <div class="route-text">{{ $origenCorta }}</div>
                </div>
                <div class="route-item">
                    <div class="route-marker destination">B</div>
                    <div class="route-text">{{ $destinoCorta }}</div>
                </div>
            </div>

            <!-- Detalles del viaje -->
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Origen</div>
                    <div class="info-value">{{ $acortarProvincia($reserva->viaje->origen_direccion) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Destino</div>
                    <div class="info-value">{{ $acortarProvincia($reserva->viaje->destino_direccion) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Hora</div>
                    <div class="info-value">{{ $reserva->viaje->hora_salida }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha de Salida</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Puestos Reservados</div>
                    <div class="info-value">{{ $reserva->cantidad_puestos }}</div>
                </div>
            </div>
        </div>

        <!-- ===============================================
             👨‍💼 INFORMACIÓN DEL CONDUCTOR
             =============================================== -->
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-icon driver">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h5 class="card-title">Conductor</h5>
            </div>
            
            <!-- Sección del conductor con estilos organizados -->
            <div class="conductor-section">
                <!-- Título del conductor -->
                <h5 class="conductor-section-title">
                    <i class="fas fa-user-tie"></i> Tu Conductor
                </h5>
                
                <!-- Badge de confianza -->
                <div class="trust-badge">
                    <i class="fas fa-shield-alt"></i>
                    <strong>Conductor y vehículo verificados por nuestra plataforma.</strong> 
                    <br class="d-sm-none">¡Puedes viajar seguro!
                </div>
                
                <div class="conductor-content">
                    <!-- Foto del conductor -->
                    <div class="conductor-photo-container">
                        @if($reserva->viaje->conductor->foto)
                            <img src="{{ asset('storage/' . $reserva->viaje->conductor->foto) }}" 
                                 alt="Foto de {{ $reserva->viaje->conductor->name }}"
                                 class="conductor-photo">
                        @else
                            <div class="conductor-photo-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Detalles del conductor -->
                    <div class="conductor-details">
                        <div class="row g-3">
                            <!-- Nombre - siempre visible -->
                            <div class="col-12 {{ $reserva->estado === 'confirmada' ? 'col-md-4' : '' }}">
                                <div class="conductor-info-item">
                                    <div class="conductor-info-label">
                                        <i class="fas fa-user"></i> Nombre
                                    </div>
                                    <div class="conductor-info-value">
                                        {{ $reserva->viaje->conductor->name ?? 'N/D' }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($reserva->estado === 'confirmada')
                                <!-- Email - solo si está confirmada -->
                                <div class="col-12 col-md-4">
                                    <div class="conductor-info-item">
                                        <div class="conductor-info-label">
                                            <i class="fas fa-envelope"></i> Email
                                        </div>
                                        <div class="conductor-info-value">
                                            {{ $reserva->viaje->conductor->email ?? 'N/D' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Teléfono - solo si está confirmada -->
                                <div class="col-12 col-md-4">
                                    <div class="conductor-info-item">
                                        <div class="conductor-info-label">
                                            <i class="fas fa-phone"></i> Contacto
                                        </div>
                                        <div class="conductor-info-value">
                                            @if($reserva->viaje->conductor->celular)
                                                <a href="tel:{{ $reserva->viaje->conductor->celular }}" 
                                                   class="conductor-phone-link">
                                                    <i class="fas fa-phone-alt"></i>
                                                    {{ $reserva->viaje->conductor->celular }}
                                                </a>
                                            @else
                                                <span class="text-muted">No disponible</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Mensaje informativo -->
                             
                            @endif
                        </div>
                    </div>
                    
                    <!-- Botones de contacto y calificación -->
                    @if($reserva->estado === 'confirmada' || $reserva->estado === 'finalizado')
                        <div class="contact-buttons">
                            <!-- Botones de contacto -->
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <a href="{{ route('chat.ver', $reserva->viaje_id) }}" 
                                       class="btn btn-success w-100 contact-btn">
                                        <i class="fas fa-comments me-2"></i>Abrir Chat
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="tel:{{ $reserva->viaje->conductor->celular }}" 
                                       class="btn btn-outline-primary w-100 contact-btn">
                                        <i class="fas fa-phone me-2"></i>Llamar ahora
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Botón de calificación -->
                          @if($reserva->estado === 'finalizado' && !$calificadoPorPasajero)
                                <button type="button" 
                                        class="btn btn-warning rating-btn"
                                        onclick="abrirModalCalificarConductor({{ $reserva->id }}, '{{ $reserva->viaje->conductor->name }}')">
                                    <i class="fas fa-star me-2"></i>Calificar al conductor
                                </button>
                            @elseif($calificadoPorPasajero)
                                <div class="rating-completed">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <span>¡Ya calificaste al conductor!</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ===============================================
             🎫 INFORMACIÓN DE LA RESERVA
             =============================================== -->
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-icon booking">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h5 class="card-title">Tu Reserva</h5>
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Estado</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ strtolower($reserva->estado) }}">
                            @if($reserva->estado == 'pendiente_pago' || $reserva->estado == 'cancelada' || $reserva->estado == 'pendiente')
                                Pendiente por pago
                            @else
                                {{ ucfirst($reserva->estado) }}
                            @endif
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha de Reserva</div>
                    <div class="info-value">{{ $reserva->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            
            <!-- Botón de pago -->
            @if($reserva->estado == 'pendiente_pago' || $reserva->estado == 'cancelada' || $reserva->estado == 'pendiente')
                <div class="payment-button-container">
                    <button type="button" class="btn btn-primary btn-pay" onclick="procesarPago({{ $reserva->id }})">
                        <i class="fas fa-credit-card"></i> 
                        @if($reserva->estado == 'cancelada')
                            REINTENTAR PAGO
                        @else
                            PAGAR
                        @endif
                    </button>
                    
                    @if($reserva->estado == 'cancelada')
                        <p class="payment-note">
                            <i class="fas fa-info-circle"></i> El pago anterior fue cancelado. Puedes intentar nuevamente.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- ===============================================
             🗺️ MAPA DE LA RUTA
             =============================================== -->
        <div class="map-container">
            <div class="map-header">
                <div class="map-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h5 class="map-title">Ruta del viaje</h5>
            </div>
            <div id="mapa" class="map-display">
                <div class="map-loading">
                    <div class="map-loading-content">
                        <i class="fas fa-map"></i>
                        <p>Preparando mapa...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===============================================
             🎬 SECCIÓN DE ACCIONES
             =============================================== -->
        <div class="action-section">
            <h5 class="action-title">¿Qué quieres hacer?</h5>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('pasajero.dashboard') }}" class="btn-custom secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al listado
                </a>
                <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn-custom primary">
                    <i class="fas fa-search me-2"></i>Buscar más viajes
                </a>
                <a href="{{ route('conductor.gestion') }}" class="btn-custom accent">
                    <i class="fas fa-plus-circle me-2"></i>Publicar un viaje
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ===============================================
     🌟 MODAL DE CALIFICACIÓN - MOVIDO AL FINAL
     =============================================== -->
<div id="modalCalificarConductor" class="modal-overlay-calificar" style="display: none;">
    <div class="modal-container-calificar">
        <!-- Header del Modal -->
        <div class="modal-header-calificar">
            <div class="modal-icon-calificar">
                <i class="fas fa-star"></i>
            </div>
            <h2 class="modal-title-calificar">Calificar Conductor</h2>
            <button type="button" class="modal-close-btn" onclick="cerrarModalCalificarConductor()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Contenido del Modal -->
        <div class="modal-body-calificar">
            <!-- Información del conductor -->
            <div class="conductor-info-modal">
                <div class="conductor-avatar">
                    @if($reserva->viaje->conductor->foto)
                        <img src="{{ asset('storage/' . $reserva->viaje->conductor->foto) }}" 
                             alt="Foto de {{ $reserva->viaje->conductor->name }}"
                             class="conductor-photo-modal">
                    @else
                        <div class="conductor-photo-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <div class="conductor-name-section">
                    <strong id="nombreConductorCalificar">{{ $reserva->viaje->conductor->name }}</strong>
                    <div class="info-subtitle">
                        ¿Cómo fue tu experiencia con este conductor?
                    </div>
                </div>
            </div>
            
            <!-- Sistema de estrellas -->
            <div class="rating-section">
                <label class="rating-label">Tu calificación:</label>
                <div class="stars-container">
                    <span class="star-conductor" data-rating="1">⭐</span>
                    <span class="star-conductor" data-rating="2">⭐</span>
                    <span class="star-conductor" data-rating="3">⭐</span>
                    <span class="star-conductor" data-rating="4">⭐</span>
                    <span class="star-conductor" data-rating="5">⭐</span>
                </div>
                <div class="rating-text" id="ratingTextConductor">Toca las estrellas para calificar</div>
            </div>
            
            <!-- Campo de comentario -->
            <div class="comentario-section">
                <label for="comentarioCalificacionConductor" class="comentario-label">
                    💬 Comentario (Opcional)
                </label>
                <textarea 
                    id="comentarioCalificacionConductor" 
                    class="comentario-textarea"
                    placeholder="Cuéntanos sobre tu experiencia con este conductor..."
                    maxlength="500"
                    rows="4"></textarea>
                <div class="comentario-help">
                    <small id="contadorCaracteresConductor">0/500 caracteres</small>
                </div>
            </div>
        </div>
        
        <!-- Footer del Modal -->
        <div class="modal-footer-calificar">
            <button class="modal-btn-cancel" onclick="cerrarModalCalificarConductor()">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button class="modal-btn-confirm" onclick="confirmarCalificacionConductor()" id="btnConfirmarCalificacionConductor">
                <i class="fas fa-check"></i> Enviar Calificación
            </button>
        </div>
    </div>
</div>

<!-- 🔧 SCRIPT INLINE PARA DEBUGGING -->
<script>
// =========================================
// 🔧 VARIABLES GLOBALES
// =========================================
let reservaIdCalificarConductor = null;
let calificacionSeleccionadaConductor = 0;

// =========================================
// 💳 FUNCIONES DE PAGO
// =========================================
function procesarPago(reservaId) {
    console.log('🚀 Procesando pago para reserva:', reservaId);
    
    // Crear formulario
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/pasajero/reservar/{{ $reserva->viaje_id }}'; // URL directa
    
    // Campos requeridos
    const campos = {
        'cantidad_puestos': {{ $reserva->cantidad_puestos }},
        'valor_cobrado': {{ $reserva->precio_por_persona }},
        'total': {{ $reserva->total }},
        'viaje_id': {{ $reserva->viaje_id }},
        '_token': '{{ csrf_token() }}'
    };
    
    // Crear inputs
    Object.entries(campos).forEach(([name, value]) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    });
    
    // Deshabilitar botón
    const btn = document.querySelector('.btn-pay');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
    }
    
    // Enviar
    document.body.appendChild(form);
    form.submit();
}

// =========================================
// 🗺️ FUNCIONES DEL MAPA
// =========================================

// Función para cargar el mapa real
function initMapaReal() {
    console.log("🗺️ Intentando cargar mapa real...");
    
    if (typeof google === 'undefined') {
        console.error("❌ Google Maps no disponible");
        document.getElementById("mapa").innerHTML = `
            <div style="padding: 20px; text-align: center; background: #ffebee; border-radius: 8px;">
                <h5 style="color: #d32f2f;">❌ Google Maps no disponible</h5>
                <p>Verifica tu API Key en el archivo .env</p>
                <code style="background: #f5f5f5; padding: 5px; border-radius: 3px;">
                    GOOGLE_MAPS_API_KEY=tu_api_key_aqui
                </code>
            </div>
        `;
        return;
    }
    
    try {
        const origen = {
            lat: {{ $reserva->viaje->origen_lat }},
            lng: {{ $reserva->viaje->origen_lng }}
        };
        
        const destino = {
            lat: {{ $reserva->viaje->destino_lat }},
            lng: {{ $reserva->viaje->destino_lng }}
        };
        
        console.log("📍 Creando mapa con:", { origen, destino });
        
        const map = new google.maps.Map(document.getElementById("mapa"), {
            zoom: 13,
            center: origen,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        
        // Marcador origen
        new google.maps.Marker({
            position: origen,
            map: map,
            title: "Origen",
            icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
        });
        
        // Marcador destino
        new google.maps.Marker({
            position: destino,
            map: map,
            title: "Destino",
            icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
        });
        
        // Ruta
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true
        });
        
        directionsService.route({
            origin: origen,
            destination: destino,
            travelMode: google.maps.TravelMode.DRIVING
        }, function(response, status) {
            if (status === "OK") {
                directionsRenderer.setDirections(response);
                console.log("✅ Ruta cargada");
            } else {
                console.warn("⚠️ Error en ruta:", status);
                const bounds = new google.maps.LatLngBounds();
                bounds.extend(origen);
                bounds.extend(destino);
                map.fitBounds(bounds);
            }
        });
        
    } catch (error) {
        console.error("❌ Error:", error);
        document.getElementById("mapa").innerHTML = `
            <div style="padding: 20px; text-align: center; background: #ffebee; border-radius: 8px;">
                <h5 style="color: #d32f2f;">❌ Error: ${error.message}</h5>
            </div>
        `;
    }
}

// Función global para Google Maps callback
window.initReservaMapa = function() {
    console.log("🔔 Callback de Google Maps ejecutado");
    setTimeout(initMapaReal, 1000);
};

// =========================================
// ⭐ FUNCIONES DEL MODAL DE CALIFICACIÓN
// =========================================

// Función para abrir el modal de calificar conductor
function abrirModalCalificarConductor(reservaId, nombreConductor) {
    reservaIdCalificarConductor = reservaId;
    calificacionSeleccionadaConductor = 0;
    
    // Actualizar información en el modal
    document.getElementById('nombreConductorCalificar').textContent = nombreConductor;
    
    // Limpiar formulario
    const comentarioField = document.getElementById('comentarioCalificacionConductor');
    comentarioField.value = '';
    document.getElementById('contadorCaracteresConductor').textContent = '0/500 caracteres';
    
    // Resetear estrellas
    document.querySelectorAll('.star-conductor').forEach(star => {
        star.classList.remove('active');
    });
    
    // Resetear texto de calificación
    document.getElementById('ratingTextConductor').textContent = 'Toca las estrellas para calificar';
    
    // Deshabilitar botón de confirmar
    const btnConfirmar = document.getElementById('btnConfirmarCalificacionConductor');
    btnConfirmar.classList.remove('enabled');
    
    // Prevenir scroll del body
    document.body.style.overflow = 'hidden';
    document.body.style.position = 'fixed';
    document.body.style.width = '100%';
    
    // Mostrar modal con animación
    const modal = document.getElementById('modalCalificarConductor');
    modal.style.display = 'flex';
    
    // Trigger animation
    requestAnimationFrame(() => {
        modal.classList.add('show');
    });
    
    // Scroll al top del modal en caso de que sea necesario
    setTimeout(() => {
        const modalContainer = modal.querySelector('.modal-container-calificar');
        if (modalContainer) {
            modalContainer.scrollTop = 0;
        }
        
        // Focus en el primer elemento interactivo
        document.querySelector('.star-conductor')?.focus();
    }, 400);
    
    console.log('Modal de calificación abierto para conductor:', nombreConductor, 'Reserva:', reservaId);
}

// Función para cerrar el modal
function cerrarModalCalificarConductor() {
    const modal = document.getElementById('modalCalificarConductor');
    modal.classList.remove('show');
    
    setTimeout(() => {
        modal.style.display = 'none';
        
        // Restaurar scroll del body
        document.body.style.overflow = '';
        document.body.style.position = '';
        document.body.style.width = '';
    }, 400);
    
    reservaIdCalificarConductor = null;
    calificacionSeleccionadaConductor = 0;
}

// Función para confirmar calificación del conductor
function confirmarCalificacionConductor() {
    if (calificacionSeleccionadaConductor === 0) {
        alert('Por favor selecciona una calificación');
        return;
    }
    
    const comentario = document.getElementById('comentarioCalificacionConductor').value.trim();
    const btn = document.getElementById('btnConfirmarCalificacionConductor');
    const textoOriginal = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
    btn.disabled = true;
    btn.style.opacity = '0.6';
    
    // Obtener token CSRF
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!token) {
        alert('Error: Token de seguridad no encontrado');
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
        btn.style.opacity = '1';
        return;
    }
    
    // 🔥 RUTA CORREGIDA: /conductor/ en lugar de /pasajero/
    fetch(`/conductor/calificar-conductor/${reservaIdCalificarConductor}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            calificacion: calificacionSeleccionadaConductor,
            comentario: comentario
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            btn.innerHTML = '<i class="fas fa-check"></i> ¡Enviado!';
            btn.style.background = '#28a745';
            
            setTimeout(() => {
                cerrarModalCalificarConductor();
                // Mostrar notificación de éxito
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Calificación enviada!',
                        text: 'Gracias por tu feedback',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert('¡Calificación enviada exitosamente!');
                }
                
                // Recargar la página para mostrar cambios
                setTimeout(() => location.reload(), 1500);
            }, 1000);
        } else {
            throw new Error(data.message || 'Error desconocido');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al enviar la calificación. Inténtalo nuevamente.'
            });
        } else {
            alert('Error de conexión. Inténtalo nuevamente.');
        }
        
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
        btn.style.opacity = '1';
    });
}

// Función para manejar las estrellas
function configurarEstrellasConductor() {
    const stars = document.querySelectorAll('.star-conductor');
    const ratingTexts = ['', 'Muy malo', 'Malo', 'Regular', 'Bueno', 'Excelente'];
    
    stars.forEach(star => {
        // Click en estrella
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            calificacionSeleccionadaConductor = rating;
            
            // Actualizar estrellas visuales
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
            
            // Actualizar texto con animación
            const ratingTextElement = document.getElementById('ratingTextConductor');
            ratingTextElement.style.opacity = '0';
            setTimeout(() => {
                ratingTextElement.textContent = ratingTexts[rating];
                ratingTextElement.style.opacity = '1';
            }, 150);
            
            // Habilitar botón de confirmar
            const btnConfirmar = document.getElementById('btnConfirmarCalificacionConductor');
            btnConfirmar.classList.add('enabled');
            
            console.log('Calificación seleccionada para conductor:', rating);
        });
        
        // Efecto hover
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.style.filter = 'grayscale(0%) brightness(1)';
                    s.style.opacity = '1';
                    s.style.transform = 'scale(1.1)';
                } else {
                    s.style.filter = 'grayscale(100%) brightness(0.7)';
                    s.style.opacity = '0.6';
                    s.style.transform = 'scale(1)';
                }
            });
        });
    });
    
    // Restaurar estrellas al salir del hover
    document.querySelector('.stars-container')?.addEventListener('mouseleave', function() {
        stars.forEach((star, index) => {
            if (star.classList.contains('active')) {
                star.style.filter = 'grayscale(0%) brightness(1)';
                star.style.opacity = '1';
                star.style.transform = 'scale(1.15)';
            } else {
                star.style.filter = 'grayscale(100%) brightness(0.7)';
                star.style.opacity = '0.6';
                star.style.transform = 'scale(1)';
            }
        });
    });
}

// Función para configurar contador de caracteres
function configurarContadorCaracteres() {
    const comentarioTextarea = document.getElementById('comentarioCalificacionConductor');
    if (comentarioTextarea) {
        comentarioTextarea.addEventListener('input', function() {
            const caracteresUsados = this.value.length;
            const contador = document.getElementById('contadorCaracteresConductor');
            contador.textContent = `${caracteresUsados}/500 caracteres`;
            
            // Cambiar color si se acerca al límite
            if (caracteresUsados > 450) {
                contador.style.color = '#dc3545';
            } else if (caracteresUsados > 400) {
                contador.style.color = '#ffc107';
            } else {
                contador.style.color = '#6c757d';
            }
        });
    }
}

// =========================================
// 🔄 EVENT LISTENERS Y CONFIGURACIÓN INICIAL
// =========================================

// Ejecutar al cargar el DOM
document.addEventListener('DOMContentLoaded', function() {
    // ⚠️ TEST BÁSICO - Esto debería aparecer en consola
    console.log("🚨 SCRIPT EJECUTÁNDOSE - Si ves esto, JavaScript funciona");
    console.log("📍 Coordenadas:", {
        origen: "{{ $reserva->viaje->origen_lat }}, {{ $reserva->viaje->origen_lng }}",
        destino: "{{ $reserva->viaje->destino_lat }}, {{ $reserva->viaje->destino_lng }}"
    });

    // 🏃‍♂️ Configurar mapa de debug inmediatamente
    const mapaElement = document.getElementById("mapa");
    if (mapaElement) {
        mapaElement.innerHTML = `
            <div style="padding: 20px; text-align: center; background: #e3f2fd; border-radius: 8px; margin: 10px;">
                <h5 style="color: #1976d2; margin-bottom: 15px;">🔧 Modo Debug</h5>
                <p><strong>Script funcionando:</strong> ✅</p>
                <p><strong>Origen:</strong> {{ $reserva->viaje->origen_lat }}, {{ $reserva->viaje->origen_lng }}</p>
                <p><strong>Destino:</strong> {{ $reserva->viaje->destino_lat }}, {{ $reserva->viaje->destino_lng }}</p>
                <p><strong>API Key configurada:</strong> ${typeof window.google !== 'undefined' ? '✅' : '❌'}</p>
                <button onclick="initMapaReal()" style="
                    background: #1976d2; 
                    color: white; 
                    border: none; 
                    padding: 10px 20px; 
                    border-radius: 5px; 
                    cursor: pointer;
                    margin-top: 10px;
                ">
                    🗺️ Intentar cargar mapa
                </button>
            </div>
        `;
    }
    
    // Configurar funcionalidades del modal
    configurarEstrellasConductor();
    configurarContadorCaracteres();
});

// Event listener para cerrar modal al hacer click fuera
document.addEventListener('click', function(e) {
    const modal = document.getElementById('modalCalificarConductor');
    if (e.target === modal) {
        cerrarModalCalificarConductor();
    }
});

// Event listeners para navegación con teclado
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('modalCalificarConductor');
    
    // Cerrar modal con Escape
    if (e.key === 'Escape') {
        if (modal && modal.classList.contains('show')) {
            cerrarModalCalificarConductor();
        }
        return;
    }
    
    // Solo continuar si el modal está abierto
    if (!modal || !modal.classList.contains('show')) {
        return;
    }
    
    // Tab para navegar entre elementos
    if (e.key === 'Tab') {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (e.shiftKey) {
            if (document.activeElement === firstElement) {
                lastElement.focus();
                e.preventDefault();
            }
        } else {
            if (document.activeElement === lastElement) {
                firstElement.focus();
                e.preventDefault();
            }
        }
    }
    
    // Números del 1-5 para calificar rápidamente
    if (e.key >= '1' && e.key <= '5') {
        const rating = parseInt(e.key);
        const star = document.querySelector(`[data-rating="${rating}"]`);
        if (star) {
            star.click();
        }
    }
});
</script>

<!-- Google Maps API -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initReservaMapa&v=3.55">
</script>