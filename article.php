<?php
session_start();
// 1. PANGGIL SEMUA FILE YANG DIBUTUHKAN
include 'db.php';
include 'function.php';

// 2. AMBIL DATA ARTIKEL & ARTIKEL TERKAIT
$data = null;
$related_posts = [];
$title = "Artikel Tidak Ditemukan";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data artikel utama
    $stmt = $conn->prepare("SELECT a.*, k.nama_kategori 
                            FROM artikel a 
                            LEFT JOIN kategori k ON a.id_kategori = k.id_kategori 
                            WHERE a.id_artikel = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $title = htmlspecialchars($data['Judul']);

        // Jika artikel ditemukan, ambil artikel terkait dari kategori yang sama
        $id_kategori_terkait = $data['id_kategori'];
        // DIUBAH: Mengurutkan berdasarkan created_at agar konsisten
        $stmt_terkait = $conn->prepare("SELECT id_artikel, Judul FROM artikel 
                                        WHERE id_kategori = ? AND id_artikel != ? 
                                        ORDER BY created_at DESC LIMIT 5");
        $stmt_terkait->bind_param("ii", $id_kategori_terkait, $id);
        $stmt_terkait->execute();
        $related_posts = $stmt_terkait->get_result();
    }
}

// 3. PANGGIL LAYOUT HEADER
include 'layout_header.php';
?>

<?php
// 4. TAMPILKAN KONTEN UTAMA
if ($data):
?>
    <div class="row">
        <main class="col-lg-8">
            <div class="article-container">
                <h1 class="article-title mb-2"><?= htmlspecialchars($data['Judul']) ?></h1>
                
                <div class="article-meta text-muted mb-4">
                    <span><?= formatTanggalIndonesia($data['created_at']) ?></span> | 
                    <a href="kategori_artikel.php?id=<?= $data['id_kategori'] ?>"><?= htmlspecialchars($data['nama_kategori']) ?></a>
                </div>

                <?php if (!empty($data['gambar'])): ?>
                    <img src="image/<?= htmlspecialchars($data['gambar']) ?>" class="img-fluid rounded mb-4" alt="<?= htmlspecialchars($data['Judul']) ?>">
                <?php endif; ?>

                <div class="article-content">
                    <?= $data['isi'] ?>
                </div>

                <hr class="my-5">

                <div class="comments-section">
                    <?php include 'komentar.php'; ?>
                </div>

                <div class="mt-5">
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </main>

        <aside class="col-lg-4">
            <?php include 'widget_pencarian.php'; ?>

            <?php if ($related_posts && $related_posts->num_rows > 0): ?>
            <div class="card mb-4">
                <h5 class="card-header">Terkait di: <?= htmlspecialchars($data['nama_kategori']) ?></h5>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <?php while ($post = $related_posts->fetch_assoc()): ?>
                            <li>
                                <a href="article.php?id=<?= $post['id_artikel'] ?>"><?= htmlspecialchars($post['Judul']) ?></a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </aside>
    </div>

<?php else:
    echo "<div class='alert alert-danger text-center'>Artikel yang Anda cari tidak ditemukan.</div>";
endif;
?>

<?php
// 5. PANGGIL FOOTER
include 'layout_footer.php';
?>