<?php
session_start();

// Panggil file koneksi database dan autoload dari Composer
require_once '../config/koneksi.php';
require_once '../vendor/autoload.php';

// Gunakan namespace Carbon dan atur locale ke Indonesia
use Carbon\Carbon;
Carbon::setLocale('id');

// Inisialisasi query untuk mengambil data dari tabel hewan (disesuaikan dengan asumsi struktur database)
$query = "SELECT * FROM hewan ORDER BY id DESC";
// Kita tambahkan @ untuk menekan error jika tabel belum ada, agar tampilan tetap bisa dirender untuk tujuan testing UI
$result = @mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Data Hewan</title>
    
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
<body class="bg-[#FAF8F5] text-gray-800 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#1E3F20] text-white flex-col hidden md:flex shrink-0">
        <div class="p-6">
            <h1 class="text-2xl font-bold tracking-wider text-white">PetAdopt<span class="text-[#E07A5F]">.</span></h1>
            <p class="text-sm text-gray-300 mt-1 opacity-80">Admin Panel</p>
        </div>
        
        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="dashboard.php" class="flex items-center px-4 py-3 hover:bg-white/10 rounded-xl transition-all text-sm font-medium text-gray-300 hover:text-white">
                <i class="fas fa-chart-line w-6 text-center"></i> 
                <span>Dashboard</span>
            </a>
            <a href="index.php" class="flex items-center px-4 py-3 bg-white/15 rounded-xl transition-all text-sm font-medium text-white shadow-inner">
                <i class="fas fa-paw w-6 text-center text-[#E07A5F]"></i> 
                <span>Data Hewan</span>
            </a>
            <a href="../index.php" class="flex items-center px-4 py-3 hover:bg-white/10 rounded-xl transition-all text-sm font-medium text-gray-300 hover:text-white">
                <i class="fas fa-home w-6 text-center"></i> 
                <span>Halaman Depan</span>
            </a>
        </nav>
        
        <div class="p-4 mb-4">
            <a href="logout.php" class="flex items-center px-4 py-3 hover:bg-red-500/20 hover:text-red-300 rounded-xl transition-all text-sm font-medium text-gray-400">
                <i class="fas fa-sign-out-alt w-6 text-center"></i> 
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <!-- Mobile Header (Hanya muncul di HP) -->
        <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between md:hidden shrink-0 z-10">
            <h1 class="text-xl font-bold text-[#1E3F20]">PetAdopt Admin</h1>
            <button class="text-[#1E3F20] hover:text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-[#FAF8F5] p-6 lg:p-10">
            <div class="max-w-7xl mx-auto">
                
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Data Hewan</h2>
                        <p class="text-gray-500 mt-2 text-sm md:text-base">Kelola daftar profil hewan peliharaan yang tersedia untuk diadopsi.</p>
                    </div>
                    <!-- Tombol Tambah Hewan -->
                    <a href="tambah.php" class="bg-[#E07A5F] hover:bg-[#c9674f] text-white px-5 py-3 rounded-xl shadow-lg shadow-[#E07A5F]/30 transition-all flex items-center gap-2 font-semibold text-sm transform hover:-translate-y-0.5">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Hewan Baru</span>
                    </a>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-5 font-semibold text-center w-16">No</th>
                                    <th class="px-6 py-5 font-semibold">Nama Hewan</th>
                                    <th class="px-6 py-5 font-semibold">Spesies / Ras</th>
                                    <th class="px-6 py-5 font-semibold">Tanggal Masuk</th>
                                    <th class="px-6 py-5 font-semibold">Status</th>
                                    <th class="px-6 py-5 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm">
                                <?php
                                if ($result && mysqli_num_rows($result) > 0) {
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Formatting tanggal_masuk menggunakan Carbon (misal: Senin, 13 Juli 2026)
                                        $tanggal_masuk_format = '-';
                                        if (!empty($row['tanggal_masuk'])) {
                                            try {
                                                $tanggal_masuk_format = Carbon::parse($row['tanggal_masuk'])->translatedFormat('l, d F Y');
                                            } catch (Exception $e) {
                                                $tanggal_masuk_format = $row['tanggal_masuk']; // Fallback jika gagal parse
                                            }
                                        }

                                        // Mock status jika tidak ada di DB
                                        $status = isset($row['status']) ? $row['status'] : 'Tersedia';
                                        
                                        // Tentukan warna badge berdasarkan status
                                        if (strtolower($status) == 'teradopsi') {
                                            $badgeClass = 'bg-green-50 text-green-600 border-green-200';
                                        } else {
                                            $badgeClass = 'bg-amber-50 text-amber-600 border-amber-200';
                                        }

                                        $nama = isset($row['nama']) ? $row['nama'] : 'Tanpa Nama';
                                        $spesies = isset($row['spesies']) ? $row['spesies'] : 'Tidak diketahui';
                                        $ras = isset($row['ras']) ? $row['ras'] : '-';

                                        echo "<tr class='hover:bg-[#FAF8F5]/50 transition-colors group'>
                                                <td class='px-6 py-4 text-center text-gray-400 font-medium'>{$no}</td>
                                                <td class='px-6 py-4'>
                                                    <div class='font-semibold text-gray-800'>" . htmlspecialchars($nama) . "</div>
                                                </td>
                                                <td class='px-6 py-4'>
                                                    <div class='text-gray-700 font-medium'>" . htmlspecialchars($spesies) . "</div>
                                                    <div class='text-gray-400 text-xs mt-0.5'>" . htmlspecialchars($ras) . "</div>
                                                </td>
                                                <td class='px-6 py-4 text-gray-600 flex items-center gap-2'>
                                                    <i class='far fa-calendar-alt text-gray-400'></i> {$tanggal_masuk_format}
                                                </td>
                                                <td class='px-6 py-4'>
                                                    <span class='px-3 py-1 rounded-full text-xs font-semibold border {$badgeClass}'>
                                                        {$status}
                                                    </span>
                                                </td>
                                                <td class='px-6 py-4'>
                                                    <div class='flex items-center justify-end gap-3 opacity-80 group-hover:opacity-100 transition-opacity'>
                                                        <!-- Tombol Edit -->
                                                        <a href='edit.php?id=" . (isset($row['id']) ? $row['id'] : '') . "' class='w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors shadow-sm' title='Edit Data'>
                                                            <i class='fas fa-pen text-xs'></i>
                                                        </a>
                                                        <!-- Tombol Hapus -->
                                                        <a href='hapus.php?id=" . (isset($row['id']) ? $row['id'] : '') . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")' class='w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors shadow-sm' title='Hapus Data'>
                                                            <i class='fas fa-trash text-xs'></i>
                                                        </a>
                                                    </div>
                                                </td>
                                              </tr>";
                                        $no++;
                                    }
                                } else {
                                    // Empty state jika data tidak ditemukan atau tabel kosong
                                    echo "<tr>
                                            <td colspan='6' class='px-6 py-12 text-center'>
                                                <div class='flex flex-col items-center justify-center text-gray-400'>
                                                    <div class='w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4'>
                                                        <i class='fas fa-cat text-2xl text-gray-300'></i>
                                                    </div>
                                                    <p class='text-base font-medium text-gray-600'>Belum ada data hewan.</p>
                                                    <p class='text-sm mt-1'>Silakan tambah data hewan baru untuk menampilkannya di sini.</p>
                                                </div>
                                            </td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>
