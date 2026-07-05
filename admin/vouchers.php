<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../config/database.php";

// Get all vouchers
$vouchers = mysqli_query($conn, "
    SELECT v.*,
           (SELECT COUNT(*) FROM voucher_usage vu WHERE vu.voucher_id = v.id) as total_usage,
           (SELECT COUNT(DISTINCT vu2.order_id) FROM voucher_usage vu2 WHERE vu2.voucher_id = v.id) as unique_orders
    FROM vouchers v
    ORDER BY v.created_at DESC
");

// Get all games for dropdown
$games = mysqli_query($conn, "SELECT id, nama_game FROM games ORDER BY nama_game ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Voucher | Admin Gudang Barokah</title>
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

    .btn-primary {
      background: linear-gradient(135deg, var(--accent), #7c3aed);
      color: white;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(91, 124, 255, 0.3);
    }

    /* STATS CARDS */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 20px;
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      color: white;
    }

    .stat-icon.purple { background: linear-gradient(135deg, #667eea, #764ba2); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }

    .stat-info h3 {
      font-size: 24px;
      font-weight: 800;
      margin-bottom: 4px;
    }

    .stat-info p {
      font-size: 13px;
      color: var(--text-secondary);
    }

    /* TABLE */
    .table-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
    }

    .table-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .table-header h2 {
      font-size: 18px;
      font-weight: 700;
    }

    .search-box {
      position: relative;
    }

    .search-box input {
      padding: 8px 16px 8px 40px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: var(--bg-primary);
      color: var(--text-primary);
      font-size: 14px;
      width: 250px;
      outline: none;
    }

    .search-box i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      background: var(--bg-primary);
      padding: 14px 20px;
      text-align: left;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--text-secondary);
      border-bottom: 1px solid var(--border);
    }

    td {
      padding: 16px 20px;
      border-bottom: 1px solid var(--border);
      font-size: 14px;
    }

    tr:hover {
      background: var(--bg-primary);
    }

    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .badge.aktif {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }

    .badge.nonaktif {
      background: rgba(107, 114, 128, 0.1);
      color: var(--text-secondary);
    }

    .badge.persen {
      background: rgba(91, 124, 255, 0.1);
      color: var(--accent);
    }

    .badge.rupiah {
      background: rgba(245, 158, 11, 0.1);
      color: var(--warning);
    }

    .action-btns {
      display: flex;
      gap: 8px;
    }

    .btn-icon {
      width: 32px;
      height: 32px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-edit {
      background: rgba(91, 124, 255, 0.1);
      color: var(--accent);
    }

    .btn-delete {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }

    .btn-icon:hover {
      transform: scale(1.1);
    }

    .voucher-code {
      font-family: 'Courier New', monospace;
      font-weight: 700;
      color: var(--accent);
    }

    .progress-bar {
      width: 100px;
      height: 6px;
      background: var(--bg-primary);
      border-radius: 3px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--success), #059669);
      transition: width 0.3s ease;
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
      .stats-grid {
        grid-template-columns: 1fr;
      }

      .table-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
      }

      .search-box input {
        width: 100%;
      }

      table {
        font-size: 12px;
      }

      td, th {
        padding: 10px;
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

<!-- MAIN CONTENT -->
<main class="main-content">
  <!-- HEADER -->
  <div class="page-header">
    <div class="page-title">
      <h1>Kelola Voucher Diskon</h1>
      <p>Buat dan kelola voucher diskon untuk pelanggan</p>
    </div>
    <div class="header-actions">
      <a href="voucher_add.php" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i>
        Tambah Voucher
      </a>
    </div>
  </div>

  <!-- STATS -->
  <div class="stats-grid">
    <?php
    $total_vouchers = mysqli_num_rows($vouchers);
    mysqli_data_seek($vouchers, 0);
    
    $active_count = 0;
    $total_usage = 0;
    $total_discount = 0;
    
    while($v = mysqli_fetch_assoc($vouchers)) {
      if($v['status'] == 'AKTIF') $active_count++;
      $total_usage += $v['total_usage'];
    }
    mysqli_data_seek($vouchers, 0);
    
    $usage_result = mysqli_query($conn, "SELECT SUM(diskon_amount) as total FROM voucher_usage");
    $usage_row = mysqli_fetch_assoc($usage_result);
    $total_discount = $usage_row['total'] ?? 0;
    ?>
    <div class="stat-card">
      <div class="stat-icon purple">
        <i class="fa-solid fa-ticket"></i>
      </div>
      <div class="stat-info">
        <h3><?= $total_vouchers ?></h3>
        <p>Total Voucher</p>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon green">
        <i class="fa-solid fa-check-circle"></i>
      </div>
      <div class="stat-info">
        <h3><?= $active_count ?></h3>
        <p>Voucher Aktif</p>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon orange">
        <i class="fa-solid fa-users"></i>
      </div>
      <div class="stat-info">
        <h3><?= $total_usage ?></h3>
        <p>Total Penggunaan</p>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon blue">
        <i class="fa-solid fa-money-bill-wave"></i>
      </div>
      <div class="stat-info">
        <h3>Rp<?= number_format($total_discount, 0, ',', '.') ?></h3>
        <p>Total Diskon Diberikan</p>
      </div>
    </div>
  </div>

  <!-- TABLE -->
  <div class="table-card">
    <div class="table-header">
      <h2>Daftar Voucher</h2>
      <div class="search-box">
        <i class="fa-solid fa-search"></i>
        <input type="text" id="searchInput" placeholder="Cari voucher...">
      </div>
    </div>

    <table id="voucherTable">
      <thead>
        <tr>
          <th>Kode Voucher</th>
          <th>Nama</th>
          <th>Tipe</th>
          <th>Nilai</th>
          <th>Min. Pembelian</th>
          <th>Kuota</th>
          <th>Berlaku</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while($v = mysqli_fetch_assoc($vouchers)): 
          $berlaku = json_decode($v['berlaku_untuk'], true);
          $berlaku_text = ($berlaku && in_array('ALL', $berlaku)) ? 'Semua Produk' : count($berlaku) . ' Produk';
          
          $kuota_persen = 0;
          if($v['kuota_total']) {
            $kuota_persen = ($v['kuota_terpakai'] / $v['kuota_total']) * 100;
          }
        ?>
        <tr>
          <td><span class="voucher-code"><?= htmlspecialchars($v['kode_voucher']) ?></span></td>
          <td><?= htmlspecialchars($v['nama_voucher']) ?></td>
          <td>
            <span class="badge <?= strtolower($v['tipe_diskon']) ?>">
              <?= $v['tipe_diskon'] ?>
            </span>
          </td>
          <td>
            <?php if($v['tipe_diskon'] == 'PERSEN'): ?>
              <?= $v['nilai_diskon'] ?>%
            <?php else: ?>
              Rp<?= number_format($v['nilai_diskon'], 0, ',', '.') ?>
            <?php endif; ?>
          </td>
          <td>Rp<?= number_format($v['min_pembelian'], 0, ',', '.') ?></td>
          <td>
            <?php if($v['kuota_total']): ?>
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 12px;"><?= $v['kuota_terpakai'] ?>/<?= $v['kuota_total'] ?></span>
                <div class="progress-bar">
                  <div class="progress-fill" style="width: <?= $kuota_persen ?>%"></div>
                </div>
              </div>
            <?php else: ?>
              <span style="color: var(--success);">Unlimited</span>
            <?php endif; ?>
          </td>
          <td><?= $berlaku_text ?></td>
          <td>
            <span class="badge <?= strtolower($v['status']) ?>">
              <?= $v['status'] ?>
            </span>
          </td>
          <td>
            <div class="action-btns">
              <a href="voucher_edit.php?id=<?= $v['id'] ?>" class="btn-icon btn-edit" title="Edit">
                <i class="fa-solid fa-pen"></i>
              </a>
              <button onclick="deleteVoucher(<?= $v['id'] ?>)" class="btn-icon btn-delete" title="Hapus">
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endwhile ?>
      </tbody>
    </table>
  </div>
</main>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
  const query = this.value.toLowerCase();
  const rows = document.querySelectorAll('#voucherTable tbody tr');
  
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(query) ? '' : 'none';
  });
});

// Delete voucher
function deleteVoucher(id) {
  if(confirm('Apakah Anda yakin ingin menghapus voucher ini?')) {
    window.location.href = `voucher_delete.php?id=${id}`;
  }
}
</script>

</body>
</html>