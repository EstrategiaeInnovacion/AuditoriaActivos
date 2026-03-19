<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Activos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #1e293b; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #4f46e5; padding-bottom: 15px; }
        .header h1 { font-size: 22px; color: #4f46e5; margin-bottom: 4px; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .header p { font-size: 11px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #4f46e5; color: #fff; font-weight: 600; text-align: left; padding: 8px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) { background-color: #f8fafc; }
        tr:hover { background-color: #f1f5f9; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 600; }
        .badge-available { background: #dcfce7; color: #166534; }
        .badge-assigned { background: #dbeafe; color: #1e40af; }
        .badge-maintenance { background: #fef3c7; color: #92400e; }
        .badge-broken { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 10px; color: #64748b; }
        .icon { width: 20px; height: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            Control de Activos
        </h1>
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
                @if(isset($includeCredentials) && $includeCredentials)
                    <th>Usuario Eq.</th>
                    <th>Clave Eq.</th>
                    <th>Correo</th>
                    <th>Clave Correo</th>
                @endif
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
                    @if(isset($includeCredentials) && $includeCredentials)
                        <td>{{ $device->credential->username ?? 'N/A' }}</td>
                        <td>{{ $device->credential->password ?? 'N/A' }}</td>
                        <td>{{ $device->credential->email ?? 'N/A' }}</td>
                        <td>{{ $device->credential->email_password ?? 'N/A' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Control de Activos — Sistema de Gestión de Inventario
    </div>
</body>
</html>
