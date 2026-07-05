<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
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
    
    // Check if kode voucher already exists
    $check = mysqli_query($conn, "SELECT id FROM vouchers WHERE kode_voucher = '$kode_voucher'");
    if (mysqli_num_rows($check) > 0) {
        header("Location: voucher_add.php?error=duplicate");
        exit;
    }
    
    // Handle berlaku_untuk (applicable products)
    if (isset($_POST['all_products']) && $_POST['all_products'] == 'on') {
        $berlaku_untuk = json_encode(['ALL']);
    } else {
        if (isset($_POST['games']) && is_array($_POST['games']) && count($_POST['games']) > 0) {
            $berlaku_untuk = json_encode($_POST['games']);
        } else {
            header("Location: voucher_add.php?error=no_products");
            exit;
        }
    }
    
    // Prepare SQL with proper NULL handling
    $max_diskon_sql = $max_diskon !== NULL ? $max_diskon : 'NULL';
    $kuota_total_sql = $kuota_total !== NULL ? $kuota_total : 'NULL';
    
    // Insert voucher
    $sql = "INSERT INTO vouchers (
        kode_voucher, 
        nama_voucher, 
        deskripsi,
        tipe_diskon, 
        nilai_diskon, 
        min_pembelian, 
        max_diskon,
        kuota_total,
        kuota_terpakai,
        tanggal_mulai, 
        tanggal_berakhir, 
        status,
        berlaku_untuk,
        created_at
    ) VALUES (
        '$kode_voucher',
        '$nama_voucher',
        '$deskripsi',
        '$tipe_diskon',
        $nilai_diskon,
        $min_pembelian,
        $max_diskon_sql,
        $kuota_total_sql,
        0,
        '$tanggal_mulai',
        '$tanggal_berakhir',
        '$status',
        '$berlaku_untuk',
        NOW()
    )";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: vouchers.php?success=created");
        exit;
    } else {
        header("Location: voucher_add.php?error=database&msg=" . urlencode(mysqli_error($conn)));
        exit;
    }
    
} else {
    header("Location: voucher_add.php");
    exit;
}
?>