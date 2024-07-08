
<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'koneksi.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $response = array();

    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        $email = mysqli_real_escape_string($koneksi, $email);

        $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
        $emailResult = mysqli_query($koneksi, $checkEmailQuery);

        if (mysqli_num_rows($emailResult) > 0) {
            $otp = rand(1000, 9999);

            $updateQuery = "UPDATE users SET verification_code='$otp' WHERE email='$email'";
            if (mysqli_query($koneksi, $updateQuery)) {
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com'; 
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'gamelyfe05@gmail.com'; 
                    $mail->Password   = 'lrlz shbs rhom ahow'; 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('otp@verif.com', 'Verification Code E-Commerce');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Verification Code E-Commerce [' . $otp . ']';
                    $mail->Body    = 'Masukkan kode OTP ini untuk memverifikasi akun Anda: ' . $otp;

                    $mail->send();
                    $response['value'] = 1;
                    $response['message'] = 'OTP berhasil dikirim';
                } catch (Exception $e) {
                    $response['value'] = 0;
                    $response['message'] = 'Gagal mengirim OTP: ' . $mail->ErrorInfo;
                }
            } else {
                $response['value'] = 0;
                $response['message'] = 'Gagal menyimpan OTP: ' . mysqli_error($koneksi);
            }
        } else {
            $response['value'] = 0;
            $response['message'] = 'Email tidak ditemukan';
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