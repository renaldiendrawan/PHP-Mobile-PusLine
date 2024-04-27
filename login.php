<?php
require_once 'connect.php';

// Tangani permintaan login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $nik = $data->nik;
    $kata_sandi = $data->kata_sandi;

    // Lakukan validasi login
    $sql = "SELECT * FROM masyarakat WHERE nik='$nik' AND kata_sandi='$kata_sandi'";
    $result = $conn->query($sql);

    // // Hashing kata sandi
    // $hashedPassword = password_hash($kata_sandi, PASSWORD_DEFAULT);

    if ($result->num_rows > 0) {
        // Login berhasil
        $row = $result->fetch_assoc();
        $response = array(
            'status' => 'success',
            'message' => 'Login berhasil',
            'nik' => $row['nik'],
            'kata_sandi' => $row['kata_sandi']
        );
    } else {
        // Login gagal
        $response = array(
            'status' => 'error',
            'message' => 'Login gagal. Username atau password salah.'
        );
    }

    echo json_encode($response);
}

$conn->close();
?>