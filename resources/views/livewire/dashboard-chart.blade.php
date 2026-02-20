<div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 h-full">
    <h3 class="text-lg font-semibold text-slate-800 mb-4">Estado de Activos</h3>
    <div class="relative h-64 w-full">
        <canvas id="deviceStatusChart"></canvas>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            const ctx = document.getElementById('deviceStatusChart');
            if (!ctx) return;

            // Destroy existing chart if it exists to prevent duplicates
            if (window.myDeviceChart) {
                window.myDeviceChart.destroy();
            }

            const data = @json($data);

            window.myDeviceChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Disponibles', 'Asignados', 'Mantenimiento', 'Averiados'],
                    datasets: [{
                        data: [data.available, data.assigned, data.maintenance, data.broken],
                        backgroundColor: [
                            '#22c55e', // green-500
                            '#3b82f6', // blue-500
                            '#eab308', // yellow-500
                            '#ef4444'  // red-500
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    cutout: '70%',
                }
            });
        });
    </script>
</div>
