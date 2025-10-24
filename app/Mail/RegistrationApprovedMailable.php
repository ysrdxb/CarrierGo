<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationApprovedMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $companyName,
        public string $domain,
        public string $loginUrl,
    ) {
        $this->queue = "default";
        $this->delay = 0;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your " . $this->companyName . " Account Has Been Approved!",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: "mail.registration-approved-mailable",
            with: [
                "firstName" => $this->firstName,
                "lastName" => $this->lastName,
                "companyName" => $this->companyName,
                "domain" => $this->domain,
                "loginUrl" => $this->loginUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}