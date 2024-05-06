<?php
// Include koneksi ke database
include 'connect.php';

// Inisialisasi array untuk response
$response = array();

// Mendapatkan data dari Postman dengan metode GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Periksa apakah nik tersedia
    if (isset($_GET['nik'])) {
        // Sanitasi input untuk mencegah SQL injection
        $nik = mysqli_real_escape_string($conn, $_GET['nik']);

        // Query untuk mengambil data masyarakat berdasarkan nik
        $query = "SELECT nik, nama, tanggal_lahir, jenis_kelamin, email, img_profil FROM masyarakat WHERE nik = '$nik'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $rowData = array();

            // Ambil semua data pada $result lalu simpan pada $rowData
            while ($row = mysqli_fetch_assoc($result)) {
                $rowData[] = $row;
            }

            $response['status'] = 'success';
            $response['data'] = $rowData[0]; // Convert $rowData menjadi array
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Profil tidak ditemukan';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'NIK tidak diterima';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Metode request tidak diizinkan';
}

// Mengembalikan response ke Postman
header('Content-Type: application/json');
echo json_encode($response);
?>