<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../config/database.php";

// Get voucher ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: vouchers.php");
    exit;
}

$id = (int)$_GET['id'];

// Get voucher data
$voucher_query = mysqli_query($conn, "SELECT * FROM vouchers WHERE id = $id");
if (mysqli_num_rows($voucher_query) == 0) {
    header("Location: vouchers.php?error=not_found");
    exit;
}

$voucher = mysqli_fetch_assoc($voucher_query);
$berlaku_untuk = json_decode($voucher['berlaku_untuk'], true);
$is_all_products = in_array('ALL', $berlaku_untuk);

// Get all games for selection
$games = mysqli_query($conn, "SELECT id, nama_game, kategori FROM games ORDER BY kategori, nama_game ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Voucher | Admin Gudang Barokah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --bg-primary: #f5f7fa;
      --bg-secondary: #ffffff;
      --bg-card: #ffffff;
      --text-primary: #1a1d29;
      --text-secondary: #6b7280;
      --accent: #5b7cff;
      --border: rgba(0, 0, 0, 0.08);
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
    }

    [data-theme="dark"] {
      --bg-primary: #1a1d29;
      --bg-secondary: #23263a;
      --bg-card: #2a2e45;
      --text-primary: #ffffff;
      --text-secondary: #a0a6c1;
      --border: rgba(255, 255, 255, 0.1);
    }

    body {
      background: var(--bg-primary);
      color: var(--text-primary);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      min-height: 100vh;
      transition: background 0.3s ease, color 0.3s ease;
    }

    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
      width: 260px;
      background: var(--bg-secondary);
      border-right: 1px solid var(--border);
      padding: 24px 0;
      z-index: 100;
    }

    .logo-section {
      padding: 0 24px 24px;
      border-bottom: 1px solid var(--border);
      margin-bottom: 24px;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 20px;
      font-weight: 800;
      color: var(--accent);
    }

    .logo img {
      width: 36px;
      height: 36px;
      object-fit: contain;
    }

    .nav-menu {
      list-style: none;
      padding: 0 16px;
    }

    .nav-item {
      margin-bottom: 4px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      border-radius: 8px;
      color: var(--text-secondary);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .nav-link:hover {
      background: rgba(91, 124, 255, 0.1);
      color: var(--accent);
    }

    .nav-link.active {
      background: linear-gradient(135deg, var(--accent), #7c3aed);
      color: white;
    }

    .nav-link i {
      width: 20px;
      font-size: 18px;
    }

    .main-content {
      margin-left: 260px;
      padding: 24px;
      min-height: 100vh;
    }

    .page-header {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
    }

    .page-title h1 {
      font-size: 28px;
      font-weight: 800;
      margin-bottom: 6px;
    }

    .page-title p {
      color: var(--text-secondary);
      font-size: 14px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      border: none;
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

    .form-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 32px;
      max-width: 900px;
    }

    .form-section {
      margin-bottom: 32px;
    }

    .section-title {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      color: var(--text-primary);
    }

    .section-title i {
      color: var(--accent);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 24px;
    }

    .form-group.full-width {
      grid-column: 1 / -1;
    }

    .form-label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 8px;
    }

    .form-label .required {
      color: var(--danger);
    }

    .form-label .optional {
      color: var(--text-secondary);
      font-weight: 400;
      font-size: 12px;
    }

    .form-input,
    .form-select,
    .form-textarea {
      width: 100%;
      padding: 12px 16px;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--text-primary);
      font-size: 14px;
      font-family: inherit;
      outline: none;
      transition: all 0.3s ease;
    }

    .form-textarea {
      resize: vertical;
      min-height: 80px;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(91, 124, 255, 0.1);
    }

    .form-input::placeholder {
      color: var(--text-secondary);
    }

    .input-group {
      position: relative;
    }

    .input-prefix {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      font-weight: 600;
    }

    .input-group .form-input {
      padding-left: 50px;
    }

    .input-suffix {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      font-weight: 600;
    }

    .input-group .form-input.with-suffix {
      padding-right: 50px;
    }

    .radio-group {
      display: flex;
      gap: 16px;
    }

    .radio-option {
      flex: 1;
      position: relative;
    }

    .radio-option input[type="radio"] {
      position: absolute;
      opacity: 0;
    }

    .radio-label {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px;
      background: var(--bg-primary);
      border: 2px solid var(--border);
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .radio-option input[type="radio"]:checked + .radio-label {
      border-color: var(--accent);
      background: rgba(91, 124, 255, 0.05);
    }

    .radio-icon {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, var(--accent), #7c3aed);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 18px;
    }

    .radio-info h4 {
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 4px;
    }

    .radio-info p {
      font-size: 12px;
      color: var(--text-secondary);
    }

    .checkbox-wrapper {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .checkbox-wrapper:hover {
      border-color: var(--accent);
    }

    .checkbox-wrapper input[type="checkbox"] {
      width: 20px;
      height: 20px;
      cursor: pointer;
      accent-color: var(--accent);
    }

    .checkbox-label {
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      flex: 1;
    }

    .game-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 12px;
      margin-top: 12px;
      max-height: 400px;
      overflow-y: auto;
      padding: 16px;
      background: var(--bg-primary);
      border-radius: 8px;
      display: none;
    }

    .game-grid.active {
      display: grid;
    }

    .game-checkbox {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .game-checkbox:hover {
      border-color: var(--accent);
    }

    .game-checkbox input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: var(--accent);
    }

    .game-checkbox label {
      font-size: 13px;
      cursor: pointer;
      flex: 1;
    }

    .info-box {
      padding: 12px 16px;
      background: rgba(91, 124, 255, 0.1);
      border-left: 4px solid var(--accent);
      border-radius: 8px;
      margin-top: 8px;
      font-size: 13px;
      color: var(--text-secondary);
    }

    .info-box i {
      color: var(--accent);
      margin-right: 6px;
    }

    .info-box.warning {
      background: rgba(245, 158, 11, 0.1);
      border-left-color: var(--warning);
    }

    .info-box.warning i {
      color: var(--warning);
    }

    .form-actions {
      display: flex;
      gap: 12px;
      margin-top: 32px;
      padding-top: 24px;
      border-top: 1px solid var(--border);
    }

    .btn-submit {
      flex: 1;
      padding: 14px 24px;
      background: linear-gradient(135deg, var(--accent), #7c3aed);
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 700;
      font-size: 15px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(91, 124, 255, 0.3);
    }

    .btn-cancel {
      padding: 14px 24px;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--text-primary);
      font-weight: 600;
      font-size: 15px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-cancel:hover {
      border-color: var(--danger);
      color: var(--danger);
    }

    @media (max-width: 1024px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .main-content {
        margin-left: 0;
      }
    }

    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
      }

      .radio-group {
        flex-direction: column;
      }

      .game-grid {
        grid-template-columns: 1fr;
      }
    }
    </style>
