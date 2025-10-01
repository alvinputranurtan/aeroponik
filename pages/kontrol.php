<?php
// Include database connection
require_once __DIR__.'/../functions/config.php';

// Ambil status dari kontrol_realtime
$sql = 'SELECT * FROM kontrol_realtime LIMIT 1';
$result = $conn->query($sql);
$kontrol = $result->fetch_assoc();

// Set default values if no data exists
if (!$kontrol) {
    $kontrol = [
        'sistem_penyiraman' => 0,
        'tekan_untuk_hidupkan_sprayer' => 0,
    ];
}

// Ambil jadwal dari kontrol_data
$sql_jadwal = 'SELECT * FROM kontrol_data ORDER BY hidupkan_sprayer_pada_jam ASC';
$result_jadwal = $conn->query($sql_jadwal);
$jadwal = [];
while ($row = $result_jadwal->fetch_assoc()) {
    $jadwal[] = $row;
}
?>

<div class="container my-4">
    <div class="row g-4">
        <!-- Sistem Penyiraman Otomatis -->
        <div class="col-md-6 col-12">
            <div class="card-custom-control text-center p-4">
                <h5 class="mb-4">Sistem Penyiraman</h5>
                <button class="circle-button <?php echo $kontrol['sistem_penyiraman'] ? 'active' : 'inactive'; ?>" 
                        id="btnOtomatis" 
                        data-status="<?php echo $kontrol['sistem_penyiraman']; ?>">
                    <?php echo $kontrol['sistem_penyiraman'] ? 'Otomatis' : 'Manual'; ?>
                </button>
            </div>
        </div>

        <!-- Kontrol Manual -->
        <div class="col-md-6 col-12">
            <div class="card-custom-control text-center p-4">
                <h5 class="mb-4">Tekan Untuk Hidupkan Sprayer</h5>
                <button class="circle-button <?php echo $kontrol['tekan_untuk_hidupkan_sprayer'] ? 'active' : 'inactive'; ?>" 
                        id="btnManual" 
                        data-status="<?php echo $kontrol['tekan_untuk_hidupkan_sprayer']; ?>">
                    <?php echo $kontrol['tekan_untuk_hidupkan_sprayer'] ? 'Hidup' : 'Mati'; ?>
                </button>
            </div>
        </div>

        <!-- Kontrol Jadwal -->
        <div class="col-md-6 col-12">
            <div class="card-custom-control p-4">
                <h5 class="text-center mb-4">Hidupkan Sprayer Pada Jam</h5>
                <form id="formJadwal">
                    <div id="jamList">
                        <?php foreach ($jadwal as $j) { ?>
                        <div class="d-flex align-items-center mb-3 jam-item">
                            <div class="me-2 w-100">
                                <label class="form-label small mb-1">Mulai</label>
                                <input type="time" class="form-control form-control-custom" 
                                       name="mulai[]" value="<?php echo $j['hidupkan_sprayer_pada_jam']; ?>">
                            </div>
                            <div class="me-2 w-100">
                                <label class="form-label small mb-1">Menit</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="menit[]" min="1" max="60" 
                                       value="<?php echo $j['hidupkan_sprayer_selama_menit']; ?>">
                            </div>
                            <div class="ms-3">
                                <input type="checkbox" name="aktif[]" class="form-check-input mt-4" checked>
                            </div>
                        </div>
                        <?php } ?>
                        
                        <!-- Template untuk jadwal baru -->
                        <div class="d-flex align-items-center mb-3 jam-item">
                            <div class="me-2 w-100">
                                <label class="form-label small mb-1">Mulai</label>
                                <input type="time" class="form-control form-control-custom" name="mulai[]">
                            </div>
                            <div class="me-2 w-100">
                                <label class="form-label small mb-1">Menit</label>
                                <input type="number" class="form-control form-control-custom" 
                                       name="menit[]" min="1" max="60">
                            </div>
                            <div class="ms-3">
                                <input type="checkbox" name="aktif[]" class="form-check-input mt-4" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle tombol otomatis
    $('#btnOtomatis').click(function() {
        const status = $(this).data('status') ? 0 : 1;
        $.post('functions/update_kontrol.php', {
            sistem_penyiraman: status
        }, function(response) {
            if(response.success) {
                location.reload();
            }
        });
    });

    // Handle tombol manual
    $('#btnManual').click(function() {
        const status = $(this).data('status') ? 0 : 1;
        $.post('functions/update_kontrol.php', {
            tekan_untuk_hidupkan_sprayer: status
        }, function(response) {
            if(response.success) {
                location.reload();
            }
        });
    });

    // Handle form jadwal
    $('#formJadwal').submit(function(e) {
        e.preventDefault();
        $.post('functions/update_jadwal.php', $(this).serialize(), function(response) {
            if(response.success) {
                location.reload();
            }
        });
    });
});
</script>