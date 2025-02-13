<?php
session_start();
include 'database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Daftar gaji berdasarkan jabatan
$gajiJabatan = [
    'Manager' => 10000000,
    'Staff Senior' => 7000000,
    'Staff Junior' => 5000000,
    'Kepala Divisi' => 9000000,
    'Analis' => 6500000,
    'Direktur' => 15000000,
    'Administrator' => 6000000,
    'Trainee' => 4000000
];

// Ambil data penggajian hanya milik user yang login
$penggajian = getData("SELECT penggajian.id, pegawai.nama, penggajian.gaji, penggajian.tanggal_gaji 
                        FROM penggajian 
                        JOIN pegawai ON penggajian.pegawai_id = pegawai.id
                        WHERE pegawai.id_user = $user_id");

$totalGaji = array_sum(array_column($penggajian, 'gaji'));

$pegawai = getData("SELECT id, nama, tanggal_masuk, jabatan_id FROM pegawai WHERE id_user = $user_id");

// Tambah penggajian
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pegawai_id = intval($_POST['pegawai_id']);
    
    $pegawaiData = getData("SELECT tanggal_masuk, jabatan_id FROM pegawai WHERE id = $pegawai_id LIMIT 1");
    if (!empty($pegawaiData)) {
        $tanggal_masuk = $pegawaiData[0]['tanggal_masuk'];
        $jabatan = getData("SELECT nama_jabatan FROM jabatan WHERE id = " . intval($pegawaiData[0]['jabatan_id']) . " LIMIT 1");
        $jabatan_nama = $jabatan[0]['nama_jabatan'] ?? 'Trainee';
        
        $gaji = $gajiJabatan[$jabatan_nama] ?? 4000000;
        $tanggal_gaji = date('Y-m-d', strtotime($tanggal_masuk . ' +1 month'));
        
        executeQuery("INSERT INTO penggajian (pegawai_id, gaji, tanggal_gaji) VALUES ($pegawai_id, $gaji, '$tanggal_gaji')");
        header("Location: penggajian.php");
        exit;
    }
}


if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    executeQuery("DELETE FROM penggajian WHERE id = $id");
    header("Location: penggajian.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penggajian</title>
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
<body class="bg-darker text-lightGray flex">
    <aside class="w-64 bg-dark h-screen p-6 shadow-lg fixed">
        <h2 class="text-xl font-bold text-white mb-6">Menu</h2>
        <nav>
            <ul>
                <li class="mb-4">
                    <a href="index.php" class="block p-2 text-white bg-primary rounded hover:bg-blue-600">📋 Daftar Pegawai</a>
                </li>
                <li class="mb-4">
                    <a href="penggajian.php" class="block p-2 text-white bg-primary rounded hover:bg-blue-600">💰 Penggajian</a>
                </li>
                <li>
                    <a href="logout.php" class="block p-2 text-red-400 hover:text-red-300" onclick="return confirm('Yakin ingin logout?');">🚪 Logout</a>
                </li>
            </ul>
        </nav>
    </aside>
    
    <main class="ml-64 p-6 w-full">
        <div class="max-w-6xl mx-auto bg-dark p-6 rounded-lg shadow-lg">
            <h2 class="font-serif text-4xl font-bold mb-6 text-center text-gray-100">Data Penggajian</h2>
            <form method="POST" class="mb-6">
                <div class="flex gap-4">
                    <select name="pegawai_id" required class="px-4 py-2 border rounded bg-darker text-lightGray w-1/3">
                        <option value="">Pilih Pegawai</option>
                        <?php foreach ($pegawai as $p): ?>
                            <option value="<?php echo $p['id']; ?>"> <?php echo $p['nama']; ?> </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded shadow-md hover:bg-blue-600">Tambah Gaji</button>
                </div>
            </form>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-dark border border-gray-600 rounded-lg shadow-md">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left border border-gray-600">No</th>
                            <th class="px-4 py-3 text-left border border-gray-600">Nama Pegawai</th>
                            <th class="px-4 py-3 text-left border border-gray-600">Gaji</th>
                            <th class="px-4 py-3 text-left border border-gray-600">Tanggal Gaji</th>
                            <th class="px-4 py-3 text-center border border-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penggajian as $index => $row): ?>
                            <tr class="border border-gray-600 hover:bg-gray-700">
                                <td class="px-4 py-3 border border-gray-600"> <?php echo $index + 1; ?> </td>
                                <td class="px-4 py-3 border border-gray-600"> <?php echo $row['nama']; ?> </td>
                                <td class="px-4 py-3 border border-gray-600">Rp <?php echo number_format($row['gaji'], 2, ',', '.'); ?> </td>
                                <td class="px-4 py-3 border border-gray-600"> <?php echo $row['tanggal_gaji']; ?> </td>
                                <td class="px-4 py-3 border border-gray-600 text-center">
                                    <a href="penggajian.php?hapus=<?php echo $row['id']; ?>" class="text-red-400 hover:underline" onclick="return confirm('Yakin ingin menghapus?');">🗑️ Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="bg-gray-700 font-bold">
                            <td colspan="2" class="px-4 py-3 text-right border border-gray-600">Total pengeluaran gaji:</td>
                            <td class="px-4 py-3 border border-gray-600">Rp <?php echo number_format($totalGaji, 2, ',', '.'); ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
