<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../config/database.php";

/* =========================
   FILTER TANGGAL
========================= */
$where = "";
if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $_GET['from'];
    $to   = $_GET['to'];
    $where = "AND o.created_at BETWEEN '$from' AND '$to'";
}

/* =========================
   REKAP ORDER
========================= */
$total_order = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM orders"))[0];
$pending     = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM orders WHERE status='PENDING'"))[0];
$selesai     = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM orders WHERE status='SELESAI'"))[0];
$batal       = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM orders WHERE status='BATAL'"))[0];

/* =========================
   KEUANGAN
========================= */
$uang_kotor = mysqli_fetch_row(mysqli_query($conn,"
SELECT SUM(v.harga_jual)
FROM orders o
JOIN product_variants v ON o.variant_id = v.id
WHERE o.status='SELESAI' $where
"))[0] ?? 0;

$modal = mysqli_fetch_row(mysqli_query($conn,"
SELECT SUM(v.harga_awal)
FROM orders o
JOIN product_variants v ON o.variant_id = v.id
WHERE o.status='SELESAI' $where
"))[0] ?? 0;

$profit = $uang_kotor - $modal;

/* =========================
   ORDER TERBARU
========================= */
$orders = mysqli_query($conn,"
SELECT 
    o.id,
    o.resi,
    g.nama_game,
    v.nama_variant,
    v.harga_jual,
    o.status,
    o.created_at,
    o.nama_pengirim
FROM orders o
JOIN product_variants v ON o.variant_id = v.id
JOIN games g ON v.game_id = g.id
WHERE 1=1 $where
ORDER BY o.id DESC
LIMIT 10
");

/* =========================
   DATA GRAFIK
========================= */
$chart = mysqli_query($conn,"
SELECT DATE(o.created_at) tgl, COUNT(*) total
FROM orders o
WHERE o.status='SELESAI'
GROUP BY DATE(o.created_at)
ORDER BY tgl DESC
LIMIT 7
");

$labels = [];
$data = [];
while($c = mysqli_fetch_assoc($chart)){
    $labels[] = date('d M', strtotime($c['tgl']));
    $data[]   = $c['total'];
}
$labels = array_reverse($labels);
$data = array_reverse($data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Gudang Barokah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      --info: #3b82f6;
    }

    [data-theme="dark"] {
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
      --info: #3b82f6;
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

    .page-title {
      flex: 1;
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

    /* FILTER */
    .filter-section {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 24px;
    }

    .filter-form {
      display: flex;
      gap: 12px;
      align-items: end;
      flex-wrap: wrap;
    }

    .form-group {
      flex: 1;
      min-width: 200px;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--text-secondary);
      margin-bottom: 8px;
    }

    .form-input {
      width: 100%;
      padding: 10px 14px;
      background: var(--bg-primary);
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--text-primary);
      font-size: 14px;
      font-family: inherit;
      outline: none;
      transition: all 0.3s ease;
    }

    .form-input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(91, 124, 255, 0.1);
    }

    /* STATS GRID */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 24px;
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 100px;
      height: 100px;
      background: var(--stat-color);
      opacity: 0.1;
      border-radius: 50%;
      transform: translate(30%, -30%);
    }

    .stat-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .stat-icon {
      width: 48px;
      height: 48px;
      background: var(--stat-color);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 22px;
    }

    .stat-trend {
      display: flex;
      align-items: center;
      gap: 4px;
      font-size: 12px;
      font-weight: 600;
      padding: 4px 8px;
      border-radius: 6px;
    }

    .stat-trend.up {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }

    .stat-trend.down {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }

    .stat-value {
      font-size: 32px;
      font-weight: 800;
      color: var(--text-primary);
      margin-bottom: 8px;
    }

    .stat-label {
      font-size: 14px;
      color: var(--text-secondary);
      font-weight: 500;
    }

    .stat-card.blue { --stat-color: var(--info); }
    .stat-card.orange { --stat-color: var(--warning); }
    .stat-card.green { --stat-color: var(--success); }
    .stat-card.red { --stat-color: var(--danger); }
    .stat-card.purple { --stat-color: #7c3aed; }

    /* CHART SECTION */
    .chart-section {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 24px;
    }

    .section-title {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .section-title i {
      color: var(--accent);
    }

    /* TABLE */
    .table-section {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
    }

    .table-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
    }

    .table-wrapper {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      background: var(--bg-primary);
      padding: 14px 24px;
      text-align: left;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--text-secondary);
      border-bottom: 1px solid var(--border);
    }

    td {
      padding: 16px 24px;
      border-bottom: 1px solid var(--border);
      font-size: 14px;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:hover {
      background: var(--bg-primary);
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-badge.pending {
      background: rgba(245, 158, 11, 0.1);
      color: var(--warning);
    }

    .status-badge.selesai {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }

    .status-badge.batal {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }

    .order-id {
      font-weight: 700;
      color: var(--accent);
    }

    .action-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 14px;
      background: rgba(91, 124, 255, 0.1);
      border: 1px solid transparent;
      border-radius: 6px;
      color: var(--accent);
      text-decoration: none;
      font-size: 13px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .action-btn:hover {
      background: var(--accent);
      color: white;
      border-color: var(--accent);
    }

    /* ANIMATIONS */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .stat-card {
      animation: fadeInUp 0.5s ease backwards;
    }

    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.15s; }
    .stat-card:nth-child(4) { animation-delay: 0.2s; }
    .stat-card:nth-child(5) { animation-delay: 0.25s; }
    .stat-card:nth-child(6) { animation-delay: 0.3s; }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .main-content {
        margin-left: 0;
      }

      .sidebar.active {
        transform: translateX(0);
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

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .filter-form {
        flex-direction: column;
      }

      .form-group {
        width: 100%;
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
      <a href="dashboard.php" class="nav-link active">
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
      <a href="vouchers.php" class="nav-link">
        <i class="fa-solid fa-gamepad"></i>
        <span>Voucher</span>
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
      <h1>Gudang Barokah Owner</h1>
      <p>Selamat datang kembali! Berikut ringkasan bisnis Anda.</p>
    </div>
    <div class="header-actions">
      <!-- <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
        <i class="fa-solid fa-moon" id="themeIcon"></i>
      </button> -->
    </div>
  </div>

  <!-- FILTER -->
  <div class="filter-section">
    <form method="GET" class="filter-form">
      <div class="form-group">
        <label class="form-label">Dari Tanggal</label>
        <input type="date" name="from" class="form-input" value="<?= $_GET['from'] ?? '' ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Sampai Tanggal</label>
        <input type="date" name="to" class="form-input" value="<?= $_GET['to'] ?? '' ?>">
      </div>
      <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-filter"></i>
        Filter Data
      </button>
    </form>
  </div>

  <!-- STATS GRID -->
  <div class="stats-grid">
    <div class="stat-card blue">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fa-solid fa-shopping-bag"></i>
        </div>
      </div>
      <div class="stat-value"><?= number_format($total_order) ?></div>
      <div class="stat-label">Total Order</div>
    </div>

    <div class="stat-card orange">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fa-solid fa-clock"></i>
        </div>
      </div>
      <div class="stat-value"><?= number_format($pending) ?></div>
      <div class="stat-label">Pending</div>
    </div>

    <div class="stat-card green">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fa-solid fa-check-circle"></i>
        </div>
      </div>
      <div class="stat-value"><?= number_format($selesai) ?></div>
      <div class="stat-label">Selesai</div>
    </div>

    <div class="stat-card red">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fa-solid fa-times-circle"></i>
        </div>
      </div>
      <div class="stat-value"><?= number_format($batal) ?></div>
      <div class="stat-label">Dibatalkan</div>
    </div>

    <div class="stat-card purple">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fa-solid fa-wallet"></i>
        </div>
      </div>
      <div class="stat-value">Rp<?= number_format($uang_kotor/1000) ?>K</div>
      <div class="stat-label">Uang Masuk</div>
    </div>

    <div class="stat-card green">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fa-solid fa-chart-line"></i>
        </div>
      </div>
      <div class="stat-value">Rp<?= number_format($profit/1000) ?>K</div>
      <div class="stat-label">Total Profit</div>
    </div>
  </div>

  <!-- CHART -->
  <div class="chart-section">
    <h3 class="section-title">
      <i class="fa-solid fa-chart-area"></i>
      Grafik Penjualan (7 Hari Terakhir)
    </h3>
    <canvas id="salesChart" height="80"></canvas>
  </div>

  <!-- TABLE -->
  <div class="table-section">
    <div class="table-header">
      <h3 class="section-title">
        <i class="fa-solid fa-list"></i>
        Order Terbaru
      </h3>
    </div>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Resi</th>
            <th>Game</th>
            <th>Varian</th>
            <th>Pembeli</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while($o=mysqli_fetch_assoc($orders)): ?>
          <tr>
            <td><span class="order-id">#<?= $o['id'] ?></span></td>
            <td><?= $o['resi'] ?></td>
            <td><?= $o['nama_game'] ?></td>
            <td><?= $o['nama_variant'] ?></td>
            <td><?= $o['nama_pengirim'] ?></td>
            <td><strong>Rp<?= number_format($o['harga_jual'], 0, ',', '.') ?></strong></td>
            <td>
              <span class="status-badge <?= strtolower($o['status']) ?>">
                <?php if($o['status'] == 'PENDING'): ?>
                  <i class="fa-solid fa-clock"></i>
                <?php elseif($o['status'] == 'SELESAI'): ?>
                  <i class="fa-solid fa-check"></i>
                <?php else: ?>
                  <i class="fa-solid fa-times"></i>
                <?php endif; ?>
                <?= $o['status'] ?>
              </span>
            </td>
            <td><?= date('d M Y, H:i', strtotime($o['created_at'])) ?></td>
            <td>
              <a href="order_detail.php?id=<?= $o['id'] ?>" class="action-btn">
                <i class="fa-solid fa-eye"></i>
                Detail
              </a>
            </td>
          </tr>
          <?php endwhile ?>
        </tbody>
      </table>
    </div>
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

// Chart
const ctx = document.getElementById('salesChart').getContext('2d');

const gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(91, 124, 255, 0.3)');
gradient.addColorStop(1, 'rgba(91, 124, 255, 0)');

new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      label: 'Order Selesai',
      data: <?= json_encode($data) ?>,
      borderColor: '#5b7cff',
      backgroundColor: gradient,
      borderWidth: 3,
      fill: true,
      tension: 0.4,
      pointRadius: 6,
      pointBackgroundColor: '#5b7cff',
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointHoverRadius: 8
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        padding: 12,
        titleColor: '#fff',
        bodyColor: '#fff',
        borderColor: '#5b7cff',
        borderWidth: 1,
        displayColors: false,
        callbacks: {
          label: function(context) {
            return 'Order: ' + context.parsed.y;
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 1,
          color: '#a0a6c1'
        },
        grid: {
          color: 'rgba(0, 0, 0, 0.05)',
          drawBorder: false
        }
      },
      x: {
        ticks: {
          color: '#a0a6c1'
        },
        grid: {
          display: false
        }
      }
    }
  }
});
</script>

</body>
</html>