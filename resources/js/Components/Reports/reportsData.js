export const filters = {
    branches: [
        { value: 'all', label: 'Tüm Şubeler' },
        { value: 'center', label: 'Merkez Şube' },
        { value: 'future', label: 'Yeni Şube Hazır' },
    ],
    warehouses: [
        { value: 'all', label: 'Tüm Depolar' },
        { value: 'main', label: 'Ana Depo' },
        { value: 'showroom', label: 'Showroom Depo' },
    ],
    customers: [
        { value: 'all', label: 'Tüm Müşteriler' },
        { value: 'alpha', label: 'Alpha Trading' },
        { value: 'delta', label: 'Delta Grup' },
        { value: 'zenit', label: 'Zenit Market' },
    ],
    products: [
        { value: 'all', label: 'Tüm Ürünler' },
        { value: 'laptop', label: 'Laptop Pro 14' },
        { value: 'scanner', label: 'Barkod Okuyucu' },
        { value: 'terminal', label: 'El Terminali' },
    ],
    categories: [
        { value: 'all', label: 'Tüm Kategoriler' },
        { value: 'electronics', label: 'Elektronik' },
        { value: 'software', label: 'Yazılım' },
        { value: 'service', label: 'Servis' },
    ],
    users: [
        { value: 'all', label: 'Tüm Personel' },
        { value: 'osman', label: 'Osman Aydemir' },
        { value: 'ayse', label: 'Ayşe Demir' },
        { value: 'mert', label: 'Mert Kaya' },
    ],
    paymentTypes: [
        { value: 'all', label: 'Tüm Ödemeler' },
        { value: 'Cash', label: 'Nakit' },
        { value: 'Credit', label: 'Vadeli' },
        { value: 'Card', label: 'Kart' },
        { value: 'Bank', label: 'Havale / EFT' },
    ],
};

export const kpis = [
    { label: 'Toplam Satış', value: '₺2.845.620,00', hint: 'Seçili dönem cirosu', tone: 'blue' },
    { label: 'Toplam Tahsilat', value: '₺1.934.850,00', hint: 'Kasa ve banka girişleri', tone: 'emerald' },
    { label: 'Toplam KDV', value: '₺341.474,40', hint: 'Oluşan satış KDV', tone: 'cyan' },
    { label: 'Tahmini Brüt Kar', value: '₺684.930,00', hint: 'Maliyet sonrası tahmini kar', tone: 'green' },
    { label: 'Kar Marjı', value: '%24,07', hint: 'KDV hariç net satış', tone: 'purple' },
    { label: 'Toplam Sipariş', value: '386', hint: 'Satış belgesi adedi', tone: 'indigo' },
    { label: 'Ortalama Sipariş', value: '₺7.372,07', hint: 'Belge başı ortalama', tone: 'amber' },
    { label: 'En Çok Satılan Ürün', value: 'Laptop Pro 14', hint: '128 adet', tone: 'slate' },
];

export const chartSeries = {
    labels: ['01', '05', '10', '15', '20', '25', '30'],
    sales: [185000, 236000, 214000, 312000, 286000, 356000, 421000],
    profit: [42000, 61000, 52000, 76000, 71000, 94000, 112000],
    categoryLabels: ['Elektronik', 'Yazılım', 'Servis', 'Sarf', 'Aksesuar'],
    categorySales: [940000, 620000, 420000, 310000, 255000],
    paymentLabels: ['Vadeli', 'Kart', 'Havale', 'Nakit'],
    paymentValues: [46, 24, 20, 10],
};

export const reportCards = [
    { title: 'Risk Analizi', description: 'Nexora AI tarafından izlenen vade, stok ve operasyon riskleri.', icon: 'Activity', route: 'risk-analysis.index' },
    { title: 'Satış Raporu', description: 'Dönemsel satış, iade, iskonto ve belge performansı.', icon: 'ReceiptText' },
    { title: 'Cari Raporu', description: 'Müşteri bakiyesi, açık hesap ve tahsilat kırılımı.', icon: 'Users' },
    { title: 'Stok Hareketleri', description: 'Giriş, çıkış, transfer ve kritik stok hareketleri.', icon: 'Boxes' },
    { title: 'Kasa Hareketleri', description: 'Kasa, banka, tahsilat ve ödeme hareketleri.', icon: 'Wallet' },
    { title: 'Ürün Karlılık Analizi', description: 'Ürün bazlı maliyet, kar ve marj karşılaştırması.', icon: 'TrendingUp' },
    { title: 'En Çok Satan Ürünler', description: 'Adet ve ciro bazlı en güçlü ürün sıralaması.', icon: 'PackageCheck' },
    { title: 'En Karlı Ürünler', description: 'Brüt kar katkısı en yüksek ürünleri analiz edin.', icon: 'BadgePercent' },
    { title: 'Depo Analizi', description: 'Depo bazlı stok değeri, hareket ve performans takibi.', icon: 'Warehouse' },
    { title: 'Kategori Analizi', description: 'Kategori bazlı satış, kar ve stok dağılımı.', icon: 'Layers3' },
    { title: 'Tahsilat Analizi', description: 'Vadeler, geciken tahsilatlar ve ödeme kanalları.', icon: 'Banknote' },
];

export const tableRows = [
    { date: '11.07.2026', no: 'SAT-000186', customer: 'Alpha Trading', sales: 84600, vat: 10152, profit: 19320, payment: 'Vadeli', status: 'Tamamlandı' },
    { date: '10.07.2026', no: 'SAT-000185', customer: 'Delta Grup', sales: 38250, vat: 7650, profit: 8120, payment: 'Kart', status: 'Tamamlandı' },
    { date: '10.07.2026', no: 'SAT-000184', customer: 'Zenit Market', sales: 126400, vat: 25280, profit: 31400, payment: 'Havale', status: 'Kısmi Tahsilat' },
    { date: '09.07.2026', no: 'SAT-000183', customer: 'Nora Teknoloji', sales: 21900, vat: 4380, profit: 5120, payment: 'Nakit', status: 'Tamamlandı' },
    { date: '08.07.2026', no: 'SAT-000182', customer: 'Mavi Ofis', sales: 67200, vat: 13440, profit: 14900, payment: 'Vadeli', status: 'Açık' },
    { date: '08.07.2026', no: 'SAT-000181', customer: 'Kuzey Lojistik', sales: 44800, vat: 8960, profit: 10560, payment: 'Kart', status: 'Tamamlandı' },
    { date: '07.07.2026', no: 'SAT-000180', customer: 'Atlas Servis', sales: 93500, vat: 18700, profit: 22600, payment: 'Havale', status: 'Tamamlandı' },
    { date: '07.07.2026', no: 'SAT-000179', customer: 'Vera Endüstri', sales: 28900, vat: 5780, profit: 6900, payment: 'Vadeli', status: 'Açık' },
];

export const sidebarInsights = [
    { label: 'En Çok Kazandıran Müşteri', value: 'Alpha Trading', detail: '₺142.600 brüt kar' },
    { label: 'En Çok Satılan Ürün', value: 'Laptop Pro 14', detail: '128 adet' },
    { label: 'En Karlı Kategori', value: 'Yazılım', detail: '%38,4 kar marjı' },
    { label: 'En Yoğun Satış Günü', value: 'Çarşamba', detail: 'Dönem satışlarının %24’ü' },
    { label: 'Son 30 Gün Trendi', value: '+%18,6', detail: 'Satışlar yükselişte' },
];
