<?php

namespace App\Services\Mail;

use App\Models\User;

class NexoraMailTemplateFactory
{
    public function trialStarted(User $user): array
    {
        return [
            'subject' => 'Nexora ERP deneme süreciniz başladı',
            'payload' => [
                'preheader' => '14 günlük ücretsiz Nexora ERP denemeniz aktif edildi.',
                'eyebrow' => 'Ücretsiz Deneme',
                'title' => 'Nexora ERP hesabınız hazır',
                'intro' => "Merhaba {$user->name}, 14 günlük ücretsiz deneme süreciniz başladı. Satış, stok, cari, tahsilat ve raporlama adımlarını tek panelden yönetebilirsiniz.",
                'button_text' => 'Dashboard’a Git',
                'button_url' => route('dashboard'),
                'sections' => [
                    [
                        'title' => 'İlk önerilen adımlar',
                        'body' => 'İlk cari kaydınızı, ilk ürününüzü ve ilk satışınızı oluşturarak onboarding ilerlemenizi tamamlayabilirsiniz.',
                    ],
                    [
                        'title' => 'Deneme süresi',
                        'body' => 'Deneme süresi boyunca mevcut verilerinizi görüntüleyebilir, temel ERP akışlarını test edebilirsiniz.',
                    ],
                ],
                'note' => 'Deneme süresi sonunda mevcut kayıtlarınıza erişebilirsiniz; yeni kayıt eklemek için lisansınızın aktif olması gerekir.',
            ],
        ];
    }

    public function trialEnding(User $user, int $remainingDays): array
    {
        return [
            'subject' => 'Nexora ERP deneme süreniz yaklaşıyor',
            'payload' => [
                'preheader' => "Deneme sürenizin bitmesine {$remainingDays} gün kaldı.",
                'eyebrow' => 'Lisans Hatırlatma',
                'title' => 'Deneme süreniz yakında sona eriyor',
                'intro' => "Merhaba {$user->name}, Nexora ERP deneme sürenizin bitmesine {$remainingDays} gün kaldı.",
                'button_text' => 'Lisans Bilgilerini Gör',
                'button_url' => route('dashboard'),
                'sections' => [
                    [
                        'title' => 'Verileriniz korunur',
                        'body' => 'Deneme süresi sona erse bile mevcut kayıtlarınız sistemde güvenli şekilde saklanır.',
                    ],
                    [
                        'title' => 'Kesintisiz kullanım',
                        'body' => 'Lisansınızı aktif ederek yeni kayıt oluşturma, satış, tahsilat ve raporlama akışlarına devam edebilirsiniz.',
                    ],
                ],
                'note' => 'Ödeme sonrası lisansınız yıllık olarak aktif edilir.',
            ],
        ];
    }

    public function licenseActivated(User $user): array
    {
        return [
            'subject' => 'Nexora ERP lisansınız aktif edildi',
            'payload' => [
                'preheader' => 'Nexora ERP yıllık lisansınız başarıyla aktif edildi.',
                'eyebrow' => 'Lisans Aktif',
                'title' => 'Lisansınız aktif',
                'intro' => "Merhaba {$user->name}, Nexora ERP lisansınız başarıyla aktif edildi. İşletme operasyonlarınıza kesintisiz devam edebilirsiniz.",
                'button_text' => 'Panele Git',
                'button_url' => route('dashboard'),
                'sections' => [
                    [
                        'title' => 'Aktif kullanım',
                        'body' => 'Satış, stok, cari, finans, raporlar ve risk analizleri aktif kullanımınıza açıktır.',
                    ],
                    [
                        'title' => 'Destek',
                        'body' => 'Kurulum, kullanım veya süreç sorularınız için destek talebi oluşturabilirsiniz.',
                    ],
                ],
                'note' => 'Lisans başlangıç ve bitiş tarihlerinizi kullanıcı menünüzden takip edebilirsiniz.',
            ],
        ];
    }

    public function supportTicketReceived(User $user): array
    {
        return [
            'subject' => 'Nexora ERP destek talebiniz alındı',
            'payload' => [
                'preheader' => 'Destek talebiniz Nexora ekibine ulaştı.',
                'eyebrow' => 'Destek',
                'title' => 'Destek talebiniz alındı',
                'intro' => "Merhaba {$user->name}, destek talebiniz başarıyla oluşturuldu. Ekibimiz talebinizi inceleyerek dönüş sağlayacaktır.",
                'button_text' => 'Destek Taleplerini Gör',
                'button_url' => route('support-tickets.index'),
                'sections' => [
                    [
                        'title' => 'Takip',
                        'body' => 'Talebinizin durumunu Çözüm Öneri ve Destek sayfasından takip edebilirsiniz.',
                    ],
                ],
                'note' => 'Acil olmayan talepler sıraya alınarak değerlendirilir.',
            ],
        ];
    }

    public function paymentReminder(User $user, string $customerName, string $amount, string $dueDate): array
    {
        return [
            'subject' => 'Nexora ERP vade hatırlatması',
            'payload' => [
                'preheader' => "{$customerName} için {$amount} tahsilat vadesi yaklaşıyor.",
                'eyebrow' => 'Vade Hatırlatma',
                'title' => 'Tahsilat vadesi yaklaşıyor',
                'intro' => "Merhaba {$user->name}, {$customerName} için {$amount} tutarındaki tahsilatın vade tarihi {$dueDate}.",
                'button_text' => 'Risk Analizini Gör',
                'button_url' => route('risk-analysis.index'),
                'sections' => [
                    [
                        'title' => 'Önerilen aksiyon',
                        'body' => 'Cari hesap hareketlerini kontrol edip tahsilat planı oluşturmanız önerilir.',
                    ],
                ],
                'note' => 'Bu bildirim Nexora AI risk takip akışları için hazırlanmıştır.',
            ],
        ];
    }
}
