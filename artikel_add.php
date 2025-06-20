<?php
session_start();
// Pastikan hanya user yang sudah login bisa mengakses halaman ini
if (!isset($_SESSION['id_penulis'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';
include 'function.php'; // function.php dipanggil di sini untuk hariIndonesia() dan namaBulan()

// Set judul halaman
$title = "Tulis Artikel Baru";
include 'layout_header.php';
?>

<div class="row">
    <div class="col-md-9 col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h3 class="mb-0">Tulis Artikel Baru</h3>
            </div>
            <div class="card-body p-4">
                <form action="proses_artikel.php" method="POST" enctype="multipart/form-data">

                   

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-bold">Judul Artikel:</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label fw-bold">Kategori:</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <?php
                            $kategori_query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
                            while ($row = mysqli_fetch_assoc($kategori_query)) {
                                echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="isi" class="form-label fw-bold">Isi Artikel:</label>
                        <textarea class="form-control" id="isi" name="isi" rows="10"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="gambar" class="form-label fw-bold">Gambar Utama (Header):</label>
                        <input class="form-control" type="file" id="gambar" name="gambar" accept="image/*" required>
                        <small class="form-text text-muted">Pilih gambar yang representatif untuk artikel Anda.</small>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="index.php" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary" name="btn_simpan">Publikasikan Artikel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#isi' ), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'link', 'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            },
            language: 'id',
            placeholder: 'Tulis artikel terbaikmu di sini...',
        } )
        .catch( error => {
            console.error( 'Terjadi error saat memuat CKEditor:', error );
        } );
</script>

<?php
include 'layout_footer.php';
?>