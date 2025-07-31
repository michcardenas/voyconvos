<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Services\EmailService;


class UniversalMail extends Mailable
{
      use Queueable, SerializesModels;

    public $usuario;
    public $asunto;
    public $mensaje;
    public $tipoEmail;

    public function __construct($usuario, $asunto, $mensaje, $tipoEmail = 'general')
    {
        $this->usuario = $usuario;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->tipoEmail = $tipoEmail;
    }

    public function build()
    {
        return $this->subject($this->asunto)
                ->view('emails.plantilla-universal');
    }
    
}
