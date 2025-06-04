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

    .chat-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(31, 78, 121, 0.03) 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
    }

    .chat-wrapper::before {
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

    .container {
        position: relative;
        z-index: 1;
        max-width: 800px;
    }

    .chat-header {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, rgba(31, 78, 121, 0.9) 50%, rgba(58, 58, 58, 0.8) 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem 2rem;
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.15);
        position: relative;
        overflow: hidden;
    }

    .chat-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .chat-header h3 {
        margin: 0;
        font-weight: 600;
        font-size: 1.3rem;
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
    }

    .chat-status {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-top: 0.3rem;
        position: relative;
        z-index: 2;
    }

    .chat-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(31, 78, 121, 0.12);
        border-radius: 0 0 16px 16px;
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        overflow: hidden;
    }

    .chat-messages {
        height: 450px;
        overflow-y: auto;
        padding: 1.5rem;
        background: rgba(252, 252, 253, 0.8);
        position: relative;
    }

    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: rgba(31, 78, 121, 0.05);
        border-radius: 3px;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: rgba(31, 78, 121, 0.3);
        border-radius: 3px;
    }

    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: rgba(31, 78, 121, 0.5);
    }

    .message-bubble {
        margin-bottom: 1rem;
        display: flex;
        animation: slideInMessage 0.3s ease-out;
    }

    @keyframes slideInMessage {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-bubble.own {
        justify-content: flex-end;
    }

    .message-bubble.other {
        justify-content: flex-start;
    }

    .message-content {
        max-width: 70%;
        position: relative;
    }

    .message-bubble.own .message-content {
        background: linear-gradient(135deg, var(--vcv-primary), rgba(31, 78, 121, 0.9));
        color: white;
        border-radius: 18px 18px 5px 18px;
        padding: 1rem 1.3rem;
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);
    }

    .message-bubble.other .message-content {
        background: white;
        color: var(--vcv-dark);
        border-radius: 18px 18px 18px 5px;
        padding: 1rem 1.3rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(31, 78, 121, 0.1);
    }

    .message-text {
        margin: 0;
        line-height: 1.4;
        word-wrap: break-word;
    }

    .message-time {
        font-size: 0.75rem;
        opacity: 0.8;
        margin-top: 0.5rem;
        text-align: right;
    }

    .message-bubble.other .message-time {
        color: rgba(58, 58, 58, 0.6);
    }

    .message-sender {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        opacity: 0.9;
    }

    .message-bubble.other .message-sender {
        color: var(--vcv-primary);
    }

    .empty-chat {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: rgba(58, 58, 58, 0.6);
    }

    .empty-chat i {
        font-size: 4rem;
        color: rgba(31, 78, 121, 0.2);
        margin-bottom: 1rem;
    }

    .empty-chat h5 {
        color: var(--vcv-primary);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .empty-chat p {
        margin: 0;
        text-align: center;
    }

    .chat-input-section {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 1.5rem;
        border-top: 1px solid rgba(31, 78, 121, 0.1);
    }

    .input-group-custom {
        display: flex;
        background: white;
        border-radius: 25px;
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.1);
        border: 2px solid rgba(31, 78, 121, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .input-group-custom:focus-within {
        border-color: var(--vcv-primary);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.2);
    }

    .message-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 1rem 1.5rem;
        font-size: 0.95rem;
        background: transparent;
        color: var(--vcv-dark);
    }

    .message-input::placeholder {
        color: rgba(58, 58, 58, 0.5);
    }

    .send-button {
        background: var(--vcv-primary);
        color: white;
        border: none;
        padding: 1rem 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .send-button:hover {
        background: rgba(31, 78, 121, 0.9);
        transform: translateX(-2px);
    }

    .send-button:active {
        transform: scale(0.98);
    }

    .back-button {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(31, 78, 121, 0.12);
        border-radius: 12px;
        padding: 0.8rem 1.5rem;
        margin-top: 1rem;
        color: var(--vcv-primary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(31, 78, 121, 0.08);
    }

    .back-button:hover {
        background: var(--vcv-primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.2);
        text-decoration: none;
    }

    .online-indicator {
        width: 10px;
        height: 10px;
        background: var(--vcv-accent);
        border-radius: 50%;
        display: inline-block;
        margin-left: 0.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    @media (max-width: 768px) {
        .chat-wrapper {
            padding: 1rem 0;
        }
        
        .container {
            margin: 0 1rem;
        }
        
        .chat-messages {
            height: 350px;
            padding: 1rem;
        }
        
        .message-content {
            max-width: 85%;
        }
        
        .chat-header {
            padding: 1rem 1.5rem;
        }
        
        .chat-input-section {
            padding: 1rem;
        }
        
        .message-input {
            padding: 0.8rem 1rem;
        }
        
        .send-button {
            padding: 0.8rem 1rem;
        }
    }
</style>

<div class="chat-wrapper">
    <div class="container">
        <!-- Chat Header -->
        <div class="chat-header">
            <h3>
                <i class="fas fa-comments me-2"></i>
                Chat de viaje
                <span class="online-indicator"></span>
            </h3>
            <div class="chat-status">
                <i class="fas fa-users me-1"></i>
                Conversación activa
            </div>
        </div>

        <!-- Chat Container -->
        <div class="chat-container">
            <!-- Messages Area -->
            <div class="chat-messages" id="chatMessages">
                @forelse ($mensajes as $mensaje)
                    <div class="message-bubble {{ $mensaje->emisor_id === auth()->id() ? 'own' : 'other' }}">
                        <div class="message-content">
                            @if($mensaje->emisor_id !== auth()->id())
                                <div class="message-sender">
                                    <i class="fas fa-user-circle me-1"></i>
                                    Otro usuario
                                </div>
                            @endif
                            <p class="message-text">{{ $mensaje->mensaje }}</p>
                            <div class="message-time">
                                <i class="fas fa-clock me-1"></i>
                                {{ $mensaje->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-chat">
                        <i class="fas fa-comment-dots"></i>
                        <h5>¡Inicia la conversación!</h5>
                        <p>Aún no hay mensajes en este chat.<br>Sé el primero en escribir un mensaje.</p>
                    </div>
                @endforelse
            </div>

            <!-- Input Section -->
            <div class="chat-input-section">
                <form action="{{ route('chat.enviar', $viaje->id) }}" method="POST" id="chatForm">
                    @csrf
                    <div class="input-group-custom">
                        <input 
                            type="text" 
                            name="mensaje" 
                            class="message-input" 
                            placeholder="Escribe tu mensaje aquí..." 
                            required
                            maxlength="500"
                            autocomplete="off"
                        >
                        <button class="send-button" type="submit">
                            <i class="fas fa-paper-plane"></i>
                            <span class="d-none d-sm-inline">Enviar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Back Button -->
        <a href="{{ url()->previous() }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>
    </div>
</div>

<script>
// Auto-scroll al final del chat
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Scroll al cargar la página
window.addEventListener('load', scrollToBottom);

// Mejorar la experiencia de envío
document.getElementById('chatForm').addEventListener('submit', function(e) {
    const input = this.querySelector('.message-input');
    if (input.value.trim() === '') {
        e.preventDefault();
        return;
    }
    
    // Deshabilitar botón temporalmente para evitar doble envío
    const button = this.querySelector('.send-button');
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span class="d-none d-sm-inline">Enviando...</span>';
    
    // Re-habilitar después de un tiempo (por si hay error)
    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-paper-plane"></i> <span class="d-none d-sm-inline">Enviar</span>';
    }, 3000);
});

// Enter para enviar (opcional)
document.querySelector('.message-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('chatForm').submit();
    }
});

// Auto-refresh cada 30 segundos (opcional - descomenta si quieres)
 setInterval(() => {
     window.location.reload();
 }, 30000);
</script>
@endsection