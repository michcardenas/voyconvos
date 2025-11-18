<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Excluir callbacks de verificación CSRF
        $middleware->validateCsrfTokens(except: [
            '/auth/apple/callback', // Apple envía POST sin token
            '/webhook/uala-bis',    // Webhook de UalaBis
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
