function loadGrafik() {
    // Store chart instances
    const charts = {};
    const chartConfigs = {
        'periodKelembaban': {
            canvasId: 'chartKelembaban',
            label: 'Kelembaban Udara (%)',
            color: '#2196f3',
            dataKey: 'kelembaban'
        },
        'periodSuhu': {
            canvasId: 'chartSuhu',
            labels: ['Suhu Udara (°C)', 'Suhu Air 1 (°C)', 'Suhu Air 2 (°C)'],
            colors: ['#f44336', '#4caf50', '#9c27b0'],
            dataKeys: ['suhu_udara', 'suhu_air1', 'suhu_air2']
        },
        'periodPH': {
            canvasId: 'chartPH',
            label: 'pH Air',
            color: '#ff9800',
            dataKey: 'ph'
        }
    };
    
    // Function to fetch data from server
    async function fetchData(period) {
        try {
            const response = await fetch(`../functions/get_chart_data.php?period=${period}`);
            return await response.json();
        } catch (error) {
            console.error('Error fetching data:', error);
            return null;
        }
    }

    // Function to render or update single line chart
    function renderSingleLineChart(canvasId, label, data, color, options = {}) {
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
                    backgroundColor: color + '33',
                    borderColor: color,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: label
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ...options.yAxis
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }

    // Function to render multi-line chart (for temperature)
    function renderMultiLineChart(canvasId, labels, data, colors) {
        if (charts[canvasId]) {
            charts[canvasId].destroy();
        }

        const ctx = document.getElementById(canvasId).getContext('2d');
        charts[canvasId] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: labels.map((label, i) => ({
                    label: label,
                    data: data.values[i],
                    borderColor: colors[i],
                    tension: 0.4,
                    fill: false
                }))
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Suhu'
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }

    // Function to update charts
    async function updateChart(selectId, period) {
        const config = chartConfigs[selectId];
        const data = await fetchData(period);
        
        if (!data) return;

        if (selectId === 'periodSuhu') {
            // Handle multi-line temperature chart
            renderMultiLineChart(
                config.canvasId,
                config.labels,
                {
                    labels: data.labels,
                    values: config.dataKeys.map(key => data[key])
                },
                config.colors
            );
        } else {
            // Handle single line charts
            const options = {
                yAxis: selectId === 'periodKelembaban' ? 
                    { max: 100 } : 
                    (selectId === 'periodPH' ? { max: 14 } : {})
            };

            renderSingleLineChart(
                config.canvasId,
                config.label,
                {
                    labels: data.labels,
                    values: data[config.dataKey]
                },
                config.color,
                options
            );
        }
    }

    // Handle period selection changes
    document.querySelectorAll('.period-select').forEach(select => {
        select.addEventListener('change', function() {
            const period = this.value;
            const selectId = this.id;
            updateChart(selectId, period);
        });
    });

    // Initial load with hourly data for all charts
    Object.keys(chartConfigs).forEach(selectId => {
        updateChart(selectId, 'hourly');
    });
}

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', loadGrafik);
