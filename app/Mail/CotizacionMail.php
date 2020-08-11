<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CotizacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cotizacion;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cotizacion, $pdf)
    {
        $this->cotizacion = $cotizacion;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.cotizacion_correo')
                    ->attachData($this->pdf, 'cotizacion.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
