export const plans = [
    {
        name: "Starter",
        subtitle: "Yeni başlayan işletmeler",
        price: "₺550",
        period: "/Ay",
        featured: false,
        button: "Ücretsiz Denemeyi Başlat",
        routeName: "register",
        features: [
            "Satış, cari ve stok yönetimi",
            "Temel tahsilat takibi",
            "Dashboard ve temel raporlar",
            "14 gün ücretsiz deneme"
        ]
    },

    {
        name: "Professional",
        subtitle: "Büyüyen ekipler için",
        price: "₺680",
        period: "/Ay",
        featured: true,
        button: "Hemen Başlayın",
        routeName: "register",
        features: [
            "Gelişmiş satış ve teklif akışı",
            "Profesyonel PDF teklif çıktısı",
            "Kâr/Zarar ve risk analizi",
            "Nexora AI günlük işletme özeti",
            "Nexora AI vade ve stok uyarıları",
            "Gider yönetimi ve depo transferi",
            "Alt kullanıcı, rol ve yetkilendirme",
            "İşlem geçmişi ve güvenlik kayıtları",
            "Canlı dashboard ve gelişmiş raporlar",
            "Öncelikli destek"
        ]
    },

    {
        name: "Enterprise",
        subtitle: "Kurumsal işletmeler",
        price: "Teklif Al",
        period: "",
        featured: false,
        button: "İletişime Geç",
        routeName: "landing.contact",
        features: [
            "Özel kullanıcı ve süreç yapılandırması",
            "Kurumsal raporlama ihtiyaçları",
            "Öncelikli destek",
            "Eğitim ve geçiş danışmanlığı",
            "Özel entegrasyon planlaması"
        ]
    }
]
