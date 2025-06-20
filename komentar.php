<?php
// File ini: komentar.php
// Menggunakan variabel $conn dan $data dari file article.php
?>

<h3>Komentar</h3>

<?php // Tampilkan form komentar HANYA jika user sudah login
if (isset($_SESSION['id_penulis'])): ?>
<div class="card my-4">
    <h5 class="card-header">Tinggalkan Komentar sebagai <?= htmlspecialchars($_SESSION['nama_penulis']) ?></h5>
    <div class="card-body">
        <form action="proses_komentar.php" method="POST">
            <input type="hidden" name="id_artikel" value="<?= $data['id_artikel'] ?? '' ?>">
            
            <div class="mb-3">
                <label for="isi_komentar" class="form-label">Komentar Anda:</label>
                <textarea class="form-control" name="isi_komentar" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Komentar</button>
        </form>
    </div>
</div>
<?php else: ?>
<div class="alert alert-info">
    Silakan <a href="login.php">login</a> untuk meninggalkan komentar.
</div>
<?php endif; ?>


<?php
// Query baru dengan JOIN ke tabel penulis untuk mengambil nama_penulis
if (isset($conn) && isset($data['id_artikel'])) {
    $stmt_komen = $conn->prepare("SELECT k.*, p.nama_penulis 
                                  FROM komentar k 
                                  JOIN penulis p ON k.id_penulis = p.id_penulis 
                                  WHERE k.id_artikel = ? 
                                  ORDER BY k.tanggal_komentar DESC");
    $stmt_komen->bind_param("i", $data['id_artikel']);
    $stmt_komen->execute();
    $result_komen = $stmt_komen->get_result();

    if ($result_komen->num_rows > 0) {
        while ($komen = $result_komen->fetch_assoc()) {
?>
            <div class="d-flex mb-4">
                <div class="flex-shrink-0"><i class="bi bi-person-circle fs-2 text-muted"></i></div>
                <div class="ms-3">
                    <div class="fw-bold"><?= htmlspecialchars($komen['nama_penulis']) ?></div>
                    <div class="text-muted" style="font-size: 0.85rem;"><?= date('d F Y, H:i', strtotime($komen['tanggal_komentar'])) ?></div>
                    <p class="mt-1"><?= nl2br(htmlspecialchars($komen['isi_komentar'])) ?></p>
                </div>
            </div>
<?php
        } // penutup while
    } else {
        echo "<p>Jadilah yang pertama berkomentar!</p>";
    } // penutup if num_rows
} // penutup if isset conn
?>