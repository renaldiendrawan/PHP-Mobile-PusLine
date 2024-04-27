<?php
// Sambungkan ke database
require_once 'connect.php';

// Mendapatkan data dari request
$data = json_decode(file_get_contents("php://input"));

// Pastikan semua data yang diperlukan tersedia
if (isset($data->nik, $data->nama, $data->jenis_kelamin, $data->tanggal_lahir, $data->no_telepon, $data->kata_sandi)) {
    $nik = $data->nik;
    $nama = $data->nama;
    $jenis_kelamin = $data->jenis_kelamin;
    $tanggal_lahir = $data->tanggal_lahir;
    $no_telepon = $data->no_telepon;
    $kata_sandi = $data->kata_sandi;

    // Validasi nomor telepon
    if (!preg_match("/^[0-9]{10,15}$/", $no_telepon)) {
        $response = array("status" => "error", "message" => "Format nomor telepon tidak valid");
        echo json_encode($response);
        exit();
    }

    // // Hashing kata sandi
    // $hashedPassword = password_hash($kata_sandi, PASSWORD_DEFAULT);

    // Lakukan query untuk menambahkan user
    $query = "INSERT INTO masyarakat (nik, nama, jenis_kelamin, tanggal_lahir, no_telepon, kata_sandi) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    // Periksa apakah pernyataan SQL berhasil diprepare
    if (!$stmt) {
        $response = array("status" => "error", "message" => "Pemrosesan permintaan gagal: " . mysqli_error($conn));
        echo json_encode($response);
        exit();
    }
    
    // Bind parameter
    mysqli_stmt_bind_param($stmt, "ssssss", $nik, $nama, $jenis_kelamin, $tanggal_lahir, $no_telepon, $kata_sandi);

    // Eksekusi pernyataan SQL
    if (mysqli_stmt_execute($stmt)) {
        $response = array("status" => "success", "message" => "Pengguna berhasil mendaftar");
        echo json_encode($response);
    } else {
        // Jika eksekusi gagal, kirimkan pesan kesalahan
        $response = array("status" => "error", "message" => "Gagal mendaftarkan pengguna: " . mysqli_error($conn));
        echo json_encode($response);
    }

    // Tutup pernyataan
    mysqli_stmt_close($stmt);
} else {
    // Jika data yang diperlukan tidak tersedia, kirimkan respons gagal
    $response = array("status" => "error", "message" => "Semua field harus diisi");
    echo json_encode($response);
}
?>