</head>
<body>

<aside class="sidebar">
  <div class="logo-section">
    <div class="logo">
      <img src="../uploads/logo.png" alt="Gudang Barokah">
      <span>Admin Panel</span>
    </div>
  </div>

  <ul class="nav-menu">
    <li class="nav-item">
      <a href="dashboard.php" class="nav-link">
        <i class="fa-solid fa-chart-line"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a href="buyer.php" class="nav-link">
        <i class="fa-solid fa-shopping-cart"></i>
        <span>Kelola Pembelian</span>
      </a>
    </li>
    <li class="nav-item">
      <a href="games.php" class="nav-link">
        <i class="fa-solid fa-gamepad"></i>
        <span>Kelola Produk</span>
      </a>
    </li>
    <li class="nav-item">
      <a href="vouchers.php" class="nav-link active">
        <i class="fa-solid fa-ticket"></i>
        <span>Kelola Voucher</span>
      </a>
    </li>
    <li class="nav-item">
      <a href="export_excel.php" class="nav-link">
        <i class="fa-solid fa-file-excel"></i>
        <span>Export Excel</span>
      </a>
    </li>
    <li class="nav-item">
      <a href="logout.php" class="nav-link">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Logout</span>
      </a>
    </li>
  </ul>
</aside>

<main class="main-content">
  <div class="page-header">
    <div class="page-title">
      <h1>Edit Voucher</h1>
      <p>Update informasi voucher diskon</p>
    </div>
    <a href="vouchers.php" class="btn btn-secondary">
      <i class="fa-solid fa-arrow-left"></i>
      Kembali
    </a>
  </div>

  <div class="form-card">
    <form action="voucher_update.php" method="POST" id="voucherForm">
      <input type="hidden" name="id" value="<?= $voucher['id'] ?>">
      
      <div class="form-section">
        <h3 class="section-title">
          <i class="fa-solid fa-info-circle"></i>
          Informasi Dasar
        </h3>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kode Voucher <span class="required">*</span></label>
            <input type="text" name="kode_voucher" class="form-input" value="<?= htmlspecialchars($voucher['kode_voucher']) ?>" maxlength="512" required style="text-transform: uppercase;">
            <div class="info-box">
              <i class="fa-solid fa-info-circle"></i>
              Maksimal 512 karakter. Gunakan huruf kapital, angka, atau tanda hubung.
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Nama Voucher <span class="required">*</span></label>
            <input type="text" name="nama_voucher" class="form-input" value="<?= htmlspecialchars($voucher['nama_voucher']) ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi <span class="optional">(Opsional)</span></label>
          <textarea name="deskripsi" class="form-textarea"><?= htmlspecialchars($voucher['deskripsi']) ?></textarea>
        </div>
      </div>

      <div class="form-section">
        <h3 class="section-title">
          <i class="fa-solid fa-percent"></i>
          Pengaturan Diskon
        </h3>

        <div class="form-group">
          <label class="form-label">Tipe Diskon <span class="required">*</span></label>
          <div class="radio-group">
            <div class="radio-option">
              <input type="radio" name="tipe_diskon" id="tipe_persen" value="PERSEN" <?= $voucher['tipe_diskon'] == 'PERSEN' ? 'checked' : '' ?>>
              <label for="tipe_persen" class="radio-label">
                <div class="radio-icon">
                  <i class="fa-solid fa-percent"></i>
                </div>
                <div class="radio-info">
                  <h4>Persentase (%)</h4>
                  <p>Diskon berdasarkan persen</p>
                </div>
              </label>
            </div>

            <div class="radio-option">
              <input type="radio" name="tipe_diskon" id="tipe_rupiah" value="RUPIAH" <?= $voucher['tipe_diskon'] == 'RUPIAH' ? 'checked' : '' ?>>
              <label for="tipe_rupiah" class="radio-label">
                <div class="radio-icon">
                  <i class="fa-solid fa-money-bill"></i>
                </div>
                <div class="radio-info">
                  <h4>Nominal (Rp)</h4>
                  <p>Potongan harga langsung</p>
                </div>
              </label>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" id="labelNilaiDiskon">
              <?= $voucher['tipe_diskon'] == 'PERSEN' ? 'Nilai Diskon (%)' : 'Nilai Diskon (Rp)' ?> <span class="required">*</span>
            </label>
            <div class="input-group">
              <input type="number" name="nilai_diskon" id="nilaiDiskon" class="form-input with-suffix" value="<?= $voucher['nilai_diskon'] ?>" step="0.01" min="0" required>
              <span class="input-suffix" id="suffixDiskon"><?= $voucher['tipe_diskon'] == 'PERSEN' ? '%' : 'Rp' ?></span>
            </div>
          </div>

          <div class="form-group" id="groupMaxDiskon" style="display: <?= $voucher['tipe_diskon'] == 'PERSEN' ? 'block' : 'none' ?>">
            <label class="form-label">Maksimal Diskon (Rp) <span class="optional">(Untuk tipe %)</span></label>
            <div class="input-group">
              <span class="input-prefix">Rp</span>
              <input type="number" name="max_diskon" class="form-input" value="<?= $voucher['max_diskon'] ?>" step="1000" min="0">
            </div>
            <div class="info-box">
              <i class="fa-solid fa-info-circle"></i>
              Batas maksimal potongan harga (kosongkan untuk unlimited)
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Minimal Pembelian (Rp) <span class="required">*</span></label>
          <div class="input-group">
            <span class="input-prefix">Rp</span>
            <input type="number" name="min_pembelian" class="form-input" value="<?= $voucher['min_pembelian'] ?>" step="1000" min="0" required>
          </div>
        </div>
      </div>

      <div class="form-section">
        <h3 class="section-title">
          <i class="fa-solid fa-calendar-alt"></i>
          Kuota & Masa Berlaku
        </h3>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Kuota Voucher <span class="optional">(Opsional)</span></label>
            <input type="number" name="kuota_total" class="form-input" value="<?= $voucher['kuota_total'] ?>" min="<?= $voucher['kuota_terpakai'] ?>">
            <div class="info-box">
              <i class="fa-solid fa-info-circle"></i>
              Sudah digunakan: <?= $voucher['kuota_terpakai'] ?>x. Kosongkan untuk unlimited.
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Status <span class="required">*</span></label>
            <select name="status" class="form-select" required>
              <option value="AKTIF" <?= $voucher['status'] == 'AKTIF' ? 'selected' : '' ?>>Aktif</option>
              <option value="NONAKTIF" <?= $voucher['status'] == 'NONAKTIF' ? 'selected' : '' ?>>Non-Aktif</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tanggal Mulai <span class="required">*</span></label>
            <input type="datetime-local" name="tanggal_mulai" class="form-input" value="<?= date('Y-m-d\TH:i', strtotime($voucher['tanggal_mulai'])) ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label">Tanggal Berakhir <span class="required">*</span></label>
            <input type="datetime-local" name="tanggal_berakhir" class="form-input" value="<?= date('Y-m-d\TH:i', strtotime($voucher['tanggal_berakhir'])) ?>" required>
          </div>
        </div>
      </div>

      <div class="form-section">
        <h3 class="section-title">
          <i class="fa-solid fa-box"></i>
          Berlaku Untuk Produk
        </h3>

        <div class="form-group">
          <div class="checkbox-wrapper" onclick="document.getElementById('allProducts').click()">
            <input type="checkbox" id="allProducts" name="all_products" <?= $is_all_products ? 'checked' : '' ?>>
            <label class="checkbox-label" for="allProducts">
              Berlaku untuk SEMUA produk
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Atau Pilih Produk Spesifik</label>
          <div class="game-grid <?= !$is_all_products ? 'active' : '' ?>" id="gameGrid">
            <?php 
            $current_kategori = '';
            mysqli_data_seek($games, 0);
            while($game = mysqli_fetch_assoc($games)): 
              $is_checked = !$is_all_products && in_array((string)$game['id'], $berlaku_untuk);
              if($current_kategori != $game['kategori']): 
                $current_kategori = $game['kategori'];
            ?>
              <div style="grid-column: 1/-1; font-weight: 700; color: var(--accent); margin-top: 8px; padding-left: 4px;">
                <?= $game['kategori'] ?>
              </div>
            <?php endif; ?>
              <div class="game-checkbox">
                <input type="checkbox" name="games[]" value="<?= $game['id'] ?>" id="game_<?= $game['id'] ?>" <?= $is_checked ? 'checked' : '' ?>>
                <label for="game_<?= $game['id'] ?>"><?= $game['nama_game'] ?></label>
              </div>
            <?php endwhile ?>
          </div>
          <div class="info-box warning" id="warningProducts" style="display: none;">
            <i class="fa-solid fa-exclamation-triangle"></i>
            Pilih minimal 1 produk atau centang "Berlaku untuk SEMUA produk"
          </div>
        </div>
      </div>

      <div class="form-actions">
        <a href="vouchers.php" class="btn-cancel">
          <i class="fa-solid fa-times"></i>
          Batal
        </a>
        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-save"></i>
          Update Voucher
        </button>
      </div>
    </form>
  </div>
