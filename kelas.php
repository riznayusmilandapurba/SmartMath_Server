<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getKelas();
        break;
    case 'POST':
        createKelas();
        break;
    case 'PUT':
        updateKelas();
        break;
    case 'DELETE':
        deleteKelas();
        break;
    default:
        echo json_encode(["message" => "Metode tidak valid"]);
        break;
}

function getKelas()
{
    global $koneksi;

    $sql = "SELECT * FROM kelas";
    $result = mysqli_query($koneksi, $sql);

    $response = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response[] = $row;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Berhasil mendapatkan data kelas',
            'data' => $response
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tidak ada data kelas yang ditemukan'
        ]);
    }
}


function createKelas()
{
    global $koneksi;
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_POST['nama_kelas'])) {
        $nama_kelas = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);
    } elseif ($data !== null && json_last_error() === JSON_ERROR_NONE && isset($data['nama_kelas'])) {
        $nama_kelas = mysqli_real_escape_string($koneksi, $data['nama_kelas']);
    } else {
        echo json_encode(["message" => "Data nama_kelas tidak ditemukan"]);
        return;
    }

    $sql = "INSERT INTO kelas (nama_kelas) VALUES ('$nama_kelas')";

    if (mysqli_query($koneksi, $sql)) {
        $new_kelas_id = mysqli_insert_id($koneksi); // Ambil ID kelas yang baru saja dibuat
        $result = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas = $new_kelas_id");
        $new_kelas = mysqli_fetch_assoc($result);

        echo json_encode([
            "message" => "Kelas berhasil dibuat",
            "kelas" => $new_kelas
        ]);
    } else {
        echo json_encode(["message" => "Gagal membuat kelas", "error" => mysqli_error($koneksi)]);
    }
}


function updateKelas()
{
    global $koneksi;

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id_kelas']) && isset($data['nama_kelas'])) {
        $id_kelas = mysqli_real_escape_string($koneksi, $data['id_kelas']);
        $nama_kelas = mysqli_real_escape_string($koneksi, $data['nama_kelas']);

        $sql = "UPDATE kelas SET nama_kelas='$nama_kelas' WHERE id_kelas=$id_kelas";

        if (mysqli_query($koneksi, $sql)) {
            $res = [
                'is_success' => true,
                'value' => 1,
                'message' => 'Berhasil edit data kelas'
            ];

            // Ambil data kelas setelah diupdate
            $cek = "SELECT * FROM kelas WHERE id_kelas = $id_kelas";
            $result = mysqli_query($koneksi, $cek);
            $row = mysqli_fetch_assoc($result);

            $res['nama_kelas'] = $row['nama_kelas'];
            $res['id_kelas'] = $row['id_kelas'];

            echo json_encode($res);
        } else {
            echo json_encode([
                'is_success' => false,
                'value' => 0,
                'message' => 'Gagal edit data kelas',
                'error' => mysqli_error($koneksi)
            ]);
        }
    } else {
        echo json_encode(["message" => "Data tidak lengkap"]);
    }
}

function deleteKelas()
{
    global $koneksi;
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id_kelas'])) {
        $id_kelas = mysqli_real_escape_string($koneksi, $data['id_kelas']);
    } elseif (isset($_POST['id_kelas'])) {
        $id_kelas = mysqli_real_escape_string($koneksi, $_POST['id_kelas']);
    } else {
        echo json_encode(["message" => "Data id_kelas tidak ditemukan"]);
        return;
    }

    $sql = "DELETE FROM kelas WHERE id_kelas=$id_kelas";

    if (mysqli_query($koneksi, $sql)) {
        echo json_encode(["message" => "Kelas berhasil dihapus"]);
    } else {
        echo json_encode(["message" => "Gagal menghapus kelas", "error" => mysqli_error($koneksi)]);
    }
}