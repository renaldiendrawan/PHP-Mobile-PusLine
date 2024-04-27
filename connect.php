<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pukesline";
$conn = new mysqli($servername, $username, $password, $dbname);
// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>