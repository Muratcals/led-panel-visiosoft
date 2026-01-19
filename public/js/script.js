// 55 inç TV LED Panel - Slider ve Video Kontrolü

document.addEventListener('DOMContentLoaded', function () {
    // ===== ÜST SLIDER (Video + Reklam) =====
    const topSlides = document.querySelectorAll('#topSliderContainer .top-slide');
    let topCurrentSlide = 0;

    function showTopSlide(index) {
        topSlides.forEach((slide, i) => {
            slide.classList.remove('active');
            const video = slide.querySelector('video');
            if (video) {
                video.pause();
                video.currentTime = 0;
            }
        });

        const currentSlideElement = topSlides[index];
        currentSlideElement.classList.add('active');

        const activeVideo = currentSlideElement.querySelector('video');
        if (activeVideo) {
            activeVideo.play().catch(e => console.log('Video play error:', e));
        }

        // Bir sonraki slide için zamanlayıcı ayarla (data-duration veya varsayılan 15sn)
        let duration = parseInt(currentSlideElement.getAttribute('data-duration')) || 15;
        setTimeout(nextTopSlide, duration * 1000);
    }

    function nextTopSlide() {
        topCurrentSlide = (topCurrentSlide + 1) % topSlides.length;
        showTopSlide(topCurrentSlide);
    }

    // Üst slider için otomatik geçiş
    if (topSlides.length > 0) {
        showTopSlide(0);
    }

    // ===== ALT SLIDER (Fiyat Listesi + Video + Promo) =====
    const slides = document.querySelectorAll('#sliderContainer .slide');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            const video = slide.querySelector('video');
            if (video) {
                video.pause();
                video.currentTime = 0;
            }
        });

        const currentSlideElement = slides[index];
        currentSlideElement.classList.add('active');

        const activeVideo = currentSlideElement.querySelector('video');
        if (activeVideo) {
            activeVideo.play().catch(e => console.log('Video play error:', e));
        }

        // Bir sonraki slide için zamanlayıcı ayarla
        let duration = parseInt(currentSlideElement.getAttribute('data-duration')) || 15;
        setTimeout(nextSlide, duration * 1000);
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    // Alt slider için otomatik geçiş
    if (slides.length > 0) {
        showSlide(0);
    }

    // ===== VİDEO OTOMATİK OYNATMA VE DÖNGÜ =====
    const allVideos = document.querySelectorAll('video');
    allVideos.forEach(video => {
        // Otomatik oynatmayı dene
        video.play().catch(e => console.log('Video autoplay error:', e));

        // Video bittiğinde başa sar ve tekrar oynat (Loop garantisi)
        video.addEventListener('ended', function () {
            video.currentTime = 0;
            video.play().catch(e => console.log('Video replay error:', e));
        });
    });

    // ===== TAM EKRAN FONKSİYONU =====
    function enterFullscreen() {
        const elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    }

    // Sayfa yüklendiğinde tam ekrana geç (kullanıcı etkileşimi gerektirebilir)
    document.body.addEventListener('click', function () {
        enterFullscreen();
    }, { once: true });

    // ===== ROTASYON KONTROLÜ =====
    const rotateBtn = document.getElementById('rotateBtn');
    let currentRotation = parseInt(localStorage.getItem('topVideoRotation')) || 0;

    function applyRotation(deg) {
        const videos = document.querySelectorAll('#topSliderContainer .top-slide video');
        videos.forEach(video => {
            video.style.transform = `translate(-50%, -50%) rotate(${deg}deg)`;
            video.style.transformOrigin = 'center center';

            if (deg === 90 || deg === 270) {
                // Dikey rotasyonda taşmayı sağla
                video.style.width = '200vh';
                video.style.height = '200vw';
            } else {
                // Yatayda normal boyut
                video.style.width = '100%';
                video.style.height = '100%';
            }
        });
    }

    // İlk yüklemede uygula
    applyRotation(currentRotation);

    if (rotateBtn) {
        rotateBtn.addEventListener('click', function (e) {
            e.stopPropagation(); // Tam ekran tetiklemesini engelle
            currentRotation = (currentRotation + 90) % 360;
            localStorage.setItem('topVideoRotation', currentRotation);
            applyRotation(currentRotation);
            console.log('Rotated to:', currentRotation);
        });
    }

    console.log('LED Panel Display initialized');
});

// ===== CİHAZ KODU VE OTOMATİK GÜNCELLEME =====
// URL'den cihaz kodunu al
const urlParams = new URLSearchParams(window.location.search);
const deviceCode = urlParams.get('device');

// Veri değişikliğini kontrol et ve sayfa yenile
const checkUpdateInterval = 5000; // 5 saniye
const heartbeatInterval = 60000; // 60 saniye
let currentDataHash = document.querySelector('meta[name="data-hash"]')?.content;

// API endpoint - cihaz kodu varsa ekle
function getApiUrl(endpoint) {
    const baseUrl = endpoint;
    if (deviceCode) {
        return `${baseUrl}?device=${deviceCode}`;
    }
    return baseUrl;
}

// İçerik güncellemelerini kontrol et
function checkForUpdates() {
    fetch(getApiUrl('/api/display'))
        .then(response => response.json())
        .then(data => {
            if (data.hash && data.hash !== currentDataHash) {
                console.log('Yeni içerik algılandı, sayfa yenileniyor...');
                window.location.reload();
            }
        })
        .catch(error => console.error('Güncelleme kontrolü hatası:', error));
}

// Cihaz heartbeat gönder (cihaz kodu varsa)
function sendHeartbeat() {
    if (!deviceCode) return;

    fetch(`/api/device/${deviceCode}/heartbeat`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
            status: 'online',
            timestamp: new Date().toISOString()
        })
    })
        .then(response => response.json())
        .then(data => {
            console.log('Heartbeat gönderildi:', data);

            // Cihaz pasif yapıldıysa uyarı göster
            if (data.device_status !== 'active') {
                console.warn('Cihaz aktif değil:', data.device_status);
            }
        })
        .catch(error => console.error('Heartbeat hatası:', error));
}

// Cihaz ilk kayıt (sadece cihaz kodu varsa)
function registerDevice() {
    if (!deviceCode) return;

    fetch('/api/device/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
            device_code: deviceCode
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Cihaz kaydı:', data.message, data.device);
            }
        })
        .catch(error => console.error('Cihaz kayıt hatası:', error));
}

// Başlangıçta cihazı kaydet
if (deviceCode) {
    console.log('Cihaz modu aktif:', deviceCode);
    registerDevice();
}

// Hash varsa periyodik güncelleme kontrolü başlat
if (currentDataHash) {
    setInterval(checkForUpdates, checkUpdateInterval);
}

// Cihaz kodu varsa periyodik heartbeat başlat
if (deviceCode) {
    setInterval(sendHeartbeat, heartbeatInterval);
    // İlk heartbeat'i hemen gönder
    sendHeartbeat();
}
