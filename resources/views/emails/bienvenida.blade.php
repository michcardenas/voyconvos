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
        
        .logo-icon {
            width: 80px;
            height: 100px;
            margin: 0 auto 15px;
            position: relative;
            display: inline-block;
        }
        
        .pin-shape {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 50%, #1e40af 100%);
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            position: relative;
            margin: 10px auto;
            box-shadow: 0 4px 15px rgba(44, 90, 160, 0.3);
        }
        
        .pin-inner {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        /* Elemento decorativo azul */
        .pin-accent {
            width: 30px;
            height: 20px;
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            border-radius: 15px 5px;
            position: absolute;
            top: 60%;
            left: 60%;
            transform: translate(-50%, -50%) rotate(25deg);
        }
        
        /* Pequeño elemento blanco */
        .pin-highlight {
            width: 8px;
            height: 4px;
            background: white;
            border-radius: 4px;
            position: absolute;
            top: 45%;
            left: 70%;
            transform: translate(-50%, -50%) rotate(25deg);
        }
        
        .logo-text {
            font-size: 36px;
            font-weight: 700;
            color: #2c5aa0;
            letter-spacing: -1px;
            margin: 0;
            line-height: 1;
        }
        
        .logo-beta {
            font-size: 14px;
            font-weight: 600;
            color: #2c5aa0;
            letter-spacing: 3px;
            margin: 5px 0 0 0;
            opacity: 0.8;
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
        
        /* Responsive */
        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }
            .logo-text {
                font-size: 28px;
            }
            .welcome {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- LOGO CSS RECREADO -->
        <div class="logo-container">
            <div class="logo-text">VoyConvos</div>
            <div class="logo-beta">B E T A</div>
        </div>
        
        <h2 class="welcome">¡Hola {{ $usuario->name }}!</h2>
        <div class="message">
            <p>Te damos la bienvenida a <strong>VoyConvos</strong>.</p>
            <p>Estamos felices de tenerte con nosotros. 🎉</p>
            <p>¡Gracias por registrarte en nuestra plataforma!</p>
        </div>
    </div>
</body>
</html>