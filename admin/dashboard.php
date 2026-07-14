<?php
session_start();

// Panggil file koneksi database dan autoload dari Composer
require_once '../config/koneksi.php';
require_once '../vendor/autoload.php';

// Gunakan namespace Carbon dan atur locale ke Indonesia
use Carbon\Carbon;
Carbon::setLocale('id');

// Statistik Total Hewan
$queryTotal = "SELECT COUNT(*) as total FROM hewan";
$resultTotal = @mysqli_query($koneksi, $queryTotal);
$rowTotal = $resultTotal ? mysqli_fetch_assoc($resultTotal) : ['total' => 0];
$totalHewan = $rowTotal['total'] ?? 0;

// Statistik Hewan Tersedia
$queryTersedia = "SELECT COUNT(*) as tersedia FROM hewan WHERE status_adopsi = 'Tersedia'";
$resultTersedia = @mysqli_query($koneksi, $queryTersedia);
$rowTersedia = $resultTersedia ? mysqli_fetch_assoc($resultTersedia) : ['tersedia' => 0];
$tersediaHewan = $rowTersedia['tersedia'] ?? 0;

// Statistik Hewan Diadopsi
$queryDiadopsi = "SELECT COUNT(*) as diadopsi FROM hewan WHERE status_adopsi = 'Diadopsi'";
$resultDiadopsi = @mysqli_query($koneksi, $queryDiadopsi);
$rowDiadopsi = $resultDiadopsi ? mysqli_fetch_assoc($resultDiadopsi) : ['diadopsi' => 0];
$diadopsiHewan = $rowDiadopsi['diadopsi'] ?? 0;

