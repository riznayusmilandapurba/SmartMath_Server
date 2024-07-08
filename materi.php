<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
// Menghubungkan file koneksi
include 'koneksi.php';

// Fungsi untuk mendapatkan semua materi
function getAllMateri()
{
    global $koneksi; // Mengakses variabel koneksi yang sudah didefinisikan di file koneksi.php

    // Query untuk mendapatkan semua data materi
    $query = "SELECT id_materi, id_kelas, title, content FROM materi";
    $result = mysqli_query($koneksi, $query);

    // Membuat array untuk menyimpan hasil
    $materi = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $materi[] = $row;
    }

    return $materi;
}

// Fungsi untuk mendapatkan materi berdasarkan id_kelas
function getMateriByIdKelas($id_kelas)
{
    global $koneksi; // Mengakses variabel koneksi yang sudah didefinisikan di file koneksi.php

    // Query untuk mendapatkan data materi berdasarkan id_kelas
    $query = "SELECT id_materi, id_kelas, title, content FROM materi WHERE id_kelas = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_kelas);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Membuat array untuk menyimpan hasil
    $materi = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $materi[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $materi;
}

// Mendapatkan id_kelas dari parameter URL jika ada
$id_kelas = isset($_GET['id_kelas']) ? $_GET['id_kelas'] : null;

if ($id_kelas !== null) {
    // Jika ada id_kelas, ambil materi berdasarkan id_kelas
    $materi = getMateriByIdKelas($id_kelas);

    if (count($materi) > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Berhasil mendapatkan data materi berdasarkan id_kelas',
            'data' => $materi
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tidak ada materi untuk kelas dengan id_kelas yang diberikan'
        ]);
    }
} else {
    // Jika tidak ada id_kelas, ambil semua materi
    $materi = getAllMateri();

    if (count($materi) > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Berhasil mendapatkan semua data materi',
            'data' => $materi
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Belum ada materi yang ditambahkan'
        ]);
    }
}

// Menutup koneksi
mysqli_close($koneksi);
