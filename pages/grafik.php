<?php
require_once __DIR__.'/../functions/config.php';
?>

<style>
/* Override khusus untuk chart container */
.card-custom canvas {
    max-height: 300px !important;
    width: 100% !important;
}
</style>

<div class="container my-4">
    <div class="row g-4">
        <!-- Grafik Kelembaban -->
        <div class="col-md-6">
            <div class="card-custom"> <!-- Sudah menggunakan class dari styles.scss -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Kelembaban Udara</h5>
                    <select class="period-select" id="periodKelembaban"> <!-- Gunakan class period-select dari styles.scss -->
                        <option value="hourly">24 Jam Terakhir</option>
                        <option value="daily">7 Hari Terakhir</option>
                    </select>
                </div>
                <canvas id="chartKelembaban"></canvas>
            </div>
        </div>

        <!-- Grafik Suhu Udara -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Suhu Udara</h5>
                    <select class="period-select" id="periodSuhuUdara">
                        <option value="hourly">24 Jam Terakhir</option>
                        <option value="daily">7 Hari Terakhir</option>
                    </select>
                </div>
                <canvas id="chartSuhuUdara"></canvas>
            </div>
        </div>

        <!-- Grafik Suhu Air 1 -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Suhu Air 1</h5>
                    <select class="period-select" id="periodSuhuAir1">
                        <option value="hourly">24 Jam Terakhir</option>
                        <option value="daily">7 Hari Terakhir</option>
                    </select>
                </div>
                <canvas id="chartSuhuAir1"></canvas>
            </div>
        </div>

        <!-- Grafik Suhu Air 2 -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Suhu Air 2</h5>
                    <select class="period-select" id="periodSuhuAir2">
                        <option value="hourly">24 Jam Terakhir</option>
                        <option value="daily">7 Hari Terakhir</option>
                    </select>
                </div>
                <canvas id="chartSuhuAir2"></canvas>
            </div>
        </div>

        <!-- Grafik pH -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>pH Air</h5>
                    <select class="period-select" id="periodPH">
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
<!-- Load grafik.js dengan path relatif yang benar -->
<script src="assets/js/grafik.js"></script>
