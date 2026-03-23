<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncEmployeesFromErp extends Command
{
    protected $signature = 'employees:sync';
    protected $description = 'Sincroniza los empleados desde el ERP externo';

    public function handle(): int
    {
        $this->info('Iniciando sincronización de empleados desde ERP...');

        $apiKey = config('app.erp_api_key');
        $erpUrl = config('app.erp_api_url') . '/api/v1/users';

        try {
            $response = Http::timeout(30)->withHeaders([
                'X-API-Key' => $apiKey,
            ])->get($erpUrl);

            if (!$response->successful()) {
                $this->error('Error al conectar con el ERP: ' . $response->status());
                return self::FAILURE;
            }

            $data = $response->json();

            if (!isset($data['data']) || empty($data['data'])) {
                $this->warn('No se encontraron empleados en el ERP.');
                return self::SUCCESS;
            }

            $synced = 0;
            $created = 0;
            $updated = 0;

            foreach ($data['data'] as $emp) {
                $employeeId = $emp['employee_id'] ?? $emp['id'] ?? null;

                if (!$employeeId) {
                    continue;
                }

                $exists = Employee::where('employee_id', $employeeId)->exists();

                Employee::updateOrCreate(
                    ['employee_id' => $employeeId],
                    [
                        'name' => $emp['name'],
                        'department' => $emp['department'] ?? null,
                        'position' => $emp['position'] ?? null,
                        'phone' => $emp['phone'] ?? null,
                        'is_active' => $emp['is_active'] ?? true,
                    ]
                );

                if ($exists) {
                    $updated++;
                } else {
                    $created++;
                }
                $synced++;
            }

            $this->info("Sincronización completada: {$synced} empleados ({$created} nuevos, {$updated} actualizados).");
            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error de conexión: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}