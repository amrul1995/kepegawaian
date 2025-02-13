<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM pegawai WHERE id = $id";
    
    if (executeQuery($query)) {
        header('Location: index.php');
        exit;
    } else {
        echo "<script>alert('Gagal menghapus pegawai!'); window.location.href='index.php';</script>";
    }
} else {
    header('Location: index.php');
    exit;
}
?>