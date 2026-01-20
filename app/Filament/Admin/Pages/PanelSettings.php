<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class PanelSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Panel Ayarları';
    protected static ?string $title = 'Panel Ayarları';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.admin.pages.panel-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        
        $this->form->fill([
            'p_logo_type' => $settings['p_logo_type'] ?? 'text',
            'p_logo_letter' => $settings['p_logo_letter'] ?? 'P',
            'p_logo_image' => $settings['p_logo_image'] ?? null,
            'brand_type' => $settings['brand_type'] ?? 'text',
            'brand_name' => $settings['brand_name'] ?? 'TOGER',
            'brand_website' => $settings['brand_website'] ?? 'toger.co',
            'brand_image' => $settings['brand_image'] ?? null,
            'sub_brand_type' => $settings['sub_brand_type'] ?? 'text',
            'sub_brand_name' => $settings['sub_brand_name'] ?? 'TPS',
            'sub_brand_description' => $settings['sub_brand_description'] ?? 'Transit Park Sistemi',
            'sub_brand_image' => $settings['sub_brand_image'] ?? null,
            'qr_url' => $settings['qr_url'] ?? 'https://toger.co',
            'qr_description' => $settings['qr_description'] ?? 'Abonelik için Toger Uygulamasını indiriniz!',
            'contact_phone' => $settings['contact_phone'] ?? '(+90) 212 909 56 76',
            'park_name' => $settings['park_name'] ?? 'Metropark Sefaköy',
            'park_logo' => $settings['park_logo'] ?? null,
            'payment_icon' => $settings['payment_icon'] ?? null,
            'hgs_icon' => $settings['hgs_icon'] ?? null,
            'ad_phone' => $settings['ad_phone'] ?? '0212 909 56 76',
            'ad_title' => $settings['ad_title'] ?? 'Bu Alana Reklam Vermek İçin Arayınız',
            'show_tariff' => ($settings['show_tariff'] ?? '1') === '1',
            'show_sub_brand' => ($settings['show_sub_brand'] ?? '1') === '1',
            'show_qr_code' => ($settings['show_qr_code'] ?? '1') === '1',
            'show_contact' => ($settings['show_contact'] ?? '1') === '1',
            'show_vehicle_rates' => ($settings['show_vehicle_rates'] ?? '1') === '1',
            'show_price_section' => ($settings['show_price_section'] ?? '1') === '1',
            'vehicle_rate_2' => $settings['vehicle_rate_2'] ?? '2 KAT',
            'vehicle_rate_3' => $settings['vehicle_rate_3'] ?? '3 KAT',
            'vehicle_rate_4' => $settings['vehicle_rate_4'] ?? '4 KAT',
            'vehicle_rate_moto' => $settings['vehicle_rate_moto'] ?? '1/2',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Ayarlar')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Sol Sidebar')
                            ->icon('heroicon-o-bars-3-bottom-left')
                            ->schema([
                                Forms\Components\Section::make('P Logosu')
                                    ->description('Sol üstteki logo - metin veya resim seçebilirsiniz')
                                    ->schema([
                                        Forms\Components\Select::make('p_logo_type')
                                            ->label('Logo Tipi')
                                            ->options([
                                                'text' => 'Metin',
                                                'image' => 'Resim',
                                            ])
                                            ->default('text')
                                            ->live()
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('p_logo_letter')
                                            ->label('Logo Harfi')
                                            ->maxLength(2)
                                            ->default('P')
                                            ->helperText('Genelde "P" harfi kullanılır')
                                            ->visible(fn (Forms\Get $get) => $get('p_logo_type') === 'text'),
                                        Forms\Components\FileUpload::make('p_logo_image')
                                            ->label('Logo Resmi')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->imagePreviewHeight('100')
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('1:1')
                                            ->imageResizeTargetWidth('200')
                                            ->imageResizeTargetHeight('200')
                                            ->helperText('Önerilen boyut: 200x200 px (kare)')
                                            ->visible(fn (Forms\Get $get) => $get('p_logo_type') === 'image'),
                                    ])
                                    ->columns(1),

                                Forms\Components\Section::make('Marka Bilgileri')
                                    ->description('Ana marka - metin veya logo seçebilirsiniz')
                                    ->schema([
                                        Forms\Components\Select::make('brand_type')
                                            ->label('Marka Tipi')
                                            ->options([
                                                'text' => 'Metin',
                                                'image' => 'Resim',
                                            ])
                                            ->default('text')
                                            ->live()
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('brand_name')
                                            ->label('Marka Adı')
                                            ->maxLength(50)
                                            ->placeholder('TOGER')
                                            ->visible(fn (Forms\Get $get) => $get('brand_type') === 'text'),
                                        Forms\Components\TextInput::make('brand_website')
                                            ->label('Web Sitesi')
                                            ->maxLength(100)
                                            ->placeholder('toger.co')
                                            ->visible(fn (Forms\Get $get) => $get('brand_type') === 'text'),
                                        Forms\Components\FileUpload::make('brand_image')
                                            ->label('Marka Logosu')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->imagePreviewHeight('100')
                                            ->imageResizeMode('contain')
                                            ->imageResizeTargetWidth('400')
                                            ->imageResizeTargetHeight('150')
                                            ->helperText('Önerilen boyut: 400x150 px (yatay)')
                                            ->visible(fn (Forms\Get $get) => $get('brand_type') === 'image')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Alt Marka')
                                    ->description('Alt marka - metin veya logo seçebilirsiniz')
                                    ->schema([
                                        Forms\Components\Toggle::make('show_sub_brand')
                                            ->label('Alt Markayı Göster')
                                            ->default(true)
                                            ->helperText('Kapatırsanız bu bölüm gizlenir')
                                            ->columnSpanFull(),
                                        Forms\Components\Select::make('sub_brand_type')
                                            ->label('Alt Marka Tipi')
                                            ->options([
                                                'text' => 'Metin',
                                                'image' => 'Resim',
                                            ])
                                            ->default('text')
                                            ->live()
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('sub_brand_name')
                                            ->label('Alt Marka Adı')
                                            ->maxLength(50)
                                            ->placeholder('TPS')
                                            ->visible(fn (Forms\Get $get) => $get('sub_brand_type') === 'text'),
                                        Forms\Components\TextInput::make('sub_brand_description')
                                            ->label('Alt Marka Açıklaması')
                                            ->maxLength(100)
                                            ->placeholder('Transit Park Sistemi')
                                            ->visible(fn (Forms\Get $get) => $get('sub_brand_type') === 'text'),
                                        Forms\Components\FileUpload::make('sub_brand_image')
                                            ->label('Alt Marka Logosu')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->imagePreviewHeight('100')
                                            ->imageResizeMode('contain')
                                            ->imageResizeTargetWidth('300')
                                            ->imageResizeTargetHeight('100')
                                            ->helperText('Önerilen boyut: 300x100 px (yatay)')
                                            ->visible(fn (Forms\Get $get) => $get('sub_brand_type') === 'image')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('QR Kod')
                                    ->description('QR kod ayarları')
                                    ->schema([
                                        Forms\Components\Toggle::make('show_qr_code')
                                            ->label('QR Kodunu Göster')
                                            ->default(true)
                                            ->helperText('Kapatırsanız QR kod bölümü gizlenir')
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('qr_url')
                                            ->label('QR URL')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://toger.co'),
                                        Forms\Components\Textarea::make('qr_description')
                                            ->label('QR Açıklaması')
                                            ->rows(2)
                                            ->maxLength(200)
                                            ->placeholder('Abonelik için Toger Uygulamasını indiriniz!')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('İletişim')
                                    ->schema([
                                        Forms\Components\Toggle::make('show_contact')
                                            ->label('İletişim Numarasını Göster')
                                            ->default(true)
                                            ->helperText('Kapatırsanız telefon numarası gizlenir'),
                                        Forms\Components\TextInput::make('contact_phone')
                                            ->label('Telefon Numarası')
                                            ->maxLength(50)
                                            ->placeholder('+90 (212) 999 99 99'),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Otopark Bilgileri')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Forms\Components\Section::make('Otopark Logo/Adı')
                                    ->description('Logo yüklenirse logo gösterilir, yoksa otopark adı metin olarak gösterilir')
                                    ->schema([
                                        Forms\Components\TextInput::make('park_name')
                                            ->label('Otopark Adı')
                                            ->required()
                                            ->maxLength(100)
                                            ->placeholder('Metropark Sefaköy')
                                            ->helperText('Logo yoksa bu isim gösterilir'),
                                        Forms\Components\FileUpload::make('park_logo')
                                            ->label('Otopark Logosu')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->imagePreviewHeight('100')
                                            ->helperText('Logo yüklenirse metin yerine logo gösterilir. Önerilen boyut: 1300x350 piksel'),
                                    ]),

                                Forms\Components\Section::make('Fiyat Sayfası Görünümü')
                                    ->description('Fiyat sayfasının tamamının veya sadece tarifenin gösterilip gösterilmeyeceğini ayarlayın')
                                    ->schema([
                                        Forms\Components\Toggle::make('show_price_section')
                                            ->label('Fiyat Sayfasını Göster')
                                            ->default(true)
                                            ->helperText('Kapatırsanız fiyat sayfasının tamamı (sidebar + tarife) gizlenir')
                                            ->columnSpanFull(),
                                        Forms\Components\Toggle::make('show_tariff')
                                            ->label('Fiyat Tarifesini Göster')
                                            ->default(true)
                                            ->helperText('Kapatırsanız sadece fiyat tarifesi gizlenir, sayfa kalır'),
                                    ]),

                                Forms\Components\Section::make('Ödeme İkonları')
                                    ->description('Footer alanında gösterilecek ikonlar')
                                    ->schema([
                                        Forms\Components\FileUpload::make('payment_icon')
                                            ->label('Temassız Ödeme İkonu')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->imagePreviewHeight('80'),
                                        Forms\Components\FileUpload::make('hgs_icon')
                                            ->label('HGS İkonu')
                                            ->image()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->visibility('public')
                                            ->imagePreviewHeight('80'),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Reklam Alanı')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                Forms\Components\Section::make('Üst Reklam Slide')
                                    ->description('Üst alandaki reklam çağrısı ayarları')
                                    ->schema([
                                        Forms\Components\TextInput::make('ad_title')
                                            ->label('Reklam Başlığı')
                                            ->maxLength(200)
                                            ->placeholder('Bu Alana Reklam Vermek İçin Arayınız'),
                                        Forms\Components\TextInput::make('ad_phone')
                                            ->label('Reklam Telefonu')
                                            ->tel()
                                            ->maxLength(50)
                                            ->placeholder('0212 909 56 76'),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Araç Sınıfı Oranları')
                            ->icon('heroicon-o-truck')
                            ->schema([
                                Forms\Components\Section::make('Oran Ayarları')
                                    ->description('Araç sınıfları için uygulanacak fiyat oranları')
                                    ->schema([
                                        Forms\Components\Toggle::make('show_vehicle_rates')
                                            ->label('Bu Slaytı Göster')
                                            ->default(true)
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('vehicle_rate_2')
                                            ->label('2. Sınıf Araç (Minibüs)')
                                            ->default('2 KAT')
                                            ->required(),
                                        Forms\Components\TextInput::make('vehicle_rate_3')
                                            ->label('3. Sınıf Araç (Otobüs)')
                                            ->default('3 KAT')
                                            ->required(),
                                        Forms\Components\TextInput::make('vehicle_rate_4')
                                            ->label('4. Sınıf Araç (Kamyon)')
                                            ->default('4 KAT')
                                            ->required(),
                                        Forms\Components\TextInput::make('vehicle_rate_moto')
                                            ->label('Motosiklet')
                                            ->default('1/2')
                                            ->required(),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            // Handle file uploads - store the path
            if (is_array($value) && !empty($value)) {
                $value = $value[0] ?? null;
            }
            
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => 'text',
                    'description' => $this->getSettingDescription($key),
                ]
            );
        }

        Notification::make()
            ->title('Ayarlar kaydedildi')
            ->success()
            ->send();
    }

    private function getSettingDescription(string $key): string
    {
        return match ($key) {
            'p_logo_letter' => 'Sol üstteki logo harfi',
            'brand_name' => 'Ana marka adı',
            'brand_website' => 'Marka web sitesi',
            'sub_brand_name' => 'Alt marka adı',
            'sub_brand_description' => 'Alt marka açıklaması',
            'qr_url' => 'QR kod URL adresi',
            'qr_description' => 'QR kod altındaki açıklama metni',
            'contact_phone' => 'İletişim telefon numarası',
            'park_name' => 'Otopark adı',
            'park_logo' => 'Otopark logosu dosya yolu',
            'payment_icon' => 'Temassız ödeme ikonu',
            'hgs_icon' => 'HGS ikonu',
            'ad_phone' => 'Reklam telefon numarası',
            'ad_title' => 'Reklam başlığı',
            'show_tariff' => 'Fiyat tarifesi gösterilsin mi',
            'show_sub_brand' => 'Alt marka gösterilsin mi',
            'show_qr_code' => 'QR kod gösterilsin mi',
            'show_contact' => 'İletişim numarası gösterilsin mi',
            'show_vehicle_rates' => 'Araç oranları slaytı gösterilsin mi',
            'show_price_section' => 'Fiyat sayfası gösterilsin mi',
            'vehicle_rate_2' => '2. Sınıf araç oranı',
            'vehicle_rate_3' => '3. Sınıf araç oranı',
            'vehicle_rate_4' => '4. Sınıf araç oranı',
            'vehicle_rate_moto' => 'Motosiklet oranı',
            'p_logo_type' => 'P logo tipi (text/image)',
            'p_logo_image' => 'P logo resmi',
            'brand_type' => 'Marka tipi (text/image)',
            'brand_image' => 'Marka logosu',
            'sub_brand_type' => 'Alt marka tipi (text/image)',
            'sub_brand_image' => 'Alt marka logosu',
            default => '',
        };
    }
}
