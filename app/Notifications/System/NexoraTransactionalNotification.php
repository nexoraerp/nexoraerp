<?php

namespace App\Notifications\System;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NexoraTransactionalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $subject,
        protected array $payload
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.transactional', [
                'preheader' => $this->payload['preheader'] ?? $this->subject,
                'eyebrow' => $this->payload['eyebrow'] ?? 'Nexora ERP',
                'title' => $this->payload['title'] ?? $this->subject,
                'intro' => $this->payload['intro'] ?? null,
                'buttonText' => $this->payload['button_text'] ?? null,
                'buttonUrl' => $this->payload['button_url'] ?? null,
                'sections' => $this->payload['sections'] ?? [],
                'note' => $this->payload['note'] ?? null,
            ]);
    }
}
