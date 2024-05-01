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
        $query = "SELECT a.id_artikel, a.judul, a.tanggal_publikasi, a.img_artikel, a.isi_artikel, p.nama AS nama_penulis 
                  FROM artikel a
                  INNER JOIN pegawai p ON a.nip = p.nip
                  WHERE a.id_artikel = '$id_artikel'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Ambil data artikel dari hasil query
            $row = mysqli_fetch_assoc($result);

            // Set header untuk tipe konten JSON
            header('Content-Type: application/json');

            // Mengatur status dan data dalam respons
            $response['status'] = 'success';
            $response['data'] = $row; // Mengirim data judul, tanggal_publikasi, img_artikel_path, isi_artikel, dan nama_penulis ke Postman
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
