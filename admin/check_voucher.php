<?php
include "../config/database.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $kode_voucher = strtoupper(mysqli_real_escape_string($conn, $_POST['kode_voucher'] ?? ''));
    $game_id = (int)($_POST['game_id'] ?? 0);
    $harga = (float)($_POST['harga'] ?? 0);
    
    // Validate input
    if (empty($kode_voucher)) {
        echo json_encode([
            'success' => false,
            'message' => 'Kode voucher tidak boleh kosong'
        ]);
        exit;
    }
    
    // Get voucher
    $voucher_query = mysqli_query($conn, "
        SELECT * FROM vouchers 
        WHERE kode_voucher = '$kode_voucher'
        LIMIT 1
    ");
    
    if (mysqli_num_rows($voucher_query) == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Kode voucher tidak valid'
        ]);
        exit;
    }
    
    $voucher = mysqli_fetch_assoc($voucher_query);
    
    // Check status
    if ($voucher['status'] != 'AKTIF') {
        echo json_encode([
            'success' => false,
            'message' => 'Voucher tidak aktif'
        ]);
        exit;
    }
    
    // Check date validity
    $now = date('Y-m-d H:i:s');
    if ($now < $voucher['tanggal_mulai']) {
        echo json_encode([
            'success' => false,
            'message' => 'Voucher belum dapat digunakan'
        ]);
        exit;
    }
    
    if ($now > $voucher['tanggal_berakhir']) {
        echo json_encode([
            'success' => false,
            'message' => 'Voucher sudah kadaluarsa'
        ]);
        exit;
    }
    
    // Check kuota
    if ($voucher['kuota_total'] !== NULL) {
        if ($voucher['kuota_terpakai'] >= $voucher['kuota_total']) {
            echo json_encode([
                'success' => false,
                'message' => 'Kuota voucher sudah habis'
            ]);
            exit;
        }
    }
    
    // Check minimal pembelian
    if ($harga < $voucher['min_pembelian']) {
        echo json_encode([
            'success' => false,
            'message' => 'Minimal pembelian Rp' . number_format($voucher['min_pembelian'], 0, ',', '.') . ' untuk menggunakan voucher ini'
        ]);
        exit;
    }
    
    // Check berlaku untuk produk
    $berlaku_untuk = json_decode($voucher['berlaku_untuk'], true);
    if (!in_array('ALL', $berlaku_untuk) && !in_array((string)$game_id, $berlaku_untuk)) {
        echo json_encode([
            'success' => false,
            'message' => 'Voucher tidak berlaku untuk produk ini'
        ]);
        exit;
    }
    
    // Calculate discount
    $diskon = 0;
    
    if ($voucher['tipe_diskon'] == 'PERSEN') {
        $diskon = ($harga * $voucher['nilai_diskon']) / 100;
        
        // Check max diskon
        if ($voucher['max_diskon'] !== NULL && $diskon > $voucher['max_diskon']) {
            $diskon = $voucher['max_diskon'];
        }
    } else {
        // RUPIAH
        $diskon = $voucher['nilai_diskon'];
        
        // Diskon tidak boleh lebih besar dari harga
        if ($diskon > $harga) {
            $diskon = $harga;
        }
    }
    
    $harga_final = $harga - $diskon;
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Voucher berhasil digunakan!',
        'voucher' => [
            'kode' => $voucher['kode_voucher'],
            'nama' => $voucher['nama_voucher'],
            'tipe' => $voucher['tipe_diskon'],
            'nilai' => $voucher['nilai_diskon']
        ],
        'harga_asli' => $harga,
        'diskon' => $diskon,
        'harga_final' => $harga_final,
        'hemat' => number_format($diskon, 0, ',', '.')
    ]);
    exit;
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}
?>