<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id = (int)$_POST['id'];
    $kode_voucher = strtoupper(mysqli_real_escape_string($conn, $_POST['kode_voucher']));
    $nama_voucher = mysqli_real_escape_string($conn, $_POST['nama_voucher']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi'] ?? '');
    $tipe_diskon = mysqli_real_escape_string($conn, $_POST['tipe_diskon']);
    $nilai_diskon = (float)$_POST['nilai_diskon'];
    $min_pembelian = (float)$_POST['min_pembelian'];
    $max_diskon = !empty($_POST['max_diskon']) ? (float)$_POST['max_diskon'] : NULL;
    $kuota_total = !empty($_POST['kuota_total']) ? (int)$_POST['kuota_total'] : NULL;
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $tanggal_mulai = mysqli_real_escape_string($conn, $_POST['tanggal_mulai']);
    $tanggal_berakhir = mysqli_real_escape_string($conn, $_POST['tanggal_berakhir']);
    
    // Check if voucher exists
    $check = mysqli_query($conn, "SELECT id FROM vouchers WHERE id = $id");
    if (mysqli_num_rows($check) == 0) {
        header("Location: vouchers.php?error=not_found");
        exit;
    }
    
    // Check if kode voucher already exists (except current voucher)
    $check_code = mysqli_query($conn, "SELECT id FROM vouchers WHERE kode_voucher = '$kode_voucher' AND id != $id");
    if (mysqli_num_rows($check_code) > 0) {
        header("Location: voucher_edit.php?id=$id&error=duplicate");
        exit;
    }
    
    // Handle berlaku_untuk
    if (isset($_POST['all_products']) && $_POST['all_products'] == 'on') {
        $berlaku_untuk = json_encode(['ALL']);
    } else {
        if (isset($_POST['games']) && is_array($_POST['games']) && count($_POST['games']) > 0) {
            $berlaku_untuk = json_encode($_POST['games']);
        } else {
            header("Location: voucher_edit.php?id=$id&error=no_products");
            exit;
        }
    }
    
    // Prepare SQL
    $max_diskon_sql = $max_diskon !== NULL ? $max_diskon : 'NULL';
    $kuota_total_sql = $kuota_total !== NULL ? $kuota_total : 'NULL';
    
    // Update voucher
    $sql = "UPDATE vouchers SET
        kode_voucher = '$kode_voucher',
        nama_voucher = '$nama_voucher',
        deskripsi = '$deskripsi',
        tipe_diskon = '$tipe_diskon',
        nilai_diskon = $nilai_diskon,
        min_pembelian = $min_pembelian,
        max_diskon = $max_diskon_sql,
        kuota_total = $kuota_total_sql,
        tanggal_mulai = '$tanggal_mulai',
        tanggal_berakhir = '$tanggal_berakhir',
        status = '$status',
        berlaku_untuk = '$berlaku_untuk',
        updated_at = NOW()
    WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: vouchers.php?success=updated");
        exit;
    } else {
        header("Location: voucher_edit.php?id=$id&error=database&msg=" . urlencode(mysqli_error($conn)));
        exit;
    }
    
} else {
    header("Location: vouchers.php");
    exit;
}
?>