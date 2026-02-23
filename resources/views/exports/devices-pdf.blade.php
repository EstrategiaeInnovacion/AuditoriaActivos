<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Activos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #1e293b; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #4f46e5; padding-bottom: 15px; }
        .header h1 { font-size: 22px; color: #4f46e5; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #4f46e5; color: #fff; font-weight: 600; text-align: left; padding: 8px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .badge-available { background: #dcfce7; color: #166534; }
        .badge-assigned { background: #dbeafe; color: #1e40af; }
        .badge-maintenance { background: #fef3c7; color: #92400e; }
        .badge-broken { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 10px; color: #64748b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 Control de Activos</h1>
        <p>Reporte de Inventario — Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="meta">
        <span>Total de dispositivos: {{ $devices->count() }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>No. Serie</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>F. Compra</th>
            </tr>
        </thead>
        <tbody>
            @php
                $types = ['computer' => 'Computadora', 'peripheral' => 'Periférico', 'printer' => 'Impresora', 'other' => 'Otro'];
                $statuses = ['available' => 'Disponible', 'assigned' => 'Asignado', 'maintenance' => 'Mantenimiento', 'broken' => 'Averiado'];
            @endphp
            @foreach($devices as $device)
                <tr>
                    <td><strong>{{ $device->name }}</strong></td>
                    <td>{{ $device->brand }}</td>
                    <td>{{ $device->model }}</td>
                    <td style="font-family: monospace; font-size: 10px;">{{ $device->serial_number }}</td>
                    <td>{{ $types[$device->type] ?? $device->type }}</td>
                    <td>
                        <span class="badge badge-{{ $device->status }}">{{ $statuses[$device->status] ?? $device->status }}</span>
                    </td>
                    <td>{{ $device->purchase_date ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Control de Activos — Sistema de Gestión de Inventario
    </div>
</body>
</html>
