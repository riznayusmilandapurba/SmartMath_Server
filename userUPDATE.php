<?php

header("Access-Control-Allow-Origin: header");
header("Access-Control-Allow-Origin: *");

include 'koneksi.php';

$id_user = $_POST['id_user'];
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$sql = "UPDATE users SET fullname = '$fullname', phone = '$phone', address = '$address' WHERE id_user=$id_user";
$isSuccess = $koneksi->query($sql);

$res = [];
if ($isSuccess) {

    $cek = "SELECT * FROM users WHERE id_user = $id_user";
    $result = mysqli_fetch_assoc(mysqli_query($koneksi, $cek));

    $res['is_success'] = true;
    $res['value'] = 1;
    $res['message'] = "Berhasil edit data user";
    $res['fullname'] = $result['fullname'];
    $res['phone'] = $result['phone'];
    $res['address'] = $result['address'];
    $res['id_user'] = $result['id_user'];
} else {
    $res['is_success'] = false;
    $res['value'] = 0;
    $res['message'] = "Gagal edit data user";
}

echo json_encode($res);

?>
