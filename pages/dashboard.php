<?php
date_default_timezone_set('Asia/Jakarta'); // sesuaikan dengan waktu database
include __DIR__.'/../functions/config.php';

// Ambil data terbaru
$sql = 'SELECT * FROM data ORDER BY id DESC LIMIT 1';
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Cek status perangkat
$status_perangkat = 'Offline';
if ($row) {
    $last_time = strtotime($row['timestamp']);
    if ($last_time !== false && (time() - $last_time) <= 15) {
        $status_perangkat = 'Online';
    }
}
?>

<div class="container my-4">
    <div class="row g-3">
        <!-- Box 1: Kontrol Penyiraman -->
        <!-- <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Sistem Penyiraman Otomatis</h5>
                <div class="circle-button">Hidup</div>
            </div>
        </div> -->

        <!-- Box 2 - 9: Statistik dan Sensor -->
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Status Perangkat</h5>
                <h3 id="status_perangkat"><?php echo $status_perangkat; ?></h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Status Pompa</h5>
                <h3 id="status_pompa"><?php echo ($row['status_pompa'] == 1) ? 'Hidup' : 'Mati'; ?></h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Kelembaban Tanah</h5>
                <h3 id="kelembaban_tanah"><?php echo $row['kelembaban_tanah']; ?>%</h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Kelembaban Udara</h5>
                <h3 id="kelembaban_udara"><?php echo $row['kelembaban_udara']; ?>%</h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Suhu Udara</h5>
                <h3 id="suhu_udara"><?php echo $row['suhu_udara']; ?>°c</h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Tombol Fisik Penyiraman Otomatis</h5>
                <h3 id="tombol_fisik_penyiraman_otomatis"><?php echo ($row['tombol_fisik_penyiraman_otomatis'] == 1) ? 'Hidup' : 'Mati'; ?></h3>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-6">
            <div class="card-custom-monitoring">
                <h5>Tombol Fisik Menyalakan Pompa</h5>
                <h3 id="tombol_fisik_menyalakan_pompa"><?php echo ($row['tombol_fisik_menyalakan_pompa'] == 1) ? 'Hidup' : 'Mati'; ?></h3>
            </div>
        </div>
 

        <!-- Tambah kotak baru di sini -->
        <!-- 
    <div class="col-lg-3 col-sm-6 col-6">
      <div class="card-custom-monitoring">
        <h5>Nama Kotak Baru</h5>
        <h3>Nilai</h3>
      </div>
    </div>
    -->
    </div>
</div>

<script>
function updateDashboard() {
    fetch('/smartscreenhouse1/pages/ajax_dashboard.php')  // Ubah path ke absolut
        .then(response => response.json())
        .then(data => {
            document.getElementById('status_perangkat').textContent = data.status_perangkat;
            document.getElementById('status_pompa').textContent = data.status_pompa;
            document.getElementById('kelembaban_tanah').textContent = data.kelembaban_tanah + '%';
            document.getElementById('kelembaban_udara').textContent = data.kelembaban_udara + '%';
            document.getElementById('suhu_udara').textContent = data.suhu_udara + '°c';
            document.getElementById('tombol_fisik_penyiraman_otomatis').textContent = data.tombol_fisik_penyiraman_otomatis;
            document.getElementById('tombol_fisik_menyalakan_pompa').textContent = data.tombol_fisik_menyalakan_pompa;
        })
        .catch(error => console.error('Error:', error)); // Tambah error handling
}

// Update setiap 10 detik
setInterval(updateDashboard, 10000);
</script>