// Query Hewan Terbaru (Aktivitas Terbaru)
$queryTerbaru = "SELECT * FROM hewan ORDER BY created_at DESC LIMIT 5";
$resultTerbaru = @mysqli_query($koneksi, $queryTerbaru);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Statistik - Admin Panel</title>
    
    <!-- Tailwind CSS (Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome untuk Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts: Inter -->
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
<body class="bg-[#FAF8F5] text-gray-800 flex flex-col min-h-screen">

    <!-- Top Navbar -->
    <nav class="bg-[#1E3F20] text-white shadow-md z-10 w-full shrink-0">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex items-center justify-between h-16">
                <!-- Logo Kiri -->
                <div class="flex items-center">
                    <a href="dashboard.php" class="flex items-center gap-2">
                        <h1 class="text-xl font-bold tracking-wider text-white">PetAdopt<span class="text-[#E07A5F]">.</span></h1>
                        <span class="hidden sm:inline-block text-sm text-gray-300 ml-2 bg-white/10 px-2.5 py-0.5 rounded-full font-medium">Admin Panel</span>
                    </a>
                </div>
                
                <!-- Menu Kanan (Desktop) -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="dashboard.php" class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-white/15 shadow-inner transition-all flex items-center gap-2">
                        <i class="fas fa-chart-line text-[#E07A5F]"></i> Dashboard
                    </a>
                    <a href="index.php" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition-all flex items-center gap-2">
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
    <main class="flex-1 w-full relative min-h-screen overflow-hidden bg-[#FAF8F5] bg-architect-grid">
        <!-- Content Area -->
        <div class="w-full p-6 lg:p-10 relative z-10">
            <div class="max-w-7xl mx-auto">
                
                <!-- Page Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Dashboard Statistik</h2>
                    <p class="text-gray-500 mt-2 text-sm md:text-base">Ringkasan aktivitas dan status hewan peliharaan di sistem PetAdopt.</p>
                </div>

                <!-- Grid Cards Statistik -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    
                    <!-- Card 1: Total Hewan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-6 hover:scale-105 transition-all duration-300 cursor-default">
                        <div class="w-14 h-14 rounded-full bg-[#1E3F20]/10 flex items-center justify-center text-[#1E3F20] text-2xl shrink-0">
                            <i class="fas fa-cat"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Hewan</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $totalHewan; ?></h3>
                        </div>
                    </div>

                    <!-- Card 2: Hewan Tersedia -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-6 hover:scale-105 transition-all duration-300 cursor-default">
                        <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-2xl shrink-0">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Tersedia</p>
                            <div class="flex items-center gap-3 mt-1">
                                <h3 class="text-3xl font-bold text-gray-800"><?php echo $tersediaHewan; ?></h3>
                                <span class="bg-green-50 text-green-600 border border-green-200 text-xs px-2 py-1 rounded-md font-semibold">Siap Adopsi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Hewan Diadopsi -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-6 hover:scale-105 transition-all duration-300 cursor-default">
                        <div class="w-14 h-14 rounded-full bg-[#E07A5F]/10 flex items-center justify-center text-[#E07A5F] text-2xl shrink-0">
                            <i class="fas fa-home"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Diadopsi</p>
                            <h3 class="text-3xl font-bold text-[#E07A5F] mt-1"><?php echo $diadopsiHewan; ?></h3>
                        </div>
                    </div>

                </div>

                <!-- Bagian Aktivitas Terbaru -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Aktivitas Terbaru</h3>
                        <a href="index.php" class="text-sm font-medium text-[#E07A5F] hover:text-[#c9674f] transition-colors">Lihat Semua Data <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse whitespace-nowrap">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                        <th class="px-6 py-4 font-semibold w-20 text-center">Foto</th>
                                        <th class="px-6 py-4 font-semibold">Nama & Info</th>
                                        <th class="px-6 py-4 font-semibold">Tanggal Masuk</th>
                                        <th class="px-6 py-4 font-semibold text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-sm">
                                    <?php
                                    if ($resultTerbaru && mysqli_num_rows($resultTerbaru) > 0) {
                                        while ($row = mysqli_fetch_assoc($resultTerbaru)) {
                                            $tanggal_masuk_format = '-';
                                            if (!empty($row['tanggal_masuk'])) {
                                                try {
                                                    $tanggal_masuk_format = Carbon::parse($row['tanggal_masuk'])->translatedFormat('d M Y');
                                                } catch (Exception $e) {
                                                    $tanggal_masuk_format = $row['tanggal_masuk'];
                                                }
                                            }

                                            $status = isset($row['status_adopsi']) ? $row['status_adopsi'] : 'Tersedia';
                                            if (strtolower($status) == 'diadopsi') {
                                                $badgeClass = 'bg-[#E07A5F]/10 text-[#E07A5F] border-[#E07A5F]/20';
                                            } else {
                                                $badgeClass = 'bg-green-50 text-green-600 border-green-200';
                                            }

                                            $nama = isset($row['nama_hewan']) ? $row['nama_hewan'] : 'Tanpa Nama';
                                            $spesies = isset($row['jenis_hewan']) ? $row['jenis_hewan'] : 'Tidak diketahui';
                                            $ras = isset($row['ras']) ? $row['ras'] : '-';
                                            
                                            $foto_path = (!empty($row['foto']) && file_exists("../assets/uploads/" . $row['foto'])) ? "../assets/uploads/" . $row['foto'] : "https://ui-avatars.com/api/?name=" . urlencode($nama) . "&background=random";

                                            echo "<tr class='hover:bg-[#FAF8F5]/50 transition-colors group'>
                                                    <td class='px-6 py-3 text-center'>
                                                        <div class='w-10 h-10 rounded-full mx-auto overflow-hidden border border-gray-200 shadow-sm'>
                                                            <img src='{$foto_path}' alt='Foto' class='w-full h-full object-cover'>
                                                        </div>
                                                    </td>
                                                    <td class='px-6 py-3'>
                                                        <div class='font-bold text-gray-800'>" . htmlspecialchars($nama) . "</div>
                                                        <div class='text-gray-400 text-xs mt-0.5'>" . htmlspecialchars($spesies) . " - " . htmlspecialchars($ras) . "</div>
                                                    </td>
                                                    <td class='px-6 py-3 text-gray-600'>
                                                        <i class='far fa-calendar-alt text-gray-400 mr-1.5'></i> {$tanggal_masuk_format}
                                                    </td>
                                                    <td class='px-6 py-3 text-right'>
                                                        <span class='px-3 py-1 rounded-full text-xs font-semibold border {$badgeClass}'>
                                                            {$status}
                                                        </span>
                                                    </td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                                <td colspan='4' class='px-6 py-12 text-center'>
                                                    <div class='flex flex-col items-center justify-center text-gray-400'>
                                                        <div class='w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3'>
                                                            <i class='fas fa-info-circle text-xl text-gray-300'></i>
                                                        </div>
                                                        <p class='text-sm font-medium text-gray-600'>Belum ada aktivitas.</p>
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
        </div>
    </main>

</body>
</html>
