<?php

// Koneksi ke database atau file konfigurasi lainnya
include 'koneksi.php';

// Tambahkan header CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Tangani permintaan OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Ambil data dari POST request
$input = json_decode(file_get_contents('php://input'), true);
$id_latihan = $input['id_latihan'];
$submission_data = $input['submission_data']; // Ini bisa berupa data JSON atau format lain sesuai kebutuhan

$response = [];

// Proses untuk mengambil jawaban benar dari database
$sql_correct_answer = "SELECT answer FROM latihan WHERE id_latihan = '$id_latihan'";
$result_correct_answer = mysqli_query($koneksi, $sql_correct_answer);

if ($result_correct_answer && mysqli_num_rows($result_correct_answer) > 0) {
    $row = mysqli_fetch_assoc($result_correct_answer);
    $correct_answer = $row['answer'];

    // Bandingkan jawaban pengguna dengan jawaban yang benar
    if ($submission_data == $correct_answer) {
        $score = 10;
    } else {
        $score = 0;
    }

    // Proses untuk menyimpan data jawaban ke dalam database
    $sql = "INSERT INTO user_latihan (id_latihan, submission_data, score) VALUES ('$id_latihan', '$submission_data', '$score')";
    $result = mysqli_query($koneksi, $sql);

    if ($result) {
        $response['success'] = true;
        $response['message'] = "Jawaban berhasil disubmit dengan score $score";
    } else {
        $response['success'] = false;
        $response['message'] = "Gagal menyimpan jawaban";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Gagal mengambil jawaban yang benar dari database";
}

// Mengembalikan respons dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);

// Log the response for debugging
file_put_contents('php://stderr', print_r($response, TRUE));
?>
