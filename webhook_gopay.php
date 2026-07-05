<?php
// ====================================================================
// 1. VALIDASI KEAMANAN (Cek API Key agar tidak sembarang orang bisa akses)
// ====================================================================
$my_secret_key = "vuxamuxkqyodhc1sn2b3miwhrnk0rh21";

if (!isset($_GET['key']) || $_GET['key'] !== $my_secret_key) {
    http_response_code(403);
    die("Akses ditolak: Key tidak valid.");
}

// ====================================================================
// 2. TANGKAP DATA DARI AUTOMATE (Format: WWW Form / $_POST)
// ====================================================================
// Di Automate tadi kita mengirim parameter text={notification_text}
if (!isset($_POST['text'])) {
    http_response_code(400);
    die("Data tidak ditemukan / Request bukan POST Form.");
}

$text_notifikasi = $_POST['text']; 

// ====================================================================
// 3. PROSES REGEX UNTUK AMBIL NOMINAL (Contoh: "Dana Rp 10.123 telah masuk...")
// ====================================================================
if (preg_match('/Rp\s?([\d.]+)/i', $text_notifikasi, $matches)) {
    // Mengubah format ribuan, misal "10.123" menjadi angka murni 10123
    $nominal_masuk = (int) str_replace('.', '', $matches[1]);
    
    // ================================================================
    // 4. PROSES DATABASE LOCALHOST (XAMPP)
    // ================================================================
    // Silakan ganti "user_db", "pass_db", dan "nama_db" sesuai config database barokah lu
    $koneksi = new mysqli("localhost", "root", "", "barokah");
    
    // Cek apakah koneksi database berhasil
    if ($koneksi->connect_error) {
        http_response_code(500);
        die(json_encode(["status" => "error", "message" => "Koneksi database gagal: " . $koneksi->connect_error]));
    }
    
    // Cari transaksi PENDING yang nominal uniknya cocok pas dengan uang masuk
    $stmt = $koneksi->prepare("SELECT id_order, username FROM transaksi WHERE total_bayar = ? AND status = 'PENDING' LIMIT 1");
    $stmt->bind_param("i", $nominal_masuk);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        $id_order = $order['id_order'];
        
        // Update status invoice / transaksi tersebut menjadi SUCCESS
        $update_stmt = $koneksi->prepare("UPDATE transaksi SET status = 'SUCCESS' WHERE id_order = ?");
        $update_stmt->bind_param("s", $id_order);
        $update_stmt->execute();
        
        // [OPSIONAL] Tempat naruh fungsi buat tembak API Digiflazz/VIP setelah status sukses
        // kirim_produk_otomatis($id_order);
        
        echo json_encode([
            "status" => "success", 
            "message" => "Order $id_order dengan nominal Rp $nominal_masuk berhasil diproses otomatis!"
        ]);
    } else {
        // Kalau uang masuk tapi di web ga ada invoice pending dengan nominal segitu
        echo json_encode([
            "status" => "ignored", 
            "message" => "Nominal Rp $nominal_masuk diterima, tapi tidak cocok dengan order PENDING manapun."
        ]);
    }
    
    $koneksi->close();
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Format teks notifikasi tidak dikenali oleh sistem regex."
    ]);
}
?>