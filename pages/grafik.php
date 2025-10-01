<?php
require_once __DIR__.'/../functions/config.php';
?>

<div class="container my-4">
    <div class="row g-4">
        <!-- Grafik Kelembaban -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Kelembaban Udara</h5>
                    <select class="form-select form-select-sm period-select" 
                            id="periodKelembaban" 
                            style="width: auto">
                        <option value="hourly">24 Jam Terakhir</option>
                        <option value="daily">7 Hari Terakhir</option>
                    </select>
                </div>
                <canvas id="chartKelembaban"></canvas>
            </div>
        </div>

        <!-- Grafik Suhu -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Suhu (Udara & Air)</h5>
                    <select class="form-select form-select-sm period-select" 
                            id="periodSuhu" 
                            style="width: auto">
                        <option value="hourly">24 Jam Terakhir</option>
                        <option value="daily">7 Hari Terakhir</option>
                    </select>
                </div>
                <canvas id="chartSuhu"></canvas>
            </div>
        </div>

        <!-- Grafik pH -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>pH Air</h5>
                    <select class="form-select form-select-sm period-select" 
                            id="periodPH" 
                            style="width: auto">
                        <option value="hourly">24 Jam Terakhir</option>
                        <option value="daily">7 Hari Terakhir</option>
                    </select>
                </div>
                <canvas id="chartPH"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Initialize charts -->
<script>
let charts = {};

async function loadGrafik(chartId = null) {
    const period = chartId ? 
        document.getElementById(`period${chartId}`).value : 
        'hourly';
    
    try {
        const response = await fetch(`../functions/get_chart_data.php?period=${period}`);
        const data = await response.json();

        if (!chartId || chartId === 'Kelembaban') {
            updateKelembabanChart(data);
        }
        if (!chartId || chartId === 'Suhu') {
            updateSuhuChart(data);
        }
        if (!chartId || chartId === 'PH') {
            updatePHChart(data);
        }
    } catch (error) {
        console.error('Error loading chart data:', error);
    }
}

function updateKelembabanChart(data) {
    const ctx = document.getElementById('chartKelembaban').getContext('2d');
    
    if (charts.kelembaban) {
        charts.kelembaban.destroy();
    }

    charts.kelembaban = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Kelembaban (%)',
                data: data.kelembaban,
                borderColor: 'rgb(54, 162, 235)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Kelembaban Udara'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

function updateSuhuChart(data) {
    const ctx = document.getElementById('chartSuhu').getContext('2d');
    
    if (charts.suhu) {
        charts.suhu.destroy();
    }

    charts.suhu = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Suhu Udara (°C)',
                data: data.suhu_udara,
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }, {
                label: 'Suhu Air 1 (°C)',
                data: data.suhu_air1,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'Suhu Air 2 (°C)',
                data: data.suhu_air2,
                borderColor: 'rgb(153, 102, 255)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Suhu'
                }
            }
        }
    });
}

function updatePHChart(data) {
    const ctx = document.getElementById('chartPH').getContext('2d');
    
    if (charts.ph) {
        charts.ph.destroy();
    }

    charts.ph = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'pH Air',
                data: data.ph,
                borderColor: 'rgb(255, 159, 64)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik pH Air'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 14
                }
            }
        }
    });
}

// Event listeners untuk perubahan periode
document.querySelectorAll('.period-select').forEach(select => {
    select.addEventListener('change', function() {
        const chartId = this.id.replace('period', '');
        loadGrafik(chartId);
    });
});

// Load semua grafik saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadGrafik();
});
</script>
