<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../config/database.php";

$q = mysqli_query($conn, "SELECT * FROM games ORDER BY id DESC");
if (!$q) die(mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Game | Admin Gudang Barokah</title>
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

    /* SEARCH BAR */
    .search-bar {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .search-bar i {
      color: var(--text-secondary);
      font-size: 18px;
    }

    .search-input {
      flex: 1;
      border: none;
      background: transparent;
      color: var(--text-primary);
      font-size: 14px;
      font-family: inherit;
      outline: none;
    }

    .search-input::placeholder {
      color: var(--text-secondary);
    }

    /* GAMES GRID */
    .games-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
      margin-bottom: 24px;
    }

    .game-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
      transition: all 0.3s ease;
      animation: fadeInUp 0.5s ease backwards;
    }

    .game-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
      border-color: var(--accent);
    }

    .game-image {
      position: relative;
      width: 100%;
      height: 180px;
      overflow: hidden;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .game-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .game-card:hover .game-image img {
      transform: scale(1.1);
    }

    .extended-badge {
      position: absolute;
      top: 12px;
      right: 12px;
      background: linear-gradient(135deg, var(--success), #059669);
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .game-body {
      padding: 20px;
    }

    .game-number {
      font-size: 12px;
      color: var(--text-secondary);
      font-weight: 600;
      margin-bottom: 8px;
    }

    .game-title {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 16px;
      color: var(--text-primary);
    }

    .game-actions {
      display: flex;
      gap: 8px;
    }

    .btn-sm {
      flex: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .btn-edit {
      background: rgba(91, 124, 255, 0.1);
      border: 1px solid transparent;
      color: var(--accent);
    }

    .btn-edit:hover {
      background: var(--accent);
      color: white;
      border-color: var(--accent);
    }

    .btn-delete {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid transparent;
      color: var(--danger);
    }

    .btn-delete:hover {
      background: var(--danger);
      color: white;
      border-color: var(--danger);
    }

    /* EMPTY STATE */
    .empty-state {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 80px 20px;
      text-align: center;
    }

    .empty-icon {
      width: 100px;
      height: 100px;
      margin: 0 auto 24px;
      background: var(--bg-primary);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .empty-icon i {
      font-size: 48px;
      color: var(--text-secondary);
    }

    .empty-state h3 {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 12px;
    }

    .empty-state p {
      color: var(--text-secondary);
      margin-bottom: 24px;
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

    .game-card:nth-child(1) { animation-delay: 0.05s; }
    .game-card:nth-child(2) { animation-delay: 0.1s; }
    .game-card:nth-child(3) { animation-delay: 0.15s; }
    .game-card:nth-child(4) { animation-delay: 0.2s; }
    .game-card:nth-child(5) { animation-delay: 0.25s; }
    .game-card:nth-child(6) { animation-delay: 0.3s; }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .main-content {
        margin-left: 0;
      }

      .games-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
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

      .games-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
      }
    }

    @media (max-width: 480px) {
      .games-grid {
        grid-template-columns: 1fr;
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
      <h1>Kelola Game</h1>
      <p>Kelola semua produk game yang tersedia di toko</p>
    </div>
    <div class="header-actions">
      <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
        <i class="fa-solid fa-moon" id="themeIcon"></i>
      </button>
      <a href="game_add.php" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i>
        Tambah Game
      </a>
    </div>
  </div>

  <!-- SEARCH BAR -->
  <div class="search-bar">
    <i class="fa-solid fa-search"></i>
    <input type="text" class="search-input" id="searchInput" placeholder="Cari game berdasarkan nama...">
  </div>

  <!-- GAMES GRID -->
  <?php if(mysqli_num_rows($q) > 0): ?>
  <div class="games-grid" id="gamesGrid">
    <?php
    $no = 1;
    mysqli_data_seek($q, 0);
    while ($g = mysqli_fetch_assoc($q)):
    ?>
    <div class="game-card" data-name="<?= strtolower($g['nama_game']) ?>">
      <div class="game-image">
        <img src="../uploads/<?= $g['gambar'] ?>" alt="<?= $g['nama_game'] ?>">
        <?php if($g['is_extended']): ?>
          <div class="extended-badge">Extended</div>
        <?php endif; ?>
      </div>
      <div class="game-body">
        <div class="game-number">#<?= $no++ ?></div>
        <div class="game-title"><?= $g['nama_game'] ?></div>
        <div class="game-actions">
          <a href="game_edit.php?id=<?= $g['id'] ?>" class="btn-sm btn-edit">
            <i class="fa-solid fa-pen"></i>
            Edit
          </a>
          <a href="game_delete.php?id=<?= $g['id'] ?>" 
             class="btn-sm btn-delete" 
             onclick="return confirm('Yakin ingin menghapus game <?= $g['nama_game'] ?>?')">
            <i class="fa-solid fa-trash"></i>
            Hapus
          </a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>

  <?php else: ?>
  <!-- EMPTY STATE -->
  <div class="empty-state">
    <div class="empty-icon">
      <i class="fa-solid fa-gamepad"></i>
    </div>
    <h3>Belum Ada Game</h3>
    <p>Mulai tambahkan game pertama Anda untuk ditampilkan di toko</p>
    <a href="game_add.php" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i>
      Tambah Game Pertama
    </a>
  </div>
  <?php endif; ?>
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

// Search functionality
const searchInput = document.getElementById('searchInput');
const gameCards = document.querySelectorAll('.game-card');

searchInput.addEventListener('input', () => {
  const query = searchInput.value.toLowerCase();
  
  gameCards.forEach(card => {
    const gameName = card.dataset.name;
    if (gameName.includes(query)) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
});
</script>

</body>
</html>