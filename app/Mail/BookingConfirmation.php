<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingData;
    public $confirmationUrl;

    public function __construct($bookingData, $confirmationUrl)
    {
        $this->bookingData = $bookingData;
        $this->confirmationUrl = $confirmationUrl;
    }

    public function build()
    {
        return $this->subject('Xác nhận đặt lịch khám')
                    ->view('emails.booking_confirmation');
    }
}