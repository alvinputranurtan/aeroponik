function loadGrafik() {
    // Store chart instances
    const charts = {};
    const chartConfigs = {
        'periodTanah': {
            canvasId: 'chartKelembabanTanah',
            label: 'Kelembaban Tanah (%)',
            color: '#00bcd4',
            dataKey: 'kelembaban_tanah'
        },
        'periodUdara': {
            canvasId: 'chartKelembabanUdara',
            label: 'Kelembaban Udara (%)',
            color: '#ffc107',
            dataKey: 'kelembaban_udara'
        },
        'periodSuhu': {
            canvasId: 'chartSuhuUdara',
            label: 'Suhu Udara (°C)',
            color: '#ff5722',
            dataKey: 'suhu_udara'
        }
    };
    
    // Function to fetch data from server
    async function fetchData(period) {
        try {
            const response = await fetch(`functions/get_chart_data.php?period=${period}`);
            return await response.json();
        } catch (error) {
            console.error('Error fetching data:', error);
            return null;
        }
    }

    // Function to render or update single chart
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
                    backgroundColor: color + '33',
                    borderColor: color,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { 
                        beginAtZero: true 
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

    // Function to update single chart
    async function updateChart(selectId, period) {
        const config = chartConfigs[selectId];
        const data = await fetchData(period);
        if (data) {
            renderChart(
                config.canvasId,
                config.label,
                {labels: data.labels, values: data[config.dataKey]},
                config.color
            );
        }
    }

    // Handle period selection changes
    $('.period-select').change(function() {
        const period = $(this).val();
        const selectId = $(this).attr('id');
        updateChart(selectId, period);
    });

    // Initial load with hourly data for all charts
    Object.keys(chartConfigs).forEach(selectId => {
        updateChart(selectId, 'hourly');
    });
}
