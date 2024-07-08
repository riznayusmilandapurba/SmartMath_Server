<?php

header("Access-Control-Allow-Origin: *");
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $response = array();
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    // Aktifkan jika ingin memeriksa email, password, dan status verifikasi
    // $query = "SELECT * FROM users WHERE email = ? AND password = ? AND verification_code IS NOT NULL AND is_verified = 'verified'";
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $koneksi->prepare($query);
    
    if ($stmt) {
        // Aktifkan jika ingin memeriksa email dan password dengan prepared statement
        $stmt->bind_param("ss", $email, $password);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verifikasi status user (misalnya role)
            $response['value'] = 1;
            $response['message'] = "Berhasil login";
            $response['fullname'] = $user['fullname'];
            $response['email'] = $user['email'];
            $response['phone'] = $user['phone'];
            $response['address'] = $user['address'];
            $response['id_user'] = $user['id_user'];

            if ($user['role'] == '1') {
                $response['role'] = 'admin';
            } else if ($user['role'] == '2') {
                $response['role'] = 'student';
            } else {
                $response['role'] = 'unknown';
            }
        } else {
            $response['value'] = 0;
            $response['message'] = "Gagal login. Pastikan email, password benar, dan akun sudah diverifikasi serta kode OTP telah diterima.";
        }
    } else {
        // Handle error saat prepare statement
        $response['value'] = 0;
        $response['message'] = "Gagal login. Terjadi kesalahan dalam mempersiapkan query.";
    }

    echo json_encode($response);

    $stmt->close();
}
?>
