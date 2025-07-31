<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Exception;
use Log;

class EmailService
{
    /**
     * Enviar email simple
     * 
     * @param string $email - Email destino
     * @param string $subject - Asunto del email
     * @param string $message - Mensaje del email
     * @return bool - true si se envió correctamente, false si falló
     */
    public function send($email, $subject, $message)
    {
        try {
            Mail::raw($message, function ($mail) use ($email, $subject) {
                $mail->to($email)
                     ->subject($subject);
            });

            return true;

        } catch (Exception $e) {
            // Log del error para debugging
            Log::error('Error enviando email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar email con HTML
     * 
     * @param string $email - Email destino
     * @param string $subject - Asunto del email
     * @param string $htmlContent - Contenido HTML del email
     * @return bool
     */
    public function sendHtml($email, $subject, $htmlContent)
    {
        try {
            Mail::html($htmlContent, function ($mail) use ($email, $subject) {
                $mail->to($email)
                     ->subject($subject);
            });

            return true;

        } catch (Exception $e) {
            Log::error('Error enviando email HTML: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar email a múltiples destinatarios
     * 
     * @param array $emails - Array de emails destino
     * @param string $subject - Asunto del email
     * @param string $message - Mensaje del email
     * @return bool
     */
    public function sendToMultiple($emails, $subject, $message)
    {
        try {
            foreach ($emails as $email) {
                $this->send($email, $subject, $message);
            }

            return true;

        } catch (Exception $e) {
            Log::error('Error enviando emails múltiples: ' . $e->getMessage());
            return false;
        }
    }
}