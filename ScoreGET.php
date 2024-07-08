<?php

// Koneksi ke database atau file konfigurasi lainnya
include 'koneksi.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Tangani permintaan OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Ambil data dari POST request
$id_kelas = $_POST['id_kelas'];

// Query untuk mendapatkan total skor berdasarkan id_kelas
$sql = "
    SELECT SUM(ul.score) AS total_score
    FROM user_latihan ul
    JOIN latihan l ON ul.id_latihan = l.id_latihan
    WHERE l.id_kelas = '$id_kelas'
";
$result = mysqli_query($koneksi, $sql);

$response = [];

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $total_score = $row['total_score'];

    $response['success'] = true;
    $response['total_score'] = $total_score;
} else {
    $response['success'] = false;
    $response['message'] = "Gagal mengambil total skor dari database";
}

// Mengembalikan respons dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
