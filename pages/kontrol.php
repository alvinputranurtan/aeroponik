<?php
// Include database connection
require_once __DIR__.'/../functions/config.php';

// Ambil durasi pompa
$sql_durasi = 'SELECT * FROM pump_duration WHERE id = 1';
$result_durasi = $conn->query($sql_durasi);
$pump_duration = $result_durasi->fetch_assoc();

// Ambil status pompa
$sql_status = 'SELECT * FROM pump_status WHERE id = 1';
$result_status = $conn->query($sql_status);
$pump_status = $result_status->fetch_assoc();
?>

<div class="container my-4">
    <div class="row g-4">
        <!-- Kontrol Durasi -->
        <div class="col-md-6 col-12">
            <div class="card-custom-control p-4">
                <h5 class="text-center mb-4">Pengaturan Durasi Pompa</h5>
                <form id="formDurasi">
                    <div class="mb-3">
                        <label class="form-label">Durasi Hidup (menit)</label>
                        <input type="number" 
                               class="form-control form-control-custom" 
                               name="active_duration" 
                               min="1" 
                               max="60" 
                               value="<?php echo $pump_duration['active_duration']; ?>"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durasi Mati (menit)</label>
                        <input type="number" 
                               class="form-control form-control-custom" 
                               name="inactive_duration" 
                               min="1" 
                               max="60" 
                               value="<?php echo $pump_duration['inactive_duration']; ?>"
                               required>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Durasi</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Status Pompa -->
        <div class="col-md-6 col-12">
            <div class="card-custom-control text-center p-4">
                <h5 class="mb-4">Status Pompa</h5>
                <button class="circle-button <?php echo $pump_status['status'] === 'ON' ? 'active' : 'inactive'; ?>" 
                        id="btnPompStatus" 
                        data-status="<?php echo $pump_status['status']; ?>">
                    <?php echo $pump_status['status']; ?>
                </button>
                <p class="mt-3">Terakhir diperbarui: <?php echo date('d/m/Y H:i:s', strtotime($pump_status['updated_at'])); ?></p>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle form durasi
    $('#formDurasi').submit(function(e) {
        e.preventDefault();
        $.post('functions/update_durasi.php', $(this).serialize(), function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert('Gagal menyimpan durasi: ' + (response.message || 'Unknown error'));
            }
        });
    });

    // Handle tombol status pompa
    $('#btnPompStatus').click(function() {
        const currentStatus = $(this).data('status');
        const newStatus = currentStatus === 'ON' ? 'OFF' : 'ON';
        
        $.post('functions/update_status.php', {
            status: newStatus
        }, function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert('Gagal mengubah status pompa: ' + (response.message || 'Unknown error'));
            }
        });
    });
});
</script>