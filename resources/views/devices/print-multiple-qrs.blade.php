<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Códigos QR de Activos</title>
    <style>
        :root {
            /* Default sizes */
            --qr-size: 200px;
            --font-size: 14px;
        }

        body {
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8fafc;
        }

        /* Controls Panel (Hidden on print) */
        .controls-panel {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e2e8f0;
        }

        .controls-panel select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-print {
            background-color: #1e293b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-print:hover {
            background-color: #334155;
        }

        /* QRs Grid Layout */
        .qrs-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }

        .qr-card {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            break-inside: avoid;
            /* width is implicitly determined by content, will auto base on --qr-size */
        }
        
        .qr-wrapper {
            margin-bottom: 10px;
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
        }

        .device-info strong {
            display: block;
            margin-bottom: 4px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                background-color: white;
            }
            .qrs-grid {
                gap: 15px; /* Adjust gap for printing if necessary */
                justify-content: flex-start;
            }
            .qr-card {
                padding: 10px;
                border: 1px dashed #ccc; /* Optional: guide lines for cutting */
            }
            
            /* Hide print headers/footers in supported browsers */
            @page {
                margin: 10mm;
            }
        }
    </style>
</head>
<body>
    
    <div class="controls-panel no-print">
        <div>
            <label for="qr-size-select" style="font-weight: bold; margin-right: 10px;">Tamaño del QR:</label>
            <select id="qr-size-select" onchange="changeSize(this.value)">
                <option value="small">Pequeño (~3x3 cm) - Mouse/Teclados</option>
                <option value="medium" selected>Mediano (~5x5 cm) - Laptops/Monitores</option>
                <option value="large">Grande (~8x8 cm) - PCs/Impresoras</option>
            </select>
        </div>
        
        <button class="btn-print" onclick="window.print()">
            🖨️ Imprimir
        </button>
    </div>

    <div class="qrs-grid mt-4">
        @foreach($devicesWithQr as $device)
            <div class="qr-card">
                <div class="qr-wrapper">
                    {!! $device->qrCode !!}
                </div>
                <div class="device-info">
                    <strong>{{ $device->name }}</strong>
                    {{ $device->serial_number }}<br>
                    {{ $device->brand }} {{ $device->model }}
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function changeSize(size) {
            const root = document.documentElement;
            if (size === 'small') {
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

        // Initialize with default size
        document.addEventListener('DOMContentLoaded', () => {
             const select = document.getElementById('qr-size-select');
             changeSize(select.value);
        });
    </script>
</body>
</html>
