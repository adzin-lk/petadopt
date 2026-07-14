<?php
session_start();
require_once '../config/koneksi.php';

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username = '$username'");
    
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        if (password_verify($password, $row['password'])) {
            $_SESSION['status_login'] = true;
            $_SESSION['admin_username'] = $row['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Password salah!';
        }
    } else {
        $error = 'Username tidak ditemukan!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PetAdopt</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .bg-architect-grid {
            background-color: #FAF8F5 !important;
            background-image: 
                linear-gradient(rgba(30, 63, 32, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30, 63, 32, 0.06) 1px, transparent 1px) !important;
            background-size: 80px 80px !important;
        }
    </style>
</head>
<body class="bg-[#FAF8F5] min-h-screen flex items-center justify-center p-6 relative overflow-hidden bg-architect-grid">
    


    <!-- Login Card -->
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 relative z-10 border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold tracking-wider text-[#1E3F20]">PetAdopt<span class="text-[#E07A5F]">.</span></h1>
            <p class="text-gray-500 mt-2">Selamat datang kembali! Silakan login untuk mengelola data hewan.</p>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-50 text-red-500 p-4 rounded-lg mb-6 flex items-center gap-3 text-sm font-medium border border-red-100">
            <i class="fas fa-exclamation-circle"></i>
            <?= $error; ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" name="username" id="username" required 
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E3F20]/50 focus:border-[#1E3F20] transition-colors"
                           placeholder="Masukkan username">
                </div>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" id="password" required 
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E3F20]/50 focus:border-[#1E3F20] transition-colors"
                           placeholder="Masukkan password">
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-[#1E3F20] hover:bg-[#152e17] text-white font-medium py-2.5 rounded-lg transition-colors shadow-md shadow-[#1E3F20]/20 flex justify-center items-center gap-2 mt-2">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </form>
    </div>

</body>
</html>
