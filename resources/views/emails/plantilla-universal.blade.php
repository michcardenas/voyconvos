<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
             font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
             background: #f9f9f9;
             padding: 20px;
             margin: 0;
        }
        .container {
             background: white;
             padding: 40px 30px;
             border-radius: 12px;
             box-shadow: 0 4px 20px rgba(0,0,0,0.1);
             max-width: 600px;
             margin: 0 auto;
         }
                 
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
                 
        .logo-img {
            max-width: 200px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
                 
        .welcome {
             color: #333;
             font-size: 26px;
             margin: 30px 0 20px;
             font-weight: 600;
        }
                 
        .message {
             color: #666;
             font-size: 16px;
             line-height: 1.6;
             margin: 0;
        }
                 
        .message p {
            margin: 0 0 15px 0;
        }
                 
        .message p:last-child {
            margin-bottom: 0;
        }

        /* Estilos para diferentes tipos de email */
        .email-bienvenida .welcome { color: #2c5aa0; }
        .email-bienvenida .message { color: #4a5568; }
                 
        .email-password .welcome { color: #e53e3e; }
        .email-password .message { color: #4a5568; }
                 
        .email-notificacion .welcome { color: #38a169; }
        .email-notificacion .message { color: #4a5568; }
                 
        /* Responsive */
        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }
            .logo-img {
                max-width: 150px;
            }
            .welcome {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container email-{{ $tipoEmail }}">
        <!-- LOGO IMAGEN -->
        <div class="logo-container">
            <img src="{{ asset('img/voyconvos-logo.png') }}" alt="VoyConvos" class="logo-img">
        </div>
                 
        <h2 class="welcome">¡Hola {{ $usuario->name }}!</h2>
        <div class="message">
            {!! nl2br(e($mensaje)) !!}
        </div>
    </div>
</body>
</html>