# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application called "VoyConvos" - a ride-sharing/carpooling platform. The application includes:

- **Driver registration and management** (RegistroConductor, DestinoConductor models)
- **Trip booking system** (Viaje, Reserva models)
- **Real-time chat functionality** (ChatController, Mensaje model)
- **Rating and review system** (Calificacion, VistaCalificacionesUsuario models)
- **Content management** for public pages (Pagina, Contenido, Seccion models)
- **Admin configuration** (ConfiguracionAdmin model)
- **Payment integration** with MercadoPago and UalaBis

## Development Commands

### Starting Development Environment
```bash
# Start all development services (PHP server, queue worker, logs, and Vite)
composer dev
```

### Building and Assets
```bash
# Build frontend assets for production
npm run build

# Start Vite development server
npm run dev
```

### Testing
```bash
# Run all tests
composer test
# or
php artisan test

# Run specific test suite
php artisan test tests/Feature
php artisan test tests/Unit
```

### Code Quality
```bash
# Format code with Laravel Pint
vendor/bin/pint

# Run tests with configuration clearing
composer test
```

### Laravel Artisan Commands
```bash
# Clear various caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Database operations
php artisan migrate
php artisan db:seed

# Queue operations
php artisan queue:work
php artisan queue:listen --tries=1
```

## Architecture

### Backend Structure
- **Controllers**: Located in `app/Http/Controllers/` organized by role:
  - `Admin/` - Platform administration (ConfiguracionAdminController, UserController, PaginaController)
  - `Conductor/` - Driver features (ViajeController, RegistroVehiculoController, DestinoController)
  - `Pasajero/` - Passenger features (ReservaPasajeroController, ChatPasajeroController)
  - `Auth/` - Authentication (includes GoogleController for OAuth)
- **Models**: Located in `app/Models/` - Eloquent models for database entities
- **Services**: Located in `app/Services/`:
  - `EmailService.php` - Email sending wrapper
  - `UalaService.php` - Payment gateway integration with detailed logging
- **Routes**:
  - `routes/web.php` - main web routes (public, admin, conductor, pasajero)
  - `routes/auth.php` - authentication routes

### Frontend Structure
- **Views**: Located in `resources/views/` - Blade templates
- **Layouts**:
  - `layouts/app.blade.php` - Main application layout
  - `layouts/app_admin.blade.php` - Admin panel layout
  - `layouts/app_dashboard.blade.php` - Driver dashboard layout
  - `layouts/app_dashboard_p.blade.php` - Passenger dashboard layout
  - `layouts/guest.blade.php` - Public/guest layout
- **Stack**: Vite + Tailwind CSS + Alpine.js
- **Real-time**: Laravel Echo + Pusher for WebSocket communication

### Core Trip Workflow
1. **Driver Creates Trip**: `Viaje` with route (GPS coordinates), pricing, available seats
2. **Passengers Book**: `Reserva` created for desired seats
3. **Driver Verification**: Manually verifies passengers before trip
4. **Payment Processing**: Via MercadoPago or UalaBis (tracked in `Reserva` model)
5. **Trip Execution**: Driver marks trip states: `pendiente` → `iniciado` → `en_curso` → `finalizado`
6. **Attendance Tracking**: Driver marks passengers as `presente` or `ausente`
7. **Rating Exchange**: Both parties rate each other via `Calificacion` model

### Key Models and Relationships

#### User Model
- **Fields**: name, email, password, fecha_nacimiento, pais, ciudad, dni, celular, foto, verificado, dni_foto, dni_foto_atras
- **Relationships**:
  - `hasMany(Reserva)` - User's bookings
  - `hasOne(RegistroConductor)` - Driver profile
  - `hasMany(Calificacion)` - Ratings given/received
- **Roles**: Uses Spatie Laravel Permission (HasRoles trait)

#### Viaje Model
- **Purpose**: Trip offered by driver
- **Fields**: conductor_id, origen/destino (GPS + address), distancia_km, vehiculo, valor_cobrado, valor_persona, puestos_totales, puestos_disponibles, hora_salida, fecha_salida, estado, pasajeros_presentes, pasajeros_ausentes
- **States**: `pendiente`, `pendiente_confirmacion`, `listo_para_iniciar`, `iniciado`, `en_curso`, `finalizado`, `cancelado`
- **Relationships**:
  - `belongsTo(User, 'conductor_id')` - Trip owner
  - `hasMany(Reserva)` - Trip bookings
  - `hasOne(RegistroConductor)` - Driver's vehicle details

#### Reserva Model
- **Purpose**: Booking linking passenger to trip
- **Fields**: viaje_id, user_id, estado, cantidad_puestos, precio_por_persona, total, fecha_reserva, verificado_por_conductor, asistencia
- **Payment Fields**:
  - MercadoPago: `mp_preference_id`, `mp_payment_id`, `fecha_pago`
  - UalaBis: `uala_bis_uuid`, `uala_payment_status`
  - Ualabis: `uala_uuid`, `uala_checkout_id`, `uala_order_id`
- **Relationships**:
  - `belongsTo(User)` - Passenger
  - `belongsTo(Viaje)` - Trip
  - `hasMany(Calificacion)` - Ratings for this booking

