<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $clientName;
    public $invoiceNumber;
    public $pdfPath;

    public function __construct($clientName, $invoiceNumber, $pdfPath)
    {
        $this->clientName = $clientName;
        $this->invoiceNumber = $invoiceNumber;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        $mail = $this->subject('Your Invoice: ' . $this->invoiceNumber)
            ->view('email.invoice')
            ->with([
                'clientName' => $this->clientName,
                'invoiceNumber' => $this->invoiceNumber,
            ]);

        if (Storage::exists($this->pdfPath)) {
            $mail->attachFromStorage($this->pdfPath);
        }

        return $mail;
    }
}
