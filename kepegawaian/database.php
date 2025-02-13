<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = '10122422_IF-11_Kepegawaian';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk menjalankan query SELECT
function getData($query) {
    global $conn;
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

// Fungsi untuk menjalankan query INSERT, UPDATE, DELETE
function executeQuery($query) {
    global $conn;
    return $conn->query($query);
}
?>
