<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("REQUEST INVALID");
}

$variant_id     = $_POST['variant_id'] ?? '';
$nama_pengirim  = $_POST['nama_pengirim'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';

// Debug - log semua POST data
error_log("=== ORDER STORE DEBUG ===");
error_log("POST Data: " . print_r($_POST, true));
error_log("FILES Data: " . print_r($_FILES, true));

/* =========================
   GABUNG DATA USER INPUT
========================= */
if (isset($_POST['id_game']) && isset($_POST['region'])) {
    $user_input = "ID: ".$_POST['id_game']." | Region: ".$_POST['region'];
} else {
    $user_input = $_POST['user_input'] ?? '';
}

if (!$variant_id || !$user_input || !$nama_pengirim || !$payment_method) {
    die("Data tidak lengkap");
}

/* ambil variant + game */
$v = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
    v.id AS variant_id,
    v.nama_variant,
    v.harga_jual,
    v.game_id,
    g.nama_game,
    g.kategori,
    g.gambar
FROM product_variants v
JOIN games g ON v.game_id = g.id
WHERE v.id='$variant_id'
"));

if (!$v) {
    die("Variant tidak ditemukan");
}

/* =========================
   HANDLE VOUCHER DISCOUNT - FIXED
========================= */
$voucher_code = '';
$diskon_voucher = 0;
$harga_sebelum_diskon = $v['harga_jual'];
$harga_final = $v['harga_jual'];
$voucher_id = null;

// PERBAIKAN: Gunakan 'voucher_code' bukan 'voucher_code_final'
if (isset($_POST['voucher_code']) && !empty($_POST['voucher_code'])) {
    $voucher_code = strtoupper(mysqli_real_escape_string($conn, $_POST['voucher_code']));
    $diskon_voucher = (float)($_POST['diskon_voucher'] ?? 0);
    $harga_final = (float)($_POST['harga_final'] ?? $v['harga_jual']);
    
    error_log("Voucher detected - Code: $voucher_code, Diskon: $diskon_voucher, Final: $harga_final");
    
    // Validate voucher one more time
    $voucher_check = mysqli_query($conn, "
        SELECT id, kode_voucher, kuota_total, kuota_terpakai 
        FROM vouchers 
        WHERE kode_voucher = '$voucher_code' 
        AND status = 'AKTIF'
        AND NOW() BETWEEN tanggal_mulai AND tanggal_berakhir
    ");
    
    if (mysqli_num_rows($voucher_check) > 0) {
        $voucher_data = mysqli_fetch_assoc($voucher_check);
        $voucher_id = $voucher_data['id'];
        error_log("Voucher valid with ID: $voucher_id, Kuota: {$voucher_data['kuota_terpakai']}/{$voucher_data['kuota_total']}");
    } else {
        error_log("Voucher invalid: $voucher_code");
        $voucher_code = '';
        $diskon_voucher = 0;
        $harga_final = $v['harga_jual'];
    }
}

/* upload bukti */
if (!isset($_FILES['bukti_tf']) || $_FILES['bukti_tf']['error'] != 0) {
    die("Bukti TF wajib");
}

$nama_file = time().'_'.$_FILES['bukti_tf']['name'];
move_uploaded_file($_FILES['bukti_tf']['tmp_name'], "../uploads/".$nama_file);

/* generate resi */
$resi = "TRX" . date("YmdHis") . strtoupper(bin2hex(random_bytes(2)));

/* simpan order */
$order_query = mysqli_query($conn,"
INSERT INTO orders
(resi, user_id, variant_id, user_input, nama_pengirim, payment_method, bukti_tf, status, voucher_code, diskon_voucher, harga_sebelum_diskon)
VALUES
(
    '$resi',
    NULL,
    '$variant_id',
    '$user_input',
    '$nama_pengirim',
    '$payment_method',
    '$nama_file',
    'PENDING',
    " . ($voucher_code ? "'$voucher_code'" : "NULL") . ",
    $diskon_voucher,
    " . ($diskon_voucher > 0 ? $harga_sebelum_diskon : "NULL") . "
)
");

if (!$order_query) {
    error_log("Order Insert Error: " . mysqli_error($conn));
    die("Gagal menyimpan order");
}

$order_id = mysqli_insert_id($conn);
error_log("Order created with ID: " . $order_id);

/* Insert voucher usage tracking - FIXED */
if ($voucher_code && $diskon_voucher > 0 && $voucher_id) {
    error_log("=== VOUCHER USAGE START ===");
    error_log("Voucher ID: $voucher_id, Order ID: $order_id");
    
    // Check if already used
    $check_usage = mysqli_query($conn, "SELECT id FROM voucher_usage WHERE order_id = $order_id");
    if (mysqli_num_rows($check_usage) > 0) {
        error_log("Voucher usage already exists for order ID: $order_id");
    } else {
        // Insert ke voucher_usage
        $insert_usage = mysqli_query($conn, "
            INSERT INTO voucher_usage 
            (voucher_id, order_id, user_identifier, diskon_amount, harga_sebelum_diskon, harga_setelah_diskon, created_at)
            VALUES
            ($voucher_id, $order_id, '$nama_pengirim', $diskon_voucher, $harga_sebelum_diskon, $harga_final, NOW())
        ");
        
        if (!$insert_usage) {
            error_log("Voucher Usage Insert Error: " . mysqli_error($conn));
        } else {
            error_log("Voucher usage inserted successfully. ID: " . mysqli_insert_id($conn));
            
            // Update kuota voucher
            $update_kuota = mysqli_query($conn, "UPDATE vouchers SET kuota_terpakai = kuota_terpakai + 1 WHERE id = $voucher_id");
            
            if (!$update_kuota) {
                error_log("Voucher Kuota Update Error: " . mysqli_error($conn));
            } else {
                error_log("Voucher kuota updated successfully. Affected rows: " . mysqli_affected_rows($conn));
                
                // Verify the update
                $check_kuota = mysqli_query($conn, "SELECT kuota_terpakai FROM vouchers WHERE id = $voucher_id");
                if ($check_kuota) {
                    $kuota_data = mysqli_fetch_assoc($check_kuota);
                    error_log("New kuota_terpakai: " . $kuota_data['kuota_terpakai']);
                }
            }
        }
    }
    error_log("=== VOUCHER USAGE END ===");
}

// ... rest of the code (telegram notification, etc) ...

/* TELEGRAM NOTIFICATION */
$BOT_TOKEN = "7406726074:AAEzvMu-ihMwGJF-FMFOTMGCLKsM_au8Atk";
$CHAT_ID  = "7221419012";

$caption = "🛒 *PESANAN BARU*\n\n".
           "📂 Kategori: {$v['kategori']}\n".
           "🎮 Game: {$v['nama_game']}\n".
           "📦 Item: {$v['nama_variant']}\n".
           "🆔 Data Game: $user_input\n".
           "👤 Pengirim: $nama_pengirim\n".
           "💳 Pembayaran: $payment_method\n";

if ($voucher_code && $diskon_voucher > 0) {
    $caption .= "🎟️ Voucher: $voucher_code\n".
               "💰 Harga Asli: Rp".number_format($harga_sebelum_diskon, 0, ',', '.')."\n".
               "🏷️ Diskon: -Rp".number_format($diskon_voucher, 0, ',', '.')."\n".
               "✅ Total Bayar: Rp".number_format($harga_final, 0, ',', '.')."\n";
} else {
    $caption .= "💵 Total: Rp".number_format($v['harga_jual'], 0, ',', '.')."\n";
}

$caption .= "🧾 Resi: $resi\n".
           "💰 Total: Rp".number_format($v['harga_jual'])."\n\n".
           "Status: *PENDING*\n\n".
           "Balas:\n".
           "1️⃣ Selesaikan\n".
           "2️⃣ Batalkan";

$url = "https://api.telegram.org/bot$BOT_TOKEN/sendPhoto";

$post_fields = [
    'chat_id' => $CHAT_ID,
    'caption' => $caption,
    'parse_mode' => 'Markdown',
    'photo' => new CURLFile("../uploads/".$nama_file)
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

/* AMBIL MESSAGE ID TELEGRAM */
$res = json_decode($response, true);

if (isset($res['result']['message_id'])) {
    $chat_id = $res['result']['chat']['id'];
    $msg_id  = $res['result']['message_id'];

    mysqli_query($conn,"
    UPDATE orders SET
        telegram_chat_id='$chat_id',
        telegram_msg_id='$msg_id'
    WHERE resi='$resi'
    ");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil | Gudang Barokah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --bg-primary: #1a1d29;
      --bg-secondary: #23263a;
      --bg-card: #2a2e45;
      --text-primary: #ffffff;
      --text-secondary: #a0a6c1;
      --accent: #5b7cff;
      --border: rgba(255, 255, 255, 0.1);
      --success: #10b981;
    }

    body {
      background: var(--bg-primary);
      color: var(--text-primary);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    /* HEADER */
    header {
      background: var(--bg-secondary);
      border-bottom: 1px solid var(--border);
      padding: 16px 32px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 22px;
      font-weight: 700;
      color: var(--accent);
      text-decoration: none;
    }

    .logo img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    /* SUCCESS CONTAINER */
    .success-container {
      width: 100%;
      max-width: 700px;
      margin-top: 100px;
    }

    /* SUCCESS CARD */
    .success-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 48px;
      text-align: center;
      animation: slideUp 0.6s ease;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* SUCCESS ICON */
    .success-icon {
      width: 100px;
      height: 100px;
      margin: 0 auto 24px;
      background: linear-gradient(135deg, var(--success), #059669);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: scaleIn 0.5s ease 0.2s backwards;
      position: relative;
    }

    .success-icon::before {
      content: '';
      position: absolute;
      inset: -10px;
      background: rgba(16, 185, 129, 0.2);
      border-radius: 50%;
      animation: pulse 2s ease infinite;
    }

    @keyframes scaleIn {
      from {
        transform: scale(0);
      }
      to {
        transform: scale(1);
      }
    }

    @keyframes pulse {
      0%, 100% {
        opacity: 1;
        transform: scale(1);
      }
      50% {
        opacity: 0.5;
        transform: scale(1.1);
      }
    }

    .success-icon i {
      font-size: 48px;
      color: white;
      position: relative;
      z-index: 1;
    }

    .success-title {
      font-size: 32px;
      font-weight: 800;
      margin-bottom: 12px;
      background: linear-gradient(135deg, var(--success), #059669);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .success-message {
      font-size: 16px;
      color: var(--text-secondary);
      margin-bottom: 40px;
    }

    /* ORDER DETAILS */
    .order-details {
      background: var(--bg-primary);
      border-radius: 12px;
      padding: 32px;
      margin-bottom: 32px;
      text-align: left;
    }

    .detail-header {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 24px;
      padding-bottom: 20px;
      border-bottom: 1px solid var(--border);
    }

    .detail-image {
      width: 80px;
      height: 80px;
      border-radius: 12px;
      overflow: hidden;
      flex-shrink: 0;
    }

    .detail-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .detail-info h3 {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 6px;
    }

    .detail-info p {
      font-size: 14px;
      color: var(--text-secondary);
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px 0;
      border-bottom: 1px solid var(--border);
    }

    .detail-row:last-child {
      border-bottom: none;
    }

    .detail-label {
      font-size: 14px;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .detail-label i {
      width: 20px;
      color: var(--accent);
    }

    .detail-value {
      font-size: 15px;
      font-weight: 600;
      color: var(--text-primary);
      text-align: right;
    }

    .resi-box {
      background: var(--bg-card);
      border: 2px dashed var(--accent);
      border-radius: 8px;
      padding: 16px;
      text-align: center;
      margin: 24px 0;
    }

    .resi-label {
      font-size: 12px;
      color: var(--text-secondary);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
    }

    .resi-code {
      font-size: 24px;
      font-weight: 800;
      color: var(--accent);
      font-family: 'Courier New', monospace;
      letter-spacing: 2px;
    }

    .copy-btn {
      margin-top: 12px;
      padding: 8px 16px;
      background: rgba(91, 124, 255, 0.1);
      border: 1px solid var(--accent);
      border-radius: 6px;
      color: var(--accent);
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .copy-btn:hover {
      background: var(--accent);
      color: white;
    }

    .total-row {
      background: rgba(91, 124, 255, 0.1);
      border-radius: 8px;
      padding: 20px;
      margin-top: 16px;
    }

    .total-row .detail-label {
      font-size: 16px;
      font-weight: 600;
      color: var(--text-primary);
    }

    .total-row .detail-value {
      font-size: 28px;
      font-weight: 800;
      color: var(--accent);
    }

    /* STATUS BADGE */
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      background: rgba(245, 158, 11, 0.1);
      border: 1px solid #f59e0b;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #f59e0b;
    }

    .status-badge i {
      animation: spin 2s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* ACTIONS */
    .actions {
      display: flex;
      gap: 12px;
    }

    .btn {
      flex: 1;
      padding: 16px 24px;
      border-radius: 10px;
      font-weight: 700;
      font-size: 15px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      border: none;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--accent), #7c3aed);
      color: white;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 24px rgba(91, 124, 255, 0.4);
    }

    .btn-secondary {
      background: var(--bg-primary);
      border: 1px solid var(--border);
      color: var(--text-primary);
    }

    .btn-secondary:hover {
      border-color: var(--accent);
      color: var(--accent);
    }

    /* INFO BOX */
    .info-box {
      background: rgba(91, 124, 255, 0.1);
      border-left: 4px solid var(--accent);
      border-radius: 8px;
      padding: 16px;
      margin-top: 24px;
      display: flex;
      gap: 12px;
    }

    .info-box i {
      color: var(--accent);
      font-size: 20px;
      flex-shrink: 0;
    }

    .info-box-content {
      font-size: 14px;
      line-height: 1.6;
      color: var(--text-secondary);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      header {
        padding: 14px 20px;
      }

      .success-card {
        padding: 32px 24px;
      }

      .success-title {
        font-size: 24px;
      }

      .order-details {
        padding: 24px;
      }

      .detail-header {
        flex-direction: column;
        text-align: center;
      }

      .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }

      .detail-value {
        text-align: left;
      }

      .actions {
        flex-direction: column;
      }

      .resi-code {
        font-size: 18px;
      }
    }
    </style>
</head>
<body>

<header>
  <a href="dashboard.php" class="logo">
    <img src="../uploads/logo.png" alt="Gudang Barokah">
    <span>Gudang Barokah</span>
  </a>
</header>

<div class="success-container">
  <div class="success-card">
    <!-- SUCCESS ICON -->
    <div class="success-icon">
      <i class="fa-solid fa-check"></i>
    </div>

    <h1 class="success-title">Pesanan Berhasil!</h1>
    <p class="success-message">Terima kasih telah melakukan pemesanan. Pesanan Anda sedang diproses.</p>

    <!-- RESI BOX -->
    <div class="resi-box">
      <div class="resi-label">Nomor Resi</div>
      <div class="resi-code" id="resiCode"><?= $resi ?></div>
      <button class="copy-btn" onclick="copyResi()">
        <i class="fa-solid fa-copy"></i> Salin Resi
      </button>
    </div>

    <!-- ORDER DETAILS -->
    <div class="order-details">
      <div class="detail-header">
        <div class="detail-image">
          <img src="../uploads/<?= $v['gambar'] ?>" alt="<?= $v['nama_game'] ?>">
        </div>
        <div class="detail-info">
          <h3><?= $v['nama_game'] ?></h3>
          <p><?= $v['nama_variant'] ?></p>
        </div>
      </div>

      <div class="detail-row">
        <span class="detail-label">
          <i class="fa-solid fa-folder"></i>
          Kategori
        </span>
        <span class="detail-value"><?= $v['kategori'] ?></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">
          <i class="fa-solid fa-gamepad"></i>
          Data Game
        </span>
        <span class="detail-value"><?= $user_input ?></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">
          <i class="fa-solid fa-user"></i>
          Nama Pengirim
        </span>
        <span class="detail-value"><?= $nama_pengirim ?></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">
          <i class="fa-solid fa-credit-card"></i>
          Metode Pembayaran
        </span>
        <span class="detail-value"><?= $payment_method ?></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">
          <i class="fa-solid fa-clock"></i>
          Status
        </span>
        <span class="status-badge">
          <i class="fa-solid fa-spinner"></i>
          PENDING
        </span>
      </div>

      <?php if ($voucher_code && $diskon_voucher > 0): ?>
      <div class="detail-row" style="background: rgba(16, 185, 129, 0.1); border-left: 4px solid var(--success); padding-left: 20px;">
        <span class="detail-label">
          <i class="fa-solid fa-ticket"></i>
          Voucher Diskon
        </span>
        <span class="detail-value" style="color: var(--success); font-weight: 600;"><?= $voucher_code ?></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">
          <i class="fa-solid fa-money-bill"></i>
          Harga Asli
        </span>
        <span class="detail-value" style="text-decoration: line-through; color: var(--text-secondary);">Rp<?= number_format($harga_sebelum_diskon, 0, ',', '.') ?></span>
      </div>

      <div class="detail-row">
        <span class="detail-label">
          <i class="fa-solid fa-tag"></i>
          Potongan Diskon
        </span>
        <span class="detail-value" style="color: var(--success); font-weight: 600;">-Rp<?= number_format($diskon_voucher, 0, ',', '.') ?></span>
      </div>
      <?php endif; ?>

      <div class="total-row">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <span class="detail-label">Total Pembayaran</span>
          <span class="detail-value">Rp<?= number_format($harga_final, 0, ',', '.') ?></span>
        </div>
      </div>
    </div>

    <!-- INFO BOX -->
    <div class="info-box">
      <i class="fa-solid fa-info-circle"></i>
      <div class="info-box-content">
        <strong>Catatan:</strong><br>
        Pesanan Anda sedang dalam proses verifikasi. Kami akan mengirimkan notifikasi setelah pembayaran dikonfirmasi. Simpan nomor resi Anda untuk tracking pesanan.
      </div>
    </div>

    <!-- ACTIONS -->
    <div class="actions">
      <a href="dashboard.php" class="btn btn-secondary">
        <i class="fa-solid fa-home"></i>
        Kembali ke Beranda
      </a>
      <a href="order.php?game_id=<?= $v['game_id'] ?>" class="btn btn-primary">
        <i class="fa-solid fa-shopping-cart"></i>
        Pesan Lagi
      </a>
    </div>
  </div>
</div>

<script>
function copyResi() {
  const resiCode = document.getElementById('resiCode').textContent;
  navigator.clipboard.writeText(resiCode).then(() => {
    const btn = event.target.closest('.copy-btn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin!';
    btn.style.background = 'var(--success)';
    btn.style.color = 'white';
    btn.style.borderColor = 'var(--success)';
    
    setTimeout(() => {
      btn.innerHTML = originalText;
      btn.style.background = 'rgba(91, 124, 255, 0.1)';
      btn.style.color = 'var(--accent)';
      btn.style.borderColor = 'var(--accent)';
    }, 2000);
  });
}
</script>

</body>
</html>