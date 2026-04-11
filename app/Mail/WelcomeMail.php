<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $html = '<h1>¡Bienvenido a Halcon System!</h1>';
        $html .= '<p>Hola ' . $this->user->name . ',</p>';
        $html .= '<p>Gracias por registrarte en Halcon System.</p>';
        $html .= '<p><strong>Detalles de tu cuenta:</strong></p>';
        $html .= '<ul>';
        $html .= '<li>Email: ' . $this->user->email . '</li>';
        $html .= '<li>Fecha de registro: ' . $this->user->created_at->format('d/m/Y H:i:s') . '</li>';
        $html .= '</ul>';
        $html .= '<p>Saludos,<br>Equipo Halcon</p>';

        return $this->from('halcon@system.com', 'Halcon System')
                    ->subject('¡Bienvenido a Halcon System!')
                    ->html($html);
    }
}