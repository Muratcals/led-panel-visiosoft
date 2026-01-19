# 🎯 Laravel LED Panel Yönetim Sistemi

55 inç 1080p dikey LED ekranlar için Laravel & Filament tabanlı modern yönetim sistemi.

## ✨ Özellikler

### 📹 Video Yönetimi
- Video dosyalarını yükle ve yönet
- Üst alan ve alt slider için ayrı video konumlandırma
- Otomatik süre takibi
- Sıralama ve aktif/pasif durumu

### 💰 Fiyat Tarifesi Yönetimi
- Park ücretlerini dinamik olarak yönet
- Ücretsiz, vurgulu ve özel tarifeleri işaretle
- Sıralama ve gösterim kontrolü

### 📢 Reklam Slaytları
- Özelleştirilebilir reklam içeriği
- Renk seçimi ve ikon desteği
- Telefon numarası ve alt başlık
- Süre kontrolü

### ⚙️ Sistem Ayarları
- Key-value tabanlı ayar sistemi
- Görüntüleme süreleri
- Tema renkleri
- Genel konfigürasyon

## 🚀 Kurulum

### Gereksinimler
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL

### Adımlar

1. **Bağımlılıkları yükle**
```bash
composer install
npm install
```

2. **Environment ayarları**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Veritabanı**
```bash
php artisan migrate
```

4. **Storage link**
```bash
php artisan storage:link
```

5. **Admin kullanıcısı oluştur**
```bash
php artisan make:filament-user
```

6. **Development sunucusu**
```bash
php artisan serve
```

Admin panel: `http://localhost:8000/admin`

## 📁 Proje Yapısı

```
app/
├── Filament/
│   └── Resources/      # Admin panel CRUD işlemleri
│       ├── VideoResource.php
│       ├── PriceTariffResource.php
│       ├── AdSlideResource.php
│       └── SettingResource.php
├── Models/             # Eloquent modeller
│   ├── Video.php
│   ├── PriceTariff.php
│   ├── AdSlide.php
│   └── Setting.php
database/
└── migrations/         # Veritabanı şemaları
```

## 🎨 Admin Panel Özellikleri

### Video Resource
- Dosya yükleme (MP4, WebM, OGG)
- Maksimum 100MB
- Konum seçimi (Üst/Alt)
- Süre ve sıralama kontrolü

### Fiyat Tarifesi Resource
- Zaman aralığı tanımı
- TRY formatında ücret
- Ücretsiz/Vurgulu işaretleme
- Sıralama sistemi

### Reklam Slaytı Resource
- Başlık ve alt başlık
- Telefon numarası
- Emoji/İkon desteği
- Özel renk seçimi
- Konum ve süre kontrolü

## 🔧 Konfigürasyon

### Dosya Yükleme
Video dosyaları `storage/app/public/videos` dizininde saklanır.

### Veritabanı
Varsayılan olarak SQLite kullanılır. MySQL veya PostgreSQL için `.env` dosyasını düzenleyin:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_led_panel
DB_USERNAME=root
DB_PASSWORD=
```

## 📱 Frontend Display (Planlanan)

LED panel görüntüsü için public endpoint:
```
/display
```

API endpoint:
```
GET /api/display
```

## 🛠️ Teknoloji Stack

- **Framework**: Laravel 11
- **Admin Panel**: Filament 3
- **Database**: SQLite/MySQL/PostgreSQL
- **Frontend**: Blade + Tailwind CSS
- **File Storage**: Laravel Storage
- **Authentication**: Laravel Breeze (Filament ile)

## 📝 Geliştirme

### Model Oluşturma
```bash
php artisan make:model ModelName -m
```

### Filament Resource Oluşturma
```bash
php artisan make:filament-resource ResourceName --generate
```

### Migration Çalıştırma
```bash
php artisan migrate
```

## 🔐 Güvenlik

- Admin panel Filament authentication ile korunur
- File upload validation
- CSRF koruması
- SQL injection koruması (Eloquent ORM)

## 📄 Lisans

Bu proje özel bir proje olup, ticari kullanım için tasarlanmıştır.

---

**Not**: Bu sistem 55 inç 1080p dikey (portrait) LED ekranlar için optimize edilmiştir.

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
