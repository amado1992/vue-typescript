<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Cliente;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use PhpParser\Node\Expr\Array_;

class StatusCancelMail extends Mailable
{
    use Queueable, SerializesModels;
    public $cliente;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Información sobre el cambio de estado de su Solicitud.')->view('mail.changeStatusEmail');
    }
}
