<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Your Daily Need Articles</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tentang.php">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kontak.php">Kontak</a>
                </li>
            </ul>

            <div class="navbar-nav ps-lg-3">
                 <?php // Cek apakah pengguna sudah login
                if(isset($_SESSION['id_penulis'])): ?>

                    <div class="dropdown">
                        <button class="btn btn-dark d-flex align-items-center dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i>
                            <span><?= htmlspecialchars($_SESSION['nama_penulis']) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
                            <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person-fill me-2"></i> Profil Saya</a></li>
                            <li><a class="dropdown-item" href="artikel_add.php"><i class="bi bi-plus-square-fill me-2"></i> Tambah Artikel</a></li>
                            <li><a class="dropdown-item" href="artikel_penulis.php"><i class="bi bi-file-earmark-text-fill me-2"></i> Artikel Saya</a></li>
    
                              <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                <li><a class="dropdown-item" href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard Admin</a></li>
                            <?php endif; ?>

                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </div>

                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light">Login</a>
                <?php endif; ?>
            </div>

        </div> </div> </nav>

<div style="height: 70px;"></div>