<?php
include __DIR__ . "/config/database.php";

$data = null;

if(isset($_POST['resi'])){
    $resi = mysqli_real_escape_string($conn, $_POST['resi']);

    $q = mysqli_query($conn,"
        SELECT 
            o.resi,
            o.status,
            o.created_at,
            o.user_input,
            o.nama_pengirim,
            o.payment_method,
            o.bukti_tf,
            v.nama_variant,
            v.harga_jual,
            g.nama_game,
            g.kategori,
            g.gambar
        FROM orders o
        JOIN product_variants v ON o.variant_id = v.id
        JOIN games g ON v.game_id = g.id
        WHERE o.resi='$resi'
        LIMIT 1
    ");

    if(mysqli_num_rows($q)>0){
        $data = mysqli_fetch_assoc($q);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan | Gudang Barokah</title>
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
      --warning: #f59e0b;
      --danger: #ef4444;
    }

    body {
      background: var(--bg-primary);
      color: var(--text-primary);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      min-height: 100vh;
      background-image: 
        radial-gradient(at 0% 0%, rgba(91, 124, 255, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(124, 58, 237, 0.15) 0px, transparent 50%);
    }

    /* HEADER */
    header {
      background: var(--bg-secondary);
      border-bottom: 1px solid var(--border);
      padding: 16px 32px;
      position: sticky;
      top: 0;
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

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--text-primary);
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .back-btn:hover {
      border-color: var(--accent);
      color: var(--accent);
    }

    /* CONTAINER */
    .container {
      max-width: 800px;
      margin: 60px auto;
      padding: 0 20px;
    }

    /* SEARCH BOX */
    .search-box {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 40px;
      margin-bottom: 32px;
      text-align: center;
      animation: fadeInDown 0.5s ease;
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .search-icon-wrapper {
      width: 80px;
      height: 80px;
      margin: 0 auto 24px;
      background: linear-gradient(135deg, var(--accent), #7c3aed);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .search-icon-wrapper::before {
      content: '';
      position: absolute;
      inset: -8px;
      background: rgba(91, 124, 255, 0.2);
      border-radius: 50%;
      animation: pulse 2s ease infinite;
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

    .search-icon-wrapper i {
      font-size: 36px;
      color: white;
      position: relative;
      z-index: 1;
    }

    .search-box h1 {
      font-size: 28px;
      font-weight: 800;
      margin-bottom: 12px;
    }

    .search-box p {
      color: var(--text-secondary);
      margin-bottom: 32px;
      font-size: 15px;
    }

    /* FORM */
    .search-form {
      position: relative;
      max-width: 500px;
      margin: 0 auto;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      font-size: 18px;
      z-index: 1;
    }

    .search-input {
      width: 100%;
      padding: 16px 20px 16px 56px;
      background: var(--bg-primary);
      border: 2px solid var(--border);
      border-radius: 12px;
      color: var(--text-primary);
      font-size: 15px;
      font-family: inherit;
      outline: none;
      transition: all 0.3s ease;
    }

    .search-input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 4px rgba(91, 124, 255, 0.1);
    }

    .search-input::placeholder {
      color: var(--text-secondary);
    }

    .search-btn {
      width: 100%;
      margin-top: 16px;
      padding: 16px;
      background: linear-gradient(135deg, var(--accent), #7c3aed);
      border: none;
      border-radius: 10px;
      color: white;
      font-weight: 700;
      font-size: 15px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .search-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 24px rgba(91, 124, 255, 0.4);
    }

    /* RESULT CARD */
    .result-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 20px;
      overflow: hidden;
      animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* STATUS HEADER */
    .status-header {
      padding: 32px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .status-header.pending {
      background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
      border-bottom: 2px solid var(--warning);
    }

    .status-header.selesai {
      background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
      border-bottom: 2px solid var(--success);
    }

    .status-header.batal {
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
      border-bottom: 2px solid var(--danger);
    }

    .status-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 16px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
    }

    .status-header.pending .status-icon {
      background: var(--warning);
      color: white;
      animation: spin 2s linear infinite;
    }

    .status-header.selesai .status-icon {
      background: var(--success);
      color: white;
    }

    .status-header.batal .status-icon {
      background: var(--danger);
      color: white;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .status-title {
      font-size: 24px;
      font-weight: 800;
      margin-bottom: 8px;
    }

    .status-header.pending .status-title { color: var(--warning); }
    .status-header.selesai .status-title { color: var(--success); }
    .status-header.batal .status-title { color: var(--danger); }

    .status-message {
      color: var(--text-secondary);
      font-size: 14px;
    }

    /* ORDER DETAILS */
    .order-details {
      padding: 32px;
    }

    .product-header {
      display: flex;
      gap: 16px;
      margin-bottom: 32px;
      padding-bottom: 24px;
      border-bottom: 1px solid var(--border);
    }

    .product-image {
      width: 80px;
      height: 80px;
      border-radius: 12px;
      overflow: hidden;
      flex-shrink: 0;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .product-info h3 {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 6px;
    }

    .product-info p {
      font-size: 14px;
      color: var(--text-secondary);
      margin-bottom: 8px;
    }

    .product-category {
      display: inline-block;
      background: rgba(91, 124, 255, 0.1);
      color: var(--accent);
      padding: 4px 12px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .detail-grid {
      display: grid;
      gap: 16px;
    }

    .detail-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 16px;
      background: var(--bg-primary);
      border-radius: 10px;
    }

    .detail-icon {
      width: 40px;
      height: 40px;
      background: rgba(91, 124, 255, 0.1);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--accent);
      flex-shrink: 0;
    }

    .detail-content {
      flex: 1;
    }

    .detail-label {
      font-size: 12px;
      color: var(--text-secondary);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
    }

    .detail-value {
      font-size: 15px;
      font-weight: 600;
      color: var(--text-primary);
      word-break: break-all;
    }

    .price-box {
      margin-top: 24px;
      padding: 20px;
      background: rgba(91, 124, 255, 0.1);
      border-radius: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .price-label {
      font-size: 14px;
      color: var(--text-secondary);
    }

    .price-value {
      font-size: 24px;
      font-weight: 800;
      color: var(--accent);
    }

    /* NOT FOUND */
    .not-found {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 48px;
      text-align: center;
      animation: fadeInUp 0.5s ease;
    }

    .not-found-icon {
      width: 100px;
      height: 100px;
      margin: 0 auto 24px;
      background: rgba(239, 68, 68, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .not-found-icon i {
      font-size: 48px;
      color: var(--danger);
    }

    .not-found h3 {
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 12px;
      color: var(--danger);
    }

    .not-found p {
      color: var(--text-secondary);
      margin-bottom: 24px;
    }

    .retry-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--text-primary);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .retry-btn:hover {
      border-color: var(--accent);
      color: var(--accent);
    }

    /* TIMELINE */
    .timeline {
      margin-top: 32px;
      padding-top: 32px;
      border-top: 1px solid var(--border);
    }

    .timeline-title {
      font-size: 16px;
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .timeline-item {
      display: flex;
      gap: 16px;
      margin-bottom: 20px;
      position: relative;
    }

    .timeline-item:not(:last-child)::before {
      content: '';
      position: absolute;
      left: 19px;
      top: 40px;
      bottom: -20px;
      width: 2px;
      background: var(--border);
    }

    .timeline-dot {
      width: 40px;
      height: 40px;
      background: var(--bg-primary);
      border: 2px solid var(--border);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      position: relative;
      z-index: 1;
    }

    .timeline-item.active .timeline-dot {
      background: var(--accent);
      border-color: var(--accent);
      color: white;
    }

    .timeline-content p {
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 4px;
    }

    .timeline-content small {
      font-size: 12px;
      color: var(--text-secondary);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      header {
        padding: 14px 20px;
      }

      .search-box {
        padding: 32px 24px;
      }

      .search-box h1 {
        font-size: 24px;
      }

      .order-details {
        padding: 24px;
      }

      .product-header {
        flex-direction: column;
        text-align: center;
      }

      .price-box {
        flex-direction: column;
        gap: 12px;
        text-align: center;
      }
    }
    </style>
</head>
<body>

<header>
  <a href="./user/dashboard.php" class="logo">
    <img src="uploads/logo.png" alt="Gudang Barokah">
    <span>Gudang Barokah</span>
  </a>

  <a href="./user/dashboard.php" class="back-btn">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali</span>
  </a>
</header>

<div class="container">
  <!-- SEARCH BOX -->
  <div class="search-box">
    <div class="search-icon-wrapper">
      <i class="fa-solid fa-magnifying-glass"></i>
    </div>
    
    <h1>Lacak Pesanan</h1>
    <p>Masukkan nomor resi untuk melacak status pesanan Anda</p>

    <form method="POST" class="search-form">
      <div class="input-wrapper">
        <i class="fa-solid fa-ticket input-icon"></i>
        <input 
          type="text" 
          name="resi" 
          class="search-input" 
          placeholder="Contoh: TRX20250120123456ABCD" 
          value="<?= isset($_POST['resi']) ? htmlspecialchars($_POST['resi']) : '' ?>"
          required
        >
      </div>
      <button type="submit" class="search-btn">
        <i class="fa-solid fa-search"></i>
        Lacak Pesanan
      </button>
    </form>
  </div>

  <!-- RESULT -->
  <?php if($data): ?>
  <div class="result-card">
    <!-- STATUS HEADER -->
    <div class="status-header <?= strtolower($data['status']) ?>">
      <div class="status-icon">
        <?php if($data['status'] == 'PENDING'): ?>
          <i class="fa-solid fa-clock"></i>
        <?php elseif($data['status'] == 'SELESAI'): ?>
          <i class="fa-solid fa-check"></i>
        <?php else: ?>
          <i class="fa-solid fa-xmark"></i>
        <?php endif; ?>
      </div>
      <h2 class="status-title"><?= strtoupper($data['status']) ?></h2>
      <p class="status-message">
        <?php if($data['status'] == 'PENDING'): ?>
          Pesanan Anda sedang dalam proses verifikasi
        <?php elseif($data['status'] == 'SELESAI'): ?>
          Pesanan Anda telah berhasil diproses
        <?php else: ?>
          Pesanan Anda telah dibatalkan
        <?php endif; ?>
      </p>
    </div>

    <!-- ORDER DETAILS -->
    <div class="order-details">
      <div class="product-header">
        <div class="product-image">
          <img src="uploads/<?= $data['gambar'] ?>" alt="<?= $data['nama_game'] ?>">
        </div>
        <div class="product-info">
          <h3><?= $data['nama_game'] ?></h3>
          <p><?= $data['nama_variant'] ?></p>
          <span class="product-category"><?= $data['kategori'] ?></span>
        </div>
      </div>

      <div class="detail-grid">
        <div class="detail-item">
          <div class="detail-icon">
            <i class="fa-solid fa-ticket"></i>
          </div>
          <div class="detail-content">
            <div class="detail-label">Nomor Resi</div>
            <div class="detail-value"><?= $data['resi'] ?></div>
          </div>
        </div>

        <div class="detail-item">
          <div class="detail-icon">
            <i class="fa-solid fa-gamepad"></i>
          </div>
          <div class="detail-content">
            <div class="detail-label">Data Game</div>
            <div class="detail-value"><?= $data['user_input'] ?></div>
          </div>
        </div>

        <div class="detail-item">
          <div class="detail-icon">
            <i class="fa-solid fa-user"></i>
          </div>
          <div class="detail-content">
            <div class="detail-label">Nama Pengirim</div>
            <div class="detail-value"><?= $data['nama_pengirim'] ?></div>
          </div>
        </div>

        <div class="detail-item">
          <div class="detail-icon">
            <i class="fa-solid fa-credit-card"></i>
          </div>
          <div class="detail-content">
            <div class="detail-label">Metode Pembayaran</div>
            <div class="detail-value"><?= $data['payment_method'] ?></div>
          </div>
        </div>

        <div class="detail-item">
          <div class="detail-icon">
            <i class="fa-solid fa-calendar"></i>
          </div>
          <div class="detail-content">
            <div class="detail-label">Tanggal Pemesanan</div>
            <div class="detail-value"><?= date('d F Y, H:i', strtotime($data['created_at'])) ?> WIB</div>
          </div>
        </div>
      </div>

      <div class="price-box">
        <span class="price-label">Total Pembayaran</span>
        <span class="price-value">Rp<?= number_format($data['harga_jual'], 0, ',', '.') ?></span>
      </div>

      <!-- TIMELINE -->
      <div class="timeline">
        <div class="timeline-title">
          <i class="fa-solid fa-clock-rotate-left"></i>
          Riwayat Status
        </div>

        <div class="timeline-item active">
          <div class="timeline-dot">
            <i class="fa-solid fa-check"></i>
          </div>
          <div class="timeline-content">
            <p>Pesanan Dibuat</p>
            <small><?= date('d F Y, H:i', strtotime($data['created_at'])) ?> WIB</small>
          </div>
        </div>

        <?php if($data['status'] != 'BATAL'): ?>
        <div class="timeline-item <?= ($data['status'] == 'SELESAI') ? 'active' : '' ?>">
          <div class="timeline-dot">
            <?php if($data['status'] == 'SELESAI'): ?>
              <i class="fa-solid fa-check"></i>
            <?php else: ?>
              <i class="fa-solid fa-clock"></i>
            <?php endif; ?>
          </div>
          <div class="timeline-content">
            <p>Pesanan Selesai</p>
            <small><?= ($data['status'] == 'SELESAI') ? 'Telah diproses' : 'Menunggu verifikasi' ?></small>
          </div>
        </div>
        <?php else: ?>
        <div class="timeline-item active">
          <div class="timeline-dot">
            <i class="fa-solid fa-xmark"></i>
          </div>
          <div class="timeline-content">
            <p>Pesanan Dibatalkan</p>
            <small>Pesanan telah dibatalkan</small>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php elseif(isset($_POST['resi'])): ?>
  <!-- NOT FOUND -->
  <div class="not-found">
    <div class="not-found-icon">
      <i class="fa-solid fa-circle-exclamation"></i>
    </div>
    <h3>Resi Tidak Ditemukan</h3>
    <p>Maaf, nomor resi yang Anda masukkan tidak ditemukan dalam sistem kami. Pastikan nomor resi sudah benar.</p>
    <a href="cek_resi.php" class="retry-btn">
      <i class="fa-solid fa-rotate-right"></i>
      Coba Lagi
    </a>
  </div>
  <?php endif; ?>
</div>

</body>
</html>