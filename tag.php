<?php
session_start();
// 1. Panggil semua file yang dibutuhkan
include 'db.php';
include 'function.php';

// 2. Ambil nama tag dari URL dan validasi
if (!isset($_GET['tag']) || empty($_GET['tag'])) {
    // Jika tidak ada parameter tag, redirect ke halaman utama
    header('Location: index.php');
    exit();
}
// urldecode() untuk mengubah kembali format URL (misal: 'wisata%20alam' menjadi 'wisata alam')
$nama_tag_url = urldecode($_GET['tag']);


// 3. Query untuk mengambil semua artikel dengan tag tersebut
// Ini menggunakan JOIN pada 3 tabel: artikel, artikel_tag, dan tags
$stmt = $conn->prepare("SELECT a.*, k.nama_kategori
                        FROM artikel a
                        JOIN artikel_tag at ON a.id_artikel = at.id_artikel
                        JOIN tags t ON at.id_tag = t.id_tag
                        LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
                        WHERE t.nama_tag = ?
                        ORDER BY a.created_at DESC");
$stmt->bind_param("s", $nama_tag_url);
$stmt->execute();
$result = $stmt->get_result();

$articles_with_tag = [];
while ($row = $result->fetch_assoc()) {
    $articles_with_tag[] = $row;
}


// 4. Set judul halaman dan panggil header
$title = 'Artikel dengan Tag: #' . htmlspecialchars($nama_tag_url);
include 'layout_header.php';
?>

<div class="row">
    <main class="col-lg-8">
        <h2 class="mb-4 border-bottom pb-2">
            Menampilkan Tag: <strong>#<?= htmlspecialchars($nama_tag_url) ?></strong>
        </h2>

        <?php if (!empty($articles_with_tag)): ?>
            <?php foreach ($articles_with_tag as $artikel): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="row g-0">
                        <div class="col-md-4">
                             <a href="article.php?id=<?= $artikel['id_artikel'] ?>">
                                <img src="image/<?= htmlspecialchars($artikel['gambar']) ?>" class="img-fluid rounded-start" alt="<?= htmlspecialchars($artikel['Judul']) ?>" style="height: 100%; object-fit: cover;">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="article.php?id=<?= $artikel['id_artikel'] ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($artikel['Judul']) ?>
                                    </a>
                                </h5>
                                <p class="card-text"><small class="text-muted"><?= formatTanggalIndonesia($artikel['created_at']) ?> | <a href="kategori_artikel.php?id=<?= $artikel['id_kategori'] ?>"><?= htmlspecialchars($artikel['nama_kategori']) ?></a></small></p>
                                <p class="card-text"><?= substr(strip_tags($artikel['isi']), 0, 150) ?>...</p>
                                <a href="article.php?id=<?= $artikel['id_artikel'] ?>" class="btn btn-primary btn-sm">Baca Selengkapnya →</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">Tidak ada artikel yang ditemukan dengan tag ini.</div>
        <?php endif; ?>
    </main>

    <?php include 'layout_sidebar.php'; ?>
</div>

<?php
// 6. Panggil footer
include 'layout_footer.php';
?>