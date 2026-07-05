<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Game | Admin Gudang Barokah</title>
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

    .form-input::placeholder {
      color: var(--text-secondary);
    }

    /* FILE UPLOAD */
    .file-upload-wrapper {
      position: relative;
      overflow: hidden;
    }

    .file-upload-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      padding: 32px;
      background: var(--bg-primary);
      border: 2px dashed var(--border);
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
    }

    .file-upload-btn:hover {
      border-color: var(--accent);
      background: rgba(91, 124, 255, 0.05);
    }

    .file-upload-btn i {
      font-size: 32px;
      color: var(--accent);
    }

    .file-upload-text {
      color: var(--text-secondary);
      font-size: 14px;
    }

    .file-upload-text strong {
      color: var(--accent);
      display: block;
      margin-bottom: 4px;
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

    .file-preview {
      display: none;
      margin-top: 16px;
      padding: 16px;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: 8px;
      align-items: center;
      gap: 12px;
    }

    .file-preview.active {
      display: flex;
    }

    .file-preview img {
      width: 80px;
      height: 80px;
      border-radius: 8px;
      object-fit: cover;
    }

    .file-info {
      flex: 1;
    }

    .file-name {
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 4px;
    }

    .file-size {
      font-size: 12px;
      color: var(--text-secondary);
    }

    /* INFO BOX */
    .info-box {
      padding: 12px 16px;
      background: rgba(91, 124, 255, 0.1);
      border-left: 4px solid var(--accent);
      border-radius: 8px;
      margin-top: 8px;
      font-size: 13px;
      color: var(--text-secondary);
      display: none;
    }

    .info-box.active {
      display: block;
    }

    .info-box i {
      color: var(--accent);
      margin-right: 6px;
    }

    /* CHECKBOX */
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
    }

    /* TABLE */
    .variant-table-wrapper {
      display: none;
      margin-top: 24px;
      overflow-x: auto;
    }

    .variant-table-wrapper.active {
      display: block;
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

    .btn-add-row {
      width: 32px;
      height: 32px;
      background: linear-gradient(135deg, var(--success), #059669);
      border: none;
      border-radius: 6px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-add-row:hover {
      transform: scale(1.1);
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
      <h1>Tambah Game Baru</h1>
      <p>Lengkapi informasi game yang akan ditambahkan</p>
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
    <form action="game_store.php" method="POST" enctype="multipart/form-data" id="gameForm">
      
      <!-- BASIC INFO -->
      <div class="form-section">
        <h3 class="section-title">
          <i class="fa-solid fa-info-circle"></i>
          Informasi Dasar
        </h3>

        <div class="form-group">
          <label class="form-label">Nama Game <span class="required">*</span></label>
          <input type="text" name="nama_game" class="form-input" placeholder="Contoh: Mobile Legends" required>
        </div>

        <div class="form-group">
          <label class="form-label">Kategori Produk <span class="required">*</span></label>
          <select name="kategori" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Game">Game</option>
            <option value="Voucher">Voucher</option>
            <option value="Pulsa">Pulsa</option>
            <option value="App Premium">App Premium</option>
            <option value="E-Wallet">E-Wallet</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Produk</label>
          <textarea name="deskripsi" class="form-input" rows="4" placeholder="Contoh: Top up diamond Mobile Legends dengan cepat dan aman. Proses otomatis 1-5 menit." style="resize: vertical; min-height: 100px;"></textarea>
          <small style="color: var(--text-secondary); font-size: 12px; margin-top: 6px; display: block;">
            <i class="fa-solid fa-info-circle"></i> Deskripsi akan ditampilkan di card produk (opsional, max 200 karakter)
          </small>
        </div>

        <div class="form-group">
          <label class="form-label">Gambar Game <span class="required">*</span></label>
          <div class="file-upload-wrapper">
            <label class="file-upload-btn" for="fileUpload">
              <div>
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <div class="file-upload-text">
                  <strong>Klik untuk upload gambar</strong>
                  <span>PNG, JPG, JPEG (Max. 5MB)</span>
                </div>
              </div>
            </label>
            <input type="file" name="gambar" id="fileUpload" class="file-input" accept="image/*" required>
          </div>
          <div class="file-preview" id="filePreview">
            <img id="previewImage" src="" alt="Preview">
            <div class="file-info">
              <div class="file-name" id="fileName"></div>
              <div class="file-size" id="fileSize"></div>
            </div>
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
          <select name="keperluan" id="keperluan" class="form-select" required>
            <option value="">-- Pilih Keperluan --</option>
            <option value="id">User isi ID Game</option>
            <option value="id_region">User isi ID Game + Server/Region</option>
            <option value="email">User isi Email Akun</option>
            <option value="text">User isi Data Lainnya</option>
          </select>
          <div class="info-box" id="infoKeperluan">
            <i class="fa-solid fa-info-circle"></i>
            <span id="infoText"></span>
          </div>
        </div>
      </div>

      <!-- VARIANTS -->
      <div class="form-section">
        <h3 class="section-title">
          <i class="fa-solid fa-list"></i>
          Varian Produk
        </h3>

        <div class="form-group">
          <div class="checkbox-wrapper">
            <input type="checkbox" id="hasVariant">
            <label class="checkbox-label" for="hasVariant">
              Produk ini memiliki lebih dari 1 varian
            </label>
          </div>
        </div>

        <div class="variant-table-wrapper" id="variantBox">
          <table>
            <thead>
              <tr>
                <th>Jenis Varian</th>
                <th>Nama Varian</th>
                <th>Harga Awal</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th width="50">Aksi</th>
              </tr>
            </thead>
            <tbody id="variantTable">
              <tr>
                <td><input type="text" name="variant_group[]" placeholder="Contoh: Diamond"></td>
                <td><input type="text" name="nama_variant[]" placeholder="Contoh: 100 Diamond"></td>
                <td><input type="number" name="harga_awal[]" placeholder="0"></td>
                <td><input type="number" name="harga_jual[]" placeholder="0"></td>
                <td><input type="number" name="stok[]" placeholder="0"></td>
                <td style="text-align: center;">
                  <button type="button" class="btn-add-row" onclick="addRow()">
                    <i class="fa-solid fa-plus"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ACTIONS -->
      <div class="form-actions">
        <a href="games.php" class="btn-cancel">
          <i class="fa-solid fa-times"></i>
          Batal
        </a>
        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-save"></i>
          Simpan Game
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

// File Upload Preview
const fileUpload = document.getElementById('fileUpload');
const filePreview = document.getElementById('filePreview');
const previewImage = document.getElementById('previewImage');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');

fileUpload.addEventListener('change', function(e) {
  if (this.files && this.files[0]) {
    const file = this.files[0];
    
    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
      previewImage.src = e.target.result;
    }
    reader.readAsDataURL(file);
    
    // Show file info
    fileName.textContent = file.name;
    fileSize.textContent = formatFileSize(file.size);
    filePreview.classList.add('active');
  }
});

function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Keperluan Info
const info = {
  id: "User akan diminta mengisi ID Game saat melakukan order",
  id_region: "User akan diminta mengisi ID Game dan Server / Region",
  email: "User akan diminta mengisi Email Akun",
  text: "User akan diminta mengisi data khusus sesuai instruksi"
};

document.getElementById('keperluan').addEventListener('change', function() {
  const infoBox = document.getElementById('infoKeperluan');
  const infoText = document.getElementById('infoText');
  
  if (this.value && info[this.value]) {
    infoText.textContent = info[this.value];
    infoBox.classList.add('active');
  } else {
    infoBox.classList.remove('active');
  }
});

// Toggle Variant Table
document.getElementById('hasVariant').addEventListener('change', function() {
  const variantBox = document.getElementById('variantBox');
  if (this.checked) {
    variantBox.classList.add('active');
  } else {
    variantBox.classList.remove('active');
  }
});

// Add Row Function
function addRow() {
  const table = document.getElementById('variantTable');
  const row = document.createElement('tr');
  row.innerHTML = `
    <td><input type="text" name="variant_group[]" placeholder="Contoh: Diamond"></td>
    <td><input type="text" name="nama_variant[]" placeholder="Contoh: 100 Diamond"></td>
    <td><input type="number" name="harga_awal[]" placeholder="0"></td>
    <td><input type="number" name="harga_jual[]" placeholder="0"></td>
    <td><input type="number" name="stok[]" placeholder="0"></td>
    <td></td>
  `;
  table.appendChild(row);
}

// Form Validation
document.getElementById('gameForm').addEventListener('submit', function(e) {
  const namaGame = document.querySelector('[name="nama_game"]').value;
  const kategori = document.querySelector('[name="kategori"]').value;
  const gambar = document.getElementById('fileUpload').files[0];
  const keperluan = document.getElementById('keperluan').value;
  
  if (!namaGame || !kategori || !gambar || !keperluan) {
    e.preventDefault();
    alert('Mohon lengkapi semua field yang wajib diisi!');
    return false;
  }
  
  // Check file size (5MB max)
  if (gambar.size > 5 * 1024 * 1024) {
    e.preventDefault();
    alert('Ukuran file terlalu besar! Maksimal 5MB');
    return false;
  }
});
</script>

</body>
</html>