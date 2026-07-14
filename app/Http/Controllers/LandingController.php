<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class LandingController extends Controller
{
    private const SITE_URL = 'https://nexoraerp.com.tr';

    public function index()
    {
        return Inertia::render('Landing/Index');
    }

    public function features()
    {
        return $this->page('Özellikler', 'ERP süreçlerinizi hızlandıran temel Nexora özellikleri.', [
            'Akıllı Dashboard' => 'Satış, stok, tahsilat, karlılık ve kritik uyarıları tek ekranda izleyin.',
            'Nexora AI Bilgilendirme' => 'Vade gecikmeleri, kritik stoklar ve satış trendleri için doğal dilde özetler alın.',
            'Güvenli Kullanıcı İzolasyonu' => 'Her müşterinin verisi yalnızca kendi hesabında görünür.',
            'Tekliften Satışa Akış' => 'Teklif, satış, tahsilat ve raporlama süreçlerini tek hatta yönetin.',
        ], [
            'Canlı finans ve satış metrikleri',
            'Kullanıcı bazlı yetki ve veri güvenliği',
            'Profesyonel raporlama ve risk analizi',
        ]);
    }

    public function modules()
    {
        return $this->page('Modüller', 'Nexora ERP işletmenizin ana operasyonlarını tek platformda toplar.', [
            'Satış Yönetimi' => 'Satış oluşturma, teklif dönüştürme, KDV ve karlılık takibi.',
            'Cari Yönetimi' => 'Müşteri kartları, bakiye, tahsilat ve hareket geçmişi.',
            'Stok Yönetimi' => 'Depo, stok hareketi, kritik stok ve ürün maliyet takibi.',
            'Finans' => 'Kasa, tahsilat, ödeme tipi dağılımı ve finansal görünürlük.',
            'Raporlar' => 'Satış, KDV, brüt kar, grafikler ve risk analizleri.',
        ], [
            'Küçük ekiplerden büyüyen işletmelere kadar ölçeklenebilir',
            'Modüller arası veri bütünlüğü',
            'Gereksiz karmaşa olmadan yoğun ERP deneyimi',
        ]);
    }

    public function solutions()
    {
        return $this->page('Çözümler', 'Nexora, satış ve stok odaklı işletmeler için pratik ERP çözümleri sunar.', [
            'Toptan Satış' => 'Cari, teklif, satış ve tahsilat süreçlerini disipline edin.',
            'Teknik Ürün Satışı' => 'Marka, model, barkod, depo ve stok seviyelerini takip edin.',
            'Servis ve Operasyon' => 'Müşteri geçmişi, vade uyarıları ve performans analizleri ile kontrol sağlayın.',
            'Yönetim Raporlama' => 'Kar, KDV, satış trendi ve risk göstergelerini tek merkezde okuyun.',
        ], [
            'Türkiye işletme pratiklerine uygun akış',
            'Hızlı başlangıç ve 14 gün ücretsiz deneme',
            'Satış ekibi ve yönetim için okunabilir ekranlar',
        ]);
    }

    public function pricing()
    {
        return $this->page('Fiyatlandırma', 'İşletmenizin büyüklüğüne göre sade, anlaşılır ve ölçeklenebilir paketler.', [
            'Başlangıç' => 'Yeni başlayan ekipler için temel satış, cari ve stok yönetimi.',
            'Profesyonel' => 'Raporlama, risk analizi, gelişmiş finans ve ekip kullanımı.',
            'Kurumsal' => 'Daha geniş ekipler, gelişmiş destek ve özel ihtiyaçlar için yapılandırma.',
        ], [
            '14 gün ücretsiz deneme',
            'Kredi kartı gerekmeden başlangıç',
            'Deneme bitince verilerinize erişmeye devam edin, yeni kayıt ekleme lisansa bağlanır',
        ], [
            [
                'name' => 'Starter',
                'subtitle' => 'Yeni başlayan işletmeler',
                'price' => '₺550',
                'period' => '/Ay',
                'button' => 'Ücretsiz Denemeyi Başlat',
                'routeName' => 'register',
                'featured' => false,
                'features' => [
                    'Satış, cari ve stok yönetimi',
                    'Temel tahsilat takibi',
                    'Dashboard ve temel raporlar',
                    '14 gün ücretsiz deneme',
                ],
            ],
            [
                'name' => 'Professional',
                'subtitle' => 'Büyüyen ekipler için',
                'price' => '₺680',
                'period' => '/Ay',
                'button' => 'Hemen Başlayın',
                'routeName' => 'register',
                'featured' => true,
                'features' => [
                    'Gelişmiş satış ve teklif akışı',
                    'Profesyonel PDF teklif çıktısı',
                    'Kâr/Zarar ve risk analizi',
                    'Nexora AI günlük işletme özeti',
                    'Nexora AI vade ve stok uyarıları',
                    'Gider yönetimi ve depo transferi',
                    'Alt kullanıcı, rol ve yetkilendirme',
                    'İşlem geçmişi ve güvenlik kayıtları',
                    'Canlı dashboard ve gelişmiş raporlar',
                    'Öncelikli destek',
                ],
            ],
            [
                'name' => 'Enterprise',
                'subtitle' => 'Kurumsal ihtiyaçlar',
                'price' => 'Teklif Al',
                'period' => '',
                'button' => 'Satış Ekibiyle Görüş',
                'routeName' => 'landing.contact',
                'featured' => false,
                'features' => [
                    'Özel kullanıcı ve süreç yapılandırması',
                    'Kurumsal raporlama ihtiyaçları',
                    'Öncelikli destek',
                    'Eğitim ve geçiş danışmanlığı',
                    'Özel entegrasyon planlaması',
                ],
            ],
        ]);
    }

    public function about()
    {
        return $this->page('Hakkımızda', 'Nexora, işletmelerin satış, stok ve finans kontrolünü sadeleştirmek için geliştirilen modern bir ERP platformudur.', [
            'Misyonumuz' => 'KOBİ seviyesindeki işletmelere kurumsal kalitede ERP deneyimi sunmak.',
            'Yaklaşımımız' => 'Karmaşık süreçleri yoğun ama okunabilir ekranlarla yönetilebilir hale getirmek.',
            'Odak Noktamız' => 'Satış, tahsilat, stok, raporlama ve karar destek akışlarını güçlendirmek.',
        ], [
            'Modern Laravel ve Vue altyapısı',
            'Güvenli kullanıcı bazlı veri yapısı',
            'Türkiye pazarına uygun iş akışları',
        ]);
    }

    public function blog()
    {
        return $this->page('Blog', 'ERP, satış yönetimi, stok takibi ve finansal görünürlük üzerine rehber içerikler.', [
            'ERP ile Tahsilat Takibi' => 'Vade gecikmelerini erken görmek nakit akışını nasıl güçlendirir?',
            'Stokta Kritik Seviye Yönetimi' => 'Eksilen ürünleri zamanında fark ederek satış kaybını azaltma.',
            'KDV ve Karlılık Raporlama' => 'Gelir, KDV ve brüt karı doğru ayırmanın işletme kararlarına etkisi.',
        ], [
            'Yakında düzenli içerik yayını',
            'Satış ve finans ekipleri için pratik rehberler',
            'ERP kullanımını hızlandıran ipuçları',
        ]);
    }

    public function contact()
    {
        return $this->page('İletişim', 'Nexora ERP hakkında bilgi almak, deneme sürecinizi başlatmak veya destek istemek için bize ulaşın.', [
            'Satış Görüşmesi' => 'İşletmenize uygun modül ve paket yapısını birlikte netleştirelim.',
            'Ürün Desteği' => 'Kurulum, deneme ve kullanım sorularınız için destek sağlayalım.',
            'İş Ortaklığı' => 'ERP çözümlerini müşterilerinize sunmak için iletişime geçin.',
        ], [
            'Adres: İstanbul / Zincirlikuyu',
            'Ücretsiz deneme için kayıt formunu kullanabilirsiniz',
        ]);
    }

    public function faq()
    {
        return $this->page('SSS', 'Nexora ERP hakkında en sık sorulan sorular.', [
            'Ücretsiz deneme kaç gün?' => 'Yeni kayıt olan kullanıcılar için 14 gün ücretsiz deneme başlar.',
            'Deneme bitince veriler silinir mi?' => 'Hayır. Verilerinize erişebilirsiniz, yeni kayıt ekleme lisansa bağlanır.',
            'Her kullanıcı kendi verisini mi görür?' => 'Evet. Müşteri, ürün, satış, rapor ve risk analizleri kullanıcı bazlı ayrılır.',
            'Kurulum zor mu?' => 'Hayır. Kayıt sonrası dashboard üzerinden temel görevlerle hızlıca başlayabilirsiniz.',
        ], [
            'Kredi kartı gerekmeden deneme',
            'Kullanıcı bazlı güvenlik',
            'Satışa hazır ERP akışları',
        ]);
    }

    public function documentation()
    {
        return $this->page('Dokümantasyon', 'Nexora ERP kullanımını hızlandıran ürün dokümantasyonu.', [
            'Başlangıç' => 'Cari, ürün, satış ve tahsilat oluşturma adımlarını öğrenin.',
            'Raporlar' => 'KDV, brüt kar, satış grafikleri ve risk analizlerini yorumlayın.',
            'Yetkilendirme' => 'Kullanıcı rolleri ve veri güvenliği yaklaşımını inceleyin.',
        ], [
            'Adım adım kullanım rehberleri',
            'Modül bazlı açıklamalar',
            'Yakında ekran görüntülü kılavuzlar',
        ]);
    }

    public function support()
    {
        return $this->page('Destek Merkezi', 'Deneme, kurulum ve kullanım süreçlerinde hızlı yardım alın.', [
            'Başlangıç Desteği' => 'İlk kurulum ve temel verilerinizi oluşturma sürecinde rehberlik.',
            'Kullanım Soruları' => 'Satış, stok, cari ve rapor ekranları için pratik destek.',
            'Hesap ve Lisans' => 'Deneme süresi, lisans başlangıç ve bitiş tarihleri hakkında yardım.',
        ], [
            'Öncelikli destek kanalları yakında',
            'SSS ve dokümantasyonla hızlı çözüm',
            'İşletme süreçlerinize uygun yönlendirme',
        ]);
    }

    public function kvkk()
    {
        return $this->legalDocument('KVKK Aydınlatma Metni', 'Nexora ERP hizmetlerinin sunulması sırasında kişisel verilerin hangi amaçlarla işlendiğini şeffaf biçimde açıklarız.', [
            'Veri sorumlusu ve kapsam' => 'Nexora ERP platformunu kullanan ziyaretçi, deneme kullanıcısı, lisanslı müşteri ve alt kullanıcıların hesap, iletişim, şirket, işlem ve kullanım verileri bu metnin kapsamındadır. Kişisel veriler, ürünün çalışması, müşteri ilişkilerinin yürütülmesi ve yasal yükümlülüklerin yerine getirilmesi için işlenir.',
            'İşlenen veri kategorileri' => 'Ad soyad, e-posta, telefon, şirket adı, kullanıcı rolü, lisans başlangıç ve bitiş bilgileri, oturum ve güvenlik kayıtları, destek talepleri, işlem geçmişi ve uygulama içi kullanım verileri işlenebilir. ERP içinde oluşturulan cari, ürün, satış, tahsilat ve rapor verileri ise ilgili müşterinin kendi hesabına ait iş verisi olarak saklanır.',
            'İşleme amaçları' => 'Hesap oluşturma, kimlik doğrulama, deneme ve lisans süreçlerini yönetme, müşteri desteği sağlama, veri güvenliğini koruma, yetkilendirme yapmak, ürün performansını ölçmek ve hizmet kalitesini artırmak amacıyla veri işlenir.',
            'Aktarım ve saklama' => 'Kişisel veriler, hizmetin çalışması için gerekli teknik altyapı sağlayıcıları ve mevzuat gereği yetkili kurumlarla sınırlı olarak paylaşılabilir. Veriler, işleme amacı ortadan kalkana veya kanuni saklama süresi sona erene kadar güvenli şekilde saklanır.',
            'Haklarınız' => 'KVKK kapsamındaki erişim, düzeltme, silme, işleme itiraz ve bilgi talebi haklarınız için Nexora iletişim kanallarından bize ulaşabilirsiniz. Başvurular makul süre içinde değerlendirilir.',
        ]);
    }

    public function privacy()
    {
        return $this->legalDocument('Gizlilik Politikası', 'Nexora ERP’de işletme verilerinizin gizliliği, ürünün temel güvenlik prensiplerinden biridir.', [
            'Gizlilik yaklaşımı' => 'Nexora, müşteri verilerini kullanıcı bazlı ayrıştırır. Bir müşterinin cari, ürün, satış, tahsilat, rapor ve destek verileri başka müşteriler tarafından görüntülenemez.',
            'Hesap ve kullanım verileri' => 'Kayıt formunda ilettiğiniz bilgiler, oturum kayıtları, lisans durumu, destek talepleri ve sistem içi işlem geçmişi; hesabınızı yönetmek, güvenliği sağlamak ve ürün deneyimini geliştirmek için kullanılır.',
            'ERP iş verileri' => 'Cari hesaplar, satışlar, teklifler, stok hareketleri, kasa kayıtları, giderler ve rapor çıktıları müşterinin kendi iş süreçlerini yürütmesi için tutulur. Bu veriler pazarlama amacıyla üçüncü kişilerle paylaşılmaz.',
            'Güvenlik önlemleri' => 'Yetkilendirme, kullanıcı izolasyonu, işlem geçmişi, lisans kontrolü ve erişim sınırlamaları gibi teknik ve idari önlemler uygulanır. Kullanıcıların güçlü şifre kullanması ve yetkileri düzenli kontrol etmesi önerilir.',
            'Politika değişiklikleri' => 'Nexora, ürün ve mevzuat değişikliklerine göre bu politikayı güncelleyebilir. Güncel metin web sitesi üzerinden yayımlanır.',
        ]);
    }

    public function cookies()
    {
        return $this->legalDocument('Çerez Politikası', 'Nexora web sitesi ve uygulama deneyimini güvenli, hızlı ve kullanıcı dostu hale getirmek için çerezlerden yararlanabilir.', [
            '1. Giriş' => 'Nexora ERP web sitesi ve uygulamasının düzgün çalışmasını sağlamak, ziyaretçilerimize ve kullanıcılarımıza güvenli ve alakalı bir deneyim sunmak amacıyla sınırlı çerezler kullanabiliriz. Bu Çerez Politikası, çerezlerin ne olduğu, hangi amaçlarla kullanıldığı ve nasıl yönetilebileceği konusunda bilgi vermek için hazırlanmıştır.',
            '2. Çerez nedir?' => 'Çerezler, internet sitemizi veya uygulamamızı ziyaret ettiğinizde tarayıcınıza ya da cihazınıza kaydedilen küçük metin dosyalarıdır. Web işaretçileri, piksel veya benzeri takip teknolojileri de bu politika kapsamında çerez olarak değerlendirilebilir.',
            '3. Çerezlerin kullanım amaçları' => "Çerezler; sitenin güvenli çalışmasını sağlamak, oturumunuzu korumak, form güvenliğini desteklemek, sayfalar arasında daha iyi gezinme sağlamak, tercihlerinizi hatırlamak, performansı ölçmek, kullanıcı deneyimini iyileştirmek ve ürün geliştirme süreçlerine toplulaştırılmış içgörü sağlamak amacıyla kullanılabilir.\n\nNexora ERP içinde zorunlu oturum ve güvenlik çerezleri, uygulamanın çalışması için gereklidir. Performans ve analiz çerezleri ise hizmet kalitesini artırmaya yardımcı olur.",
            '4. Çerezlerin genel özellikleri' => 'Oturum çerezleri tarayıcınızı kapattığınızda sona erebilir. Kalıcı çerezler ise tarayıcı ayarlarınıza ve ilgili çerezin saklama süresine bağlı olarak cihazınızda daha uzun süre kalabilir. Çerezlerin saklama süresi, kullanım amacına göre değişebilir.',
            '5. Kullanılan çerez türleri' => "Zorunlu çerezler: Giriş yapma, güvenlik, lisans oturumu, form doğrulama ve temel uygulama fonksiyonları için kullanılır.\n\nTercih ve işlevsellik çerezleri: Tema, dil, oturum tercihleri ve kullanıcı deneyimini kolaylaştıran ayarları hatırlayabilir.\n\nAnalitik çerezler: Sayfaların nasıl kullanıldığını toplulaştırılmış şekilde anlamamıza ve ürün deneyimini geliştirmemize yardımcı olur.\n\nPazarlama çerezleri: Nexora ileride reklam ve kampanya performansını ölçmek için üçüncü taraf araçlar kullanırsa, bu tür çerezler açık rıza veya geçerli mevzuat koşullarına uygun şekilde yönetilir.",
            '6. Üçüncü taraf çerezler' => 'Bazı durumlarda analiz, güvenlik, iletişim veya altyapı sağlayıcıları tarafından üçüncü taraf çerezler kullanılabilir. Üçüncü taraf çerezlerin kapsamı ilgili sağlayıcının politikalarına göre değişebilir. Nexora bu kullanımları hizmetin güvenliği ve performansı için makul sınırlar içinde tutmayı hedefler.',
            '7. Çerezler nasıl yönetilir?' => 'Çerez tercihlerinizi tarayıcı ayarlarınız üzerinden değiştirebilir, çerezleri silebilir veya belirli çerezleri engelleyebilirsiniz. Chrome, Firefox, Safari, Edge ve Opera gibi tarayıcıların yardım sayfalarında çerezleri etkinleştirme, devre dışı bırakma ve silme adımları yer alır. Çerezleri tamamen kapatmanız halinde Nexora web sitesi veya uygulamasındaki bazı fonksiyonlar sınırlı çalışabilir.',
            '8. Bize ulaşın' => 'Çerez Politikası veya Nexora ERP’deki çerez kullanımı hakkında soru, görüş ve talepleriniz için iletişim sayfamız üzerinden bize ulaşabilirsiniz. Nexora, mevzuat veya ürün değişikliklerine göre bu politikayı güncelleyebilir; güncel metin web sitesi üzerinden yayımlanır.',
        ]);
    }

    public function terms()
    {
        return $this->legalDocument('Kullanıcı Sözleşmesi', 'Nexora ERP hesabı oluşturan kullanıcıların hizmetten yararlanırken uyması gereken temel koşulları açıklar.', [
            'Taraflar ve kabul' => 'Bu sözleşme, Nexora ERP hizmetlerini kullanan müşteri veya alt kullanıcı ile Nexora arasında geçerlidir. Hesap oluşturmanız, deneme sürecini başlatmanız veya uygulamaya giriş yapmanız bu koşulları kabul ettiğiniz anlamına gelir.',
            'Hizmet kapsamı' => 'Nexora ERP; cari, ürün, teklif, satış, tahsilat, stok, kasa, gider, rapor, risk analizi, destek talebi ve Nexora AI destekli bilgilendirme özellikleri sunar. Bazı özellikler lisans durumuna, kullanıcı rolüne veya teknik geliştirme takvimine bağlı olarak değişebilir.',
            'Deneme ve lisans' => 'Yeni kayıt olan kullanıcılar için 14 günlük ücretsiz deneme başlatılabilir. Deneme süresi sonunda kullanıcı kendi verilerine erişmeye devam edebilir; ancak yeni kayıt oluşturma, güncelleme veya bazı ileri işlemler aktif lisans gerektirebilir.',
            'Kullanıcı yükümlülükleri' => 'Kullanıcı; doğru kayıt bilgisi vermek, hesap güvenliğini korumak, yetkisiz erişim sağlamamak, başkasına ait verileri işlememek ve sistemi hukuka aykırı amaçlarla kullanmamakla yükümlüdür.',
            'Veri sorumluluğu' => 'ERP’ye girilen cari, ürün, satış, tahsilat ve benzeri iş kayıtlarının doğruluğu kullanıcıya aittir. Nexora, yazılım altyapısını sağlar; kullanıcı tarafından girilen ticari verilerin hukuki ve mali doğruluğunu garanti etmez.',
            'Nexora AI kullanımı' => 'Nexora AI, sistemdeki verilere göre özet, uyarı ve öneri üretir. Bu çıktılar karar destek niteliğindedir; muhasebe, vergi, hukuk veya finans danışmanlığı yerine geçmez. Nihai karar ve kontrol kullanıcıya aittir.',
            'Hizmet değişiklikleri' => 'Nexora, güvenlik, performans, mevzuat veya ürün geliştirme gerekçeleriyle hizmette değişiklik yapabilir. Kritik değişiklikler makul yöntemlerle kullanıcılara duyurulur.',
            'Sorumluluk sınırı' => 'Nexora, hizmetin kesintisiz ve hatasız çalışması için makul teknik önlemleri alır. İnternet kesintisi, üçüncü taraf servisler, kullanıcı hatası veya mücbir sebeplerden doğan sonuçlardan sorumlu tutulamaz.',
            'Fesih' => 'Kullanıcı hesabını kapatma talebinde bulunabilir. Nexora, kötüye kullanım, güvenlik riski veya sözleşmeye aykırılık halinde erişimi sınırlandırabilir veya sonlandırabilir.',
        ], [
            '14 gün ücretsiz deneme koşulları',
            'Nexora AI karar destek niteliği',
            'Kullanıcı bazlı veri güvenliği',
        ]);
    }

    public function explicitConsent()
    {
        return $this->legalDocument('Açık Rıza Metni', 'Belirli veri işleme faaliyetleri için özgür iradenizle onay verebilmeniz amacıyla hazırlanmıştır.', [
            '1. Açık rızanın konusu' => 'Nexora ERP’ye kayıt olurken veya iletişim kanallarımızı kullanırken paylaştığım kişisel verilerimin; kullanıcı kayıtlarımın güvenle saklanması, müşteri ilişkileri süreçlerinin yürütülmesi, destek hizmetlerinin sağlanması, ürün deneyiminin iyileştirilmesi ve bana uygun bilgilendirme yapılması amacıyla işlenmesine açık rıza verebilirim.',
            '2. İşlenebilecek veriler' => 'Ad soyad, e-posta, telefon, şirket adı, hesap ve lisans bilgileri, destek talepleri, uygulama kullanım tercihleri, işlem geçmişi ve Nexora ERP içinde oluşturduğum iş süreçlerine ilişkin özet veriler bu kapsamda değerlendirilebilir.',
            '3. İletişim ve bilgilendirme' => 'Nexora tarafından ürün duyuruları, kampanya bilgileri, eğitim içerikleri, kullanım önerileri ve lisans hatırlatmaları için e-posta, telefon veya dijital kanallar üzerinden benimle iletişime geçilmesine onay verebilirim. Bu rıza, temel hizmetten yararlanmak için zorunlu değildir.',
            '4. Nexora AI destekli analiz' => 'Nexora AI tarafından daha anlamlı öneri, risk uyarısı ve rapor özeti üretilebilmesi için cari, satış, tahsilat, stok, gider ve rapor özetlerimin kullanıcı hesabım kapsamında analiz edilmesini kabul edebilirim. Nexora AI çıktılarının karar destek niteliğinde olduğunu; muhasebe, vergi, hukuk veya finans danışmanlığı yerine geçmediğini bilirim.',
            '5. Ürün geliştirme ve güvenlik' => 'Kullanım alışkanlıklarımın, geri bildirimlerimin ve destek taleplerimin toplulaştırılmış şekilde analiz edilerek Nexora ERP deneyiminin iyileştirilmesi, güvenlik ve performans çalışmalarının yürütülmesi için kullanılmasına rıza verebilirim.',
            '6. Rızanın geri alınması' => 'Açık rızamı dilediğim zaman geri alabileceğimi, geri alma talebinin geleceğe yönelik sonuç doğuracağını ve kanuni yükümlülükler kapsamında işlenen verilerin bu kapsam dışında değerlendirileceğini bilirim. Taleplerim için Nexora iletişim kanallarını kullanabilirim.',
        ], [
            'Özgür iradeyle onay',
            'Geri alınabilir rıza',
            'Ürün ve destek deneyimini iyileştirme',
        ]);
    }

    private function page(string $title, string $description, array $sections, array $highlights, array $pricingPlans = [])
    {
        return Inertia::render('Landing/Page', [
            'page' => [
                'title' => $title,
                'description' => $description,
                'sections' => collect($sections)
                    ->map(fn (string $body, string $heading) => compact('heading', 'body'))
                    ->values(),
                'highlights' => $highlights,
                'pricingPlans' => $pricingPlans,
                'type' => 'marketing',
                'canonical' => $this->canonical(),
                'robots' => 'index, follow',
            ],
        ]);
    }

    private function legal(string $title, string $description, array $paragraphs)
    {
        return Inertia::render('Landing/Page', [
            'page' => [
                'title' => $title,
                'description' => $description,
                'sections' => collect($paragraphs)
                    ->map(fn (string $body, int $index) => [
                        'heading' => 'Madde ' . ($index + 1),
                        'body' => $body,
                    ])
                    ->values(),
                'highlights' => [
                    'Şeffaf veri işleme yaklaşımı',
                    'Kullanıcı bazlı veri ayrımı',
                    'Güvenli erişim ve hesap yönetimi',
                ],
                'type' => 'legal',
                'canonical' => $this->canonical(),
                'robots' => 'index, follow',
            ],
        ]);
    }

    private function legalDocument(string $title, string $description, array $sections, ?array $highlights = null)
    {
        return Inertia::render('Landing/Page', [
            'page' => [
                'title' => $title,
                'description' => $description,
                'sections' => collect($sections)
                    ->map(fn (string $body, string $heading) => compact('heading', 'body'))
                    ->values(),
                'highlights' => $highlights ?? [
                    'Şeffaf ve anlaşılır metinler',
                    'Kullanıcı bazlı veri güvenliği',
                    'Nexora ERP’ye özel koşullar',
                ],
                'type' => 'legal',
                'canonical' => $this->canonical(),
                'robots' => 'index, follow',
            ],
        ]);
    }

    private function canonical(): string
    {
        $path = trim(request()->path(), '/');

        return $path === ''
            ? self::SITE_URL
            : self::SITE_URL . '/' . $path;
    }
}
