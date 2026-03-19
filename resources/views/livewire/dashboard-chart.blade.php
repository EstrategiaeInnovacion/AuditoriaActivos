<div class="glass-light rounded-2xl overflow-hidden card-hover">
    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center gap-3">
        <div class="p-2 rounded-xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20">
            <svg class="w-5 h-5 text-indigo-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-white">Estado de Activos</h3>
    </div>
    <div class="p-6">
        <div class="relative h-64 w-full">
            <canvas id="deviceStatusChart"></canvas>
        </div>
    </div>

    <script>
        function initDeviceChart() {
            const ctx = document.getElementById('deviceStatusChart');
            if (!ctx) return;

            if (typeof Chart === 'undefined') {
                setTimeout(initDeviceChart, 100);
                return;
            }

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
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(234, 179, 8, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgba(34, 197, 94, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(234, 179, 8, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 8,
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
                                color: '#94a3b8'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
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
                            boxPadding: 4,
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1
                        }
                    },
                    cutout: '65%',
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initDeviceChart);
        document.addEventListener('livewire:navigated', initDeviceChart);
        document.addEventListener('livewire:init', initDeviceChart);
    </script>
</div>
