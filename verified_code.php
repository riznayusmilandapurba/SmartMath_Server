<?php

include 'koneksi.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $response = array();

    if (isset($_POST['email']) && isset($_POST['verification_code'])) {

        $email = $_POST['email'];
        $otp = $_POST['verification_code'];

        $email = mysqli_real_escape_string($koneksi, $email);
        $otp = mysqli_real_escape_string($koneksi, $otp);

        $query = "SELECT * FROM users WHERE email='$email' AND verification_code='$otp'";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) > 0) {
            $response['value'] = 1;
            $response['message'] = 'OTP berhasil diverifikasi';
            
            $updateQuery = "UPDATE users SET is_verified = 'verified' WHERE email='$email'";
            if (mysqli_query($koneksi, $updateQuery)) {
                $response['message'] = 'Status user berhasil diperbarui';
            } else {
                $response['message'] = 'Gagal memperbarui status user';
            }
        } else {
            $response['value'] = 0;
            $response['message'] = 'OTP tidak valid atau telah kedaluwarsa';
        }
    } else {
        $response['value'] = 0;
        $response['message'] = 'Parameter yang diperlukan tidak ada';
    }

    echo json_encode($response);
} else {
    $response['value'] = 0;
    $response['message'] = 'Metode permintaan tidak valid';
    echo json_encode($response);
}

mysqli_close($koneksi);
?>