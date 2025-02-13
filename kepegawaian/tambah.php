<?php
session_start();
include 'database.php';

// Ambil data departemen dan jabatan untuk dropdown
$departemen = getData("SELECT * FROM departemen");
$jabatan = getData("SELECT * FROM jabatan");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $departemen_id = $_POST['departemen_id'];
    $jabatan_id = $_POST['jabatan_id'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $id_user = $_SESSION['user_id']; // Ambil ID user yang sedang login

    $query = "INSERT INTO pegawai (nama, departemen_id, jabatan_id, tanggal_masuk, id_user) 
              VALUES ('$nama', '$departemen_id', '$jabatan_id', '$tanggal_masuk', '$id_user')";

    if (executeQuery($query)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Gagal menambahkan pegawai!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#1E293B',
                        darker: '#0F172A',
                        lightGray: '#CBD5E1',
                        primary: '#3B82F6'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-darker flex justify-center items-center h-screen text-lightGray">
    <div class="bg-dark p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-3xl font-bold mb-4 text-center">Tambah Pegawai</h2>
        <?php if (isset($error)): ?>
            <p class="text-red-400 text-center mb-4"> <?php echo $error; ?> </p>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-lightGray">Nama Pegawai</label>
                <input type="text" name="nama" required class="w-full px-4 py-2 bg-darker text-lightGray border border-gray-600 rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-lightGray">Departemen</label>
                <select name="departemen_id" required class="w-full px-4 py-2 bg-darker text-lightGray border border-gray-600 rounded-lg">
                    <?php foreach ($departemen as $d): ?>
                        <option value="<?php echo $d['id']; ?>"> <?php echo $d['nama_departemen']; ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-lightGray">Jabatan</label>
                <select name="jabatan_id" required class="w-full px-4 py-2 bg-darker text-lightGray border border-gray-600 rounded-lg">
                    <?php foreach ($jabatan as $j): ?>
                        <option value="<?php echo $j['id']; ?>"> <?php echo $j['nama_jabatan']; ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-lightGray">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" required class="w-full px-4 py-2 bg-darker text-lightGray border border-gray-600 rounded-lg">
            </div>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded w-full shadow-md hover:bg-blue-600">Tambah</button>
        </form>
    </div>
</body>
</html>