#### RegistroConductor Model
- **Purpose**: Driver vehicle registration and verification documents
- **Table**: `registro_conductores`
- **Fields**: user_id, marca_vehiculo, modelo_vehiculo, anio_vehiculo, patente, licencia, cedula, cedula_verde, seguro, rto, antecedentes, estado_verificacion, estado_registro, numero_puestos, verificar_pasajeros, consumo_por_galon

#### Calificacion Model
- **Purpose**: Rating/review system
- **Fields**: reserva_id, usuario_id, tipo (`pasajero_a_conductor` or `conductor_a_pasajero`), comentario, calificacion (1-5)

#### Mensaje Model
- **Purpose**: Real-time chat messages between driver and passenger
- **Fields**: viaje_id, emisor_id, receptor_id, mensaje

#### ConfiguracionAdmin Model
- **Purpose**: Platform settings (fuel prices, commission rates)
- **Table**: `configuracion_admin`
- **Primary Key**: `id_configuracion`
- **Fields**: nombre (key like 'gasolina', 'comision'), valor

#### CMS Models
- **Pagina**: Public pages (`hasMany(Seccion)`)
- **Seccion**: Page sections (`belongsTo(Pagina)`, `hasMany(Contenido)`)
- **Contenido**: Key-value content pairs for sections
- **MetadatoPagina**: SEO metadata
- **Politica**: Privacy policies
- **Termino**: Terms and conditions
- **Faq**: FAQ entries

#### Supporting Models
- **DestinoConductor**: GPS location tracking for conductors
- **VistaCalificacionesUsuario**: Read-only view for user ratings
- **VistaCalificacionesDetalle**: Read-only view for detailed ratings

### Payment Integration
- **MercadoPago** (`mercadopago/dx-php ^3.5`): Preference creation, callbacks for success/failure/pending
- **UalaBis/Uala** (`uala-bis/ualabis-php ^1.0`): Advanced SDK with `UalaService.php` wrapper, webhook handling at `/webhook/uala-bis`

### Authentication & Authorization
- **Laravel Breeze** for authentication scaffolding
- **Laravel Socialite 5.20** with Google OAuth (`GoogleController`)
- **Spatie Laravel Permission 6.18** for role-based access control (admin, conductor, pasajero)

## Important Route Patterns

### Admin Routes (`/admin` prefix)
- `/admin/dashboard` - Admin panel
- `/admin/gestion` - Trip management table
- `/admin/gestor-pagos` - Payment management interface
- `/admin/users` - User CRUD operations
- `/admin/paginas` - CMS page management
- `/admin/viajes/*` - Trip details and editing

### Conductor Routes (`/conductor` prefix)
- `/conductor/gestion` - Driver's trip list
- `/conductor/completar-registro` - Vehicle registration form
- `/conductor/guardar-viaje` - Create new trip
- `/conductor/viaje/{id}/iniciar` - Start trip (changes estado to 'iniciado')
- `/conductor/viaje/{id}/en-curso` - Mark trip in progress
- `/conductor/viaje/{id}/verificar-pasajeros` - Mark passenger attendance (presente/ausente)
- `/conductor/viaje/{id}/finalizar` - Finalize trip
- `/conductor/calificar-pasajero/{reserva}` - Rate passenger

### Pasajero Routes (`/pasajero` prefix)
- `/pasajero/dashboard` - My reservations list
- `/pasajero/viajes-disponibles` - Search available trips
- `/pasajero/reservar/{viaje}` - Book seats on trip
- `/pasajero/reserva/{reserva}/detalles` - Reservation details
- `/reserva/{reserva}/pagar` - Payment page (MercadoPago/Uala selection)
- `/pago/success|failure|pending/{reserva}` - Payment callback handlers
- `/pasajero/reserva/{reserva}/calificar` - Rate driver
- `/chat/{viaje}` - Real-time chat with driver (uses Pusher)

### Public Routes
- `/` - Homepage
- `/sobre-nosotros`, `/contacto`, `/como-funciona` - Info pages
- `/preguntas-frecuentes`, `/terminos-y-condiciones`, `/politica-de-privacidad` - Legal/help pages
- `/dashboard-hibrido` - Smart dashboard that detects user role and redirects appropriately

### Webhooks
- `/webhook/uala-bis` - UalaBis payment confirmation webhook

## Database
- **Testing**: SQLite in-memory database (configured in phpunit.xml)
- **Development**: SQLite or MySQL (configured via .env)
- **Migrations**: `database/migrations/` - 20+ migrations including GPS coordinates, payment fields, verification states
- **Key Tables**: users, viajes, reservas, registro_conductores, calificaciones, mensajes, configuracion_admin, paginas/secciones/contenidos

## Configuration Files
- `composer.json` - PHP dependencies and custom scripts (dev, test)
- `package.json` - Node dependencies (Vite, Tailwind, Alpine, Echo, Pusher)
- `vite.config.js` - Vite build configuration with Laravel plugin
- `tailwind.config.js` - Tailwind CSS configuration with forms plugin
- `phpunit.xml` - PHPUnit testing configuration (SQLite, array drivers)