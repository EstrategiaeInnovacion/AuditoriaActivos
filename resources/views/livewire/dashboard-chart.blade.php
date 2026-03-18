<div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-cyan-50/50 flex items-center gap-3">
        <div class="p-2 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl">
            <svg class="w-5 h-5 text-cyan-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Estado de Activos</h3>
    </div>
    <div class="p-6">
        <div class="relative h-64 w-full">
            <canvas id="deviceStatusChart"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            const ctx = document.getElementById('deviceStatusChart');
            if (!ctx) return;

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
                            '#22c55e',
                            '#3b82f6',
                            '#eab308',
                            '#ef4444'
                        ],
                        borderWidth: 0,
                        hoverOffset: 6,
                        hoverBorderWidth: 3,
                        hoverBorderColor: '#fff'
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
                                pointStyle: 'circle',
                                padding: 20,
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 12,
                                    weight: '500'
                                },
                                color: '#475569'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: {
                                family: 'Inter, sans-serif',
                                size: 14,
                                weight: '600'
                            },
                            bodyFont: {
                                family: 'Inter, sans-serif',
                                size: 13
                            },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: true,
                            boxPadding: 4
                        }
                    },
                    cutout: '65%',
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        });
    </script>
</div>
