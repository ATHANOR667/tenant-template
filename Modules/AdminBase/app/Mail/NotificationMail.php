<?php

namespace Modules\AdminBase\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $message;
    protected string $color;
    protected string $type;

    public function __construct(string $message, string $type,  string $color = '#FF0000')
    {
        $this->message = $message;
        $this->color = $color;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: ucfirst($this->type) . ' Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'adminbase::mail.notification-mail',
            with: [
                'mailMessage' => $this->message,
                'color' => $this->color,
                'type' => $this->type,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
