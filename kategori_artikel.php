<?php
session_start();
// 1. Panggil semua file yang dibutuhkan di awal
include 'db.php';
include 'function.php';

// 2. Logika pengambilan data
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id_kategori = intval($_GET['id']);

// Dapatkan nama kategori untuk judul halaman
$stmt_kat = $conn->prepare("SELECT nama_kategori FROM kategori WHERE id_kategori = ?");
$stmt_kat->bind_param("i", $id_kategori);
$stmt_kat->execute();
$result_kat = $stmt_kat->get_result();

// Jika ID kategori tidak valid, alihkan ke halaman utama
if ($result_kat->num_rows == 0) {
    header('Location: index.php');
    exit;
}
$kategori_data = $result_kat->fetch_assoc();
$nama_kategori = $kategori_data['nama_kategori'];

// Set judul halaman
$title = "Kategori: " . htmlspecialchars($nama_kategori);

// Ambil semua artikel yang termasuk dalam kategori ini
$stmt_artikel = $conn->prepare("SELECT * FROM artikel WHERE id_kategori = ? ORDER BY tanggal DESC");
$stmt_artikel->bind_param("i", $id_kategori);
$stmt_artikel->execute();
$result = $stmt_artikel->get_result();

// 3. Panggil layout header setelah semua data siap
include 'layout_header.php';
?>

<div class="row">
    <main class="col-lg-8">
        <h2 class="mb-4 border-bottom pb-2">
            Menampilkan Kategori: "<?= htmlspecialchars($nama_kategori) ?>"
        </h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($artikel = $result->fetch_assoc()): ?>
                <div class="card mb-4 shadow-sm">
                    <?php if(!empty($artikel['gambar'])): ?>
                        <a href="article.php?id=<?= $artikel['id_artikel'] ?>">
                            <img src="image/<?= htmlspecialchars($artikel['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($artikel['Judul']) ?>" style="aspect-ratio: 16/9; object-fit: cover;">
                        </a>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="article.php?id=<?= $artikel['id_artikel'] ?>" class="text-decoration-none text-dark">
                                <?= htmlspecialchars($artikel['Judul']) ?>
                            </a>
                        </h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <?= formatTanggalIndonesia($artikel['tanggal']) ?>
                            </small>
                        </p>
                        <p class="card-text">
                            <?= substr(strip_tags($artikel['isi']), 0, 200) ?>...
                        </p>
                        <a href="article.php?id=<?= $artikel['id_artikel'] ?>" class="btn btn-primary">Baca Selengkapnya →</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">Belum ada artikel dalam kategori ini.</div>
        <?php endif; ?>
    </main>

    <?php include 'layout_sidebar.php'; ?>
</div>

<?php
// 5. Panggil layout footer
include 'layout_footer.php';
?>