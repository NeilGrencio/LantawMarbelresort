<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class smtpSender extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $username;
    public $datetime;

    public function __construct($username, $otp)
    {
        $this->username = $username;
        $this->otp = $otp;
        $this->datetime = now()->format('F j, Y, g:i A'); // Current date and time
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your OTP Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'auth.password_OTP',
            with: [
                'username' => $this->username,
                'otp' => $this->otp,
                'datetime' => $this->datetime,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
