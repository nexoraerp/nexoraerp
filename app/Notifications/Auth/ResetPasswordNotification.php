<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $token
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nexora ERP şifre sıfırlama')
            ->view('emails.transactional', [
                'preheader' => 'Nexora ERP hesabınız için şifre sıfırlama bağlantısı.',
                'eyebrow' => 'Hesap Güvenliği',
                'title' => 'Şifrenizi güvenli şekilde yenileyin',
                'intro' => "Merhaba {$notifiable->name}, Nexora ERP hesabınız için şifre sıfırlama talebi aldık.",
                'buttonText' => 'Şifremi Sıfırla',
                'buttonUrl' => $this->resetUrl($notifiable),
                'sections' => [
                    [
                        'title' => 'Güvenlik notu',
                        'body' => 'Bu işlemi siz başlatmadıysanız herhangi bir işlem yapmayın. Mevcut şifreniz değişmeyecektir.',
                    ],
                    [
                        'title' => 'Öneri',
                        'body' => 'Yeni şifrenizde büyük harf, küçük harf, rakam ve özel karakter kullanmanız hesabınızın güvenliği için önerilir.',
                    ],
                ],
                'note' => 'Şifre sıfırlama bağlantısı sınırlı süre geçerlidir.',
            ]);
    }

    protected function resetUrl(object $notifiable): string
    {
        return route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
    }
}
