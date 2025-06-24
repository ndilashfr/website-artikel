<?php
session_start();
// Pastikan hanya user yang sudah login bisa mengakses halaman ini
if (!isset($_SESSION['id_penulis'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';
include 'function.php';

// Ambil ID penulis yang sedang login dari sesi
$id_penulis_login = $_SESSION['id_penulis'];

// Ambil semua artikel yang ditulis oleh penulis ini dari database
// Diurutkan dari yang paling baru
$stmt = $conn->prepare("SELECT a.id_artikel, a.Judul, a.created_at, k.nama_kategori 
                        FROM artikel a 
                        LEFT JOIN kategori k ON a.id_kategori = k.id_kategori 
                        WHERE a.id_penulis = ? 
                        ORDER BY a.created_at DESC");
$stmt->bind_param("i", $id_penulis_login);
$stmt->execute();
$result = $stmt->get_result();

$my_articles = [];
while ($row = $result->fetch_assoc()) {
    $my_articles[] = $row;
}

// Set judul halaman dan panggil header
$title = "Artikel Saya";
include 'layout_header.php';
?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Artikel yang Telah Anda Publikasikan</h3>
                <a href="artikel_add.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tulis Artikel Baru</a>
            </div>
            <div class="card-body p-4">
                
                <?php if (!empty($my_articles)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Judul Artikel</th>
                                <th>Kategori</th>
                                <th>Tanggal Publikasi</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_articles as $artikel): ?>
                            <tr>
                                <td><?= htmlspecialchars($artikel['Judul']) ?></td>
                                <td><?= htmlspecialchars($artikel['nama_kategori']) ?></td>
                                <td><?= formatTanggalIndonesia($artikel['created_at']) ?></td>
                                
</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        Anda belum mempublikasikan artikel apapun. <br>
                        <a href="tambah_artikel.php" class="btn btn-primary mt-3">Mulai Tulis Artikel Pertama Anda</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php
// Panggil footer
include 'layout_footer.php';
?>