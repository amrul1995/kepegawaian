<?php
session_start();
include 'database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Ambil data pegawai hanya milik user yang login
$pegawai = getData("SELECT pegawai.id, pegawai.nama, departemen.nama_departemen, jabatan.nama_jabatan, pegawai.tanggal_masuk 
                    FROM pegawai 
                    LEFT JOIN departemen ON pegawai.departemen_id = departemen.id 
                    LEFT JOIN jabatan ON pegawai.jabatan_id = jabatan.id
                    WHERE pegawai.id_user = $user_id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pegawai</title>
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
    <!-- Sidebar -->
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

    <!-- Konten utama -->
    <main class="ml-64 p-6 w-full">
        <div class="max-w-6xl mx-auto bg-dark p-6 rounded-lg shadow-lg">
            <h2 class="font-serif text-4xl font-bold mb-6 text-center text-gray-100">Daftar Pegawai</h2>
            <div class="flex justify-between mb-4">
                <a href="tambah.php" class="bg-blue-700 text-white px-4 py-2 rounded shadow-md hover:bg-blue-600">Tambah Pegawai</a>
                <input type="text" id="search" placeholder="🔍 Cari" class="px-4 py-2 border rounded w-1/3 bg-darker text-lightGray">
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-dark border border-gray-600 rounded-lg shadow-md">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left border border-gray-600">No</th>
                            <th class="px-4 py-3 text-left border border-gray-600">Nama</th>
                            <th class="px-4 py-3 text-left border border-gray-600">Departemen</th>
                            <th class="px-4 py-3 text-left border border-gray-600">Jabatan</th>
                            <th class="px-4 py-3 text-left border border-gray-600">Tanggal Masuk</th>
                            <th class="px-4 py-3 text-center border border-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pegawaiTable">
                        <?php foreach ($pegawai as $index => $row): ?>
                            <tr class="border border-gray-600 hover:bg-gray-700">
                                <td class="px-4 py-3 border border-gray-600"> <?php echo $index + 1; ?> </td>
                                <td class="px-4 py-3 border border-gray-600"> <?php echo $row['nama']; ?> </td>
                                <td class="px-4 py-3 border border-gray-600"> <?php echo $row['nama_departemen']; ?> </td>
                                <td class="px-4 py-3 border border-gray-600"> <?php echo $row['nama_jabatan']; ?> </td>
                                <td class="px-4 py-3 border border-gray-600"> <?php echo $row['tanggal_masuk']; ?> </td>
                                <td class="px-4 py-3 border border-gray-600 text-center">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="text-primary hover:underline">✏️ Edit</a>
                                    |
                                    <a href="hapus.php?id=<?php echo $row['id']; ?>" class="text-red-400 hover:underline" onclick="return confirm('Yakin ingin menghapus?');">🗑️ Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        document.getElementById("search").addEventListener("keyup", function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#pegawaiTable tr");
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>
</html>
