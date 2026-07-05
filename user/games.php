<?php
include "../config/database.php";

$kategori = $_GET['kategori'] ?? '';
if (!$kategori) die("Kategori tidak ada");

// Ambil semua games berdasarkan kategori
// Hanya ambil field yang ada di database
$games = mysqli_query($conn, "
SELECT * FROM games 
WHERE kategori='$kategori'
ORDER BY nama_game ASC
");

// Hitung total games
$total_games = mysqli_num_rows($games);
?>
<!DOCTYPE html>
<html>
<head>
<title><?= htmlspecialchars($kategori) ?> - Toko Digital Store</title>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    color: #333;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Header */
.page-header {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 40px;
    color: white;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.page-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.page-header h1 i {
    color: #ffd166;
}

.category-info {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    margin-top: 20px;
    flex-wrap: wrap;
}

.info-box {
    background: rgba(255, 255, 255, 0.15);
    padding: 15px 25px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.1rem;
}

.info-box i {
    color: #ffd166;
    font-size: 1.3rem;
}

/* Filter Section */
.filter-section {
    background: white;
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.filter-header h3 {
    font-size: 1.4rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-header h3 i {
    color: #667eea;
}

.search-box {
    position: relative;
    flex-grow: 1;
    max-width: 400px;
}

.search-box input {
    width: 100%;
    padding: 14px 20px 14px 50px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-box i {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.sort-options {
    display: flex;
    gap: 10px;
}

.sort-btn {
    padding: 10px 20px;
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 500;
    color: #555;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.sort-btn:hover,
.sort-btn.active {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

/* Games Grid */
.games-section {
    background: white;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 20px;
}

.game-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
    border: 1px solid rgba(0, 0, 0, 0.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.game-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
    border-color: #667eea;
}

.game-image {
    width: 100%;
    height: 200px;
    position: relative;
    overflow: hidden;
}

.game-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.game-card:hover .game-image img {
    transform: scale(1.05);
}

.game-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(90deg, #ff6b6b, #ff8e53);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    z-index: 2;
}

.game-content {
    padding: 25px;
}

.game-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.4;
}

.game-category {
    color: #667eea;
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.game-category i {
    font-size: 0.9rem;
}

.game-description {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 20px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.game-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.game-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #333;
}

.price-from {
    font-size: 0.85rem;
    color: #999;
    font-weight: 400;
}

.select-btn {
    padding: 10px 25px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.select-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

/* No Games Message */
.no-games {
    text-align: center;
    padding: 60px 20px;
    grid-column: 1 / -1;
}

.no-games i {
    font-size: 4rem;
    color: #e0e0e0;
    margin-bottom: 20px;
}

.no-games h3 {
    font-size: 1.5rem;
    color: #666;
    margin-bottom: 10px;
}

.no-games p {
    color: #999;
    margin-bottom: 30px;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 30px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: #5a6fd8;
    transform: translateY(-2px);
}

/* Footer */
.page-footer {
    text-align: center;
    color: white;
    padding: 30px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.page-footer p {
    opacity: 0.8;
    font-size: 0.95rem;
}

/* Responsif */
@media (max-width: 768px) {
    .games-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .category-info {
        flex-direction: column;
        gap: 15px;
    }
    
    .filter-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        max-width: 100%;
    }
    
    .sort-options {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .game-footer {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .select-btn {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .games-grid {
        grid-template-columns: 1fr;
    }
    
    .container {
        padding: 15px;
    }
    
    .games-section {
        padding: 20px;
    }
    
    .game-image {
        height: 180px;
    }
}
</style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-gamepad"></i>
            <?= htmlspecialchars($kategori) ?>
        </h1>
        <p>Temukan koleksi <?= strtolower($kategori) ?> terbaik dengan harga spesial</p>
        
        <div class="category-info">
            <div class="info-box">
                <i class="fas fa-boxes"></i>
                <span><?= $total_games ?> Produk Tersedia</span>
            </div>
            <div class="info-box">
                <i class="fas fa-bolt"></i>
                <span>Pengiriman Instan</span>
            </div>
            <div class="info-box">
                <i class="fas fa-shield-alt"></i>
                <span>Garansi 100%</span>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-header">
            <h3><i class="fas fa-filter"></i> Filter & Urutkan</h3>
            
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari <?= strtolower($kategori) ?>...">
            </div>
            
            <div class="sort-options">
                <button class="sort-btn active" data-sort="name">
                    <i class="fas fa-sort-alpha-down"></i> Nama A-Z
                </button>
                <button class="sort-btn" data-sort="newest">
                    <i class="fas fa-calendar-plus"></i> Terbaru
                </button>
            </div>
        </div>
    </div>

    <!-- Games Grid -->
    <div class="games-section">
        <div class="games-grid" id="gamesContainer">
            <?php if($total_games > 0): ?>
                <?php while($g = mysqli_fetch_assoc($games)): ?>
                <div class="game-card" data-name="<?= strtolower($g['nama_game']) ?>" 
                     data-date="<?= $g['created_at'] ?? date('Y-m-d') ?>">
                    
                    <?php if(!empty($g['gambar'])): ?>
                    <div class="game-image">
                        <img src="../uploads/<?= $g['gambar'] ?>" alt="<?= $g['nama_game'] ?>">
                        <?php if(isset($g['is_new']) && $g['is_new'] == 1): ?>
                        <div class="game-badge">BARU</div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="game-content">
                        <h3 class="game-title"><?= $g['nama_game'] ?></h3>
                        
                        <div class="game-category">
                            <i class="fas fa-tag"></i>
                            <?= $g['kategori'] ?>
                        </div>
                        
                        <?php if(!empty($g['deskripsi'])): ?>
                        <p class="game-description"><?= $g['deskripsi'] ?></p>
                        <?php endif; ?>
                        
                        <div class="game-footer">
                            <div class="game-price">
                                <?php if(!empty($g['harga_terendah'])): ?>
                                <span class="price-from">Mulai dari</span>
                                Rp<?= number_format($g['harga_terendah'], 0, ',', '.') ?>
                                <?php else: ?>
                                <span class="price-from">Hubungi Admin</span>
                                <?php endif; ?>
                            </div>
                            
                            <a href="order.php?game_id=<?= $g['id'] ?>" class="select-btn">
                                <i class="fas fa-shopping-cart"></i> Pilih
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-games">
                    <i class="fas fa-gamepad"></i>
                    <h3>Tidak ada <?= strtolower($kategori) ?> tersedia</h3>
                    <p>Silakan kembali lagi nanti untuk melihat koleksi terbaru</p>
                    <a href="dashboard.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="page-footer">
        <p>&copy; <?= date('Y') ?> Toko Digital Store. Semua hak dilindungi.</p>
        <p><i class="fas fa-headset"></i> Customer Support: support@tokodigital.com</p>
    </div>
</div>

<script>
// Search functionality
const searchInput = document.getElementById('searchInput');
const gameCards = document.querySelectorAll('.game-card');

searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    
    gameCards.forEach(card => {
        const gameName = card.getAttribute('data-name');
        if (gameName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Sort functionality
const sortButtons = document.querySelectorAll('.sort-btn');

sortButtons.forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all buttons
        sortButtons.forEach(btn => btn.classList.remove('active'));
        // Add active class to clicked button
        this.classList.add('active');
        
        const sortType = this.getAttribute('data-sort');
        sortGames(sortType);
    });
});

function sortGames(sortType) {
    const gamesArray = Array.from(gameCards);
    const gamesContainer = document.getElementById('gamesContainer');
    
    gamesArray.sort((a, b) => {
        switch(sortType) {
            case 'name':
                return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
            
            case 'newest':
                const dateA = new Date(a.getAttribute('data-date'));
                const dateB = new Date(b.getAttribute('data-date'));
                return dateB - dateA;
            
            default:
                return 0;
        }
    });
    
    // Clear container
    gamesContainer.innerHTML = '';
    
    // Append sorted games
    gamesArray.forEach(game => {
        gamesContainer.appendChild(game);
    });
}

// Add hover effects to cards
gameCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});
</script>

</body>
</html>