<aside class="col-lg-4">
    
    <?php 
    // Memanggil komponen pencarian yang sudah kita buat
    include 'widget_pencarian.php'; 
    ?>

    <div class="card mb-4">
        <h5 class="card-header">Kategori</h5>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                <?php
                // Pastikan koneksi db sudah ada dari file utama (index.php, dll)
                if (isset($conn)) {
                    $kategori_sidebar_query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
                    while($kat = mysqli_fetch_assoc($kategori_sidebar_query)): ?>
                        <li><a href="kategori_artikel.php?id=<?= $kat['id_kategori'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></a></li>
                    <?php endwhile; 
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="card mb-4">
        <h5 class="card-header">Tentang</h5>
        <div class="card-body">
        Stuck di rutinitas? Let's go! 🚀 Your Daily Need Articles adalah cheat sheet-mu buat upgrade hari-hari. Kita kurasiin tempat makan paling viral, spot foto yang auto-masuk FYP, dan semua hal seru lainnya. No more FOMO, just pure good vibes. Let's make every day an adventure!
        </div>
    </div>

</aside>