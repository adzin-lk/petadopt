<?php
session_start();
require_once '../config/koneksi.php';

// Cek apakah parameter id_hewan ada di URL
if (!isset($_GET['id_hewan'])) {
    header("Location: index.php");
    exit;
}

$id_hewan = mysqli_real_escape_string($koneksi, $_GET['id_hewan']);
$query = "SELECT * FROM hewan WHERE id_hewan = '$id_hewan'";
$result = mysqli_query($koneksi, $query);

// Jika data tidak ditemukan, kembali ke index.php
if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit;
}

$hewan = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Hewan - PetAdopt Admin</title>
    
    <!-- Tailwind CSS (Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome untuk Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts: Inter -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#FAF8F5] text-gray-800 flex flex-col min-h-screen">

    <!-- Top Navbar -->
    <nav class="bg-[#1E3F20] text-white shadow-md z-10 w-full shrink-0">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex items-center justify-between h-16">
                <!-- Logo Kiri -->
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center gap-2">
                        <h1 class="text-xl font-bold tracking-wider text-white">PetAdopt<span class="text-[#E07A5F]">.</span></h1>
                        <span class="hidden sm:inline-block text-sm text-gray-300 ml-2 bg-white/10 px-2.5 py-0.5 rounded-full font-medium">Admin Panel</span>
                    </a>
                </div>
                
                <!-- Menu Kanan (Desktop) -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="dashboard.php" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition-all flex items-center gap-2">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a href="index.php" class="px-4 py-2 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-all flex items-center gap-2">
                        <i class="fas fa-paw"></i> Data Hewan
                    </a>
                    <a href="../index.php" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition-all flex items-center gap-2">
                        <i class="fas fa-home"></i> Halaman Depan
                    </a>
                    <div class="h-5 w-px bg-white/20 mx-2"></div>
                    <a href="logout.php" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-red-300 hover:bg-red-500/20 transition-all flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>

                <!-- Hamburger Menu (Mobile) -->
                <div class="md:hidden flex items-center">
                    <button class="text-gray-300 hover:text-white focus:outline-none p-2 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-1 w-full bg-[#FAF8F5]">
        <!-- Content Area -->
        <div class="w-full p-6 lg:p-10">
            <div class="max-w-3xl mx-auto">
                
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Data Hewan</h2>
                    <p class="text-gray-500 mt-2 text-sm md:text-base">Ubah informasi profil hewan peliharaan yang telah terdaftar di sistem.</p>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 lg:p-8">
                    <form action="proses.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <!-- Input hidden wajib -->
                        <input type="hidden" name="aksi" value="edit">
                        <input type="hidden" name="id_hewan" value="<?php echo htmlspecialchars($hewan['id_hewan']); ?>">
                        <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($hewan['foto']); ?>">
                        
                        <!-- Nama Hewan -->
                        <div>
                            <label for="nama_hewan" class="block text-sm font-semibold text-gray-700 mb-2">Nama Hewan</label>
                            <input type="text" id="nama_hewan" name="nama_hewan" required value="<?php echo htmlspecialchars($hewan['nama_hewan']); ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#1E3F20] focus:ring-2 focus:ring-[#1E3F20]/20 transition-all outline-none" placeholder="Masukkan nama hewan peliharaan">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Jenis Hewan -->
                            <div>
                                <label for="jenis_hewan" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Hewan</label>
                                <select id="jenis_hewan" name="jenis_hewan" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#1E3F20] focus:ring-2 focus:ring-[#1E3F20]/20 transition-all outline-none bg-white">
                                    <option value="" disabled>Pilih jenis hewan...</option>
                                    <option value="Kucing" <?php echo ($hewan['jenis_hewan'] == 'Kucing') ? 'selected' : ''; ?>>Kucing</option>
                                    <option value="Anjing" <?php echo ($hewan['jenis_hewan'] == 'Anjing') ? 'selected' : ''; ?>>Anjing</option>
                                    <option value="Kelinci" <?php echo ($hewan['jenis_hewan'] == 'Kelinci') ? 'selected' : ''; ?>>Kelinci</option>
                                    <option value="Lainnya" <?php echo ($hewan['jenis_hewan'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                                </select>
                            </div>
                            
                            <!-- Ras -->
                            <div>
                                <label for="ras" class="block text-sm font-semibold text-gray-700 mb-2">Ras</label>
                                <input type="text" id="ras" name="ras" value="<?php echo htmlspecialchars($hewan['ras']); ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#1E3F20] focus:ring-2 focus:ring-[#1E3F20]/20 transition-all outline-none" placeholder="Contoh: Persia, Golden Retriever">
                            </div>

                            <!-- Status Adopsi -->
                            <div>
                                <label for="status_adopsi" class="block text-sm font-semibold text-gray-700 mb-2">Status Adopsi</label>
                                <select id="status_adopsi" name="status_adopsi" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#1E3F20] focus:ring-2 focus:ring-[#1E3F20]/20 transition-all outline-none bg-white">
                                    <option value="Tersedia" <?php echo (isset($hewan['status_adopsi']) && $hewan['status_adopsi'] == 'Tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                                    <option value="Diadopsi" <?php echo (isset($hewan['status_adopsi']) && $hewan['status_adopsi'] == 'Diadopsi') ? 'selected' : ''; ?>>Diadopsi</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($hewan['tanggal_lahir']); ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#1E3F20] focus:ring-2 focus:ring-[#1E3F20]/20 transition-all outline-none text-gray-600">
                            </div>

                            <!-- Tanggal Masuk -->
                            <div>
                                <label for="tanggal_masuk" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Masuk</label>
                                <input type="date" id="tanggal_masuk" name="tanggal_masuk" required value="<?php echo htmlspecialchars($hewan['tanggal_masuk']); ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#1E3F20] focus:ring-2 focus:ring-[#1E3F20]/20 transition-all outline-none text-gray-600">
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#1E3F20] focus:ring-2 focus:ring-[#1E3F20]/20 transition-all outline-none resize-y" placeholder="Ceritakan kepribadian hewan ini..."><?php echo htmlspecialchars($hewan['deskripsi']); ?></textarea>
                        </div>

                        <!-- Foto Hewan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Hewan Saat Ini</label>
                            <?php if (!empty($hewan['foto']) && file_exists("../assets/uploads/" . $hewan['foto'])): ?>
                                <div class="mb-4">
                                    <img src="../assets/uploads/<?php echo htmlspecialchars($hewan['foto']); ?>" alt="Foto Hewan" class="w-32 h-32 object-cover rounded-xl border border-gray-200 shadow-sm">
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500 mb-4 italic">Belum ada foto yang diunggah.</p>
                            <?php endif; ?>

                            <label for="foto" class="block text-sm font-semibold text-gray-700 mb-2">Upload Foto Baru (Opsional)</label>
                            <input type="file" id="foto" name="foto" accept=".png, .jpg, .jpeg" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#1E3F20] focus:ring-2 focus:ring-[#1E3F20]/20 transition-all outline-none bg-gray-50 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#1E3F20]/10 file:text-[#1E3F20] hover:file:bg-[#1E3F20]/20 cursor-pointer">
                            <p class="text-xs text-gray-400 mt-2">Kosongkan jika tidak ingin mengubah foto. Maksimal 2MB (JPG, JPEG, PNG).</p>
                        </div>

                        <hr class="border-gray-100 my-8">

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3">
                            <a href="index.php" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 font-medium hover:bg-gray-50 transition-colors text-sm">
                                Kembali
                            </a>
                            <button type="submit" class="px-6 py-3 rounded-xl bg-[#E07A5F] hover:bg-[#c9674f] text-white font-semibold shadow-lg shadow-[#E07A5F]/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5 text-sm">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>

</body>
</html>
