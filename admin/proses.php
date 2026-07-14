<?php
include 'cek_login.php';
// Wajib menggunakan koneksi yang sudah ada (variabel $koneksi)
require_once '../config/koneksi.php';

// Cek apakah ada aksi tambah
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    
    // Ambil data dari form dan lindungi dari SQL Injection
    // Variabel penampung (dari $_POST) dicocokkan dengan name di tambah.php
    $nama_hewan = mysqli_real_escape_string($koneksi, $_POST['nama_hewan']);
    $jenis_hewan = mysqli_real_escape_string($koneksi, $_POST['jenis_hewan']);
    $ras = mysqli_real_escape_string($koneksi, $_POST['ras']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $tanggal_masuk = mysqli_real_escape_string($koneksi, $_POST['tanggal_masuk']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    
    $foto_name = '';

    // Proses unggah foto jika ada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed_ext = array('png', 'jpg', 'jpeg');
        $file_name = $_FILES['foto']['name'];
        $file_size = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validasi Ekstensi
        if (in_array($file_ext, $allowed_ext)) {
            // Validasi Ukuran (Max 2MB)
            if ($file_size <= 2097152) {
                // Rename file agar unik menggunakan fungsi time()
                $new_file_name = time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                $upload_path = '../assets/uploads/';
                
                // Pastikan direktori tujuan ada, jika belum maka buat otomatis
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }
                
                // Pindahkan file ke folder target
                if (move_uploaded_file($file_tmp, $upload_path . $new_file_name)) {
                    $foto_name = $new_file_name;
                }
            } else {
                echo "<script>alert('Gagal! Ukuran file foto melebihi batas maksimal 2MB.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Gagal! Ekstensi file tidak valid. Hanya izinkan PNG, JPG, dan JPEG.'); window.history.back();</script>";
            exit;
        }
    }

    // Eksekusi query INSERT ke database menggunakan variabel $koneksi
    $query = "INSERT INTO hewan (nama_hewan, jenis_hewan, ras, lokasi, tanggal_lahir, tanggal_masuk, deskripsi, foto) 
              VALUES ('$nama_hewan', '$jenis_hewan', '$ras', '$lokasi', '$tanggal_lahir', '$tanggal_masuk', '$deskripsi', '$foto_name')";
              
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, redirect ke halaman utama admin
        header("Location: index.php");
        exit;
    } else {
        // Jika gagal query
        echo "Error saat menyimpan data: " . mysqli_error($koneksi);
        exit;
    }
} else if (isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {
    // Ambil data form
    $id_hewan = mysqli_real_escape_string($koneksi, $_POST['id_hewan']);
    $nama_hewan = mysqli_real_escape_string($koneksi, $_POST['nama_hewan']);
    $jenis_hewan = mysqli_real_escape_string($koneksi, $_POST['jenis_hewan']);
    $ras = mysqli_real_escape_string($koneksi, $_POST['ras']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $tanggal_masuk = mysqli_real_escape_string($koneksi, $_POST['tanggal_masuk']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $status_adopsi = mysqli_real_escape_string($koneksi, $_POST['status_adopsi']); // Menangkap data status_adopsi
    $foto_lama = mysqli_real_escape_string($koneksi, $_POST['foto_lama']);
    
    // Secara default, foto_name menggunakan nama foto lama jika tidak ada upload baru
    $foto_name = $foto_lama;

    // Proses unggah foto baru jika user memilih file baru
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed_ext = array('png', 'jpg', 'jpeg');
        $file_name = $_FILES['foto']['name'];
        $file_size = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed_ext)) {
            if ($file_size <= 2097152) { // Maksimal 2MB
                $new_file_name = time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                $upload_path = '../assets/uploads/';
                
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }
                
                if (move_uploaded_file($file_tmp, $upload_path . $new_file_name)) {
                    $foto_name = $new_file_name;
                    // Hapus file foto lama agar menghemat space
                    if (!empty($foto_lama) && file_exists($upload_path . $foto_lama)) {
                        unlink($upload_path . $foto_lama);
                    }
                }
            } else {
                echo "<script>alert('Gagal! Ukuran file foto melebihi batas maksimal 2MB.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Gagal! Ekstensi file tidak valid. Hanya izinkan PNG, JPG, dan JPEG.'); window.history.back();</script>";
            exit;
        }
    }

    // Eksekusi query UPDATE ke database
    $query = "UPDATE hewan SET 
                nama_hewan = '$nama_hewan', 
                jenis_hewan = '$jenis_hewan', 
                ras = '$ras', 
                lokasi = '$lokasi',
                tanggal_lahir = '$tanggal_lahir', 
                tanggal_masuk = '$tanggal_masuk', 
                deskripsi = '$deskripsi', 
                status_adopsi = '$status_adopsi',
                foto = '$foto_name' 
              WHERE id_hewan = '$id_hewan'";
              
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error saat mengupdate data: " . mysqli_error($koneksi);
        exit;
    }
} else if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_hewan = mysqli_real_escape_string($koneksi, $_GET['id_hewan']);
    
    // Ambil data foto
    $query_foto = "SELECT foto FROM hewan WHERE id_hewan = '$id_hewan'";
    $result_foto = mysqli_query($koneksi, $query_foto);
    
    if ($row_foto = mysqli_fetch_assoc($result_foto)) {
        $foto_lama = $row_foto['foto'];
        if (!empty($foto_lama)) {
            $path_foto = '../assets/uploads/' . $foto_lama;
            if (file_exists($path_foto)) {
                unlink($path_foto);
            }
        }
    }
    
    // Delete data dari database
    $query = "DELETE FROM hewan WHERE id_hewan = '$id_hewan'";
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error saat menghapus data: " . mysqli_error($koneksi);
        exit;
    }
} else {
    // Jika ada yang mencoba mengakses file ini secara langsung tanpa melewati form
    header("Location: index.php");
    exit;
}
?>
