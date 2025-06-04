@extends('layouts.app')

@section('content')
<div class="rating-page-wrapper">
    <div class="container-fluid">
        <div class="row justify-content-center min-vh-100 align-items-center py-5">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="rating-card">
                    <!-- Header con información del viaje -->
                    <div class="rating-header">
                        <div class="trip-info">
                            <i class="fas fa-route text-primary"></i>
                            <div class="trip-details">
                                <h5 class="mb-1">Viaje completado</h5>
                                <p class="text-muted mb-0">Reserva #{{ $reserva->id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario principal -->
                    <div class="rating-body">
                        <div class="text-center mb-4">
                            <div class="driver-avatar">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h3 class="mt-3 mb-2">¿Cómo fue tu experiencia?</h3>
                            <p class="text-muted">Tu opinión nos ayuda a mejorar el servicio</p>
                        </div>

                        <form action="{{ route('pasajero.calificar.guardar', $reserva->id) }}" method="POST" id="ratingForm">
                            @csrf
                            
                            <!-- Sistema de estrellas interactivo -->
                            <div class="rating-section">
                                <label class="form-label text-center d-block mb-3">
                                    <strong>Califica el servicio</strong>
                                </label>
                                <div class="stars-container" id="starsContainer">
                                    <div class="stars" id="stars">
                                        <i class="star fas fa-star" data-rating="1"></i>
                                        <i class="star fas fa-star" data-rating="2"></i>
                                        <i class="star fas fa-star" data-rating="3"></i>
                                        <i class="star fas fa-star" data-rating="4"></i>
                                        <i class="star fas fa-star" data-rating="5"></i>
                                    </div>
                                    <div class="rating-text" id="ratingText">Selecciona una calificación</div>
                                </div>
                                <input type="hidden" name="calificacion" id="calificacionInput" required>
                            </div>

                            <!-- Área de comentarios -->
                            <div class="comment-section">
                                <label for="comentario" class="form-label">
                                    <strong>Comparte tu experiencia</strong>
                                </label>
                                <div class="comment-input-wrapper">
                                    <textarea 
                                        name="comentario" 
                                        id="comentario" 
                                        class="form-control comment-textarea" 
                                        rows="4" 
                                        placeholder="¿Qué tal fue el viaje? ¿El conductor fue puntual? ¿Te sentiste seguro?" 
                                        required
                                    ></textarea>
                                    <div class="character-count">
                                        <span id="charCount">0</span>/255
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="action-buttons">
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="history.back()">
                                    <i class="fas fa-arrow-left me-2"></i>Regresar
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                    <i class="fas fa-paper-plane me-2"></i>Enviar calificación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-page-wrapper {
    background: linear-gradient(135deg, #1F4E79 0%, #DDF2FE 100%);
    min-height: 97vh;
    padding-top: 80px; /* Espacio para el navbar */
}

.rating-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.rating-header {
    background: linear-gradient(135deg, #1F4E79, #3A3A3A);
    color: white;
    padding: 2rem;
}

.trip-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.trip-info i {
    font-size: 2rem;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.8rem;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.trip-details h5 {
    color: white;
    font-weight: 600;
}

.rating-body {
    padding: 3rem 2rem;
}

.driver-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #1F4E79, #4CAF50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 2rem;
}

.rating-section {
    margin-bottom: 2.5rem;
}

.stars-container {
    text-align: center;
    padding: 2rem;
    background: #DDF2FE;
    border-radius: 15px;
    border: 2px solid #1F4E79;
    transition: all 0.3s ease;
}

.stars {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.star {
    font-size: 2.5rem;
    color: #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
    transform-origin: center;
}

.star:hover {
    transform: scale(1.2);
}

.star.active {
    color: #ffc107;
    text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
}

.star.hovered {
    color: #ffc107;
    transform: scale(1.1);
}

.rating-text {
    font-size: 1.1rem;
    font-weight: 500;
    color: #666;
    transition: all 0.3s ease;
}

.comment-section {
    margin-bottom: 2.5rem;
}

.comment-input-wrapper {
    position: relative;
}

.comment-textarea {
    border: 2px solid #DDF2FE;
    border-radius: 15px;
    padding: 1.2rem;
    font-size: 1rem;
    resize: none;
    transition: all 0.3s ease;
    background: #FCFCFD;
}

.comment-textarea:focus {
    border-color: #1F4E79;
    box-shadow: 0 0 0 0.2rem rgba(31, 78, 121, 0.25);
    background: white;
}

.character-count {
    position: absolute;
    bottom: 10px;
    right: 15px;
    font-size: 0.85rem;
    color: #3A3A3A;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-lg {
    padding: 0.8rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 160px;
}

.btn-primary {
    background: linear-gradient(135deg, #1F4E79, #4CAF50);
    border: none;
    position: relative;
    overflow: hidden;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(31, 78, 121, 0.4);
}

.btn-primary:disabled {
    background: #3A3A3A;
    cursor: not-allowed;
}

.btn-outline-secondary {
    border: 2px solid #3A3A3A;
    color: #3A3A3A;
}

.btn-outline-secondary:hover {
    background: #3A3A3A;
    color: white;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .rating-page-wrapper {
        padding-top: 60px;
    }
    
    .rating-body {
        padding: 2rem 1.5rem;
    }
    
    .stars {
        gap: 0.3rem;
    }
    
    .star {
        font-size: 2rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}

/* Animaciones adicionales */
.stars-container.filled {
    background: linear-gradient(135deg, rgba(31, 78, 121, 0.1), rgba(76, 175, 80, 0.1));
    border-color: #4CAF50;
}

.pulse {
    animation: pulse 0.6s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('calificacionInput');
    const ratingText = document.getElementById('ratingText');
    const starsContainer = document.getElementById('starsContainer');
    const comentario = document.getElementById('comentario');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');
    
    let selectedRating = 0;
    
    const ratingTexts = {
        1: '😞 Muy malo - No recomendado',
        2: '😐 Malo - Necesita mejorar',
        3: '😊 Regular - Aceptable',
        4: '😄 Bueno - Recomendado',
        5: '🤩 Excelente - Altamente recomendado'
    };
    
    // Manejo de estrellas
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', () => {
            highlightStars(index + 1, true);
        });
        
        star.addEventListener('mouseleave', () => {
            highlightStars(selectedRating, false);
        });
        
        star.addEventListener('click', () => {
            selectedRating = index + 1;
            ratingInput.value = selectedRating;
            highlightStars(selectedRating, false);
            ratingText.textContent = ratingTexts[selectedRating];
            starsContainer.classList.add('filled', 'pulse');
            
            setTimeout(() => {
                starsContainer.classList.remove('pulse');
            }, 600);
            
            checkFormValidity();
        });
    });
    
    function highlightStars(rating, isHover) {
        stars.forEach((star, index) => {
            star.classList.remove('active', 'hovered');
            if (index < rating) {
                star.classList.add(isHover ? 'hovered' : 'active');
            }
        });
    }
    
    // Contador de caracteres
    comentario.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        charCount.style.color = length > 200 ? '#dc3545' : '#666';
        checkFormValidity();
    });
    
    // Validación del formulario
    function checkFormValidity() {
        const isValid = selectedRating > 0 && comentario.value.trim().length > 0;
        submitBtn.disabled = !isValid;
    }
    
    // Animación de envío
    document.getElementById('ratingForm').addEventListener('submit', function(e) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';
        submitBtn.disabled = true;
    });
});
</script>
@endsection