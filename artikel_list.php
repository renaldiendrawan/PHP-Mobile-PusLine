<?php
// Include koneksi ke database
include 'connect.php';

// Inisialisasi array untuk response
$response = array();

// Mendapatkan data dari Postman dengan metode GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query untuk mengambil img_artikel dan judul dari tabel artikel
    $query = "SELECT id_artikel, img_artikel, judul FROM artikel";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $rowData = array();

        // Ambil semua img_artikel dan judul dari hasil query lalu simpan pada $rowData
        while ($row = mysqli_fetch_assoc($result)) {
            $rowData[] = $row;
        }


        $response['status'] = 'success';
        $response['data'] = $rowData; // Convert $rowData menjadi array
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Data img_artikel dan judul tidak ditemukan';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Metode request tidak diizinkan';
}

// Mengembalikan response ke Postman
echo json_encode($response);
?>