<?php
include_once 'function.php';
date_default_timezone_set("Asia/Jakarta");
$hari = hariIndonesia(date("l"));
$tanggal_lengkap = date("d") . " " . namaBulan(date("m")) . " " . date("Y");
$waktu = date("H:i");
$hari_tanggal_waktu = "$hari, $tanggal_lengkap | $waktu";
?>

<div class="modal-header">
  <h4 class="modal-title">Tambah Artikel</h4>
  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
  <form action="proses_artikel.php" method="POST" enctype="multipart/form-data" id="formArtikel">
    <div class="mb-3">
      <label for="tanggal">Tanggal:</label>
      <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?= $hari_tanggal_waktu ?>" readonly>
    </div>

    <div class="mb-3">
      <label for="judul">Judul:</label>
      <input type="text" class="form-control" id="judul" name="judul" required>
    </div>

    <div class="mb-3">
      <label for="kategori">Kategori:</label>
      <select class="form-select" id="kategori" name="kategori" required>
        <?php
        include 'db.php';
        $kategori = mysqli_query($conn, "SELECT * FROM kategori");
        while ($row = mysqli_fetch_assoc($kategori)) {
          echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="isi">Isi Artikel:</label>
      <textarea class="form-control" id="isi" name="isi" rows="5" required></textarea>
    </div>

    <div class="mb-3">
      <label for="gambar">Gambar:</label>
      <input class="form-control" type="file" id="gambar" name="gambar" accept="image/*" required>
    </div>

    <div class="d-flex justify-content-end gap-2">
      <button type="button" class="btn btn-info" onclick="previewArtikel()">Preview</button>
      <button type="submit" class="btn btn-primary" name="btn_simpan">Simpan</button>
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    </div>
  </form>
</div>

<!-- Modal Preview -->
<div class="modal fade" id="previewModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Preview Artikel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="previewContent"></div>
    </div>
  </div>
</div>

<!-- JS Preview -->
<script>
function previewArtikel() {
  const judul = document.getElementById('judul').value || '(Tanpa Judul)';
  const isi = document.getElementById('isi').value || '(Tanpa Isi)';
  const tanggal = document.getElementById('tanggal').value;
  const kategori = document.getElementById('kategori').options[document.getElementById('kategori').selectedIndex].text;

  const htmlPreview = `
    <h2 style="color:#6200ea;">${judul}</h2>
    <p><em>${tanggal} | ${kategori}</em></p>
    <hr>
    <div>${isi.replace(/\n/g, '<br>')}</div>
  `;

  document.getElementById('previewContent').innerHTML = htmlPreview;
  const modal = new bootstrap.Modal(document.getElementById('previewModal'));
  modal.show();
}
</script>
