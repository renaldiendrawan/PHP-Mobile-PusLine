<?php
// Include koneksi ke database
include 'connect.php';

// Inisialisasi array untuk response
$response = array();

// Mendapatkan data dari Postman dengan metode GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Periksa apakah id_artikel tersedia
    if (isset($_GET['id_artikel'])) {
        $id_artikel = $_GET['id_artikel'];

        // Query untuk mengambil data artikel berdasarkan id_artikel
        $query = "SELECT judul, img_artikel FROM artikel WHERE id_artikel = '$id_artikel'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Ambil data artikel dari hasil query
            $row = mysqli_fetch_assoc($result);

            // Konversi gambar ke base64
            $row['img_artikel'] = base64_encode($row['img_artikel']);

            // Set header untuk tipe konten JSON
            header('Content-Type: application/json');

            // Mengatur status dan data dalam respons
            $response['status'] = 'success';
            $response['data'] = $row; // Mengirim data judul dan img_artikel ke Postman
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Artikel tidak ditemukan';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'ID Artikel tidak diterima';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Metode request tidak diizinkan';
}

// Mengembalikan response ke Postman
echo json_encode($response);
?>