</main>

<script>
const tipePersen = document.getElementById('tipe_persen');
const tipeRupiah = document.getElementById('tipe_rupiah');
const labelNilaiDiskon = document.getElementById('labelNilaiDiskon');
const suffixDiskon = document.getElementById('suffixDiskon');
const groupMaxDiskon = document.getElementById('groupMaxDiskon');
const nilaiDiskon = document.getElementById('nilaiDiskon');

function updateDiskonType() {
  if(tipePersen.checked) {
    labelNilaiDiskon.innerHTML = 'Nilai Diskon (%) <span class="required">*</span>';
    suffixDiskon.textContent = '%';
    nilaiDiskon.placeholder = '10';
    nilaiDiskon.max = '100';
    groupMaxDiskon.style.display = 'block';
  } else {
    labelNilaiDiskon.innerHTML = 'Nilai Diskon (Rp) <span class="required">*</span>';
    suffixDiskon.textContent = 'Rp';
    nilaiDiskon.placeholder = '50000';
    nilaiDiskon.max = '';
    groupMaxDiskon.style.display = 'none';
  }
}

tipePersen.addEventListener('change', updateDiskonType);
tipeRupiah.addEventListener('change', updateDiskonType);

const allProducts = document.getElementById('allProducts');
const gameGrid = document.getElementById('gameGrid');
const gameCheckboxes = document.querySelectorAll('input[name="games[]"]');

