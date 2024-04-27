<?php
// Include koneksi ke database
include 'connect.php';

// Inisialisasi array untuk response
$response = array();

// Mendapatkan data dari Postman dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah data yang dibutuhkan tersedia
    if(isset($_POST['nik'], $_POST['id_poli'], $_POST['tanggal_pendaftaran'], $_POST['deskripsi_keluhan'], $_POST['status_pendaftaran'], $_POST['antrian'])) {
        $nik = $_POST['nik'];
        $id_poli = $_POST['id_poli'];
        $tanggal_pendaftaran = $_POST['tanggal_pendaftaran'];
        $deskripsi_keluhan = $_POST['deskripsi_keluhan'];
        $status_pendaftaran = $_POST['status_pendaftaran'];
        $antrian = $_POST['antrian'];

        // Periksa apakah nilai status_pendaftaran valid
        $allowed_statuses = array('Diterima', 'Diproses', 'Ditolak');
        if (in_array($status_pendaftaran, $allowed_statuses)) {
            // Masukkan nilai ke dalam database
            $query = "INSERT INTO pendaftaran (nik, id_poli, tanggal_pendaftaran, deskripsi_keluhan, status_pendaftaran, antrian) 
            VALUES ('$nik','$id_poli','$tanggal_pendaftaran','$deskripsi_keluhan','$status_pendaftaran','$antrian')";

            $result = mysqli_query($conn, $query);

            if ($result) {
                $response['status'] = 'success';
                $response['message'] = 'Berhasil mendaftar poli';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Gagal mendaftar poli. ' . mysqli_error($conn); // Memberikan pesan error dari MySQL
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Status pendaftaran tidak valid. Harus memilih dari Diterima, Diproses, atau Ditolak';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Data yang diterima tidak lengkap';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Metode request tidak diizinkan';
}

// Mengembalikan response ke Postman
echo json_encode($response);
?>
