<?php

namespace App\Mail;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeNewTenantMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tenant $tenant;
    public User $user;
    public ?string $password;

    /**
     * Create a new message instance.
     */
    public function __construct(Tenant $tenant, User $user, ?string $password = null)
    {
        $this->tenant = $tenant;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "¡Bienvenido a GES Taller! - {$this->tenant->name} (Credenciales de Acceso)",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $trackingUrl = route('rastreo.tenant', ['tenant' => $this->tenant->slug]);

        return new Content(
            view: 'emails.welcome-tenant',
            with: [
                'tenant' => $this->tenant,
                'user' => $this->user,
                'password' => $this->password,
                'trialDays' => 15,
                'loginUrl' => route('login'),
                'trackingUrl' => $trackingUrl,
            ]
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
