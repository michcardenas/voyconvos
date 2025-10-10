<x-guest-layout>
    <div class="login-container">
        <div class="login-card">
            <!-- Panel izquierdo - Bienvenida -->
            <div class="welcome-panel">
                <h2 class="welcome-title">¡Bienvenido!</h2>
                <p class="welcome-text">Crea tu cuenta en solo unos pasos y comienza a viajar con nosotros</p>
                
                <!-- Barra de progreso -->
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressBar"></div>
                    </div>
                    <p class="progress-text">
                        <span id="currentStep">1</span> de <span id="totalSteps">4</span>
                    </p>
                </div>
                
                <a href="{{ route('login') }}" class="register-button">
                    YA TENGO CUENTA
                </a>
            </div>
            
            <!-- Panel derecho - Formulario -->
            <div class="login-form-panel">
                <!-- Logo de VoyConVos -->
                <div class="logo-container">
                    <img src="{{ asset('img/voyconvos-logo.png') }}" alt="VoyConVos" class="logo-image">
                </div>
                
                <h1 class="login-title" id="stepTitle">Crear Cuenta</h1>
                <p class="step-subtitle" id="stepSubtitle">Completa tu información básica</p>
                
                <form method="POST" action="{{ route('register') }}" id="registerForm" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- PASO 1: Datos Básicos -->
                    <div class="form-step active" data-step="1">
                        <div class="form-group">
                            <x-input-label for="email" :value="__('Correo electrónico')" class="form-label" />
                            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ejemplo@correo.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            <span class="error-message" id="error-email"></span>
                        </div>
                        
                        <div class="form-group">
                            <x-input-label for="name" :value="__('Nombre y apellido')" class="form-label" />
                            <x-text-input id="name" class="form-input" type="text" name="name" :value="old('name')" required autocomplete="name" placeholder="Juan Pérez" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            <span class="error-message" id="error-name"></span>
                        </div>
                        
                        <div class="form-group">
                            <x-input-label for="fecha_nacimiento" :value="__('Fecha de nacimiento')" class="form-label" />
                            <x-text-input id="fecha_nacimiento" class="form-input" type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento')" required />
                            <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
                            <span class="error-message" id="error-fecha"></span>
                            <small class="form-hint">Debes ser mayor de 18 años</small>
                        </div>
                    </div>
                    
                    <!-- PASO 2: Ubicación y Contacto -->
                    <div class="form-step" data-step="2">
                        <div class="form-group">
                            <x-input-label for="pais" :value="__('Nacionalidad')" class="form-label" />
                            <select id="pais" name="pais" class="form-input" required>
                                <option value="">Selecciona tu país</option>
                                <option value="Argentina" {{ old('pais') == 'Argentina' ? 'selected' : '' }}>Argentina</option>
                                <option value="Bolivia" {{ old('pais') == 'Bolivia' ? 'selected' : '' }}>Bolivia</option>
                                <option value="Brasil" {{ old('pais') == 'Brasil' ? 'selected' : '' }}>Brasil</option>
                                <option value="Chile" {{ old('pais') == 'Chile' ? 'selected' : '' }}>Chile</option>
                                <option value="Colombia" {{ old('pais') == 'Colombia' ? 'selected' : '' }}>Colombia</option>
                                <option value="Costa Rica" {{ old('pais') == 'Costa Rica' ? 'selected' : '' }}>Costa Rica</option>
                                <option value="Cuba" {{ old('pais') == 'Cuba' ? 'selected' : '' }}>Cuba</option>
                                <option value="Ecuador" {{ old('pais') == 'Ecuador' ? 'selected' : '' }}>Ecuador</option>
                                <option value="El Salvador" {{ old('pais') == 'El Salvador' ? 'selected' : '' }}>El Salvador</option>
                                <option value="Guatemala" {{ old('pais') == 'Guatemala' ? 'selected' : '' }}>Guatemala</option>
                                <option value="Honduras" {{ old('pais') == 'Honduras' ? 'selected' : '' }}>Honduras</option>
                                <option value="México" {{ old('pais') == 'México' ? 'selected' : '' }}>México</option>
                                <option value="Nicaragua" {{ old('pais') == 'Nicaragua' ? 'selected' : '' }}>Nicaragua</option>
                                <option value="Panamá" {{ old('pais') == 'Panamá' ? 'selected' : '' }}>Panamá</option>
                                <option value="Paraguay" {{ old('pais') == 'Paraguay' ? 'selected' : '' }}>Paraguay</option>
                                <option value="Perú" {{ old('pais') == 'Perú' ? 'selected' : '' }}>Perú</option>
                                <option value="República Dominicana" {{ old('pais') == 'República Dominicana' ? 'selected' : '' }}>República Dominicana</option>
                                <option value="Uruguay" {{ old('pais') == 'Uruguay' ? 'selected' : '' }}>Uruguay</option>
                                <option value="Venezuela" {{ old('pais') == 'Venezuela' ? 'selected' : '' }}>Venezuela</option>
                            </select>
                            <x-input-error :messages="$errors->get('pais')" class="mt-2" />
                            <span class="error-message" id="error-pais"></span>
                        </div>
                        
                        <div class="form-group">
                            <x-input-label for="ciudad" :value="__('Ciudad')" class="form-label" />
                            <x-text-input id="ciudad" class="form-input" type="text" name="ciudad" :value="old('ciudad')" required placeholder="Ej: Buenos aires" />
                            <x-input-error :messages="$errors->get('ciudad')" class="mt-2" />
                            <span class="error-message" id="error-ciudad"></span>
                        </div>
                        
                        <div class="form-group">
                            <x-input-label for="celular" :value="__('Celular')" class="form-label" />
                            <x-text-input id="celular" class="form-input" type="tel" name="celular" :value="old('celular')" required placeholder="+54 300 123 4567" />
                            <x-input-error :messages="$errors->get('celular')" class="mt-2" />
                            <span class="error-message" id="error-celular"></span>
                            <small class="form-hint">Incluye el código de país</small>
                        </div>
                    </div>
                    
                    <!-- PASO 3: Contraseñas -->
                    <div class="form-step" data-step="3">
                        <div class="form-group">
                            <x-input-label for="password" :value="__('Contraseña')" class="form-label" />
                            <x-text-input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <span class="error-message" id="error-password"></span>
                            <small class="form-hint">Mínimo 8 caracteres</small>
                        </div>
                        
                        <div class="form-group">
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="form-label" />
                            <x-text-input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repite tu contraseña" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            <span class="error-message" id="error-password-confirmation"></span>
                        </div>
                    </div>
                    
                    <!-- PASO 4: Foto de Perfil y Resumen -->
                    <div class="form-step" data-step="4">
                        <div class="form-group">
                            <x-input-label for="foto" :value="__('Foto de perfil (opcional)')" class="form-label" />
                            <div class="photo-upload-container">
                                <div class="photo-preview" id="photoPreview">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <p>Sube tu foto</p>
                                </div>
                                <input type="file" id="foto" name="foto" class="photo-input" accept="image/*">
                                <label for="foto" class="photo-label">Elegir foto</label>
                            </div>
                            <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                            <small class="form-hint">Formatos: JPG, PNG. Máximo 2MB</small>
                        </div>
                        
                        <!-- Resumen final -->
                        <div class="summary-box">
                            <h3>📋 Revisa tus datos:</h3>
                            <div class="summary-grid">
                                <div class="summary-item">
                                    <span class="summary-label">Email:</span>
                                    <span class="summary-value" id="summary-email"></span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Nombre:</span>
                                    <span class="summary-value" id="summary-name"></span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Fecha de nacimiento:</span>
                                    <span class="summary-value" id="summary-fecha"></span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">País:</span>
                                    <span class="summary-value" id="summary-pais"></span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Ciudad:</span>
                                    <span class="summary-value" id="summary-ciudad"></span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Celular:</span>
                                    <span class="summary-value" id="summary-celular"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de navegación -->
                    <div class="button-group">
                        <button type="button" class="btn-secondary" id="btnPrev" style="display: none;">
                            ← Atrás
                        </button>
                        
                        <button type="button" class="btn-primary" id="btnNext">
                            Siguiente →
                        </button>
                        
                        <button type="submit" class="btn-primary btn-submit" id="btnSubmit" style="display: none;">
                            <span>Registrarse</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Separador y Google (solo en paso 1) -->
                    <div id="googleSection">
                        <div class="separator">
                            <span>ó</span>
                        </div>
                        
                        <a href="{{ route('login.google') }}" class="google-btn">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="google-icon" alt="Google logo">
                            <span>Registrarse con Google</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        /* Paleta de colores */
        :root {
            --color-principal: #1F4E79;  
            --color-azul-claro: #DDF2FE;  
            --color-neutro-oscuro: #3A3A3A; 
            --color-complementario: #4CAF50; 
            --color-fondo-base: #FCFCFD;
            --color-error: #ef4444;
        }
        
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: var(--color-fondo-base);
        }
        
        .min-h-screen {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* Contenedor principal */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }
        
        /* Tarjeta de login */
        .login-card {
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
        }
        
        /* Panel izquierdo - Bienvenida */
        .welcome-panel {
            background-color: var(--color-principal);
            color: white;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            width: 35%;
        }
        
        .welcome-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .welcome-text {
            font-size: 0.9rem;
            margin-bottom: 2rem;
            max-width: 220px;
            line-height: 1.5;
        }
        
        /* Barra de progreso */
        .progress-container {
            width: 100%;
            margin-bottom: 2rem;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .progress-fill {
            height: 100%;
            background-color: var(--color-complementario);
            width: 25%;
            transition: width 0.4s ease;
            border-radius: 10px;
        }
        
        .progress-text {
            font-size: 0.85rem;
            margin: 0;
            opacity: 0.9;
        }
        
        .register-button {
            background-color: transparent;
            color: white;
            border: 2px solid white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .register-button:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        /* Panel derecho - Formulario */
        .login-form-panel {
            flex: 1;
            padding: 2.5rem;
            position: relative;
            overflow-y: auto;
            max-height: 90vh;
        }
        
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .logo-image {
            height: 70px;
            width: auto;
        }
        
        .login-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-align: center;
            color: var(--color-principal);
            transition: all 0.3s ease;
        }
        
        .step-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        /* Pasos del formulario */
        .form-step {
            display: none;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
        }
        
        .form-step.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Campos del formulario */
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--color-neutro-oscuro);
            font-weight: 500;
        }
        
        .form-input, select.form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-color: white;
        }
        
        .form-input:focus, select.form-input:focus {
            outline: none;
            border-color: var(--color-principal);
            box-shadow: 0 0 0 3px rgba(31, 78, 121, 0.1);
        }
        
        .form-input.error {
            border-color: var(--color-error);
        }
        
        .form-hint {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #64748b;
        }
        
        .error-message {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: var(--color-error);
            font-weight: 500;
        }
        
        /* Upload de foto */
        .photo-upload-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .photo-preview {
            width: 100px;
            height: 100px;
            border: 2px dashed #E2E8F0;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
            overflow: hidden;
            background-color: #F8FAFC;
        }
        
        .photo-preview p {
            margin: 0.5rem 0 0 0;
            font-size: 0.75rem;
        }
        
        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-input {
            display: none;
        }
        
        .photo-label {
            padding: 0.75rem 1.5rem;
            background-color: var(--color-azul-claro);
            color: var(--color-principal);
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .photo-label:hover {
            background-color: var(--color-principal);
            color: white;
        }
        
        /* Resumen final */
        .summary-box {
            background: linear-gradient(135deg, var(--color-azul-claro) 0%, #fff 100%);
            padding: 1.5rem;
            border-radius: 12px;
            margin-top: 1rem;
            border: 2px solid var(--color-azul-claro);
        }
        
        .summary-box h3 {
            font-size: 1rem;
            color: var(--color-principal);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .summary-grid {
            display: grid;
            gap: 0.75rem;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem;
            background-color: white;
            border-radius: 6px;
        }
        
        .summary-label {
            font-weight: 500;
            color: #64748b;
            font-size: 0.85rem;
        }
        
        .summary-value {
            font-weight: 500;
            color: var(--color-neutro-oscuro);
            font-size: 0.85rem;
            text-align: right;
        }
        
        /* Botones */
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .btn-primary, .btn-secondary {
            flex: 1;
            padding: 0.85rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--color-principal);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #173d61;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(31, 78, 121, 0.3);
        }
        
        .btn-submit {
            background-color: var(--color-complementario);
        }
        
        .btn-submit:hover {
            background-color: #45a049;
        }
        
        .btn-secondary {
            background-color: #f1f5f9;
            color: var(--color-neutro-oscuro);
        }
        
        .btn-secondary:hover {
            background-color: #e2e8f0;
        }
        
        /* Separador y Google */
        #googleSection {
            margin-top: 1.5rem;
        }
        
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #94A3B8;
        }
        
        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #E2E8F0;
        }
        
        .separator::before {
            margin-right: 0.5rem;
        }
        
        .separator::after {
            margin-left: 0.5rem;
        }
        
        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.75rem;
            background-color: white;
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            color: #4B5563;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .google-btn:hover {
            background-color: #f8f9fa;
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }
        
        .google-icon {
            width: 20px;
            height: 20px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 450px;
            }
            
            .welcome-panel {
                width: 100%;
                padding: 2rem;
            }
            
            .login-form-panel {
                padding: 2rem;
                max-height: none;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .photo-upload-container {
                flex-direction: column;
            }
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 4;
            
            const btnNext = document.getElementById('btnNext');
            const btnPrev = document.getElementById('btnPrev');
            const btnSubmit = document.getElementById('btnSubmit');
            const progressBar = document.getElementById('progressBar');
            const currentStepEl = document.getElementById('currentStep');
            const stepTitle = document.getElementById('stepTitle');
            const stepSubtitle = document.getElementById('stepSubtitle');
            const googleSection = document.getElementById('googleSection');
            const photoInput = document.getElementById('foto');
            const photoPreview = document.getElementById('photoPreview');
            
            const stepInfo = {
                1: {
                    title: 'Datos Básicos',
                    subtitle: 'Cuéntanos un poco sobre ti'
                },
                2: {
                    title: 'Ubicación y Contacto',
                    subtitle: '¿Dónde te encuentras?'
                },
                3: {
                    title: 'Seguridad',
                    subtitle: 'Crea una contraseña segura'
                },
                4: {
                    title: 'Foto de Perfil',
                    subtitle: '¡Casi listo! Revisa tu información'
                }
            };
            
            // Preview de foto
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    }
                    reader.readAsDataURL(file);
                }
            });
            
            function updateUI() {
                // Actualizar pasos
                document.querySelectorAll('.form-step').forEach(step => {
                    step.classList.remove('active');
                });
                document.querySelector(`[data-step="${currentStep}"]`).classList.add('active');
                
                // Actualizar título y subtítulo
                stepTitle.textContent = stepInfo[currentStep].title;
                stepSubtitle.textContent = stepInfo[currentStep].subtitle;
                
                // Actualizar progreso
                const progress = (currentStep / totalSteps) * 100;
                progressBar.style.width = progress + '%';
                currentStepEl.textContent = currentStep;
                
                // Mostrar/ocultar botones
                btnPrev.style.display = currentStep === 1 ? 'none' : 'flex';
                btnNext.style.display = currentStep === totalSteps ? 'none' : 'flex';
                btnSubmit.style.display = currentStep === totalSteps ? 'flex' : 'none';
                
                // Mostrar Google solo en paso 1
                googleSection.style.display = currentStep === 1 ? 'block' : 'none';
                
                // Actualizar resumen en paso 4
                if (currentStep === 4) {
                    document.getElementById('summary-email').textContent = document.getElementById('email').value;
                    document.getElementById('summary-name').textContent = document.getElementById('name').value;
                    document.getElementById('summary-fecha').textContent = formatDate(document.getElementById('fecha_nacimiento').value);
                    document.getElementById('summary-pais').textContent = document.getElementById('pais').value;
                    document.getElementById('summary-ciudad').textContent = document.getElementById('ciudad').value;
                    document.getElementById('summary-celular').textContent = document.getElementById('celular').value;
                }
            }
            
            function formatDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' });
            }
            
            function validateStep(step) {
                let isValid = true;
                
                // Limpiar errores previos
                document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
                document.querySelectorAll('.form-input').forEach(el => el.classList.remove('error'));
                
                switch(step) {
                    case 1: // Datos Básicos
                        const email = document.getElementById('email');
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!email.value) {
                            showError('email', 'El correo es obligatorio');
                            isValid = false;
                        } else if (!emailRegex.test(email.value)) {
                            showError('email', 'Ingresa un correo válido');
                            isValid = false;
                        }
                        
                        const name = document.getElementById('name');
                        if (!name.value || name.value.trim().length < 3) {
                            showError('name', 'Ingresa tu nombre completo (mínimo 3 caracteres)');
                            isValid = false;
                        }
                        
                        const fecha = document.getElementById('fecha_nacimiento');
                        if (!fecha.value) {
                            showError('fecha', 'La fecha de nacimiento es obligatoria');
                            isValid = false;
                        } else {
                            const birthDate = new Date(fecha.value);
                            const today = new Date();
                            let age = today.getFullYear() - birthDate.getFullYear();
                            const monthDiff = today.getMonth() - birthDate.getMonth();
                            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                                age--;
                            }
                            if (age < 18) {
                                showError('fecha', 'Debes ser mayor de 18 años');
                                isValid = false;
                            }
                        }
                        break;
                        
                    case 2: // Ubicación y Contacto
                        const pais = document.getElementById('pais');
                        if (!pais.value) {
                            showError('pais', 'Selecciona tu país');
                            isValid = false;
                        }
                        
                        const ciudad = document.getElementById('ciudad');
                        if (!ciudad.value || ciudad.value.trim().length < 2) {
                            showError('ciudad', 'Ingresa tu ciudad');
                            isValid = false;
                        }
                        
                        const celular = document.getElementById('celular');
                        if (!celular.value || celular.value.trim().length < 8) {
                            showError('celular', 'Ingresa un número de celular válido');
                            isValid = false;
                        }
                        break;
                        
                    case 3: // Contraseñas
                        const password = document.getElementById('password');
                        if (!password.value || password.value.length < 8) {
                            showError('password', 'La contraseña debe tener al menos 8 caracteres');
                            isValid = false;
                        }
                        
                        const passwordConf = document.getElementById('password_confirmation');
                        if (!passwordConf.value) {
                            showError('password-confirmation', 'Confirma tu contraseña');
                            isValid = false;
                        } else if (passwordConf.value !== password.value) {
                            showError('password-confirmation', 'Las contraseñas no coinciden');
                            isValid = false;
                        }
                        break;
                        
                    case 4: // Foto (opcional, no validar)
                        // La foto es opcional, no hay validación
                        break;
                }
                
                return isValid;
            }
            
            function showError(fieldId, message) {
                const errorEl = document.getElementById(`error-${fieldId}`);
                const inputEl = document.getElementById(fieldId.replace('-', '_'));
                if (errorEl) errorEl.textContent = message;
                if (inputEl) inputEl.classList.add('error');
            }
            
            btnNext.addEventListener('click', function() {
                if (validateStep(currentStep)) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        updateUI();
                        // Focus en el siguiente campo
                        setTimeout(() => {
                            const nextInput = document.querySelector(`[data-step="${currentStep}"] input, [data-step="${currentStep}"] select`);
                            if (nextInput) nextInput.focus();
                        }, 300);
                    }
                }
            });
            
            btnPrev.addEventListener('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateUI();
                }
            });
            
            // Permitir avanzar con Enter (excepto en textarea y file inputs)
            document.querySelectorAll('.form-input').forEach(input => {
                if (input.type !== 'file') {
                    input.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (currentStep < totalSteps) {
                                btnNext.click();
                            } else {
                                btnSubmit.click();
                            }
                        }
                    });
                }
            });
            
            // Validación en tiempo real (opcional)
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('blur', function() {
                    const step = parseInt(this.closest('.form-step').dataset.step);
                    if (step === currentStep) {
                        validateStep(step);
                    }
                });
            });
            
            // Inicializar
            updateUI();
        });
    </script>
</x-guest-layout>