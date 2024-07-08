<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'koneksi.php';

// Memeriksa apakah parameter 'id_kelas' ada di URL
if (isset($_GET['id_kelas'])) {
    $id_kelas = $_GET['id_kelas'];

    // Query untuk mendapatkan soal latihan berdasarkan kategori kelas
    $sql = "SELECT latihan.id_latihan, latihan.soal, latihan.soal, latihan.option_A, latihan.option_B, latihan.option_C, latihan.option_D, latihan.option_E, kelas.nama_kelas 
            FROM latihan 
            JOIN kelas ON latihan.id_kelas = kelas.id_kelas 
            WHERE latihan.id_kelas = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_kelas);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika data ditemukan, kirim respons dengan status success
    if ($result->num_rows > 0) {
        $response['isSuccess'] = true;
        $response['message'] = "Berhasil Menampilkan Latihan";
        $response['data'] = array();
        while ($row = $result->fetch_assoc()) {
            $response['data'][] = $row;
        }
    } else {
        // Jika data tidak ditemukan, kirim respons dengan status error
        $response['isSuccess'] = false;
        $response['message'] = "Gagal menampilkan Latihan";
        $response['data'] = null;
    }
    $stmt->close();
} else {
    // Jika parameter 'id_kelas' tidak ada, kirim respons dengan status error
    $response['isSuccess'] = false;
    $response['message'] = "Parameter 'id_kelas' diperlukan";
    $response['data'] = null;
}

echo json_encode($response);

?>
