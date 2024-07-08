<?php
// Sertakan file koneksi.php untuk mengatur koneksi ke database
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'koneksi.php';

// Tangkap data dari request
$id_user = $_POST['id_user']; // Menggunakan POST karena data sensitif
$rating = $_POST['rating'];

// Query untuk menyimpan rating
$sql = "INSERT INTO ratings (id_user, rating) VALUES ('$id_user', '$rating')";

if ($conn->query($sql) === TRUE) {
    $response = array(
        'success' => true,
        'message' => 'Rating berhasil disimpan.'
    );
} else {
    $response = array(
        'success' => false,
        'message' => 'Gagal menyimpan rating: ' . $conn->error
    );
}

// Mengembalikan response dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
