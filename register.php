<?php

header("Access-Control-Allow-Origin: *");
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $response = array();

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $cek = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $cek);


    $role = (strpos($email, 'admin') !== false) ? 1 : 2; // Role ID 1 untuk admin, Role ID 2 untuk customer

    $insert = "INSERT INTO users (fullname, email, password, phone, address, role) 
               VALUES (?, ?, ?, ?, ?, ?)"; // Tambahkan kolom role

    $stmt = $koneksi->prepare($insert);
    $stmt->bind_param("sssssi", $fullname, $email, $password, $phone, $address, $role); // Sesuaikan binding parameter

    if ($stmt->execute()) {
        $response['value'] = 1;
        $response['message'] = "Berhasil Tambah Data";
    } else {
        $response['value'] = 0;
        $response['message'] = "Tambah Data: " . $stmt->error;
    }
    $stmt->close();
} else {
    $response['value'] = 0;
    $response['message'] = "Metode permintaan tidak valid";
}

echo json_encode($response);

?>