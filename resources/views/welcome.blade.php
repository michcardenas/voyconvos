<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoyConVoz - Viajes Compartidos</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/voyconvos-logo.png') }}" alt="VoyConVos" class="logo-image">
                    <span>VoyConVoz</span>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Coche compartido</a></li>
                    <li><a href="#">Bus</a></li>
                    <li><a href="#">Tren</a></li>
                </ul>
            </nav>
            <div class="user-profile">
    <div class="dropdown">
        <a href="#" class="profile-icon" id="userDropdown">
            <img src="{{ asset('img/usuario.png') }}" alt="Usuario">
        </a>
        <div class="dropdown-menu" id="userMenu">
            <a href="{{ route('login') }}" class="dropdown-item">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="dropdown-item">Registrarse</a>
        </div>
    </div>
</div>
        </div>
    </header>
 
    <main>
        <section class="hero">
            <div class="container">
                <h1>Descubre cuánto ahorras en un viaje en VoyConVoz.</h1>
                
                <div class="search-box">
                    <div class="route-inputs">
                        <div class="input-group">
                            <span class="icon">O</span>
                            <input type="text" placeholder="Origen" value="">
                            <button class="switch-btn">⇄</button>
                        </div>
                        <div class="input-group">
                            <span class="icon">O</span>
                            <input type="text" placeholder="Destino" value="">
                        </div>
                    </div>
                    
                    <div class="passengers">
                        <span class="person-icon">👤</span>
                        <span>2 pasajeros</span>
                    </div>
                    
                    <div class="savings">
                        <h2>Ahorra hasta <span class="highlight">$ 100</span> en cada viaje.</h2>
                    </div>
                    
                    <button class="publish-trip-btn">Publica un viaje</button>
                </div>
            </div>
            
            <div class="car-illustration">
                <img src="{{ asset('img/undraw_vintage_q09n.png') }}" alt="Coche compartido">
            </div>
        </section>
 
        <section class="slogan">
            <div class="container">
                <h2>Conduce. Comparte. Ahorra.</h2>
            </div>
        </section>
    </main>
 
    <footer>
        <div class="container">
            <button class="publish-trip-btn-footer">Publica un viaje</button>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userDropdown = document.getElementById('userDropdown');
        const userMenu = document.getElementById('userMenu');

        userDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            userMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.remove('show');
            }
        });
    });
</script>
</body>
</html>