<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TenantCredentialsMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $tenantName,
        public string $domain,
        public string $email,
        public string $password
    ) {
        $this->queue = 'default';
        $this->delay = 0;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your ' . $this->tenantName . ' Account is Ready',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.tenant-credentials-mailable',
            with: [
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'tenantName' => $this->tenantName,
                'domain' => $this->domain,
                'email' => $this->email,
                'password' => $this->password,
                'loginUrl' => "http://{$this->domain}/login",
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
