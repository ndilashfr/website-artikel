<?php
session_start();
include 'admin_only.php'; // Proteksi admin
include 'db.php';         // Koneksi DB
include 'function.php';   // Fungsi-fungsi

// Ambil semua data untuk tiga tabel - diurutkan berdasarkan ID menaik (ASC)
$artikel_result = mysqli_query($conn, "SELECT artikel.*, kategori.nama_kategori FROM artikel JOIN kategori ON artikel.id_kategori = kategori.id_kategori ORDER BY artikel.id_artikel ASC");
$kategori_result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori ASC");
$penulis_result = mysqli_query($conn, "SELECT * FROM penulis ORDER BY id_penulis ASC");

// Set judul halaman dan panggil header standar kita
$title = "Admin Dashboard";
include 'layout_header.php';
?>

<div class="row g-0">
    <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebarMenu">
        <div class="position-sticky pt-4">
            <h5 class="px-3 mb-3">Admin Dashboard</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-bs-target="#tab-artikel" onclick="showTab(event)">📝 Artikel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-target="#tab-kategori" onclick="showTab(event)">🏷️ Kategori</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-target="#tab-penulis" onclick="showTab(event)">👤 Penulis</a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        
        <?php // Menampilkan pesan notifikasi
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-' . $_SESSION['message']['type'] . ' alert-dismissible fade show" role="alert">' . $_SESSION['message']['text'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            unset($_SESSION['message']);
        }
        ?>

        <div id="tab-artikel" class="tab-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Manajemen Artikel</h2>
                           </div>
                <div class="card-body">
                    <input type="text" id="searchArtikel" placeholder="Cari Judul Artikel..." class="form-control mb-3">
                    <div class="table-responsive">
                        <table id="tabelArtikel" class="table table-hover align-middle">
                            <thead>
                                <tr><th>ID</th><th>Judul</th><th>Tanggal</th><th>Kategori</th><th>Gambar</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($artikel_result)) : ?>
                                    <tr>
                                        <td><?= $row['id_artikel'] ?></td>
                                        <td><?= htmlspecialchars($row['Judul']) ?></td>
                                        <td><?= formatTanggalIndonesia($row['created_at']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td><img src="image/<?= $row['gambar'] ?>" width="100" class="img-thumbnail"></td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="artikel_edit.php?id=<?= $row['id_artikel'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="artikel_delete.php?id=<?= $row['id_artikel'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-kategori" class="tab-content d-none">
            <div class="card">
                <div class="card-header"><h2>Manajemen Kategori</h2></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr><th>ID</th><th>Nama Kategori</th><th>Keterangan</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($kategori_result)) : ?>
                                    <tr>
                                        <td><?= $row['id_kategori'] ?></td>
                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="kategori_edit.php?id=<?= $row['id_kategori'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="kategori_delete.php?id=<?= $row['id_kategori'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-penulis" class="tab-content d-none">
            <div class="card">
                <div class="card-header"><h2>Manajemen Penulis</h2></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr><th>ID</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                <?php mysqli_data_seek($penulis_result, 0); 
                                      while ($row = mysqli_fetch_assoc($penulis_result)) : ?>
                                    <tr>
                                        <td><?= $row['id_penulis'] ?></td>
                                        <td><?= htmlspecialchars($row['nama_penulis']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['role']) ?></td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-1">
                                                
                                                <a href="penulis_delete.php?id=<?= $row['id_penulis'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
    function showTab(evt) {
        evt.preventDefault();
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('d-none'));
        let target = document.querySelector(evt.currentTarget.getAttribute('data-bs-target'));
        if (target) {
            target.classList.remove('d-none');
        }
        document.querySelectorAll('.sidebar .nav-link').forEach(link => link.classList.remove('active'));
        evt.currentTarget.classList.add('active');
    }
    document.getElementById('searchArtikel').addEventListener('keyup', function () {
        let keyword = this.value.toLowerCase();
        document.querySelectorAll('#tabelArtikel tbody tr').forEach(row => {
            const text = row.querySelector('td:nth-child(2)').innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });
</script>

<?php
include 'layout_footer.php';
?>