allProducts.addEventListener('change', function() {
  if(this.checked) {
    gameGrid.classList.remove('active');
    gameCheckboxes.forEach(cb => cb.checked = false);
  } else {
    gameGrid.classList.add('active');
  }
});

gameCheckboxes.forEach(cb => {
  cb.addEventListener('change', function() {
    if(this.checked) {
      allProducts.checked = false;
    }
  });
});

document.getElementById('voucherForm').addEventListener('submit', function(e) {
  const allProductsChecked = allProducts.checked;
  const anyGameSelected = Array.from(gameCheckboxes).some(cb => cb.checked);
  const warningProducts = document.getElementById('warningProducts');
  
  if(!allProductsChecked && !anyGameSelected) {
    e.preventDefault();
    warningProducts.style.display = 'block';
    alert('Pilih minimal 1 produk atau centang "Berlaku untuk SEMUA produk"');
    return false;
  }
  
  warningProducts.style.display = 'none';
  
  const tanggalMulai = new Date(document.querySelector('[name="tanggal_mulai"]').value);
  const tanggalBerakhir = new Date(document.querySelector('[name="tanggal_berakhir"]').value);
  
  if(tanggalBerakhir <= tanggalMulai) {
    e.preventDefault();
    alert('Tanggal berakhir harus lebih besar dari tanggal mulai!');
    return false;
  }
});

document.querySelector('[name="kode_voucher"]').addEventListener('input', function() {
  this.value = this.value.toUpperCase();
});
</script>

</body>
</html>