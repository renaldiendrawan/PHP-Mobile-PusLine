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
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah data untuk update tersedia
    if (isset($_POST['nik']) && isset($_POST['nama']) && isset($_POST['tanggal_lahir']) && isset($_POST['jenis_kelamin']) && isset($_POST['email'])) {
        // Sanitasi input untuk mencegah SQL injection
        $nik = mysqli_real_escape_string($conn, $_POST['nik']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
        $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        // Handle file upload
        $img_profil = null;
        if (isset($_FILES['img_profil']) && $_FILES['img_profil']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "flutter/foto_profil/"; // Sesuaikan dengan jalur folder yang benar
            $target_file = $target_dir . basename($_FILES["img_profil"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["img_profil"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["img_profil"]["tmp_name"], $target_file)) {
                    $img_profil = basename($_FILES["img_profil"]["name"]); // Hanya menyimpan nama file di database
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Gagal mengupload gambar';
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'File bukan gambar';
                echo json_encode($response);
                exit;
            }
        }

        // Query untuk mengupdate data masyarakat
        $updateQuery = "UPDATE masyarakat SET nama = '$nama', tanggal_lahir = '$tanggal_lahir', jenis_kelamin = '$jenis_kelamin', email = '$email'";
        if ($img_profil) {
            $updateQuery .= ", img_profil = '$img_profil'";
        }
        $updateQuery .= " WHERE nik = '$nik'";

        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            $response['status'] = 'success';
            $response['message'] = 'Profil berhasil diupdate';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Gagal mengupdate profil';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Profil tidak lengkap untuk update';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Metode request tidak diizinkan';
}

// Mengembalikan response ke Postman
header('Content-Type: application/json');
echo json_encode($response);
?>