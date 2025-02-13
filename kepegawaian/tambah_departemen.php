<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_departemen'];
    executeQuery("INSERT INTO departemen (nama_departemen) VALUES ('$nama')");
    echo json_encode(["id" => $conn->insert_id, "nama" => $nama]);
}
?>
