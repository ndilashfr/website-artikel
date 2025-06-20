<?php
include 'db.php';
include 'admin_only.php';


if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  // Hapus gambar dari folder
  $result = mysqli_query($conn, "SELECT gambar FROM artikel WHERE id_artikel = $id");
  $data = mysqli_fetch_assoc($result);
  if ($data && file_exists("image/" . $data['gambar'])) {
    unlink("image/" . $data['gambar']);
  }

  // Hapus dari DB
  mysqli_query($conn, "DELETE FROM artikel WHERE id_artikel = $id");
}

header("Location: artikel_list.php");
