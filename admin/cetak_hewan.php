<?php
include 'cek_login.php';
// 1. Load Dompdf Autoloader
require_once '../vendor/autoload.php';
require_once '../config/koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// 2. Ambil Data Hewan dari Database petadopt_db
$query = mysqli_query($koneksi, "SELECT * FROM hewan ORDER BY id_hewan DESC");

// 3. Rancang HTML Laporan Khas PetAdopt
$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; color: #1E3F20; }
        .header p { margin: 5px 0 0 0; font-size: 12px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #1E3F20; color: white; padding: 10px; font-size: 13px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; font-size: 12px; }
        .status-aktif { color: #2e7d32; font-weight: bold; }
        .status-diadopsi { color: #c62828; font-weight: bold; }
        .footer { margin-top: 50px; text-align: right; font-size: 12px; color: #666; }
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
if ($query && mysqli_num_rows($query) > 0) {
    while($row = mysqli_fetch_assoc($query)) {
        // Adjusting field names to match the database structure based on admin/index.php
        $nama = isset($row['nama_hewan']) ? $row['nama_hewan'] : '-';
        $spesies_ras = isset($row['jenis_hewan']) ? $row['jenis_hewan'] : '-';
        if (!empty($row['ras'])) {
            $spesies_ras .= ' / ' . $row['ras'];
        }
        $tanggal = !empty($row['tanggal_masuk']) ? date('d M Y', strtotime($row['tanggal_masuk'])) : '-';
        $status = isset($row['status_adopsi']) ? $row['status_adopsi'] : 'Tersedia';
        
        $status_class = (strtolower($status) == 'tersedia') ? 'status-aktif' : 'status-diadopsi';
        
        $html .= '<tr>
            <td>' . $no++ . '</td>
            <td>' . htmlspecialchars($nama) . '</td>
            <td>' . htmlspecialchars($spesies_ras) . '</td>
            <td>' . $tanggal . '</td>
            <td class="' . $status_class . '">' . htmlspecialchars($status) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="5" style="text-align: center;">Belum ada data hewan</td></tr>';
}

$html .= '
        </tbody>
    </table>
    <div class="footer">
        <p>Hormat Kami,</p>
        <br><br>
        <p><b>Admin PetAdopt Panel</b></p>
    </div>
</body>
</html>';

// 4. Proses Konversi Ke PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // useful if they eventually want to load images
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 5. Stream (Otomatis Download di Browser)
$dompdf->stream("Laporan_Data_Hewan_PetAdopt.pdf", array("Attachment" => true));
?>
