<?php
include 'database.php';

$error = ""; // Variabel untuk menampung pesan error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Cek apakah username sudah ada
    $existingUser = getData("SELECT * FROM users WHERE username = '$username'");

    if (!empty($existingUser)) {
        $error = "Username sudah digunakan, silakan pilih yang lain.";
    } else {
        // Jika username belum ada, lakukan insert
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

        if (executeQuery($query)) {
            header("Location: login.php"); // Redirect ke halaman login setelah registrasi sukses
            exit;
        } else {
            $error = "Registrasi gagal! Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
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
        <h2 class="text-3xl font-bold mb-4 text-center">Registrasi</h2>
        <?php if (isset($error)): ?>
            <p class="text-red-400 text-center mb-4"> <?php echo $error; ?> </p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="text-green-400 text-center mb-4"> <?php echo $success; ?> </p>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-lightGray">Username</label>
                <input type="text" name="username" required class="w-full px-4 py-2 bg-darker text-lightGray border border-gray-600 rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-lightGray">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 bg-darker text-lightGray border border-gray-600 rounded-lg">
            </div>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded w-full shadow-md hover:bg-blue-600">Register</button>
        </form>
        <p class="mt-4 text-center">Sudah punya akun? <a href="login.php" class="text-blue-400 hover:underline">Login</a></p>
    </div>
</body>
</html>