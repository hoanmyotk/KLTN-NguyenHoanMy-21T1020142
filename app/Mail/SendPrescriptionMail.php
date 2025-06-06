<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPrescriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patientName;
    public $filePath;

    public function __construct($patientName, $filePath)
    {
        $this->patientName = $patientName;
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('Đơn thuốc từ bệnh viện')
                    ->view('emails.send-prescription')
                    ->attach($this->filePath, [
                        'as' => 'don-thuoc.txt',
                        'mime' => 'text/plain',
                    ]);
    }
}