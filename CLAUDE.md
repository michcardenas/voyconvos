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
- **Controllers**: Located in `app/Http/Controllers/` - handles web requests and API endpoints
- **Models**: Located in `app/Models/` - Eloquent models for database entities
- **Services**: Located in `app/Services/` - business logic and external service integrations
- **Mail**: Located in `app/Mail/` - email templates and mail handling
- **Routes**:
  - `routes/web.php` - web routes
  - `routes/auth.php` - authentication routes

### Frontend Structure
- **Views**: Located in `resources/views/` - Blade templates
- **Assets**:
  - `resources/css/app.css` - main stylesheet
  - `resources/js/app.js` - main JavaScript file
- **Build Tool**: Vite with Laravel plugin for asset compilation

### Key Models and Relationships
- **User**: Base user model with authentication
- **Viaje**: Trip/journey model for ride listings
- **Reserva**: Booking/reservation model linking users to trips
- **RegistroConductor**: Driver registration and profile data
- **Calificacion**: Rating system for users and trips
- **Mensaje**: Chat messages for trip communication
- **Pagina/Contenido/Seccion**: CMS for managing public content

### Real-time Features
- **Laravel Echo** with Pusher for real-time chat and notifications
- **Queue system** for background job processing

### Payment Integration
- **MercadoPago** (`mercadopago/dx-php`) for payment processing
- **UalaBis** (`uala-bis/ualabis-php`) for additional payment options

### Authentication & Authorization
- **Laravel Breeze** for authentication scaffolding
- **Spatie Laravel Permission** for role and permission management

## Database
- Uses SQLite for testing (configured in phpunit.xml)
- Migrations located in `database/migrations/`
- Seeders in `database/seeders/`
- Factories in `database/factories/`

## Configuration Files
- `composer.json` - PHP dependencies and scripts
- `package.json` - Node.js dependencies and build scripts
- `vite.config.js` - Vite build configuration
- `tailwind.config.js` - Tailwind CSS configuration
- `phpunit.xml` - PHPUnit testing configuration