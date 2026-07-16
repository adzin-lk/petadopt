<?php
// 1. SET ZONA WAKTU INDONESIA (WIB)
date_default_timezone_set('Asia/Jakarta');

ini_set('display_errors', 0);
error_reporting(0);

include 'cek_login.php';
include '../config/koneksi.php'; 

ob_start();

require_once '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// 2. AMBIL DATA DARI DATABASE
$query = mysqli_query($koneksi, "SELECT * FROM hewan");

$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: sans-serif; color: #333; line-height: 1.4; }
.header { text-align: center; margin-bottom: 25px; }
.header h2 { margin: 0; color: #1E3F20; font-size: 22px; }
.header p { margin: 5px 0 0 0; font-size: 12px; color: #666; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; }
th { background-color: #1E3F20; color: white; padding: 10px; font-size: 13px; text-align: left; }
td { padding: 10px; border-bottom: 1px solid #ddd; font-size: 12px; }
.status-aktif { color: #2e7d32; font-weight: bold; }
.status-diadopsi { color: #c62828; font-weight: bold; }
.footer { margin-top: 60px; text-align: right; font-size: 12px; color: #333; }
</style>
</head>
<body>
<div class="header">
<h2>LAPORAN DATA HEWAN PETADOPT</h2>
<p>Dicetak pada: ' . date('d F Y') . '</p>
</div>
<table>
<thead>
<tr>
<th style="width: 5%">No</th>
<th style="width: 30%">Nama Hewan</th>
<th style="width: 25%">Spesies / Ras</th>
<th style="width: 20%">Tanggal Masuk</th>
<th style="width: 20%">Status</th>
</tr>
</thead>
<tbody>';

$no = 1;
// 3. LOOPING DENGAN VARIABEL STERIL
while($row = mysqli_fetch_assoc($query)) {
    $nama = isset($row['nama']) ? $row['nama'] : (isset($row['nama_hewan']) ? $row['nama_hewan'] : 'Tanpa Nama');
    $spesies = isset($row['spesies']) ? $row['spesies'] : (isset($row['jenis_hewan']) ? $row['jenis_hewan'] : (isset($row['jenis']) ? $row['jenis'] : 'Tanpa Spesies'));
    
    if (!empty($row['ras'])) { 
        $spesies .= ' / ' . $row['ras']; 
    }
    
    $status = isset($row['status']) ? $row['status'] : (isset($row['status_adopsi']) ? $row['status_adopsi'] : 'Tersedia');
    $tanggal = isset($row['tanggal_masuk']) ? $row['tanggal_masuk'] : date('Y-m-d');
    
    // Perbaikan: gunakan variabel $status yang sudah aman
    $status_class = (strtolower($status) == 'tersedia') ? 'status-aktif' : 'status-diadopsi';
    
    // Perbaikan: panggil variabel hasil ekstraksi ($nama, $spesies, $status), BUKAN $row[...] murni
    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . htmlspecialchars($nama) . '</td>
        <td>' . htmlspecialchars($spesies) . '</td>
        <td>' . date('d M Y', strtotime($tanggal)) . '</td>
        <td class="' . $status_class . '">' . htmlspecialchars($status) . '</td>
    </tr>';
}

$html .= '
</tbody>
</table>
<div class="footer">
<p>Hormat Kami,</p>
<br><br><br>
<p><b>Admin PetAdopt Panel</b></p>
</div>
</body>
</html>';

// Bersihkan segala kebocoran output buffer sebelum render
if (ob_get_length()) {
    ob_end_clean();
}

// 4. PROSES RENDER DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 5. STREAM DOWNLOAD PDF
$dompdf->stream("Laporan_Data_Hewan_PetAdopt.pdf", array("Attachment" => true));
exit;
?>