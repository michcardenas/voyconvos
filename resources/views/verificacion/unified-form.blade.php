@extends('layouts.app')

@section('content')
<div class="verification-container">
    <div class="verification-card">
        <!-- Panel izquierdo - Info -->
        <div class="info-panel">
            <div class="shield-icon">🛡️</div>
            <h2 class="info-title">Verificación de Cuenta</h2>
            <p class="info-text">
                Verifica tu identidad para poder <strong>reservar y publicar viajes</strong> en VoyConVos.
            </p>
            
            <div class="important-note">
                <strong>⚠️ Importante:</strong>
                <p>Sin verificación no podrás usar la plataforma para viajar.</p>
            </div>
            
            <div class="benefits">
                <div class="benefit">✓ Reserva viajes</div>
                <div class="benefit">✓ Publica viajes</div>
                <div class="benefit">✓ Perfil verificado</div>
                <div class="benefit">✓ Seguridad garantizada</div>
            </div>
        </div>
        
        <!-- Panel derecho - Formulario -->
        <div class="form-panel">
            <div class="logo-container">
                <img src="{{ asset('img/voyconvos-logo.png') }}" alt="VoyConVos" class="logo">
            </div>
            
            <h1 class="title">Verificar Identidad</h1>
            <p class="subtitle">Sube tus documentos para empezar a viajar</p>
            
            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>⚠️ Errores:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('verificacion.store') }}" enctype="multipart/form-data" id="verificationForm">
                @csrf
                
                <!-- DNI -->
                <div class="form-group">
                    <label class="label">
                        Número de Documento <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="dni" 
                        id="dni"
                        class="input @error('dni') error @enderror"
                        value="{{ old('dni') }}"
                        placeholder="12345678"
                        required
                    >
                    @error('dni')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- DNI Frente -->
                <div class="form-group">
                    <label class="label">
                        📄 DNI / Cédula (Frente) <span class="required">*</span>
                    </label>
                    <div class="upload-box" id="upload1">
                        <div class="preview" id="preview1">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <p>Clic o arrastra aquí</p>
                        </div>
                        <input type="file" name="dni_foto" id="dni_foto" accept="image/*" required>
                    </div>
                    @error('dni_foto')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- DNI Atrás -->
                <div class="form-group">
                    <label class="label">
                        📄 DNI / Cédula (Atrás) <span class="required">*</span>
                    </label>
                    <div class="upload-box" id="upload2">
                        <div class="preview" id="preview2">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <p>Clic o arrastra aquí</p>
                        </div>
                        <input type="file" name="dni_foto_atras" id="dni_foto_atras" accept="image/*" required>
                    </div>
                    @error('dni_foto_atras')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="divider">
                    <span>¿Quieres ser conductor? (opcional)</span>
                </div>

                <div class="tip-box">
                    <strong>💡 Documentos opcionales de conductor</strong>
                    <p>Si quieres ofrecer viajes, sube estos documentos ahora. O completa solo tu DNI y agrégalos después desde tu perfil.</p>
                </div>

                <!-- Licencia -->
                <div class="form-group">
                    <label class="label">🪪 Licencia de Conducir</label>
                    <div class="upload-box small" id="upload3">
                        <div class="preview" id="preview3">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <p>Subir (opcional)</p>
                        </div>
                        <input type="file" name="licencia" id="licencia" accept="image/*">
                    </div>
                    @error('licencia')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Cédula de Identidad -->
                <div class="form-group">
                    <label class="label">🪪 Cédula de Identidad</label>
                    <div class="upload-box small" id="upload4">
                        <div class="preview" id="preview4">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <p>Subir (opcional)</p>
                        </div>
                        <input type="file" name="cedula" id="cedula" accept="image/*">
                    </div>
                    @error('cedula')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Cédula Verde -->
                <div class="form-group">
                    <label class="label">📋 Cédula Verde del Vehículo</label>
                    <div class="upload-box small" id="upload5">
                        <div class="preview" id="preview5">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <p>Subir (opcional)</p>
                        </div>
                        <input type="file" name="cedula_verde" id="cedula_verde" accept="image/*">
                    </div>
                    @error('cedula_verde')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Seguro -->
                <div class="form-group">
                    <label class="label">🛡️ Seguro del Vehículo</label>
                    <div class="upload-box small" id="upload6">
                        <div class="preview" id="preview6">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <p>Subir (opcional)</p>
                        </div>
                        <input type="file" name="seguro" id="seguro" accept="image/*">
                    </div>
                    @error('seguro')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botones -->
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-primary" id="btnSubmit" style="flex: 1; min-width: 200px;">
                        <span>Verificar y Continuar</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </button>

                    <a href="{{ route('hibrido.dashboard') }}" class="btn btn-secondary" style="flex: 1; min-width: 200px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <span>Completar Después</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </a>
                </div>

                <p class="privacy">🔒 Tus datos están seguros y encriptados</p>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #1F4E79;
        --secondary: #4CAF50;
        --error: #ef4444;
        --warning: #f59e0b;
        --bg: #FCFCFD;
    }
    
    body { background: var(--bg); }
    
    .verification-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 1.5rem;
    }
    
    .verification-card {
        width: 100%;
        max-width: 950px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: flex;
        overflow: hidden;
    }
    
    /* Panel izquierdo */
    .info-panel {
        background: linear-gradient(135deg, var(--primary) 0%, #163d5f 100%);
        color: white;
        padding: 2rem;
        width: 35%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .shield-icon {
        font-size: 3rem;
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .info-title {
        font-size: 1.4rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 0.75rem;
    }
    
    .info-text {
        font-size: 0.9rem;
        line-height: 1.5;
        text-align: center;
        margin-bottom: 1.5rem;
        opacity: 0.95;
    }
    
    .important-note {
        background: rgba(245, 158, 11, 0.2);
        padding: 0.75rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        border-left: 3px solid var(--warning);
        font-size: 0.85rem;
    }
    
    .important-note strong {
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .important-note p {
        margin: 0;
        opacity: 0.95;
    }
    
    .benefits {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .benefit {
        background: rgba(255,255,255,0.1);
        padding: 0.6rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }
    
    /* Panel derecho */
    .form-panel {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
        max-height: 90vh;
    }
    
    .logo-container {
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .logo {
        height: 50px;
        width: auto;
    }
    
    .title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        text-align: center;
        margin-bottom: 0.25rem;
    }
    
    .subtitle {
        text-align: center;
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
    }
    
    /* Alertas */
    .alert {
        padding: 0.75rem;
        border-radius: 6px;
        margin-bottom: 1rem;
    }
    
    .alert-error {
        background: #fee;
        border-left: 3px solid var(--error);
    }
    
    .alert strong {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--error);
    }
    
    .alert ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    
    .alert li {
        color: var(--error);
        font-size: 0.85rem;
    }
    
    /* Formulario */
    .form-group {
        margin-bottom: 1rem;
    }
    
    .label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        color: #3A3A3A;
        font-weight: 600;
    }
    
    .required {
        color: var(--error);
    }
    
    .input {
        width: 100%;
        padding: 0.7rem;
        border: 2px solid #E2E8F0;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(31,78,121,0.1);
    }
    
    .input.error {
        border-color: var(--error);
    }
    
    .error-msg {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: var(--error);
    }
    
    /* Upload boxes */
    .upload-box {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #f8fafc;
    }
    
    .upload-box.small {
        padding: 1rem;
    }
    
    .upload-box:hover {
        border-color: var(--primary);
        background: #DDF2FE;
    }
    
    .upload-box.has-file {
        border-color: var(--secondary);
        background: white;
    }
    
    .preview {
        pointer-events: none;
    }
    
    .preview svg {
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }
    
    .preview p {
        margin: 0;
        font-size: 0.85rem;
        color: #64748b;
    }
    
    .preview img {
        max-width: 100%;
        max-height: 150px;
        border-radius: 6px;
        margin-bottom: 0.5rem;
    }
    
    .upload-box input[type="file"] {
        display: none;
    }
    
    /* Divider */
    .divider {
        text-align: center;
        margin: 1.5rem 0 1rem;
        position: relative;
    }
    
    .divider::before,
    .divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 42%;
        height: 1px;
        background: #e2e8f0;
    }
    
    .divider::before { left: 0; }
    .divider::after { right: 0; }
    
    .divider span {
        background: white;
        padding: 0 0.75rem;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }
    
    /* Tip box */
    .tip-box {
        background: #DDF2FE;
        padding: 0.75rem;
        border-radius: 6px;
        margin: 1rem 0;
        border-left: 3px solid var(--primary);
        font-size: 0.8rem;
    }
    
    .tip-box strong {
        display: block;
        margin-bottom: 0.25rem;
        color: var(--primary);
    }
    
    .tip-box p {
        margin: 0;
        color: #3A3A3A;
        line-height: 1.4;
    }
    
    /* Botones */
    .btn {
        width: 100%;
        padding: 0.85rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        margin-bottom: 0.75rem;
    }
    
    .btn-primary {
        background: var(--secondary);
        color: white;
    }
    
    .btn-primary:hover {
        background: #45a049;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(76,175,80,0.3);
    }
    
    .btn-secondary {
        background: transparent;
        color: #64748b;
        border: 2px solid #e2e8f0;
    }
    
    .btn-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    
    .privacy {
        text-align: center;
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.5rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .verification-card {
            flex-direction: column;
        }
        
        .info-panel {
            width: 100%;
            padding: 1.5rem;
        }
        
        .form-panel {
            padding: 1.5rem;
            max-height: none;
        }
    }
    
    /* Loading */
    .btn.loading {
        opacity: 0.7;
        pointer-events: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar uploads (DNI obligatorio + documentos conductor opcionales)
        const uploads = [
            { input: 'dni_foto', preview: 'preview1', box: 'upload1' },
            { input: 'dni_foto_atras', preview: 'preview2', box: 'upload2' },
            { input: 'licencia', preview: 'preview3', box: 'upload3' },
            { input: 'cedula', preview: 'preview4', box: 'upload4' },
            { input: 'cedula_verde', preview: 'preview5', box: 'upload5' },
            { input: 'seguro', preview: 'preview6', box: 'upload6' }
        ];

        uploads.forEach(item => setupUpload(item));
        
        function setupUpload({ input, preview, box }) {
            const inputEl = document.getElementById(input);
            const previewEl = document.getElementById(preview);
            const boxEl = document.getElementById(box);
            
            if (!inputEl || !previewEl || !boxEl) return;
            
            boxEl.addEventListener('click', () => inputEl.click());
            
            inputEl.addEventListener('change', function(e) {
                handleFile(e.target.files[0], previewEl, boxEl);
            });
            
            // Drag & drop
            boxEl.addEventListener('dragover', (e) => {
                e.preventDefault();
                boxEl.style.borderColor = '#4CAF50';
            });
            
            boxEl.addEventListener('dragleave', () => {
                boxEl.style.borderColor = '';
            });
            
            boxEl.addEventListener('drop', (e) => {
                e.preventDefault();
                boxEl.style.borderColor = '';
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    inputEl.files = e.dataTransfer.files;
                    handleFile(file, previewEl, boxEl);
                }
            });
        }
        
        function handleFile(file, preview, box) {
            if (!file) return;
            
            if (file.size > 5 * 1024 * 1024) {
                alert('⚠️ Máximo 5MB por imagen');
                return;
            }
            
            if (!file.type.startsWith('image/')) {
                alert('⚠️ Solo imágenes');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <p style="color: #4CAF50; font-weight: 600;">✓ Cargado</p>
                `;
                box.classList.add('has-file');
            };
            reader.readAsDataURL(file);
        }
        
        // Validar formulario
        const form = document.getElementById('verificationForm');
        const btnSubmit = document.getElementById('btnSubmit');
        
        form.addEventListener('submit', function(e) {
            const dni = document.getElementById('dni').value.trim();
            const dniFoto = document.getElementById('dni_foto').files[0];
            const dniAtras = document.getElementById('dni_foto_atras').files[0];
            
            if (!dni || !dniFoto || !dniAtras) {
                e.preventDefault();
                alert('⚠️ DNI y fotos del documento son obligatorios');
                return;
            }
            
            btnSubmit.classList.add('loading');
            btnSubmit.disabled = true;
        });
        
        // Solo números en DNI
        document.getElementById('dni').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
@endsection