<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR Code - {{ $device->name }}</title>
    <style>
        :root {
            --qr-size: 200px;
            --font-size: 14px;
        }

        body {
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f1f5f9;
            text-align: center;
        }

        .controls-panel {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: inline-flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e2e8f0;
            gap: 20px;
        }

        .controls-panel select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            cursor: pointer;
            background: white;
        }

        .btn-print {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .qr-card {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 20px;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .qr-wrapper {
            margin-bottom: 12px;
        }

        .qr-wrapper svg {
            width: var(--qr-size) !important;
            height: var(--qr-size) !important;
        }

        .device-info {
            font-size: var(--font-size);
            line-height: 1.4;
            max-width: var(--qr-size);
            word-wrap: break-word;
            color: #334155;
        }

        .device-info strong {
            display: block;
            margin-bottom: 4px;
            font-size: calc(var(--font-size) + 2px);
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                background-color: white;
                text-align: left;
            }
            .qr-card {
                border: none;
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="controls-panel no-print">
        <div>
            <label for="qr-size-select" style="font-weight: bold; margin-right: 10px; color: #334155;">Tamaño del QR:</label>
            <select id="qr-size-select" onchange="changeSize(this.value)">
                <option value="extra_small">Extra Pequeño (~2x2 cm)</option>
                <option value="small">Pequeño (~3x3 cm) - Mouse/Teclados</option>
                <option value="medium" selected>Mediano (~5x5 cm) - Laptops/Monitores</option>
                <option value="large">Grande (~8x8 cm) - PCs/Impresoras</option>
            </select>
        </div>
        
        <button class="btn-print" onclick="window.print()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimir
        </button>
    </div>

    <div style="margin-top: 20px;">
        <div class="qr-card">
            <div class="qr-wrapper">
                {!! $qrCode !!}
            </div>
            <div class="device-info">
                <strong>{{ $device->name }}</strong>
                {{ $device->serial_number }}<br>
                {{ $device->brand }} {{ $device->model }}
            </div>
        </div>
    </div>

    <script>
        function changeSize(size) {
            const root = document.documentElement;
            if (size === 'extra_small') {
                root.style.setProperty('--qr-size', '65px');
                root.style.setProperty('--font-size', '8px');
            } else if (size === 'small') {
                root.style.setProperty('--qr-size', '100px');
                root.style.setProperty('--font-size', '10px');
            } else if (size === 'medium') {
                root.style.setProperty('--qr-size', '150px');
                root.style.setProperty('--font-size', '12px');
            } else if (size === 'large') {
                root.style.setProperty('--qr-size', '200px');
                root.style.setProperty('--font-size', '14px');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
             const select = document.getElementById('qr-size-select');
             changeSize(select.value);
        });
    </script>
</body>
</html>
