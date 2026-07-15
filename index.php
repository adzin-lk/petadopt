<?php
// Include koneksi database
require_once 'config/koneksi.php';

// Fungsi Waktu Relatif
function selisihWaktu($datetime) {
    if (empty($datetime)) return '';
    $timestamp = strtotime($datetime);
    $selisih = time() - $timestamp;
    
    if ($selisih < 60) {
        return "Baru saja";
    } elseif ($selisih < 3600) {
        $menit = floor($selisih / 60);
        return "$menit menit lalu";
    } elseif ($selisih < 86400) {
        $jam = floor($selisih / 3600);
        return "$jam jam lalu";
    } elseif ($selisih < 604800) {
        $hari = floor($selisih / 86400);
        if ($hari == 1) return "kemarin";
        return "$hari hari lalu";
    } elseif ($selisih < 2592000) {
        $minggu = floor($selisih / 604800);
        return "$minggu minggu lalu";
    } elseif ($selisih < 31536000) {
        $bulan = floor($selisih / 2592000);
        return "$bulan bulan lalu";
    } else {
        $tahun = floor($selisih / 31536000);
        return "$tahun tahun lalu";
    }
}

// Filter Kategori
$kategori_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : '';

if (!empty($kategori_aktif)) {
    $kategori_safe = mysqli_real_escape_string($koneksi, $kategori_aktif);
    $query_hewan = "SELECT * FROM hewan WHERE status_adopsi = 'Tersedia' AND jenis_hewan = '$kategori_safe' ORDER BY id_hewan DESC";
} else {
    $query_hewan = "SELECT * FROM hewan WHERE status_adopsi = 'Tersedia' ORDER BY id_hewan DESC";
}
// Tambahkan @ untuk menghindari error saat belum ada DB setup di awal
$result_hewan = @mysqli_query($koneksi, $query_hewan);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetAdopt - Platform Jual Beli & Adopsi Hewan Peliharaan No. 1</title>
    
    <!-- Tailwind CSS (Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome untuk Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts: Inter -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .bg-architect-grid {
            background-color: #FAF8F5 !important;
            background-size: 100px 100px;
            background-image: 
                linear-gradient(to right, rgba(30, 63, 32, 0.09) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(30, 63, 32, 0.09) 1px, transparent 1px) !important;
        }
        @keyframes catChase {
            0% { transform: translateX(-20%); }
            100% { transform: translateX(110vw); }
        }
        .animate-cat-1 { animation: catChase 14s linear infinite; }
        .animate-cat-2 { animation: catChase 14s linear infinite; animation-delay: 2.5s; }
        .animate-cat-3 { animation: catChase 14s linear infinite; animation-delay: 5s; }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

    <!-- Navbar Publik -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 w-full transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="index.php" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-xl bg-[#1E3F20]/10 flex items-center justify-center text-[#1E3F20] group-hover:bg-[#1E3F20] group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-paw text-xl"></i>
                        </div>
                        <h1 class="text-2xl font-extrabold tracking-tight text-[#1E3F20]">PetAdopt<span class="text-[#E07A5F]">.</span></h1>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#beranda" class="text-sm font-semibold text-[#1E3F20] hover:text-[#E07A5F] transition-colors">Beranda</a>
                    <a href="#katalog" class="text-sm font-medium text-gray-500 hover:text-[#1E3F20] transition-colors">Adopsi</a>
                    <a href="#tentang-kami" class="text-sm font-medium text-gray-500 hover:text-[#1E3F20] transition-colors">Tentang Kami</a>
                    <div class="h-6 w-px bg-gray-200 mx-2"></div>
                    <a href="admin/" class="px-5 py-2.5 rounded-full text-sm font-semibold text-[#1E3F20] border-2 border-[#1E3F20] hover:bg-[#1E3F20] hover:text-white transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Portal Admin
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button class="text-[#1E3F20] hover:text-[#E07A5F] focus:outline-none p-2 transition-colors">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="bg-[#FAF8F5] relative overflow-hidden">
        <!-- Container Jalur Lari Kucing (Sekarang Mengambang di Hero) -->
        <div class="absolute top-2 left-0 w-full overflow-hidden h-12 bg-transparent pointer-events-none z-20 flex items-center">
            <!-- Kucing 1 -->
            <div class="absolute animate-cat-1 flex items-center text-[#1E3F20] opacity-70">
                <svg class="w-10 h-8" viewBox="0 0 30 20" fill="currentColor">
                    <path d="M4,11 C1,11 1,5 3,3 C3.5,2.5 4,3 3.5,3.5 C2,5 2,9 4,9 Z" /> <!-- Ekor -->
                    <ellipse cx="12" cy="11" rx="6" ry="4" /> <!-- Badan -->
                    <rect x="7" y="14" width="1.5" height="4" rx="0.5" /> <!-- Kaki 1 -->
                    <rect x="9.5" y="14" width="1.5" height="4" rx="0.5" /> <!-- Kaki 2 -->
                    <rect x="13" y="14" width="1.5" height="4" rx="0.5" /> <!-- Kaki 3 -->
                    <rect x="15.5" y="14" width="1.5" height="4" rx="0.5" /> <!-- Kaki 4 -->
                    <circle cx="18" cy="8" r="3.5" /> <!-- Kepala -->
                    <polygon points="15,6 15,2 17.5,5" /> <!-- Telinga L -->
                    <polygon points="18.5,5 21,2 21,6" /> <!-- Telinga R -->
                </svg>
                <span class="text-xs font-semibold ml-1">🐾</span>
            </div>
            <!-- Kucing 2 (Mengejar) -->
            <div class="absolute animate-cat-2 flex items-center text-[#1E3F20] opacity-70">
                <svg class="w-10 h-8" viewBox="0 0 30 20" fill="currentColor">
                    <path d="M4,11 C1,11 1,5 3,3 C3.5,2.5 4,3 3.5,3.5 C2,5 2,9 4,9 Z" />
                    <ellipse cx="12" cy="11" rx="6" ry="4" />
                    <rect x="7" y="14" width="1.5" height="4" rx="0.5" />
                    <rect x="9.5" y="14" width="1.5" height="4" rx="0.5" />
                    <rect x="13" y="14" width="1.5" height="4" rx="0.5" />
                    <rect x="15.5" y="14" width="1.5" height="4" rx="0.5" />
                    <circle cx="18" cy="8" r="3.5" />
                    <polygon points="15,6 15,2 17.5,5" />
                    <polygon points="18.5,5 21,2 21,6" />
                </svg>
            </div>
            <!-- Kucing 3 (Paling Belakang) -->
            <div class="absolute animate-cat-3 flex items-center text-[#E07A5F] opacity-80">
                <svg class="w-10 h-8" viewBox="0 0 30 20" fill="currentColor">
                    <path d="M4,11 C1,11 1,5 3,3 C3.5,2.5 4,3 3.5,3.5 C2,5 2,9 4,9 Z" />
                    <ellipse cx="12" cy="11" rx="6" ry="4" />
                    <rect x="7" y="14" width="1.5" height="4" rx="0.5" />
                    <rect x="9.5" y="14" width="1.5" height="4" rx="0.5" />
                    <rect x="13" y="14" width="1.5" height="4" rx="0.5" />
                    <rect x="15.5" y="14" width="1.5" height="4" rx="0.5" />
                    <circle cx="18" cy="8" r="3.5" />
                    <polygon points="15,6 15,2 17.5,5" />
                    <polygon points="18.5,5 21,2 21,6" />
                </svg>
                <span class="text-xs font-semibold ml-1">meow!</span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-10 pt-20 pb-24 lg:pt-32 lg:pb-32 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                
                <!-- Left Content -->
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-gray-200 shadow-sm text-xs font-semibold text-[#E07A5F] uppercase tracking-wider mb-6">
                        <span class="w-2 h-2 rounded-full bg-[#E07A5F] animate-pulse"></span>
                        Temukan Teman Baru Anda
                    </div>
                    
                    <h1 class="text-4xl lg:text-5xl xl:text-6xl font-extrabold text-[#1E3F20] leading-[1.15] mb-6 tracking-tight">
                        Platform Jual Beli & Adopsi Hewan Peliharaan <span class="text-[#E07A5F]">No. 1</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 mb-10 leading-relaxed max-w-lg">
                        Berikan rumah yang penuh cinta untuk mereka yang membutuhkan. Kami menghubungkan Anda dengan sahabat berbulu impian secara mudah, aman, dan terpercaya.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <a href="#katalog" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-[#E07A5F] hover:bg-[#c9674f] text-white font-bold text-center shadow-lg shadow-[#E07A5F]/30 hover:shadow-xl hover:shadow-[#E07A5F]/40 transform hover:-translate-y-1 transition-all duration-300">
                            Adopsi Sekarang
                        </a>
                        <a href="#tentang-kami" class="w-full sm:w-auto px-8 py-4 rounded-xl border-2 border-[#1E3F20] text-[#1E3F20] font-bold text-center hover:bg-[#1E3F20] hover:text-white transform hover:-translate-y-1 transition-all duration-300">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                    
                    <div class="mt-12 flex items-center gap-4 text-sm text-gray-500 font-medium">
                        <div class="flex -space-x-3">
                            <img class="w-10 h-10 rounded-full border-2 border-[#FAF8F5] object-cover" src="https://ui-avatars.com/api/?name=Ani&background=random" alt="User">
                            <img class="w-10 h-10 rounded-full border-2 border-[#FAF8F5] object-cover" src="https://ui-avatars.com/api/?name=Budi&background=random" alt="User">
                            <img class="w-10 h-10 rounded-full border-2 border-[#FAF8F5] object-cover" src="https://ui-avatars.com/api/?name=Caca&background=random" alt="User">
                            <div class="w-10 h-10 rounded-full border-2 border-[#FAF8F5] bg-gray-100 flex items-center justify-center text-xs text-gray-600 font-bold">+1k</div>
                        </div>
                        <p>Telah mempercayakan adopsi kepada kami.</p>
                    </div>
                </div>
                
                <!-- Right Content (Image Placeholder) -->
                <div class="relative lg:ml-auto w-full max-w-lg lg:max-w-none mx-auto">
                    <!-- Decorative background shape -->
                    <div class="absolute inset-0 bg-[#1E3F20] rounded-[3rem] rotate-3 opacity-10 transform scale-105"></div>
                    
                    <div class="relative bg-white p-4 rounded-[2.5rem] shadow-xl border border-white/50 aspect-[4/5] md:aspect-square lg:aspect-[4/5] overflow-hidden group">
                        <!-- Placeholder Image dari Unsplash -->
                        <div class="w-full h-full rounded-[2rem] bg-gray-100 flex flex-col items-center justify-center overflow-hidden relative">
                            <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Anjing Menggemaskan" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            
                            <!-- Overlay gradient -->
                            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-[#1E3F20]/80 to-transparent"></div>
                            
                            <!-- Floating Badge inside image -->
                            <div class="absolute bottom-6 left-6 right-6 bg-white/90 backdrop-blur-sm p-4 rounded-2xl shadow-lg border border-white flex items-center gap-4 transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 delay-100">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Kesehatan Terjamin</p>
                                    <p class="text-xs text-gray-500 font-medium">Telah divaksin & diperiksa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Section Kenapa Memilih Kami -->
    <section id="tentang-kami" class="py-24 bg-white relative z-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-[#1E3F20] tracking-tight mb-4">Kenapa Memilih Platform Rumah Hewan?</h2>
                <p class="text-gray-500 text-lg">Kami berkomitmen memberikan pelayanan terbaik demi kesejahteraan hewan peliharaan Anda sebelum dan sesudah adopsi.</p>
            </div>
            
            <!-- Grid 3 Kolom -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 group cursor-default">
                    <div class="w-16 h-16 rounded-2xl bg-[#FAF8F5] text-[#1E3F20] flex items-center justify-center mb-6 group-hover:bg-[#1E3F20] group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-hand-holding-heart text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Adopsi Gratis & Terpercaya</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Proses adopsi hewan yang mudah, transparan, dan tanpa biaya tersembunyi. Kepercayaan Anda adalah prioritas utama kami.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 group cursor-default">
                    <div class="w-16 h-16 rounded-2xl bg-[#FAF8F5] text-[#1E3F20] flex items-center justify-center mb-6 group-hover:bg-[#1E3F20] group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-stethoscope text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Kesehatan Hewan Terjamin</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Semua hewan yang terdaftar telah melalui pemeriksaan medis dasar dan divaksinasi untuk menjamin kesehatannya.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 group cursor-default">
                    <div class="w-16 h-16 rounded-2xl bg-[#FAF8F5] text-[#E07A5F] flex items-center justify-center mb-6 group-hover:bg-[#E07A5F] group-hover:text-white transition-colors duration-300">
                        <i class="fab fa-whatsapp text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Proses Cepat via WhatsApp</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Tidak perlu ribet, konsultasi dan proses adopsi bisa langsung dilakukan dengan cepat dan personal melalui WhatsApp.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Section Katalog Hewan -->
    <section id="katalog" class="py-24 bg-white relative z-20 bg-architect-grid">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-[#1E3F20] tracking-tight mb-4">Katalog Hewan Populer Pilihan</h2>
                <p class="text-gray-500 text-lg">Temukan hewan peliharaan menggemaskan yang sedang menunggu rumah penuh cinta dari Anda.</p>
            </div>

            <!-- Filter Kategori Dinamis -->
            <div class="flex flex-wrap justify-center gap-3 mb-12">
                <a href="index.php#katalog" class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 <?php echo empty($kategori_aktif) ? 'bg-[#1E3F20] text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-[#1E3F20] hover:text-[#1E3F20]'; ?>">
                    Semua
                </a>
                <a href="index.php?kategori=Kucing#katalog" class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 <?php echo ($kategori_aktif == 'Kucing') ? 'bg-[#1E3F20] text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-[#1E3F20] hover:text-[#1E3F20]'; ?>">
                    Kucing
                </a>
                <a href="index.php?kategori=Anjing#katalog" class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 <?php echo ($kategori_aktif == 'Anjing') ? 'bg-[#1E3F20] text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:border-[#1E3F20] hover:text-[#1E3F20]'; ?>">
                    Anjing
                </a>
            </div>

            <!-- Grid Card Hewan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
                <?php
                if ($result_hewan && mysqli_num_rows($result_hewan) > 0) {
                    while ($row = mysqli_fetch_assoc($result_hewan)) {
                        $nama = isset($row['nama_hewan']) ? htmlspecialchars($row['nama_hewan']) : 'Tanpa Nama';
                        $ras = isset($row['ras']) ? htmlspecialchars($row['ras']) : '-';
                        $lokasi = isset($row['lokasi']) && !empty($row['lokasi']) ? htmlspecialchars($row['lokasi']) : 'Lokasi tidak diketahui';
                        $waktu_posting = isset($row['created_at']) ? selisihWaktu($row['created_at']) : '';
                        $id_hewan = isset($row['id_hewan']) ? $row['id_hewan'] : '';
                        
                        // Cek foto
                        $foto_path = (!empty($row['foto']) && file_exists("assets/uploads/" . $row['foto'])) ? "assets/uploads/" . $row['foto'] : "https://images.unsplash.com/photo-1543466835-00a7907e9de1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80";

                        echo "
                        <div class='bg-white rounded-2xl overflow-hidden shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100 hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.12)] hover:-translate-y-1.5 transition-all duration-300 group flex flex-col'>
                            <!-- Bagian Atas: Foto -->
                            <div class='relative h-48 sm:h-56 w-full overflow-hidden'>
                                <img src='{$foto_path}' alt='Foto {$nama}' class='w-full h-full object-cover group-hover:scale-110 transition-transform duration-700'>
                                <div class='absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold text-[#1E3F20] shadow-sm'>
                                    Siap Adopsi
                                </div>
                            </div>
                            
                            <!-- Bagian Tengah: Info -->
                            <div class='p-6 flex-1 flex flex-col'>
                                <h3 class='text-xl font-bold text-gray-800 mb-1 line-clamp-1'>{$nama}</h3>
                                <p class='text-sm text-gray-500 font-medium mb-3 line-clamp-1'>{$ras}</p>
                                
                                <div class='flex items-center gap-2 text-sm text-gray-600 mb-4'>
                                    <i class='fas fa-map-marker-alt text-[#E07A5F]'></i>
                                    <span class='line-clamp-1'>{$lokasi}</span>
                                </div>
                                
                                <div class='flex items-center gap-2 text-xs text-gray-400 mt-auto'>
                                    <i class='far fa-clock'></i>
                                    <span>Diposting {$waktu_posting}</span>
                                </div>
                            </div>
                            
                            <!-- Bagian Bawah: Action -->
                            <div class='p-6 pt-0 mt-auto'>
                                <a href='detail.php?id_hewan={$id_hewan}' class='w-full block text-center py-3 rounded-xl bg-[#E07A5F] hover:bg-[#c9674f] text-white font-bold shadow-md shadow-[#E07A5F]/20 hover:shadow-lg transition-all duration-300'>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                        ";
                    }
                } else {
                    // Empty state jika data tidak ditemukan
                    echo "
                    <div class='col-span-1 sm:col-span-2 md:col-span-3 bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm'>
                        <div class='w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6'>
                            <i class='fas fa-cat text-4xl text-gray-300'></i>
                        </div>
                        <h3 class='text-xl font-bold text-gray-800 mb-2'>Belum Ada Hewan</h3>
                        <p class='text-gray-500'>Maaf, saat ini belum ada hewan yang tersedia untuk kategori ini.</p>
                    </div>
                    ";
                }
                ?>
            </div>
            
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#1E3F20] pt-20 pb-10 mt-auto">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
                <!-- Kolom 1 -->
                <div>
                    <a href="index.php" class="flex items-center gap-2 mb-6 group">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white group-hover:bg-white/20 transition-colors">
                            <i class="fas fa-paw text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold tracking-tight text-white">PetAdopt<span class="text-[#E07A5F]">.</span></h2>
                    </a>
                    <p class="text-gray-300 leading-relaxed text-sm">
                        Platform inovatif yang menjembatani kasih sayang. Kami membantu menemukan rumah impian untuk hewan peliharaan yang membutuhkan perhatian dan kehangatan keluarga.
                    </p>
                </div>
                
                <!-- Kolom 2 -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 tracking-wide">Quick Links</h3>
                    <ul class="space-y-4">
                        <li><a href="#beranda" class="text-gray-300 hover:text-[#E07A5F] transition-colors text-sm font-medium flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Beranda</a></li>
                        <li><a href="#katalog" class="text-gray-300 hover:text-[#E07A5F] transition-colors text-sm font-medium flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Katalog Adopsi</a></li>
                        <li><a href="#tentang-kami" class="text-gray-300 hover:text-[#E07A5F] transition-colors text-sm font-medium flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Tentang Kami</a></li>
                        <li><a href="admin/" class="text-gray-300 hover:text-[#E07A5F] transition-colors text-sm font-medium flex items-center gap-2"><i class="fas fa-chevron-right text-xs"></i> Portal Admin</a></li>
                    </ul>
                </div>
                
                <!-- Kolom 3 -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 tracking-wide">Hubungi Kami</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-[#E07A5F] shrink-0"><i class="fas fa-map-marker-alt"></i></div>
                            <p class="text-gray-300 text-sm mt-2">Jl. Cinta Hewan No. 123, Jakarta Selatan, Indonesia</p>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-[#E07A5F] shrink-0"><i class="fas fa-envelope"></i></div>
                            <p class="text-gray-300 text-sm">hello@petadopt.id</p>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-[#E07A5F] shrink-0"><i class="fab fa-instagram"></i></div>
                            <p class="text-gray-300 text-sm">@petadopt_id</p>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-400 text-xs font-medium">© 2026 PetAdopt. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors text-xs">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors text-xs">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6281227884330?text=Halo%20Admin%20PetAdopt,%20saya%20ingin%20bertanya%20mengenai%20informasi%20adopsi%20hewan." target="_blank" class="fixed bottom-6 right-6 z-50 flex items-center space-x-2 group">
        <!-- Badge Teks -->
        <div class="px-4 py-2 bg-[#1E3F20] text-white text-sm font-semibold rounded-xl shadow-lg opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all duration-300 pointer-events-none border border-white/20">
            Admin Rumah Hewan
        </div>
        <!-- Ikon Bulat -->
        <div class="w-14 h-14 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-lg hover:shadow-xl hover:scale-110 transition-transform duration-300">
            <i class="fab fa-whatsapp text-3xl"></i>
        </div>
    </a>

</body>
</html>
