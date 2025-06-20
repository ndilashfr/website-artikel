<?php
// File: function.php

// ... (fungsi hariIndonesia dan namaBulan Anda yang lain tetap di sini) ...

function hariIndonesia($day) {
    $map = [
        "Sunday" => "Minggu", "Monday" => "Senin", "Tuesday" => "Selasa",
        "Wednesday" => "Rabu", "Thursday" => "Kamis", "Friday" => "Jumat",
        "Saturday" => "Sabtu"
    ];
    return $map[$day] ?? $day;
}

function namaBulan($bulan) {
    $bulanArr = [
        "01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", 
        "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus", 
        "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember"
    ];
    return $bulanArr[$bulan] ?? $bulan;
}

// ======================================================
// GANTI FUNGSI LAMA DENGAN VERSI BARU YANG LEBIH PINTAR INI
// ======================================================
function formatTanggalIndonesia($tanggal_db) {
    // LANGKAH PENGAMANAN: Cek apakah inputnya kosong atau tidak valid
    if (empty($tanggal_db) || $tanggal_db === '0000-00-00 00:00:00') {
        return "Tanggal tidak dipublikasi"; // Kembalikan teks alternatif yang lebih baik
    }

    // Ubah string tanggal dari database menjadi timestamp
    $timestamp = strtotime($tanggal_db);
    
    // Cek lagi jika strtotime gagal mengkonversi
    if ($timestamp === false) {
        // Jika formatnya aneh, kembalikan saja teks aslinya
        return htmlspecialchars($tanggal_db); 
    }

    // Ambil bagian-bagian tanggal
    $tgl = date('d', $timestamp);
    $bln = date('m', $timestamp);
    $thn = date('Y', $timestamp);

    // Panggil fungsi namaBulan() yang sudah ada
    $nama_bulan_indonesia = namaBulan($bln);

    // Gabungkan kembali menjadi format "20 Juni 2025"
    return $tgl . ' ' . $nama_bulan_indonesia . ' ' . $thn;
}
?>