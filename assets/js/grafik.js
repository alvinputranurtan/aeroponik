// Buat IIFE (Immediately Invoked Function Expression) untuk mengisolasi scope
const ChartManager = (() => {
    // Store chart instances
    const charts = {};

    // Konfigurasi chart (dropdown id → canvas, label, warna, field DB)
    const chartConfigs = {
        'periodKelembaban': {
            canvasId: 'chartKelembaban',
            label: 'Kelembaban Udara (%)',
            color: 'rgba(32, 107, 196, 0.8)',
            dataKey: 'kelembaban'
        },
        'periodSuhuUdara': {
            canvasId: 'chartSuhuUdara',
            label: 'Suhu Udara (°C)',
            color: 'rgba(220, 53, 69, 0.8)',
            dataKey: 'suhu_udara'
        },
        'periodSuhuAir1': {
            canvasId: 'chartSuhuAir1',
            label: 'Suhu Air 1 (°C)',
            color: 'rgba(40, 167, 69, 0.8)',
            dataKey: 'suhu_air1'
        },
        'periodSuhuAir2': {
            canvasId: 'chartSuhuAir2',
            label: 'Suhu Air 2 (°C)',
            color: 'rgba(111, 66, 193, 0.8)',
            dataKey: 'suhu_air2'
        },
        'periodPH': {
            canvasId: 'chartPH',
            label: 'pH Air',
            color: 'rgba(255, 123, 0, 0.8)',
            dataKey: 'ph'
        }
    };

    // 🔹 Fetch data dari server
    async function fetchData(period) {
        try {
            const url = '/smartaeroponik.inosakti.com/functions/get_chart_data.php?period=' + period;
            console.log('Fetching from:', url);
            
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log('Received data:', data);
            return data;
        } catch (error) {
            console.error('Error fetching data:', error);
            return null;
        }
    }

    // 🔹 Render / update chart tunggal
    function renderChart(canvasId, label, data, color) {
        if (charts[canvasId]) {
            charts[canvasId].destroy();
        }

        const ctx = document.getElementById(canvasId).getContext('2d');
        charts[canvasId] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: label,
                    data: data.values,
                    backgroundColor: color.replace('0.8', '0.1'),
                    borderColor: color,
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    title: {
                        display: true,
                        text: label,
                        font: {
                            size: 14,
                            weight: '500'
                        },
                        padding: {
                            top: 10,
                            bottom: 15
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                layout: {
                    padding: {
                        left: 10,
                        right: 10
                    }
                }
            }
        });
    }

    // Render semua chart dengan data yang sama
    function renderAllCharts(data) {
        Object.entries(chartConfigs).forEach(([selectId, config]) => {
            renderChart(
                config.canvasId,
                config.label,
                { labels: data.labels, values: data[config.dataKey] },
                config.color
            );
        });
    }

    // Update single chart
    async function updateSingleChart(selectId, period) {
        const data = await fetchData(period);
        if (!data) return;

        const config = chartConfigs[selectId];
        renderChart(
            config.canvasId,
            config.label,
            { labels: data.labels, values: data[config.dataKey] },
            config.color
        );
    }

    // Initialize charts
    async function initialize() {
        // Single fetch untuk semua chart
        const data = await fetchData('hourly');
        if (data) {
            renderAllCharts(data);
        }

        // Setup event listeners
        document.querySelectorAll('.period-select').forEach(select => {
            select.addEventListener('change', function() {
                const period = this.value;
                const selectId = this.id;
                updateSingleChart(selectId, period);
            });
        });
    }

    // Return public API
    return {
        initialize
    };
})();

// Single event listener untuk initialization
document.addEventListener('DOMContentLoaded', () => {
    ChartManager.initialize();
});
