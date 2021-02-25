<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorrespondenciaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $correspondencia;
    public $tipo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($correspondencia, $tipo)
    {
        $this->correspondencia = $correspondencia;
        $this->tipo = $tipo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('terceros.mail', ['correspondencia' => $this->correspondencia, 'tipo' => $this->tipo]);
    }
}
