<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR Code - {{ $device->name }}</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding: 20px;
        }
        .qr-container {
            border: 1px solid #ccc;
            padding: 20px;
            display: inline-block;
            margin-top: 20px;
        }
        .device-info {
            margin-top: 10px;
            font-size: 14px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
            .qr-container {
                border: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print">
        <button onclick="window.print()">Print This Page</button>
    </div>

    <div class="qr-container">
        <div>
            {!! $qrCode !!}
        </div>
        <div class="device-info">
            <strong>{{ $device->name }}</strong><br>
            {{ $device->serial_number }}<br>
            {{ $device->brand }} {{ $device->model }}
        </div>
    </div>
</body>
</html>
