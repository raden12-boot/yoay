<?php
include "../config/database.php";

/* =====================
   MODE 1: PILIH VARIANT
===================== */
if (isset($_GET['game_id']) && is_numeric($_GET['game_id'])) {

    $game_id = $_GET['game_id'];

    $game_q = mysqli_query($conn, "
        SELECT * FROM games WHERE id='$game_id'
    ");

    if (mysqli_num_rows($game_q) == 0) {
        die("Game tidak ditemukan");
    }

    $g = mysqli_fetch_assoc($game_q);

    $variants = mysqli_query($conn, "
        SELECT * FROM product_variants
        WHERE game_id='$game_id'
        ORDER BY harga_jual ASC
    ");
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $g['nama_game'] ?> - Gudang Barokah</title>
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

        [data-theme="light"] {
          --bg-primary: #f5f7fa;
          --bg-secondary: #ffffff;
          --bg-card: #ffffff;
          --text-primary: #1a1d29;
          --text-secondary: #6b7280;
          --accent: #5b7cff;
          --border: rgba(0, 0, 0, 0.08);
          --success: #10b981;
        }

        body {
          background: var(--bg-primary);
          color: var(--text-primary);
          font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
          min-height: 100vh;
          transition: background 0.3s ease, color 0.3s ease;
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
          gap: 24px;
          transition: background 0.3s ease;
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

        .logo i {
          font-size: 26px;
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
          max-width: 1200px;
          margin: 40px auto;
          padding: 0 32px;
        }

        /* GAME HEADER */
        .game-header {
          background: var(--bg-card);
          border: 1px solid var(--border);
          border-radius: 16px;
          padding: 32px;
          margin-bottom: 32px;
          display: flex;
          align-items: center;
          gap: 24px;
        }

        .game-image {
          width: 120px;
          height: 120px;
          border-radius: 12px;
          overflow: hidden;
          flex-shrink: 0;
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .game-image img {
          width: 100%;
          height: 100%;
          object-fit: cover;
        }

        .game-info h1 {
          font-size: 32px;
          font-weight: 800;
          margin-bottom: 12px;
          background: linear-gradient(135deg, var(--text-primary), var(--text-secondary));
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
        }

        .game-info p {
          font-size: 15px;
          color: var(--text-secondary);
          line-height: 1.6;
          margin-bottom: 16px;
        }

        .game-stats {
          display: flex;
          gap: 24px;
        }

        .stat-item {
          display: flex;
          align-items: center;
          gap: 8px;
          font-size: 14px;
          color: var(--text-secondary);
        }

        .stat-item i {
          color: var(--accent);
        }

        /* SECTION TITLE */
        .section-title {
          display: flex;
          align-items: center;
          gap: 12px;
          margin-bottom: 24px;
        }

        .section-icon {
          width: 40px;
          height: 40px;
          background: var(--accent);
          border-radius: 10px;
          display: flex;
          align-items: center;
          justify-content: center;
          color: white;
        }

        .section-title h2 {
          font-size: 24px;
          font-weight: 700;
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

        .search-count {
          font-size: 13px;
          color: var(--text-secondary);
          padding: 4px 12px;
          background: var(--bg-primary);
          border-radius: 6px;
        }

        /* VARIANTS GRID */
        .variant-group-section {
          margin-bottom: 48px;
        }

        .group-header {
          display: flex;
          align-items: center;
          gap: 12px;
          margin-bottom: 20px;
          padding-bottom: 16px;
          border-bottom: 2px solid var(--border);
        }

        .group-icon {
          width: 36px;
          height: 36px;
          background: linear-gradient(135deg, var(--accent), #7c3aed);
          border-radius: 8px;
          display: flex;
          align-items: center;
          justify-content: center;
          color: white;
          font-size: 18px;
        }

        .group-title {
          font-size: 20px;
          font-weight: 700;
          color: var(--text-primary);
          flex: 1;
        }

        .group-count {
          font-size: 13px;
          color: var(--text-secondary);
          padding: 4px 12px;
          background: var(--bg-primary);
          border-radius: 6px;
        }

        .variants-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
          gap: 16px;
        }

        .variant-card {
          background: var(--bg-card);
          border: 1px solid var(--border);
          border-radius: 10px;
          padding: 16px;
          transition: all 0.3s ease;
          cursor: pointer;
          position: relative;
          text-align: center;
        }

        .variant-card:hover {
          border-color: var(--accent);
          transform: translateY(-2px);
          box-shadow: 0 8px 16px rgba(91, 124, 255, 0.2);
        }

        .variant-header {
          display: flex;
          align-items: flex-start;
          justify-content: space-between;
          margin-bottom: 12px;
        }

        .variant-name {
          font-size: 14px;
          font-weight: 600;
          color: var(--text-primary);
          line-height: 1.3;
          margin-bottom: 12px;
          min-height: 36px;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .popular-badge {
          background: linear-gradient(135deg, #10b981, #059669);
          color: white;
          padding: 4px 8px;
          border-radius: 4px;
          font-size: 10px;
          font-weight: 700;
          text-transform: uppercase;
          letter-spacing: 0.5px;
          position: absolute;
          top: 8px;
          right: 8px;
        }

        .variant-price {
          font-size: 18px;
          font-weight: 800;
          color: var(--accent);
          margin-bottom: 12px;
        }

        .variant-btn {
          display: block;
          width: 100%;
          padding: 10px 12px;
          background: linear-gradient(135deg, var(--accent), #7c3aed);
          color: white;
          border: none;
          border-radius: 6px;
          font-weight: 600;
          font-size: 13px;
          cursor: pointer;
          transition: all 0.3s ease;
          text-decoration: none;
          text-align: center;
        }

        .variant-btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 12px rgba(91, 124, 255, 0.4);
        }

        .variant-btn i {
          font-size: 12px;
          margin-right: 4px;
        }

        .empty-state {
          text-align: center;
          padding: 80px 20px;
          background: var(--bg-card);
          border: 1px solid var(--border);
          border-radius: 16px;
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
          font-size: 40px;
          color: var(--text-secondary);
        }

        .empty-state h3 {
          font-size: 20px;
          font-weight: 700;
          margin-bottom: 12px;
        }

        .empty-state p {
          color: var(--text-secondary);
          font-size: 15px;
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

        .variant-card {
          animation: fadeInUp 0.5s ease backwards;
        }

        .variant-card:nth-child(1) { animation-delay: 0.05s; }
        .variant-card:nth-child(2) { animation-delay: 0.1s; }
        .variant-card:nth-child(3) { animation-delay: 0.15s; }
        .variant-card:nth-child(4) { animation-delay: 0.2s; }
        .variant-card:nth-child(5) { animation-delay: 0.25s; }
        .variant-card:nth-child(6) { animation-delay: 0.3s; }

        /* RESPONSIVE */
        @media (max-width: 768px) {
          header {
            padding: 14px 20px;
          }

          .container {
            padding: 0 20px;
          }

          .game-header {
            flex-direction: column;
            text-align: center;
            padding: 24px;
          }

          .game-image {
            width: 100px;
            height: 100px;
          }

          .game-info h1 {
            font-size: 24px;
          }

          .game-stats {
            justify-content: center;
            flex-wrap: wrap;
          }

          .variant-group-section {
            margin-bottom: 32px;
          }

          .group-header {
            flex-wrap: wrap;
          }

          .group-title {
            font-size: 18px;
          }

          .variants-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
          }

          .variant-card {
            padding: 12px;
          }

          .variant-name {
            font-size: 12px;
            min-height: 32px;
          }

          .variant-price {
            font-size: 15px;
            margin-bottom: 10px;
          }

          .variant-btn {
            padding: 8px;
            font-size: 11px;
          }

          .search-bar {
            padding: 12px 16px;
          }
        }

        @media (max-width: 480px) {
          .variants-grid {
            grid-template-columns: repeat(2, 1fr);
          }

          .variant-card {
            padding: 10px;
          }

          .variant-name {
            font-size: 11px;
            min-height: 30px;
          }

          .variant-price {
            font-size: 14px;
          }

          .variant-btn {
            padding: 7px;
            font-size: 10px;
          }
        }
        </style>
    </head>
    <body>

    <header>
      <a href="dashboard.php" class="logo">
        <img src="../uploads/logo.png" alt="Gudang Barokah" style="width: 40px; height: 40px; object-fit: contain;">
        <span>Gudang Barokah</span>
      </a>

      <div style="display: flex; align-items: center; gap: 12px;">
        <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode" style="width: 44px; height: 44px; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; color: var(--text-secondary);">
          <i class="fa-solid fa-moon" id="themeIcon"></i>
        </button>

        <a href="dashboard.php" class="back-btn">
          <i class="fa-solid fa-arrow-left"></i>
          <span>Kembali</span>
        </a>
      </div>
    </header>

    <div class="container">
      <!-- GAME HEADER -->
      <div class="game-header">
        <div class="game-image">
          <img src="../uploads/<?= $g['gambar'] ?>" alt="<?= $g['nama_game'] ?>">
        </div>
        <div class="game-info">
          <h1><?= $g['nama_game'] ?></h1>
          <?php if(!empty($g['deskripsi'])): ?>
            <p><?= htmlspecialchars($g['deskripsi']) ?></p>
          <?php else: ?>
            <p>Produk <?= $g['nama_game'] ?> tersedia dengan berbagai pilihan nominal. Pilih paket yang sesuai kebutuhan Anda.</p>
          <?php endif; ?>
          <div class="game-stats">
            <div class="stat-item">
              <i class="fa-solid fa-shield-halved"></i>
              <span>Transaksi Aman</span>
            </div>
            <div class="stat-item">
              <i class="fa-solid fa-bolt"></i>
              <span>Proses Otomatis</span>
            </div>
            <div class="stat-item">
              <i class="fa-solid fa-clock"></i>
              <span>24/7</span>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION TITLE -->
      <div class="section-title">
        <div class="section-icon">
          <i class="fa-solid fa-shopping-cart"></i>
        </div>
        <h2>Pilih Nominal</h2>
      </div>

      <!-- SEARCH BAR -->
      <?php if(mysqli_num_rows($variants) > 0): 
        mysqli_data_seek($variants, 0); // Reset pointer
        $total_variants = mysqli_num_rows($variants);
      ?>
      <div class="search-bar">
        <i class="fa-solid fa-search"></i>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari varian... (contoh: 100, diamond, saldo)">
        <span class="search-count" id="searchCount"><?= $total_variants ?> varian</span>
      </div>
      <?php endif; ?>

      <!-- VARIANTS -->
      <?php if(mysqli_num_rows($variants) > 0): ?>
        <?php 
        // Group variants by variant_group
        $grouped_variants = [];
        mysqli_data_seek($variants, 0);
        while($v = mysqli_fetch_assoc($variants)) {
          $group = $v['variant_group'] ?: 'Lainnya';
          $grouped_variants[$group][] = $v;
        }
        ?>

        <?php foreach($grouped_variants as $group_name => $group_variants): ?>
        <!-- GROUP SECTION -->
        <div class="variant-group-section">
          <div class="group-header">
            <div class="group-icon">
              <i class="fa-solid fa-gem"></i>
            </div>
            <h3 class="group-title"><?= $group_name ?></h3>
            <span class="group-count"><?= count($group_variants) ?> varian</span>
          </div>

          <div class="variants-grid" data-group="<?= strtolower($group_name) ?>">
            <?php foreach($group_variants as $v): ?>
            <div class="variant-card" data-name="<?= strtolower($v['nama_variant']) ?>">
              <div class="variant-name"><?= $v['nama_variant'] ?></div>
              <div class="variant-price">Rp<?= number_format($v['harga_jual'],0,',','.') ?></div>
              <a href="order.php?variant_id=<?= $v['id'] ?>" class="variant-btn">
                <i class="fa-solid fa-shopping-cart"></i> Beli
              </a>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fa-solid fa-box-open"></i>
          </div>
          <h3>Belum Ada Produk</h3>
          <p>Item untuk game ini belum tersedia. Silakan cek kembali nanti.</p>
        </div>
      <?php endif ?>
    </div>

    <script>
    // Search functionality for grouped variants
    const searchInput = document.getElementById('searchInput');
    const searchCount = document.getElementById('searchCount');
    const variantCards = document.querySelectorAll('.variant-card');
    const groupSections = document.querySelectorAll('.variant-group-section');
    const totalVariants = variantCards.length;

    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        let visibleCount = 0;

        // Hide/show individual cards
        variantCards.forEach(card => {
          const variantName = card.dataset.name;
          
          if (variantName.includes(query)) {
            card.style.display = 'block';
            visibleCount++;
          } else {
            card.style.display = 'none';
          }
        });

        // Hide/show group sections if all cards are hidden
        groupSections.forEach(section => {
          const visibleCards = section.querySelectorAll('.variant-card[style*="display: block"], .variant-card:not([style*="display: none"])');
          if (query && visibleCards.length === 0) {
            section.style.display = 'none';
          } else {
            section.style.display = 'block';
          }
        });

        // Update count
        if (query) {
          searchCount.textContent = `${visibleCount} dari ${totalVariants} varian`;
        } else {
          searchCount.textContent = `${totalVariants} varian`;
        }
      });
    }
    </script>

    </body>
    </html>
    <?php
    exit;
}


/* =====================
   MODE 2: FORM ORDER
===================== */
if (isset($_GET['variant_id']) && is_numeric($_GET['variant_id'])) {

    $variant_id = $_GET['variant_id'];

    $v = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT v.*, g.nama_game, g.keperluan, g.gambar
        FROM product_variants v
        JOIN games g ON v.game_id = g.id
        WHERE v.id='$variant_id'
    "));

    if (!$v) {
        die("Variant tidak ditemukan");
    }

    $k = strtolower($v['keperluan']);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout - <?= $v['nama_game'] ?> | Gudang Barokah</title>
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

        .logo i {
          font-size: 26px;
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
          max-width: 1000px;
          margin: 40px auto;
          padding: 0 32px;
        }

        /* CHECKOUT LAYOUT */
        .checkout-layout {
          display: grid;
          grid-template-columns: 1fr 400px;
          gap: 32px;
        }

        /* FORM SECTION */
        .form-section {
          background: var(--bg-card);
          border: 1px solid var(--border);
          border-radius: 16px;
          padding: 32px;
        }

        .form-title {
          font-size: 24px;
          font-weight: 700;
          margin-bottom: 24px;
          display: flex;
          align-items: center;
          gap: 12px;
        }

        .form-title i {
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
          color: #ef4444;
        }

        .form-input,
        .form-select {
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
        .form-select:focus {
          border-color: var(--accent);
          box-shadow: 0 0 0 3px rgba(91, 124, 255, 0.1);
        }

        .form-input::placeholder {
          color: var(--text-secondary);
        }

        .input-icon {
          position: relative;
        }

        .input-icon i {
          position: absolute;
          left: 16px;
          top: 50%;
          transform: translateY(-50%);
          color: var(--text-secondary);
        }

        .input-icon .form-input {
          padding-left: 44px;
        }

        .file-upload-wrapper {
          position: relative;
          overflow: hidden;
        }

        .file-upload-btn {
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 8px;
          padding: 32px;
          background: var(--bg-primary);
          border: 2px dashed var(--border);
          border-radius: 8px;
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
          font-size: 14px;
          color: var(--text-secondary);
        }

        .file-upload-text strong {
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

        .file-preview {
          display: none;
          margin-top: 12px;
          padding: 12px;
          background: var(--bg-primary);
          border: 1px solid var(--border);
          border-radius: 8px;
          font-size: 13px;
          color: var(--text-secondary);
        }

        .file-preview.active {
          display: flex;
          align-items: center;
          gap: 8px;
        }

        /* PAYMENT OPTION */
        .payment-card {
          display: flex;
          align-items: center;
          gap: 16px;
          padding: 16px;
          background: var(--bg-primary);
          border: 2px solid var(--border);
          border-radius: 12px;
          cursor: pointer;
          transition: all 0.3s ease;
        }

        .payment-card.selected {
          border-color: var(--accent);
          background: rgba(91, 124, 255, 0.05);
        }

        .payment-icon {
          width: 48px;
          height: 48px;
          background: linear-gradient(135deg, var(--accent), #7c3aed);
          border-radius: 10px;
          display: flex;
          align-items: center;
          justify-content: center;
          color: white;
          font-size: 22px;
          flex-shrink: 0;
        }

        .payment-info {
          flex: 1;
        }

        .payment-name {
          font-size: 16px;
          font-weight: 700;
          margin-bottom: 4px;
        }

        .payment-desc {
          font-size: 13px;
          color: var(--text-secondary);
        }

        .payment-check {
          color: var(--accent);
          font-size: 24px;
        }

        /* VOUCHER STYLES */
        .btn-check-voucher {
          padding: 12px 24px;
          background: linear-gradient(135deg, var(--accent), #7c3aed);
          color: white;
          border: none;
          border-radius: 8px;
          font-weight: 600;
          font-size: 14px;
          cursor: pointer;
          transition: all 0.3s ease;
          display: flex;
          align-items: center;
          gap: 8px;
          white-space: nowrap;
        }

        .btn-check-voucher:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 12px rgba(91, 124, 255, 0.3);
        }

        .btn-check-voucher:disabled {
          opacity: 0.5;
          cursor: not-allowed;
          transform: none;
        }

        .voucher-success {
          background: rgba(16, 185, 129, 0.1);
          border: 1px solid rgba(16, 185, 129, 0.3);
          color: var(--success);
        }

        .voucher-error {
          background: rgba(239, 68, 68, 0.1);
          border: 1px solid rgba(239, 68, 68, 0.3);
          color: var(--danger);
        }

        .voucher-info {
          display: flex;
          align-items: center;
          gap: 8px;
        }

        .voucher-info i {
          font-size: 16px;
        }

        .discount-row {
          color: var(--success);
          font-weight: 600;
        }

        .discount-row .discount-amount {
          display: flex;
          align-items: center;
          gap: 6px;
        }

        .original-price {
          text-decoration: line-through;
          color: var(--text-secondary);
          font-size: 14px;
        }

        /* QRIS CONTAINER */
        .qris-container {
          background: white;
          border-radius: 16px;
          padding: 24px;
          text-align: center;
        }

        .qris-image {
          width: 100%;
          max-width: 400px;
          height: auto;
          border-radius: 12px;
          margin-bottom: 20px;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .qris-info {
          display: flex;
          flex-direction: column;
          gap: 12px;
          text-align: left;
        }

        .qris-merchant,
        .qris-nmid,
        .qris-note {
          display: flex;
          align-items: center;
          gap: 10px;
          padding: 12px 16px;
          background: #f5f7fa;
          border-radius: 8px;
          font-size: 14px;
          color: #1a1d29;
        }

        .qris-merchant i,
        .qris-nmid i,
        .qris-note i {
          color: var(--accent);
          font-size: 16px;
          flex-shrink: 0;
        }

        .qris-merchant {
          font-weight: 700;
        }

        .qris-nmid {
          font-family: 'Courier New', monospace;
        }

        .qris-note {
          background: rgba(16, 185, 129, 0.1);
          color: var(--success);
          border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .qris-note i {
          color: var(--success);
        }

        /* ORDER SUMMARY */
        .order-summary {
          background: var(--bg-card);
          border: 1px solid var(--border);
          border-radius: 16px;
          padding: 32px;
          height: fit-content;
          position: sticky;
          top: 88px;
        }

        .summary-title {
          font-size: 20px;
          font-weight: 700;
          margin-bottom: 24px;
        }

        .product-info {
          display: flex;
          gap: 16px;
          padding: 16px;
          background: var(--bg-primary);
          border-radius: 12px;
          margin-bottom: 24px;
        }

        .product-image {
          width: 80px;
          height: 80px;
          border-radius: 8px;
          overflow: hidden;
          flex-shrink: 0;
        }

        .product-image img {
          width: 100%;
          height: 100%;
          object-fit: cover;
        }

        .product-details h3 {
          font-size: 16px;
          font-weight: 700;
          margin-bottom: 6px;
        }

        .product-details p {
          font-size: 14px;
          color: var(--text-secondary);
        }

        .summary-divider {
          height: 1px;
          background: var(--border);
          margin: 24px 0;
        }

        .summary-row {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 16px;
          font-size: 14px;
        }

        .summary-row.total {
          font-size: 18px;
          font-weight: 700;
          color: var(--accent);
          margin-top: 16px;
        }

        .submit-btn {
          width: 100%;
          padding: 16px;
          background: linear-gradient(135deg, var(--accent), #7c3aed);
          color: white;
          border: none;
          border-radius: 10px;
          font-weight: 700;
          font-size: 16px;
          cursor: pointer;
          transition: all 0.3s ease;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 8px;
        }

        .submit-btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 12px 24px rgba(91, 124, 255, 0.4);
        }

        .security-note {
          display: flex;
          align-items: center;
          gap: 8px;
          margin-top: 16px;
          padding: 12px;
          background: rgba(16, 185, 129, 0.1);
          border-radius: 8px;
          font-size: 12px;
          color: var(--success);
        }

        /* RESPONSIVE */
        @media (max-width: 968px) {
          .checkout-layout {
            grid-template-columns: 1fr;
          }

          .order-summary {
            position: static;
          }
        }

        @media (max-width: 768px) {
          header {
            padding: 14px 20px;
          }

          .container {
            padding: 0 20px;
          }

          .form-section,
          .order-summary {
            padding: 24px;
          }
        }
        </style>
    </head>
    <body>

    <header>
      <a href="dashboard.php" class="logo">
        <img src="../uploads/logo.png" alt="Gudang Barokah" style="width: 40px; height: 40px; object-fit: contain;">
        <span>Gudang Barokah</span>
      </a>

      <div style="display: flex; align-items: center; gap: 12px;">
        <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode" style="width: 44px; height: 44px; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; color: var(--text-secondary);">
          <i class="fa-solid fa-moon" id="themeIcon"></i>
        </button>

        <a href="order.php?game_id=<?= $v['game_id'] ?>" class="back-btn">
          <i class="fa-solid fa-arrow-left"></i>
          <span>Kembali</span>
        </a>
      </div>
    </header>

    <div class="container">
      <div class="checkout-layout">
        <!-- FORM SECTION -->
        <div class="form-section">
          <h2 class="form-title">
            <i class="fa-solid fa-file-invoice"></i>
            Detail Pesanan
          </h2>

          <form method="POST" action="order_store.php" enctype="multipart/form-data" id="orderForm">
            <input type="hidden" name="variant_id" value="<?= $v['id'] ?>">

            <!-- ACCOUNT INFO -->
            <?php if(strpos($k,'region') !== false): ?>
              <div class="form-group">
                <label class="form-label">ID Game <span class="required">*</span></label>
                <div class="input-icon">
                  <i class="fa-solid fa-gamepad"></i>
                  <input type="text" name="id_game" class="form-input" placeholder="Masukkan ID Game" required>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Region / Server <span class="required">*</span></label>
                <div class="input-icon">
                  <i class="fa-solid fa-server"></i>
                  <input type="text" name="region" class="form-input" placeholder="Contoh: Asia" required>
                </div>
              </div>

            <?php elseif(strpos($k,'id') !== false): ?>
              <div class="form-group">
                <label class="form-label">ID Game <span class="required">*</span></label>
                <div class="input-icon">
                  <i class="fa-solid fa-gamepad"></i>
                  <input type="text" name="user_input" class="form-input" placeholder="Masukkan ID Game" required>
                </div>
              </div>

            <?php elseif(strpos($k,'email') !== false): ?>
              <div class="form-group">
                <label class="form-label">Email Akun <span class="required">*</span></label>
                <div class="input-icon">
                  <i class="fa-solid fa-envelope"></i>
                  <input type="email" name="user_input" class="form-input" placeholder="email@example.com" required>
                </div>
              </div>

            <?php else: ?>
              <div class="form-group">
                <label class="form-label">Data Akun <span class="required">*</span></label>
                <div class="input-icon">
                  <i class="fa-solid fa-user"></i>
                  <input type="text" name="user_input" class="form-input" placeholder="Masukkan data akun" required>
                </div>
              </div>
            <?php endif; ?>

            <!-- SENDER INFO -->
            <div class="form-group">
              <label class="form-label">Nama Pengirim <span class="required">*</span></label>
              <div class="input-icon">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="nama_pengirim" class="form-input" placeholder="Nama lengkap Anda" required>
              </div>
            </div>

            <!-- PAYMENT METHOD -->
            <div class="form-group">
              <label class="form-label">Metode Pembayaran <span class="required">*</span></label>
              <div class="payment-option" id="qrisOption">
                <input type="hidden" name="payment_method" value="QRIS" required>
                <div class="payment-card selected">
                  <div class="payment-icon">
                    <i class="fa-solid fa-qrcode"></i>
                  </div>
                  <div class="payment-info">
                    <div class="payment-name">QRIS</div>
                    <div class="payment-desc">Scan QR untuk bayar</div>
                  </div>
                  <div class="payment-check">
                    <i class="fa-solid fa-check-circle"></i>
                  </div>
                </div>
              </div>
            </div>

            <!-- QRIS IMAGE -->
            <div class="form-group" id="qrisImageSection">
              <label class="form-label">Scan QRIS untuk Pembayaran</label>
              <div class="qris-container">
                <img src="../uploads/qris.png" alt="QRIS Payment" class="qris-image">
                <div class="qris-info">
                  <div class="qris-merchant">
                    <i class="fa-solid fa-store"></i>
                    <span>RADENSTORE, TOKO SEPATU</span>
                  </div>
                  <div class="qris-nmid">
                    <i class="fa-solid fa-id-card"></i>
                    <span>NMID: ID1025466895824</span>
                  </div>
                  <div class="qris-note">
                    <i class="fa-solid fa-info-circle"></i>
                    <span>Satu QRIS untuk semua metode pembayaran</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- VOUCHER DISCOUNT -->
            <div class="form-group">
              <label class="form-label">Kode Voucher <span class="optional" style="font-weight: 400; font-size: 12px;">(Opsional)</span></label>
              <div style="display: flex; gap: 12px;">
                <input type="text" name="voucher_code" id="voucherCode" class="form-input" placeholder="Masukkan kode voucher" style="text-transform: uppercase; flex: 1;">
                <button type="button" id="btnCheckVoucher" class="btn-check-voucher" onclick="checkVoucher()">
                  <i class="fa-solid fa-check"></i> Gunakan
                </button>
              </div>
              <div id="voucherMessage" style="display: none; margin-top: 12px; padding: 12px; border-radius: 8px; font-size: 13px;"></div>
            </div>

            <!-- UPLOAD PROOF -->
            <div class="form-group">
              <label class="form-label">Bukti Transfer <span class="required">*</span></label>
              <div class="file-upload-wrapper">
                <label class="file-upload-btn" for="fileUpload">
                  <div>
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <div class="file-upload-text">
                      <strong>Klik untuk upload</strong> atau drag & drop<br>
                      <small>PNG, JPG, JPEG (Max. 5MB)</small>
                    </div>
                  </div>
                </label>
                <input type="file" name="bukti_tf" id="fileUpload" class="file-input" accept="image/*" required>
              </div>
              <div class="file-preview" id="filePreview">
                <i class="fa-solid fa-check-circle" style="color: var(--success);"></i>
                <span id="fileName"></span>
              </div>
            </div>
          </form>
        </div>

        <!-- ORDER SUMMARY -->
        <div class="order-summary">
          <h3 class="summary-title">Ringkasan Pesanan</h3>

          <div class="product-info">
            <div class="product-image">
              <img src="../uploads/<?= $v['gambar'] ?>" alt="<?= $v['nama_game'] ?>">
            </div>
            <div class="product-details">
              <h3><?= $v['nama_game'] ?></h3>
              <p><?= $v['nama_variant'] ?></p>
            </div>
          </div>

          <div class="summary-row">
            <span>Harga Produk</span>
            <span id="hargaAsli">Rp<?= number_format($v['harga_jual'],0,',','.') ?></span>
          </div>

          <div class="summary-row discount-row" id="voucherDiscount" style="display: none;">
            <span>Diskon Voucher</span>
            <span class="discount-amount">
              <i class="fa-solid fa-tag"></i>
              <span id="diskonAmount">-Rp0</span>
            </span>
          </div>

          <div class="summary-row">
            <span>Biaya Admin</span>
            <span style="color: var(--success);">Gratis</span>
          </div>

          <div class="summary-divider"></div>

          <div class="summary-row total">
            <span>Total Bayar</span>
            <span id="totalBayar">Rp<?= number_format($v['harga_jual'],0,',','.') ?></span>
          </div>

          <input type="hidden" name="harga_asli" id="hargaAsliValue" value="<?= $v['harga_jual'] ?>">
          <input type="hidden" name="diskon_voucher" id="diskonVoucherValue" value="0">
          <input type="hidden" name="harga_final" id="hargaFinalValue" value="<?= $v['harga_jual'] ?>">
          <input type="hidden" name="voucher_code_final" id="voucherCodeFinal" value="">
          <input type="hidden" name="game_id" value="<?= $v['game_id'] ?>">

          <button type="submit" form="orderForm" class="submit-btn">
            <i class="fa-solid fa-lock"></i>
            Bayar Sekarang
          </button>

          <div class="security-note">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Transaksi aman & terpercaya</span>
          </div>
        </div>
      </div>
    </div>

    <script>
    // Theme Toggle
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const html = document.documentElement;

    const savedTheme = localStorage.getItem('siteTheme') || 'dark';
    html.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    themeToggle.addEventListener('click', () => {
      const currentTheme = html.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      
      html.setAttribute('data-theme', newTheme);
      localStorage.setItem('siteTheme', newTheme);
      updateThemeIcon(newTheme);
    });

    function updateThemeIcon(theme) {
      if (theme === 'dark') {
        themeIcon.className = 'fa-solid fa-moon';
      } else {
        themeIcon.className = 'fa-solid fa-sun';
      }
    }

    // File upload preview
    const fileUpload = document.getElementById('fileUpload');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');

    fileUpload.addEventListener('change', function(e) {
      if (this.files && this.files[0]) {
        const file = this.files[0];
        fileName.textContent = file.name;
        filePreview.classList.add('active');
      }
    });

    // Voucher validation
    let voucherApplied = false;
    const hargaAsli = <?= $v['harga_jual'] ?>;
    const gameId = <?= $v['game_id'] ?>;

    function checkVoucher() {
      const voucherCode = document.getElementById('voucherCode').value.trim().toUpperCase();
      const voucherMessage = document.getElementById('voucherMessage');
      const btnCheck = document.getElementById('btnCheckVoucher');
      
      if (!voucherCode) {
        showVoucherMessage('Masukkan kode voucher terlebih dahulu', 'error');
        return;
      }

      // Disable button
      btnCheck.disabled = true;
      btnCheck.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Cek...';

      // Call API
      fetch('../admin/check_voucher.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `kode_voucher=${encodeURIComponent(voucherCode)}&game_id=${gameId}&harga=${hargaAsli}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success
          showVoucherMessage(`✅ ${data.message} Hemat Rp${data.hemat}`, 'success');
          
          // Update summary
          applyVoucher(data.diskon, data.harga_final, voucherCode);
          
          // Disable input
          document.getElementById('voucherCode').disabled = true;
          btnCheck.innerHTML = '<i class="fa-solid fa-check"></i> Terpakai';
          voucherApplied = true;
        } else {
          showVoucherMessage(`❌ ${data.message}`, 'error');
          btnCheck.disabled = false;
          btnCheck.innerHTML = '<i class="fa-solid fa-check"></i> Gunakan';
        }
      })
      .catch(error => {
        showVoucherMessage('❌ Terjadi kesalahan saat validasi voucher', 'error');
        btnCheck.disabled = false;
        btnCheck.innerHTML = '<i class="fa-solid fa-check"></i> Gunakan';
      });
    }

    function showVoucherMessage(message, type) {
      const voucherMessage = document.getElementById('voucherMessage');
      voucherMessage.style.display = 'block';
      voucherMessage.className = type === 'success' ? 'voucher-success' : 'voucher-error';
      voucherMessage.innerHTML = `<div class="voucher-info"><i class="fa-solid fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${message}</span></div>`;
    }

    function applyVoucher(diskon, hargaFinal, voucherCode) {
      // Update hidden inputs
      document.getElementById('diskonVoucherValue').value = diskon;
      document.getElementById('hargaFinalValue').value = hargaFinal;
      document.getElementById('voucherCodeFinal').value = voucherCode;

      // Update display
      document.getElementById('voucherDiscount').style.display = 'flex';
      document.getElementById('diskonAmount').textContent = '-Rp' + formatRupiah(diskon);
      document.getElementById('totalBayar').textContent = 'Rp' + formatRupiah(hargaFinal);
    }

    function formatRupiah(angka) {
      return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Auto uppercase voucher code
    document.getElementById('voucherCode').addEventListener('input', function() {
      this.value = this.value.toUpperCase();
    });

    // Form validation
    document.getElementById('orderForm').addEventListener('submit', function(e) {
      const inputs = this.querySelectorAll('[required]');
      let isValid = true;

      inputs.forEach(input => {
        if (!input.value.trim()) {
          isValid = false;
          input.style.borderColor = '#ef4444';
        } else {
          input.style.borderColor = '';
        }
      });

      if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi!');
      }
    });
    </script>

    </body>
    </html>
    <?php
    exit;
}

die("Akses tidak valid");
?>