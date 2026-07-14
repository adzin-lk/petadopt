<?php
require_once 'config/koneksi.php';

if (!isset($_GET['id_hewan'])) {
    header("Location: index.php");
    exit;
}

$id_hewan = mysqli_real_escape_string($koneksi, $_GET['id_hewan']);
$query = "SELECT * FROM hewan WHERE id_hewan = '$id_hewan'";
$result = @mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit;
}

$hewan = mysqli_fetch_assoc($result);

$nama = isset($hewan['nama_hewan']) ? htmlspecialchars($hewan['nama_hewan']) : 'Tanpa Nama';
$jenis = isset($hewan['jenis_hewan']) ? htmlspecialchars($hewan['jenis_hewan']) : '-';
$ras = isset($hewan['ras']) ? htmlspecialchars($hewan['ras']) : '-';
$lokasi = isset($hewan['lokasi']) && !empty($hewan['lokasi']) ? htmlspecialchars($hewan['lokasi']) : 'Lokasi tidak diketahui';
$tanggal_masuk = isset($hewan['tanggal_masuk']) ? htmlspecialchars($hewan['tanggal_masuk']) : '-';
$deskripsi = isset($hewan['deskripsi']) ? nl2br(htmlspecialchars($hewan['deskripsi'])) : 'Tidak ada deskripsi.';
$status = isset($hewan['status_adopsi']) ? htmlspecialchars($hewan['status_adopsi']) : 'Tersedia';

$foto_path = (!empty($hewan['foto']) && file_exists("assets/uploads/" . $hewan['foto'])) ? "assets/uploads/" . $hewan['foto'] : "https://images.unsplash.com/photo-1543466835-00a7907e9de1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80";

// WhatsApp Link
$wa_number = "6281227884330";
$wa_text = rawurlencode("Halo, saya sangat tertarik untuk mengadopsi {$nama} yang berlokasi di {$lokasi}. Apakah bisa dibantu untuk proses selanjutnya?");
$wa_link = "https://wa.me/{$wa_number}?text={$wa_text}";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail <?php echo $nama; ?> - PetAdopt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#FAF8F5] text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Navbar Publik -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 w-full transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center shrink-0">
                    <a href="index.php" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-xl bg-[#1E3F20]/10 flex items-center justify-center text-[#1E3F20] group-hover:bg-[#1E3F20] group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-paw text-xl"></i>
                        </div>
                        <h1 class="text-2xl font-extrabold tracking-tight text-[#1E3F20]">PetAdopt<span class="text-[#E07A5F]">.</span></h1>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php#beranda" class="text-sm font-semibold text-gray-500 hover:text-[#1E3F20] transition-colors">Beranda</a>
                    <a href="index.php#katalog" class="text-sm font-semibold text-[#1E3F20] hover:text-[#E07A5F] transition-colors">Adopsi</a>
                    <a href="index.php#tentang-kami" class="text-sm font-medium text-gray-500 hover:text-[#1E3F20] transition-colors">Tentang Kami</a>
                    <div class="h-6 w-px bg-gray-200 mx-2"></div>
                    <a href="admin/" class="px-5 py-2.5 rounded-full text-sm font-semibold text-[#1E3F20] border-2 border-[#1E3F20] hover:bg-[#1E3F20] hover:text-white transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Portal Admin
                    </a>
                </div>
                <div class="md:hidden flex items-center">
                    <button class="text-[#1E3F20] hover:text-[#E07A5F] focus:outline-none p-2 transition-colors">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-6 lg:px-10 py-12">
        
        <!-- Back Button -->
        <a href="index.php#katalog" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#1E3F20] transition-colors mb-8">
            <i class="fas fa-arrow-left"></i> Kembali ke Katalog
        </a>

        <!-- Detail Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                
                <!-- Kiri: Gambar Besar -->
                <div class="h-[400px] lg:h-[600px] w-full relative">
                    <img src="<?php echo $foto_path; ?>" alt="<?php echo $nama; ?>" class="absolute inset-0 w-full h-full object-cover">
                </div>

                <!-- Kanan: Info -->
                <div class="p-8 lg:p-12 flex flex-col">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <h1 class="text-4xl font-bold text-[#1E3F20] tracking-tight"><?php echo $nama; ?></h1>
                        <?php if ($status == 'Tersedia'): ?>
                            <span class="px-4 py-1.5 rounded-full bg-green-100 text-green-700 text-sm font-bold border border-green-200 whitespace-nowrap">
                                Tersedia
                            </span>
                        <?php else: ?>
                            <span class="px-4 py-1.5 rounded-full bg-gray-100 text-gray-500 text-sm font-bold border border-gray-200 whitespace-nowrap">
                                Diadopsi
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-8 mt-4">
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Spesies</p>
                            <p class="text-gray-800 font-medium flex items-center gap-2">
                                <i class="fas fa-paw text-gray-300"></i> <?php echo $jenis; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Ras</p>
                            <p class="text-gray-800 font-medium flex items-center gap-2">
                                <i class="fas fa-dna text-gray-300"></i> <?php echo $ras; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Lokasi</p>
                            <p class="text-gray-800 font-medium flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-[#E07A5F]"></i> <?php echo $lokasi; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Tanggal Masuk</p>
                            <p class="text-gray-800 font-medium flex items-center gap-2">
                                <i class="far fa-calendar-alt text-gray-300"></i> <?php echo $tanggal_masuk; ?>
                            </p>
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Tentang <?php echo $nama; ?></h3>
                        <p class="text-gray-600 leading-relaxed text-sm lg:text-base">
                            <?php echo $deskripsi; ?>
                        </p>
                    </div>

                    <div class="mt-auto pt-8 border-t border-gray-100">
                        <?php if ($status == 'Tersedia'): ?>
                            <a href="<?php echo $wa_link; ?>" target="_blank" class="w-full block text-center py-4 rounded-2xl bg-[#E07A5F] hover:bg-[#c9674f] text-white text-lg font-bold shadow-lg shadow-[#E07A5F]/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <i class="fab fa-whatsapp mr-2 text-xl"></i> Hubungi Pemilik via WhatsApp
                            </a>
                            <p class="text-center text-xs text-gray-400 mt-4">Anda akan diarahkan ke WhatsApp untuk proses adopsi lebih lanjut.</p>
                        <?php else: ?>
                            <button disabled class="w-full py-4 rounded-2xl bg-gray-200 text-gray-500 text-lg font-bold cursor-not-allowed">
                                Sudah Diadopsi
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>
