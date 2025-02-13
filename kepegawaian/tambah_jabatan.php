<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_jabatan'];
    executeQuery("INSERT INTO jabatan (nama_jabatan) VALUES ('$nama')");
    echo json_encode(["id" => $conn->insert_id, "nama" => $nama]);
}
?>
