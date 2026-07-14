<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nexora ERP e-posta doğrulama')
            ->view('emails.transactional', [
                'preheader' => 'Nexora ERP hesabınızı doğrulayarak güvenli kullanıma başlayın.',
                'eyebrow' => 'Hesap Güvenliği',
                'title' => 'E-posta adresinizi doğrulayın',
                'intro' => "Merhaba {$notifiable->name}, Nexora ERP hesabınızı güvenli şekilde kullanabilmeniz için e-posta adresinizi doğrulamanız gerekiyor.",
                'buttonText' => 'E-postamı Doğrula',
                'buttonUrl' => $this->verificationUrl($notifiable),
                'sections' => [
                    [
                        'title' => 'Bu işlem neden gerekli?',
                        'body' => 'Doğrulama, hesabınızın size ait olduğunu teyit eder ve bildirim, şifre sıfırlama, lisans bilgileri gibi kritik süreçlerin doğru adrese gönderilmesini sağlar.',
                    ],
                    [
                        'title' => 'Deneme süreciniz',
                        'body' => 'E-posta doğrulandıktan sonra Nexora ERP panelinize güvenli şekilde erişebilir ve 14 günlük ücretsiz deneme sürecinizi kullanabilirsiniz.',
                    ],
                ],
                'note' => 'Bu bağlantı sınırlı süre geçerlidir. Süresi dolarsa giriş ekranından yeni doğrulama e-postası isteyebilirsiniz.',
            ]);
    }

    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
