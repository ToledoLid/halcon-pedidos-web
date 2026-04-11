<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ip;
    public $time;

    public function __construct($user, $ip)
    {
        $this->user = $user;
        $this->ip = $ip;
        $this->time = now()->format('d/m/Y H:i:s');
    }

    public function build()
    {
        $html = '<h1>Alerta de Inicio de Sesión</h1>';
        $html .= '<p>Hola ' . $this->user->name . ',</p>';
        $html .= '<p>Se ha detectado un inicio de sesión en tu cuenta de Halcon System.</p>';
        $html .= '<p><strong>Detalles del acceso:</strong></p>';
        $html .= '<ul>';
        $html .= '<li>Email: ' . $this->user->email . '</li>';
        $html .= '<li>Dirección IP: ' . $this->ip . '</li>';
        $html .= '<li>Fecha y hora: ' . $this->time . '</li>';
        $html .= '</ul>';
        $html .= '<p><strong>¿No fuiste tú?</strong> Contacta al administrador inmediatamente.</p>';
        $html .= '<p>Saludos,<br>Equipo Halcon</p>';

        return $this->from('halcon@system.com', 'Halcon System')
                    ->subject('Alerta de Inicio de Sesión - Halcon System')
                    ->html($html);
    }
}