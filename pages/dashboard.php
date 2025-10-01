<?php
date_default_timezone_set('Asia/Jakarta');
include __DIR__.'/../functions/config.php';

// Ambil data terbaru dari tabel monitoring
$sql = 'SELECT * FROM monitoring ORDER BY id DESC LIMIT 1';
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Cek status perangkat
$status_perangkat = 'Offline';
if ($row) {
    $last_time = strtotime($row['created_at']);
    if ($last_time !== false && (time() - $last_time) <= 15) {
        $status_perangkat = 'Online';
    }
}
?>

<div class="container my-4">
    <div class="row g-3">
        <!-- Status Perangkat -->
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Status Perangkat</h5>
                <h3 id="status_perangkat"><?php echo $status_perangkat; ?></h3>
            </div>
        </div>

        <!-- Status Pompa -->
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Status Pompa</h5>
                <h3 id="status_pompa">
                    <?php echo ($row['status_pompa'] == '1') ? 'Aktif' : 'Nonaktif'; ?>
                </h3>
            </div>
        </div>

        <!-- Status Chiller -->
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Status Chiller</h5>
                <h3 id="status_chiller">
                    <?php echo ($row['status_chiller'] == '1') ? 'Aktif' : 'Nonaktif'; ?>
                </h3>
            </div>
        </div>

        <!-- Kelembaban Udara -->
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Kelembaban Udara</h5>
                <h3 id="kelembaban_udara"><?php echo number_format($row['dht22_kelembaban'], 1); ?>%</h3>
            </div>
        </div>

        <!-- Suhu Udara -->
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Suhu Udara</h5>
                <h3 id="suhu_udara"><?php echo number_format($row['dht22_suhu'], 1); ?>°C</h3>
            </div>
        </div>

        <!-- Suhu Air 1 -->
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Suhu Air 1</h5>
                <h3 id="suhu_air1"><?php echo number_format($row['ds18b20_suhu1'], 1); ?>°C</h3>
            </div>
        </div>

        <!-- Suhu Air 2 -->
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Suhu Air 2</h5>
                <h3 id="suhu_air2"><?php echo number_format($row['ds18b20_suhu2'], 1); ?>°C</h3>
            </div>
        </div>

        <!-- pH Air -->
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>pH Air</h5>
                <h3 id="ph_air"><?php echo number_format($row['ph_keasaman'], 1); ?></h3>
            </div>
        </div>
    </div>
</div>

<script>
async function updateDashboard() {
    try {
        const response = await fetch('/smartaeroponik/pages/ajax_dashboard.php');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        
        // Update semua elemen
        document.getElementById('status_perangkat').textContent = data.status_perangkat;
        document.getElementById('status_pompa').textContent = data.status_pompa === '1' ? 'Aktif' : 'Nonaktif';
        document.getElementById('status_chiller').textContent = data.status_chiller === '1' ? 'Aktif' : 'Nonaktif';
        document.getElementById('kelembaban_udara').textContent = data.dht22_kelembaban.toFixed(1) + '%';
        document.getElementById('suhu_udara').textContent = data.dht22_suhu.toFixed(1) + '°C';
        document.getElementById('suhu_air1').textContent = data.ds18b20_suhu1.toFixed(1) + '°C';
        document.getElementById('suhu_air2').textContent = data.ds18b20_suhu2.toFixed(1) + '°C';
        document.getElementById('ph_air').textContent = data.ph_keasaman.toFixed(1);
    } catch (error) {
        console.error('Error updating dashboard:', error);
    }
}

// Update pertama kali saat halaman dimuat
updateDashboard();

// Update setiap 5 detik
setInterval(updateDashboard, 5000);
</script>