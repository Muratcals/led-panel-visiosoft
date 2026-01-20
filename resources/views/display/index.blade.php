<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="55 inç TV LED Panel - Otopark Fiyat Tarifesi">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="data-hash" content="{{ $dataHash ?? '' }}">
    <title>55" Dikey TV - Reklam & Video</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <style>
        /* 55 inç TV için özel optimizasyon */
        @media screen and (min-width: 1000px) and (min-height: 1500px) {
            body {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Üst Video Alanı (1/3) - Slider -->
    <div class="video-section" id="topSliderContainer">
        <div id="rotateBtn" style="position: absolute; top: 10px; right: 10px; z-index: 9999; background: rgba(0,0,0,0.5); color: white; padding: 10px; border-radius: 5px; cursor: pointer; font-size: 24px;">
            ⟳
        </div>
        @php
            $topAds = $adSlides->where('position', 0); // veya 'top'
            $bottomAds = $adSlides->where('position', 1); // veya 'bottom'
            
            $topSlideIndex = 0;
            $hasTopContent = $topVideos->count() > 0 || $topAds->count() > 0;
        @endphp
        
        @if($hasTopContent)
            {{-- Üst alandaki videolar --}}
            @foreach($topVideos as $video)
                <div class="top-slide {{ $topSlideIndex === 0 ? 'active' : '' }}" data-duration="{{ $video->duration }}">
                    <video autoplay muted loop playsinline preload="auto" data-video-id="{{ $video->id }}">
                        @if($video->file_path)
                            <source src="{{ asset('storage/' . $video->file_path) }}" type="video/mp4">
                        @endif
                        Tarayıcınız video etiketini desteklemiyor.
                    </video>
                </div>
                @php $topSlideIndex++; @endphp
            @endforeach

            {{-- Reklam slaytları (Sadece ÜST) --}}
            @foreach($topAds as $slide)
                <div class="top-slide {{ $topSlideIndex === 0 ? 'active' : '' }} {{ $slide->media_type === 'text' ? 'ad-call-slide' : '' }}" 
                     data-duration="{{ $slide->duration }}"
                     @if($slide->media_type === 'text')
                        style="background-color: {{ $slide->background_color }};"
                     @endif>
                    
                    @if($slide->media_type === 'text')
                        {{-- Metin Tipi Reklam --}}
                        <div class="ad-call-content">
                            @if($slide->icon)
                                <div class="ad-icon">{{ $slide->icon }}</div>
                            @endif
                            <h2>{{ $slide->title }}</h2>
                            @if($slide->subtitle)
                                <p class="ad-subtitle">{{ $slide->subtitle }}</p>
                            @endif
                            @if($slide->phone_number)
                                <div class="phone-number">
                                    <span class="phone-icon">📞</span>
                                    <span class="number">{{ $slide->phone_number }}</span>
                                </div>
                            @endif
                        </div>
                    @elseif($slide->media_type === 'image')
                        {{-- Görsel Tipi Reklam --}}
                        <img src="{{ asset('storage/' . $slide->media_path) }}" 
                             alt="{{ $slide->title }}" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @elseif($slide->media_type === 'video')
                        {{-- Video Tipi Reklam --}}
                        <video autoplay muted loop playsinline preload="auto" style="width: 100%; height: 100%; object-fit: cover;">
                            <source src="{{ asset('storage/' . $slide->media_path) }}" type="video/mp4">
                        </video>
                    @endif
                </div>
                @php $topSlideIndex++; @endphp
            @endforeach
        @else
            {{-- Varsayılan: Hiç içerik yoksa reklam çağrısı göster --}}
            <div class="top-slide active ad-call-slide" style="background-color: #0055ff;">
                <div class="ad-call-content">
                    <div class="ad-icon">📢</div>
                    <h2>Bu Alana Reklam Vermek İçin Arayınız</h2>
                    <div class="phone-number">
                        <span class="phone-icon">📞</span>
                        <span class="number">{{ $settings['contact_phone'] ?? '0212 000 00 00' }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Alt Fiyat Tarifesi Alanı (2/3) -->
    <div class="price-section" id="sliderContainer">

        @php
            $showPriceSection = ($settings['show_price_section'] ?? '1') === '1' || ($settings['show_price_section'] ?? true) === true;
        @endphp

        @if($showPriceSection)
        <!-- Page 1: Fiyat Tarifesi & Sidebar -->
        <div class="slide content-slide active" data-duration="20">
            <!-- Sol Mavi Banner -->
            <div class="sidebar">
                <div class="p-logo">
                    @if(($settings['p_logo_type'] ?? 'text') === 'image' && !empty($settings['p_logo_image']))
                        <img src="{{ asset('storage/' . $settings['p_logo_image']) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <span>{{ $settings['p_logo_letter'] ?? 'P' }}</span>
                    @endif
                </div>
                <div class="brand">
                    @if(($settings['brand_type'] ?? 'image') === 'image')
                        <div class="brand-logo-container" style="height: 65px; display: flex; align-items: center; justify-content: center;">
                            @if(!empty($settings['brand_image']))
                                <img src="{{ asset('storage/' . $settings['brand_image']) }}" alt="Marka" style="max-height: 65px; max-width: 100%; width: auto; object-fit: contain;">
                            @else
                                <img src="{{ asset('images/toger.png') }}" alt="Marka" style="max-height: 65px; max-width: 100%; width: auto; object-fit: contain;">
                            @endif
                        </div>
                    @else
                        <h1>{{ $settings['brand_name'] ?? 'TOGER' }}</h1>
                        <p>{{ $settings['brand_website'] ?? 'toger.co' }}</p>
                    @endif
                </div>
                
                @php
                    $showSubBrand = ($settings['show_sub_brand'] ?? '1') === '1' || ($settings['show_sub_brand'] ?? true) === true;
                    $showQrCode = ($settings['show_qr_code'] ?? '1') === '1' || ($settings['show_qr_code'] ?? true) === true;
                    $showContact = ($settings['show_contact'] ?? '1') === '1' || ($settings['show_contact'] ?? true) === true;
                @endphp

                @if($showSubBrand)
                <div class="sub-brand">
                    @if(($settings['sub_brand_type'] ?? 'image') === 'image')
                        <div class="sub-brand-logo-container" style="height: 65px; display: flex; align-items: center; justify-content: center;">
                            @if(!empty($settings['sub_brand_image']))
                                <img src="{{ asset('storage/' . $settings['sub_brand_image']) }}" alt="Alt Marka" style="max-height: 65px; max-width: 100%; width: auto; object-fit: contain;">
                            @else
                                <img src="{{ asset('images/tps-logo.png') }}" alt="TPS" style="max-height: 65px; max-width: 100%; width: auto; object-fit: contain;">
                            @endif
                        </div>
                    @else
                        <h2>{{ $settings['sub_brand_name'] ?? 'TPS' }}</h2>
                        <p>{{ $settings['sub_brand_description'] ?? 'Transit Park Sistemi' }}</p>
                    @endif
                </div>
                @endif

                @if($showQrCode)
                <!-- QR Alanı -->
                <div class="qr-area">
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($settings['qr_url'] ?? 'https://toger.co') }}"
                            alt="QR Code">
                    </div>
                    <p>{!! nl2br(e($settings['qr_description'] ?? 'Abonelik için Toger Uygulamasını indiriniz!')) !!}</p>
                </div>
                @endif

                @if($showContact)
                <div class="contact">
                    <p>{{ $settings['contact_phone'] ?? '(+90) 212 909 56 76' }}</p>
                </div>
                @endif
            </div>

            <!-- Sağ Fiyat Listesi -->
            <div class="tariff-content">
                <div class="header">
                    @if(!empty($settings['park_logo']))
                        <div class="park-logo-container" style="height: 65px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/' . $settings['park_logo']) }}" alt="{{ $settings['park_name'] ?? 'Otopark' }}" style="max-height: 65px; max-width: 100%; width: auto; object-fit: contain;">
                        </div>
                    @else
                        <div class="park-name-text">
                            <h1>{{ $settings['park_name'] ?? 'Otopark' }}</h1>
                        </div>
                    @endif
                </div>


                <div class="price-list">
                    @php
                        $showTariff = ($settings['show_tariff'] ?? '1') === '1' || ($settings['show_tariff'] ?? true) === true;
                    @endphp
                    
                    @if($showTariff)
                        @if($priceTariffs->count() > 0)
                            @foreach($priceTariffs as $tariff)
                                <div class="price-row {{ $tariff->is_highlighted ? 'highlight' : '' }}">
                                    <span class="time">{{ $tariff->time_range }}</span>
                                    <span class="price">
                                        @if($tariff->is_free)
                                            ÜCRETSİZ
                                        @else
                                            {{ number_format($tariff->price, 0, ',', '.') }} ₺
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        @else
                            {{-- Varsayılan fiyat tarifesi --}}
                            <div class="price-row highlight">
                                <span class="time">0-1 Saat</span>
                                <span class="price">ÜCRETSİZ</span>
                            </div>
                            <div class="price-row">
                                <span class="time">1-2 Saat</span>
                                <span class="price">- ₺</span>
                            </div>
                            <div class="price-row">
                                <span class="time">2-4 Saat</span>
                                <span class="price">- ₺</span>
                            </div>
                            <div class="price-row">
                                <span class="time">4-8 Saat</span>
                                <span class="price">- ₺</span>
                            </div>
                            <div class="price-row">
                                <span class="time">TAM GÜN</span>
                                <span class="price">- ₺</span>
                            </div>
                        @endif
                    @else
                        {{-- Fiyat tarifesi gizli --}}
                        <div class="tariff-hidden-info">
                            <p>Fiyat bilgisi için lütfen danışma noktasına başvurunuz.</p>
                        </div>
                    @endif
                </div>

                @if(($settings['show_vehicle_rates'] ?? '1') === '1')
                <!-- Araç Sınıfı Oranları Footer -->
                <div class="vehicle-rates-footer">
                    <div class="rate-content-horizontal">
                        <!-- 2. Sınıf -->
                        <div class="rate-item-h">
                            <div class="rate-icon-h">🚌</div>
                            <div class="rate-info-h">
                                <h3>2. SINIF</h3>
                                <span>{{ $settings['vehicle_rate_2'] ?? '2 KAT' }}</span>
                            </div>
                        </div>
                        <!-- 3. Sınıf -->
                        <div class="rate-item-h">
                            <div class="rate-icon-h">🚍</div>
                            <div class="rate-info-h">
                                <h3>3. SINIF</h3>
                                <span>{{ $settings['vehicle_rate_3'] ?? '3 KAT' }}</span>
                            </div>
                        </div>
                        <!-- 4. Sınıf -->
                        <div class="rate-item-h">
                            <div class="rate-icon-h">🚛</div>
                            <div class="rate-info-h">
                                <h3>4. SINIF</h3>
                                <span>{{ $settings['vehicle_rate_4'] ?? '4 KAT' }}</span>
                            </div>
                        </div>
                        <!-- Motosiklet -->
                        <div class="rate-item-h">
                            <div class="rate-icon-h">🏍️</div>
                            <div class="rate-info-h">
                                <h3>MOTOR</h3>
                                <span>{{ $settings['vehicle_rate_moto'] ?? '1/2' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="rate-text-bottom">
                         <span>ORANINDA ÜCRET UYGULANACAKTIR</span>
                    </div>
                </div>
                @endif

                <div class="footer">
                    <div class="footer-logo">
                        @if(!empty($settings['payment_icon']))
                            <img src="{{ asset('storage/' . $settings['payment_icon']) }}" alt="Temassız Ödeme" class="pos-icon">
                        @else
                            <img src="{{ asset('images/temassiz-icon.jpg') }}" alt="Temassız Ödeme" class="pos-icon">
                        @endif
                        @if(!empty($settings['hgs_icon']))
                            <img src="{{ asset('storage/' . $settings['hgs_icon']) }}" alt="HGS Park" class="hgs-icon">
                        @else
                            <img src="{{ asset('images/hgslogoikon.png') }}" alt="HGS Park" class="hgs-icon">
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif



        {{-- Alt Reklam Slaytları (ALT) --}}
        @foreach($bottomAds as $slide)
            <div class="slide bottom-ad-slide" data-duration="{{ $slide->duration }}" style="background-color: {{ $slide->background_color ?? '#000' }};">
                @if($slide->media_type === 'image')
                    <img src="{{ asset('storage/' . $slide->media_path) }}" 
                         alt="{{ $slide->title }}" 
                         class="contain-media">
                @elseif($slide->media_type === 'video')
                    <video autoplay muted loop playsinline preload="auto" class="contain-media">
                        <source src="{{ asset('storage/' . $slide->media_path) }}" type="video/mp4">
                    </video>
                @elseif($slide->media_type === 'text')
                     {{-- Metin Tipi Reklam (Alt) --}}
                     <div class="ad-call-content">
                        @if($slide->icon)
                            <div class="ad-icon">{{ $slide->icon }}</div>
                        @endif
                        <h2>{{ $slide->title }}</h2>
                        @if($slide->subtitle)
                            <p class="ad-subtitle">{{ $slide->subtitle }}</p>
                        @endif
                        @if($slide->phone_number)
                            <div class="phone-number">
                                <span class="phone-icon">📞</span>
                                <span class="number">{{ $slide->phone_number }}</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach

        {{-- Alt Video Slaytları (varsa) --}}
        @foreach($bottomVideos as $video)
            <div class="slide video-slide" data-duration="{{ $video->duration }}">
                <video autoplay muted loop playsinline preload="auto" data-video-id="{{ $video->id }}">
                    @if($video->file_path)
                        <source src="{{ asset('storage/' . $video->file_path) }}" type="video/mp4">
                    @endif
                    Tarayıcınız video etiketini desteklemiyor.
                </video>
            </div>
        @endforeach
    </div>

    <script src="{{ asset('js/script.js') }}?v={{ time() }}"></script>
</body>

</html>