<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationRejectedMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $companyName,
        public string $rejectionReason,
    ) {
        $this->queue = "default";
        $this->delay = 0;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Registration Update for " . $this->companyName,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: "mail.registration-rejected-mailable",
            with: [
                "firstName" => $this->firstName,
                "lastName" => $this->lastName,
                "companyName" => $this->companyName,
                "rejectionReason" => $this->rejectionReason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}