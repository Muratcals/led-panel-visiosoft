<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Hata' }} - LED Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .error-container {
            text-align: center;
            padding: 40px;
            max-width: 600px;
        }
        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #ff6b6b;
        }
        .message {
            font-size: 1.2rem;
            color: #ccc;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .code-box {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 20px;
            font-family: monospace;
            font-size: 1.1rem;
            color: #4ecdc4;
            margin-bottom: 20px;
        }
        .device-info {
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            text-align: left;
        }
        .device-info h3 {
            color: #ffd93d;
            margin-bottom: 10px;
        }
        .device-info p {
            color: #aaa;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        @if(($code ?? '') === 'NO_DEVICE_CODE')
            <div class="error-icon">🔌</div>
        @elseif(($code ?? '') === 'DEVICE_INACTIVE')
            <div class="error-icon">🔴</div>
        @else
            <div class="error-icon">⚠️</div>
        @endif
        
        <h1>{{ $title ?? 'Hata' }}</h1>
        <p class="message">{{ $message ?? 'Bir hata oluştu.' }}</p>
        
        @if(($code ?? '') === 'NO_DEVICE_CODE')
            <div class="code-box">
                /display?device=LED-001
            </div>
            <p style="color: #888; font-size: 0.9rem;">
                Raspberry Pi kiosk modunda cihaz kodu URL'ye eklenmelidir.
            </p>
        @endif
        
        @if(isset($device))
            <div class="device-info">
                <h3>Cihaz Bilgisi</h3>
                <p><strong>Kod:</strong> {{ $device->device_code }}</p>
                <p><strong>Ad:</strong> {{ $device->name }}</p>
                <p><strong>Durum:</strong> {{ $device->status }}</p>
            </div>
        @endif
    </div>
</body>
</html>
