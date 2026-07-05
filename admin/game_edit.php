<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../config/database.php";

$id = (int)$_GET['id'];
$game = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM games WHERE id=$id"));
$variants = mysqli_query($conn, "SELECT * FROM product_variants WHERE game_id=$id ORDER BY id ASC");

if (!$game) {
    die("Game tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game | Admin Gudang Barokah</title>
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

    /* SIDEBAR */
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
      transition: all 0.3s ease;
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

    /* MAIN CONTENT */
    .main-content {
      margin-left: 260px;
      padding: 24px;
      min-height: 100vh;
    }

    /* HEADER */
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

    .header-actions {
      display: flex;
      align-items: center;
      gap: 12px;
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

    .theme-toggle {
      width: 44px;
      height: 44px;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      color: var(--text-secondary);
    }

    .theme-toggle:hover {
      color: var(--text-primary);
      border-color: var(--accent);
    }

    /* FORM CARD */
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

    .form-group {
      margin-bottom: 24px;
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

    .form-input,
    .form-select,
    textarea.form-input {
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

    .form-input:focus,
    .form-select:focus,
    textarea.form-input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(91, 124, 255, 0.1);
    }

    /* IMAGE PREVIEW */
    .current-image {
      margin-top: 12px;
      padding: 16px;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .current-image img {
      width: 100px;
      height: 100px;
      border-radius: 8px;
      object-fit: cover;
    }

    .image-info {
      flex: 1;
    }

    .image-label {
      font-size: 12px;
      color: var(--text-secondary);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 6px;
    }

    .image-name {
      font-size: 14px;
      font-weight: 600;
    }

    /* FILE UPLOAD */
    .file-upload-wrapper {
      position: relative;
      overflow: hidden;
    }

    .file-upload-btn {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px;
      background: var(--bg-primary);
      border: 2px dashed var(--border);
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .file-upload-btn:hover {
      border-color: var(--accent);
      background: rgba(91, 124, 255, 0.05);
    }

    .file-upload-btn i {
      font-size: 20px;
      color: var(--accent);
    }

    .file-input {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
    }

    /* TABLE */
    .table-wrapper {
      overflow-x: auto;
      margin-top: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 8px;
      overflow: hidden;
    }

    th {
      background: var(--bg-primary);
      padding: 12px;
      text-align: left;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--text-secondary);
      border-bottom: 1px solid var(--border);
    }

    td {
      padding: 12px;
      border-bottom: 1px solid var(--border);
    }

    tr:last-child td {
      border-bottom: none;
    }

    td input {
      width: 100%;
      padding: 8px 12px;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: 6px;
      color: var(--text-primary);
      font-size: 13px;
      outline: none;
    }

    td input:focus {
      border-color: var(--accent);
    }

    .btn-delete-variant {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 12px;
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid transparent;
      border-radius: 6px;
      color: var(--danger);
      text-decoration: none;
      font-size: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-delete-variant:hover {
      background: var(--danger);
      color: white;
      border-color: var(--danger);
    }

    .new-variant-row {
      background: rgba(16, 185, 129, 0.05);
    }

    .new-variant-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 4px 10px;
      background: var(--success);
      color: white;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
    }

    .btn-add-variant {
      margin-top: 16px;
      padding: 10px 16px;
      background: rgba(16, 185, 129, 0.1);
      border: 1px solid var(--success);
      border-radius: 8px;
      color: var(--success);
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-add-variant:hover {
      background: var(--success);
      color: white;
    }

    /* SUBMIT BUTTONS */
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

    /* RESPONSIVE */
    @media (max-width: 1024px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .main-content {
        margin-left: 0;
      }
    }

    @media (max-width: 768px) {
      .main-content {
        padding: 16px;
      }

      .page-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .form-card {
        padding: 24px;
      }

      table {
        font-size: 12px;
      }

      th, td {
        padding: 8px;
      }
    }
    </style>
</head>
<body>

<!-- SIDEBAR -->
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
      <a href="games.php" class="nav-link active">
        <i class="fa-solid fa-gamepad"></i>
        <span>Kelola Produk</span>
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

<!-- MAIN CONTENT -->
<main class="main-content">
  <!-- HEADER -->
  <div class="page-header">
    <div class="page-title">
      <h1>Edit Game</h1>
      <p>Update informasi game: <?= $game['nama_game'] ?></p>
    </div>
    <div class="header-actions">
      <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
        <i class="fa-solid fa-moon" id="themeIcon"></i>
      </button>
      <a href="games.php" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali
      </a>
    </div>
  </div>

  <!-- FORM -->
  <div class="form-card">
    <form action="game_update.php" method="POST" enctype="multipart/form-data" id="gameForm">
      <input type="hidden" name="id" value="<?= $game['id'] ?>">
      
      <!-- BASIC INFO -->
      <div class="form-section">
        <h3 class="section-title">
          <i class="fa-solid fa-info-circle"></i>
          Informasi Dasar
        </h3>

        <div class="form-group">
          <label class="form-label">Nama Game <span class="required">*</span></label>
          <input type="text" name="nama_game" class="form-input" value="<?= htmlspecialchars($game['nama_game']) ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Kategori Produk <span class="required">*</span></label>
          <select name="kategori" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Game" <?= $game['kategori']=='Game'?'selected':'' ?>>Game</option>
            <option value="Voucher" <?= $game['kategori']=='Voucher'?'selected':'' ?>>Voucher</option>
            <option value="Pulsa" <?= $game['kategori']=='Pulsa'?'selected':'' ?>>Pulsa</option>
            <option value="App Premium" <?= $game['kategori']=='App Premium'?'selected':'' ?>>App Premium</option>
            <option value="E-Wallet" <?= $game['kategori']=='E-Wallet'?'selected':'' ?>>E-Wallet</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Produk</label>
          <textarea name="deskripsi" class="form-input" rows="4" placeholder="Contoh: Top up diamond Mobile Legends dengan cepat dan aman. Proses otomatis 1-5 menit." style="resize: vertical; min-height: 100px;"><?= htmlspecialchars($game['deskripsi'] ?? '') ?></textarea>
          <small style="color: var(--text-secondary); font-size: 12px; margin-top: 6px; display: block;">
            <i class="fa-solid fa-info-circle"></i> Deskripsi akan ditampilkan di card produk (opsional, max 200 karakter)
          </small>
        </div>

        <div class="form-group">
          <label class="form-label">Gambar Game Saat Ini</label>
          <div class="current-image">
            <img src="../uploads/<?= $game['gambar'] ?>" alt="<?= $game['nama_game'] ?>">
            <div class="image-info">
              <div class="image-label">File Saat Ini</div>
              <div class="image-name"><?= $game['gambar'] ?></div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Ganti Gambar (Opsional)</label>
          <div class="file-upload-wrapper">
            <label class="file-upload-btn" for="fileUpload">
              <i class="fa-solid fa-cloud-arrow-up"></i>
              <div>
                <strong>Klik untuk upload gambar baru</strong>
                <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">
                  Kosongkan jika tidak ingin mengganti gambar
                </div>
              </div>
            </label>
            <input type="file" name="gambar" id="fileUpload" class="file-input" accept="image/*">
          </div>
        </div>
      </div>

      <!-- REQUIREMENT -->
      <div class="form-section">
        <h3 class="section-title">
          <i class="fa-solid fa-clipboard-list"></i>
          Keperluan Data User
        </h3>

        <div class="form-group">
          <label class="form-label">Keperluan Produk <span class="required">*</span></label>
          <select name="keperluan" class="form-select" required>
            <option value="id" <?= $game['keperluan']=='id'?'selected':'' ?>>User isi ID Game</option>
            <option value="id_region" <?= $game['keperluan']=='id_region'?'selected':'' ?>>User isi ID Game + Server/Region</option>
            <option value="email" <?= $game['keperluan']=='email'?'selected':'' ?>>User isi Email Akun</option>
            <option value="text" <?= $game['keperluan']=='text'?'selected':'' ?>>User isi Data Lainnya</option>
          </select>
        </div>
      </div>

      <!-- VARIANTS -->
      <div class="form-section">
        <h3 class="section-title">
          <i class="fa-solid fa-list"></i>
          Varian Produk
        </h3>

        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Jenis Varian</th>
                <th>Nama Varian</th>
                <th>Harga Awal</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th width="100">Aksi</th>
              </tr>
            </thead>
            <tbody id="variantTable">
              <?php 
              mysqli_data_seek($variants, 0);
              while($v = mysqli_fetch_assoc($variants)): 
              ?>
              <tr>
                <td><input type="text" name="variant_group[<?= $v['id'] ?>]" value="<?= htmlspecialchars($v['variant_group']) ?>"></td>
                <td><input type="text" name="nama_variant[<?= $v['id'] ?>]" value="<?= htmlspecialchars($v['nama_variant']) ?>"></td>
                <td><input type="number" name="harga_awal[<?= $v['id'] ?>]" value="<?= $v['harga_awal'] ?>"></td>
                <td><input type="number" name="harga_jual[<?= $v['id'] ?>]" value="<?= $v['harga_jual'] ?>"></td>
                <td><input type="number" name="stok[<?= $v['id'] ?>]" value="<?= $v['stok'] ?>"></td>
                <td style="text-align: center;">
                  <a href="variant_delete.php?id=<?= $v['id'] ?>" 
                     class="btn-delete-variant" 
                     onclick="return confirm('Yakin ingin menghapus varian <?= $v['nama_variant'] ?>?')">
                    <i class="fa-solid fa-trash"></i>
                    Hapus
                  </a>
                </td>
              </tr>
              <?php endwhile; ?>

              <!-- NEW VARIANT ROW -->
              <tr class="new-variant-row" id="newVariantRow">
                <td><input type="text" name="variant_group_new[]" placeholder="Contoh: Diamond"></td>
                <td><input type="text" name="nama_variant_new[]" placeholder="Contoh: 100 Diamond"></td>
                <td><input type="number" name="harga_awal_new[]" placeholder="0"></td>
                <td><input type="number" name="harga_jual_new[]" placeholder="0"></td>
                <td><input type="number" name="stok_new[]" placeholder="0"></td>
                <td style="text-align: center;">
                  <span class="new-variant-badge">
                    <i class="fa-solid fa-plus"></i>
                    Baru
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <button type="button" class="btn-add-variant" onclick="addNewVariantRow()">
          <i class="fa-solid fa-plus"></i>
          Tambah Varian Baru
        </button>
      </div>

      <!-- ACTIONS -->
      <div class="form-actions">
        <a href="games.php" class="btn-cancel">
          <i class="fa-solid fa-times"></i>
          Batal
        </a>
        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-save"></i>
          Update Game
        </button>
      </div>
    </form>
  </div>
</main>

<script>
// Theme Toggle
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const html = document.documentElement;

const savedTheme = localStorage.getItem('adminTheme') || 'light';
html.setAttribute('data-theme', savedTheme);
updateThemeIcon(savedTheme);

themeToggle.addEventListener('click', () => {
  const currentTheme = html.getAttribute('data-theme');
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  
  html.setAttribute('data-theme', newTheme);
  localStorage.setItem('adminTheme', newTheme);
  updateThemeIcon(newTheme);
});

function updateThemeIcon(theme) {
  if (theme === 'dark') {
    themeIcon.className = 'fa-solid fa-sun';
  } else {
    themeIcon.className = 'fa-solid fa-moon';
  }
}

// Add New Variant Row
function addNewVariantRow() {
  const table = document.getElementById('variantTable');
  const newRow = document.createElement('tr');
  newRow.className = 'new-variant-row';
  newRow.innerHTML = `
    <td><input type="text" name="variant_group_new[]" placeholder="Contoh: Diamond"></td>
    <td><input type="text" name="nama_variant_new[]" placeholder="Contoh: 100 Diamond"></td>
    <td><input type="number" name="harga_awal_new[]" placeholder="0"></td>
    <td><input type="number" name="harga_jual_new[]" placeholder="0"></td>
    <td><input type="number" name="stok_new[]" placeholder="0"></td>
    <td style="text-align: center;">
      <span class="new-variant-badge">
        <i class="fa-solid fa-plus"></i>
        Baru
      </span>
    </td>
  `;
  table.appendChild(newRow);
}

// File Upload Preview (optional)
const fileUpload = document.getElementById('fileUpload');
fileUpload.addEventListener('change', function(e) {
  if (this.files && this.files[0]) {
    const file = this.files[0];
    console.log('File selected:', file.name);
  }
});

// Form Validation
document.getElementById('gameForm').addEventListener('submit', function(e) {
  const namaGame = document.querySelector('[name="nama_game"]').value;
  const keperluan = document.querySelector('[name="keperluan"]').value;
  
  if (!namaGame || !keperluan) {
    e.preventDefault();
    alert('Mohon lengkapi semua field yang wajib diisi!');
    return false;
  }
});
</script>

</body>
</html>