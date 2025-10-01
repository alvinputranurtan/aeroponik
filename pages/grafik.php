<?php
require_once __DIR__.'/../functions/config.php';
?>

<div class="container my-4">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Kelembaban Tanah</h5>
                    <select class="form-select form-select-sm period-select" 
                            id="periodTanah" 
                            style="width: auto">
                        <option value="hourly">24 Jam Terakhir</option>
                        <option value="daily">7 Hari Terakhir</option>
                    </select>
                </div>
                <canvas id="chartKelembabanTanah"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Kelembaban Udara</h5>
                    <select class="form-select form-select-sm period-select" 
                            id="periodUdara" 
                            style="width: auto">
                        <option value="hourly">24 Jam Terakhir</option>
                        <option value="daily">7 Hari Terakhir</option>
                    </select>
                </div>
                <canvas id="chartKelembabanUdara"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Suhu Udara</h5>
                    <select class="form-select form-select-sm period-select" 
                            id="periodSuhu" 
                            style="width: auto">
                        <option value="hourly">24 Jam Terakhir</option>
                        <option value="daily">7 Hari Terakhir</option>
                    </select>
                </div>
                <canvas id="chartSuhuUdara"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Initialize charts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadGrafik();
});
</script>
