<?php
include "../config/database.php";

$kategori_aktif = $_GET['kategori'] ?? '';

// ambil kategori unik
$kategori_q = mysqli_query($conn, "
    SELECT DISTINCT kategori 
    FROM games 
    ORDER BY kategori ASC
");

// filter kategori
$where = $kategori_aktif 
    ? "WHERE kategori='".mysqli_real_escape_string($conn,$kategori_aktif)."'" 
    : "";

// ambil produk
$games = mysqli_query($conn, "
    SELECT * FROM games 
    $where 
    ORDER BY nama_game ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Gudang Barokah</title>
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
}

[data-theme="light"] {
  --bg-primary: #f5f7fa;
  --bg-secondary: #ffffff;
  --bg-card: #ffffff;
  --text-primary: #1a1d29;
  --text-secondary: #6b7280;
  --accent: #5b7cff;
  --border: rgba(0, 0, 0, 0.08);
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
}

.logo i {
  font-size: 26px;
}

.header-center {
  flex: 1;
  max-width: 600px;
}

.search-box {
  position: relative;
  width: 100%;
}

.search-box input {
  width: 100%;
  padding: 10px 42px 10px 42px;
  background: var(--bg-primary);
  border: 1px solid var(--border);
  border-radius: 8px;
  color: var(--text-primary);
  font-size: 14px;
  font-family: inherit;
  outline: none;
  transition: all 0.3s ease;
}

.search-box input::placeholder {
  color: var(--text-secondary);
}

.search-box input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(91, 124, 255, 0.1);
}

.search-icon {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-secondary);
  pointer-events: none;
}

.search-shortcut {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: var(--bg-card);
  border: 1px solid var(--border);
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  color: var(--text-secondary);
  pointer-events: none;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.theme-toggle {
  width: 40px;
  height: 40px;
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

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  overflow: hidden;
  cursor: pointer;
  border: 2px solid var(--border);
  transition: all 0.3s ease;
}

.user-avatar:hover {
  border-color: var(--accent);
}

.user-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* TABS */
.tabs-wrapper {
  background: var(--bg-secondary);
  border-bottom: 1px solid var(--border);
  padding: 0 32px;
  position: sticky;
  top: 72px;
  z-index: 99;
  transition: background 0.3s ease;
}

.tabs-container {
  position: relative;
  display: flex;
  align-items: center;
}

.tabs-scroll {
  overflow-x: auto;
  overflow-y: hidden;
  scrollbar-width: none;
  -ms-overflow-style: none;
  scroll-behavior: smooth;
}

.tabs-scroll::-webkit-scrollbar {
  display: none;
}

.tabs {
  display: inline-flex;
  gap: 8px;
  padding: 12px 0;
  min-width: 100%;
}

.tabs a {
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  color: var(--text-secondary);
  white-space: nowrap;
  font-weight: 500;
  font-size: 14px;
  transition: all 0.3s ease;
  position: relative;
  border: 1px solid transparent;
}

.tabs a:hover {
  color: var(--text-primary);
  background: var(--bg-primary);
}

.tabs a.active {
  background: var(--accent);
  color: #ffffff;
  font-weight: 600;
  border-color: var(--accent);
}

.scroll-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 32px;
  height: 32px;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 6px;
  display: none;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 2;
  transition: all 0.3s ease;
}

.scroll-btn:hover {
  background: var(--accent);
  color: white;
  border-color: var(--accent);
}

.scroll-btn.left {
  left: 0;
}

.scroll-btn.right {
  right: 0;
}

.scroll-btn.visible {
  display: flex;
}

/* SWIPER BANNER */
.swiper-section {
  max-width: 1400px;
  margin: 32px auto;
  padding: 0 32px;
}

.swiper-container {
  position: relative;
  width: 100%;
  border-radius: 16px;
  overflow: hidden;
}

.swiper-wrapper {
  position: relative;
  width: 100%;
  height: 380px;
  overflow: hidden;
}

.swiper-slide {
  position: absolute;
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: opacity 0.8s ease, transform 0.8s ease;
  transform: scale(1.05);
}

.swiper-slide.active {
  opacity: 1;
  transform: scale(1);
  z-index: 2;
}

.swiper-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.slide-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(26, 29, 41, 0.9) 0%, rgba(26, 29, 41, 0.3) 50%, transparent 100%);
}

.slide-caption {
  position: absolute;
  bottom: 40px;
  left: 40px;
  right: 40px;
  z-index: 3;
  transform: translateY(30px);
  opacity: 0;
  transition: all 0.8s ease 0.2s;
}

.swiper-slide.active .slide-caption {
  transform: translateY(0);
  opacity: 1;
}

.slide-caption h3 {
  font-size: 32px;
  font-weight: 700;
  margin-bottom: 10px;
  color: #ffffff;
  line-height: 1.2;
}

.slide-caption p {
  font-size: 15px;
  color: rgba(255, 255, 255, 0.8);
  max-width: 600px;
  line-height: 1.5;
}

.swiper-navigation {
  position: absolute;
  bottom: 32px;
  right: 40px;
  display: flex;
  gap: 10px;
  z-index: 10;
}

.swiper-button {
  width: 44px;
  height: 44px;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  color: #fff;
}

.swiper-button:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: scale(1.05);
}

.swiper-pagination {
  position: absolute;
  bottom: 32px;
  left: 40px;
  display: flex;
  gap: 6px;
  z-index: 10;
}

.pagination-dot {
  width: 8px;
  height: 8px;
  border-radius: 4px;
  background: rgba(255, 255, 255, 0.3);
  cursor: pointer;
  transition: all 0.3s ease;
}

.pagination-dot.active {
  width: 28px;
  background: #ffffff;
}

/* SECTION */
.section {
  max-width: 1400px;
  margin: 0 auto 48px;
  padding: 0 32px;
}

.section-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 24px;
}

.section-icon {
  width: 36px;
  height: 36px;
  background: var(--accent);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 18px;
}

.section-header h2 {
  font-size: 22px;
  font-weight: 700;
}

/* GRID */
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 20px;
}

/* CARD */
.card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
  border-color: var(--accent);
}

.card-image {
  position: relative;
  width: 100%;
  height: 180px;
  overflow: hidden;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-image.black {
  background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
}

.card-image.blue {
  background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
}

.card-image.red {
  background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
}

.card-image.green {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.card-image.purple {
  background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
}

.card-image.yellow {
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.card-logo {
  width: 80px;
  height: 80px;
  object-fit: contain;
  filter: brightness(0) invert(1);
}

.card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.card:hover img {
  transform: scale(1.1);
}

.badge {
  position: absolute;
  top: 10px;
  left: 10px;
  background: rgba(220, 38, 38, 0.95);
  padding: 5px 12px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  z-index: 2;
  color: white;
}

.status-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(91, 124, 255, 0.95);
  backdrop-filter: blur(8px);
  padding: 5px 12px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
  z-index: 2;
  color: white;
}

.card-body {
  padding: 16px;
}

.card-category {
  font-size: 11px;
  color: var(--text-secondary);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 6px;
  font-weight: 600;
}

.card-body h3 {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 6px;
  color: var(--text-primary);
  line-height: 1.3;
}

.card-body p {
  font-size: 13px;
  color: var(--text-secondary);
  line-height: 1.5;
  height: 38px;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  margin-bottom: 12px;
}

.price {
  font-size: 18px;
  font-weight: 700;
  color: var(--accent);
  margin-bottom: 14px;
}

.btn {
  display: block;
  text-align: center;
  padding: 10px 18px;
  border-radius: 8px;
  background: var(--accent);
  color: #fff;
  text-decoration: none;
  font-weight: 600;
  font-size: 13px;
  transition: all 0.3s ease;
}

.btn:hover {
  background: #4a69e6;
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(91, 124, 255, 0.3);
}

.load-more {
  text-align: center;
  margin: 32px 0;
}

.load-more-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 32px;
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 8px;
  color: var(--text-primary);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.load-more-btn:hover {
  border-color: var(--accent);
  color: var(--accent);
}

/* SEARCH MODAL */
.search-modal {
  position: fixed;
  inset: 0;
  z-index: 1000;
  display: none;
  align-items: flex-start;
  justify-content: center;
  padding: 80px 20px;
  overflow-y: auto;
}

.search-modal.active {
  display: flex;
}

.search-modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(4px);
  animation: fadeIn 0.2s ease;
}

.search-modal-content {
  position: relative;
  width: 100%;
  max-width: 700px;
  background: var(--bg-secondary);
  border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: 0 24px 48px rgba(0, 0, 0, 0.3);
  animation: slideDown 0.3s ease;
  z-index: 1;
}

.search-modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px;
  border-bottom: 1px solid var(--border);
}

.search-modal-header h2 {
  font-size: 20px;
  font-weight: 700;
}

.close-modal {
  width: 36px;
  height: 36px;
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

.close-modal:hover {
  color: var(--text-primary);
  border-color: var(--accent);
}

.search-modal-input-wrapper {
  position: relative;
  padding: 20px 24px;
  border-bottom: 1px solid var(--border);
}

.search-modal-input-wrapper i {
  position: absolute;
  left: 38px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-secondary);
  font-size: 18px;
}

.search-modal-input-wrapper input {
  width: 100%;
  padding: 14px 20px 14px 46px;
  background: var(--bg-primary);
  border: 1px solid var(--border);
  border-radius: 10px;
  color: var(--text-primary);
  font-size: 16px;
  font-family: inherit;
  outline: none;
  transition: all 0.3s ease;
}

.search-modal-input-wrapper input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(91, 124, 255, 0.1);
}

.search-results {
  padding: 24px;
  max-height: 500px;
  overflow-y: auto;
}

.search-empty {
  text-align: center;
  padding: 60px 20px;
}

.search-icon-large {
  width: 80px;
  height: 80px;
  margin: 0 auto 20px;
  background: var(--bg-primary);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.search-icon-large i {
  font-size: 32px;
  color: #ef4444;
}

.search-empty p {
  color: var(--text-secondary);
  font-size: 16px;
}

.search-result-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 14px;
  background: var(--bg-primary);
  border: 1px solid var(--border);
  border-radius: 10px;
  margin-bottom: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  color: var(--text-primary);
  position: relative;
}

.search-result-item:hover {
  border-color: var(--accent);
  transform: translateX(4px);
  background: var(--bg-card);
}

.search-result-image-wrapper {
  position: relative;
  flex-shrink: 0;
}

.search-result-img {
  width: 70px;
  height: 70px;
  border-radius: 8px;
  object-fit: cover;
  flex-shrink: 0;
}

.search-result-badge {
  position: absolute;
  top: 4px;
  left: 4px;
  background: rgba(239, 68, 68, 0.95);
  color: white;
  padding: 3px 8px;
  border-radius: 4px;
  font-size: 9px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.search-result-info {
  flex: 1;
  min-width: 0;
}

.search-result-category {
  display: inline-block;
  background: rgba(91, 124, 255, 0.15);
  color: var(--accent);
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
}

.search-result-info h4 {
  font-size: 15px;
  font-weight: 600;
  margin-bottom: 6px;
  color: var(--text-primary);
}

.search-result-info p {
  font-size: 13px;
  color: var(--text-secondary);
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  line-height: 1.4;
  margin-bottom: 6px;
}

.search-result-price {
  font-size: 14px;
  font-weight: 700;
  color: var(--accent);
  margin-top: 6px;
}

.search-result-arrow {
  color: var(--text-secondary);
  font-size: 16px;
  opacity: 0;
  transform: translateX(-10px);
  transition: all 0.3s ease;
}

.search-result-item:hover .search-result-arrow {
  opacity: 1;
  transform: translateX(0);
}

.no-results {
  text-align: center;
  padding: 40px 20px;
}

.no-results i {
  font-size: 48px;
  color: var(--text-secondary);
  margin-bottom: 16px;
}

.no-results p {
  color: var(--text-secondary);
  font-size: 15px;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
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

.card {
  animation: fadeInUp 0.5s ease backwards;
}

.card:nth-child(1) { animation-delay: 0.05s; }
.card:nth-child(2) { animation-delay: 0.1s; }
.card:nth-child(3) { animation-delay: 0.15s; }
.card:nth-child(4) { animation-delay: 0.2s; }
.card:nth-child(5) { animation-delay: 0.25s; }
.card:nth-child(6) { animation-delay: 0.3s; }

/* RESPONSIVE */
@media (max-width: 1024px) {
  .grid {
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 16px;
  }
}

@media (max-width: 768px) {
  header {
    padding: 14px 20px;
    flex-wrap: wrap;
  }

  .header-center {
    order: 3;
    width: 100%;
    max-width: 100%;
  }

  .tabs-wrapper {
    padding: 0 20px;
    top: 120px;
  }

  .swiper-section,
  .section {
    padding: 0 20px;
  }

  .swiper-wrapper {
    height: 260px;
  }

  .slide-caption {
    bottom: 28px;
    left: 28px;
    right: 28px;
  }

  .slide-caption h3 {
    font-size: 22px;
  }

  .slide-caption p {
    font-size: 13px;
  }

  .swiper-navigation,
  .swiper-pagination {
    bottom: 24px;
  }

  .swiper-navigation {
    right: 28px;
  }

  .swiper-pagination {
    left: 28px;
  }

  .grid {
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 14px;
  }

  .card-image {
    height: 140px;
  }
}

@media (max-width: 480px) {
  .grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }

  .card-image {
    height: 120px;
  }

  .card-body {
    padding: 12px;
  }
}

/* FOOTER */
.site-footer {
  background: var(--bg-secondary);
  border-top: 1px solid var(--border);
  margin-top: 60px;
  padding: 40px 20px 20px;
}

.footer-container {
  max-width: 1400px;
  margin: auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 32px;
}

.footer-brand p {
  font-size: 14px;
  color: var(--text-secondary);
  line-height: 1.6;
}

.footer-logo {
  width: 60px;
  margin-bottom: 12px;
}

.footer-section h4 {
  font-size: 15px;
  margin-bottom: 12px;
}

.footer-link {
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--text-secondary);
  text-decoration: none;
  margin-bottom: 8px;
  font-size: 14px;
  transition: 0.3s;
}

.footer-link:hover {
  color: var(--accent);
}

.footer-link.wa {
  font-weight: 600;
}

.qris-logo {
  width: 120px;
  margin-top: 8px;
}

.footer-bottom {
  text-align: center;
  margin-top: 32px;
  font-size: 13px;
  color: var(--text-secondary);
}

/* POPUP */
.popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  padding: 20px;
}

.popup-content {
  background: var(--bg-secondary);
  border-radius: 16px;
  max-width: 420px;
  width: 100%;
  padding: 24px;
  text-align: center;
  position: relative;
  animation: fadeInUp 0.4s ease;
}

.popup-img {
  width: 100%;
  border-radius: 12px;
  margin-bottom: 16px;
}

.popup-content h3 {
  margin-bottom: 10px;
}

.popup-content p {
  font-size: 14px;
  color: var(--text-secondary);
  margin-bottom: 16px;
}

.popup-btn {
  display: inline-block;
  padding: 12px 24px;
  background: var(--accent);
  color: white;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
}

.popup-close {
  position: absolute;
  top: 12px;
  right: 12px;
  background: transparent;
  border: none;
  color: var(--text-secondary);
  font-size: 18px;
  cursor: pointer;
}


</style>
</head>

<body>

<!-- SEARCH MODAL -->
<div class="search-modal" id="searchModal">
  <div class="search-modal-overlay" id="searchOverlay"></div>
  <div class="search-modal-content">
    <div class="search-modal-header">
      <h2>Cari</h2>
      <button class="close-modal" id="closeModal">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    
    <div class="search-modal-input-wrapper">
      <i class="fa fa-search"></i>
      <input type="text" id="searchInput" placeholder="Cari produk, voucher, atau layanan" autofocus>
    </div>

    <div class="search-results" id="searchResults">
      <div class="search-empty">
        <div class="search-icon-large">
          <i class="fa fa-search"></i>
        </div>
        <p>Mulai mengetik untuk mencari...</p>
      </div>
    </div>
  </div>
</div>

<header>
  <a href="dashboard.php" class="logo">
    <img src="../uploads/logo.png" alt="Gudang Barokah" style="width: 40px; height: 40px; object-fit: contain;">
    <span>Gudang Barokah</span>
  </a>

  <div class="header-center">
    <div class="search-box" id="searchTrigger">
      <i class="fa fa-search search-icon"></i>
      <input type="text" readonly placeholder="Cari produk & transaksi...">
      <span class="search-shortcut">⌘ K</span>
    </div>
  </div>

  <div class="header-actions">
    <button class="theme-toggle" id="themeToggle">
      <i class="fa-solid fa-moon" id="themeIcon"></i>
    </button>
    
    <a href="../cek_resi.php" title="Cek Resi">
    <div class="user-avatar">
      <img src="../uploads/cekresi.jpg" alt="User">
    </div>
  </a>

  </div>
</header>

<!-- TABS -->
<div class="tabs-wrapper">
  <div class="tabs-container">
    <button class="scroll-btn left" id="scrollLeft">
      <i class="fa-solid fa-chevron-left"></i>
    </button>
    
    <div class="tabs-scroll" id="tabsScroll">
      <div class="tabs">
        <a href="dashboard.php" class="<?= !$kategori_aktif ? 'active' : '' ?>">Semua</a>
        <?php 
        mysqli_data_seek($kategori_q, 0);
        while($k = mysqli_fetch_assoc($kategori_q)): 
        ?>
          <a href="?kategori=<?= urlencode($k['kategori']) ?>"
             class="<?= $kategori_aktif == $k['kategori'] ? 'active' : '' ?>">
             <?= $k['kategori'] ?>
          </a>
        <?php endwhile ?>
      </div>
    </div>
    
    <button class="scroll-btn right" id="scrollRight">
      <i class="fa-solid fa-chevron-right"></i>
    </button>
  </div>
</div>

<!-- SWIPER BANNER -->
<div class="swiper-section">
  <div class="swiper-container">
    <div class="swiper-wrapper">
      <div class="swiper-slide active">
        <img src="https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=1600&h=600&fit=crop" alt="Promo">
        <div class="slide-overlay"></div>
        <div class="slide-caption">
          <h3>🎮 Promo Spesial Mobile Legends</h3>
          <p>Dapatkan diskon hingga 50% untuk semua diamond! Proses otomatis dan aman.</p>
        </div>
      </div>

      <div class="swiper-slide">
        <img src="https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=1600&h=600&fit=crop" alt="Free Fire">
        <div class="slide-overlay"></div>
        <div class="slide-caption">
          <h3>🔥 Top Up Free Fire Termurah</h3>
          <p>Harga terbaik se-Indonesia! Gratis bonus setiap pembelian 1000 diamond.</p>
        </div>
      </div>

      <div class="swiper-slide">
        <img src="https://images.unsplash.com/photo-1560419015-7c427e8ae5ba?w=1600&h=600&fit=crop" alt="PUBG">
        <div class="slide-overlay"></div>
        <div class="slide-caption">
          <h3>⚡ PUBG Mobile UC Murah</h3>
          <p>Koleksi skin eksklusif menanti! Top up sekarang dan menangkan hadiah menarik.</p>
        </div>
      </div>

      <div class="swiper-slide">
        <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?w=1600&h=600&fit=crop" alt="Genshin">
        <div class="slide-overlay"></div>
        <div class="slide-caption">
          <h3>💎 Genshin Impact Genesis Crystal</h3>
          <p>Dapatkan karakter favorit dengan harga spesial! Transaksi 100% aman.</p>
        </div>
      </div>

      <div class="swiper-slide">
        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1600&h=600&fit=crop" alt="Support">
        <div class="slide-overlay"></div>
        <div class="slide-caption">
          <h3>🎯 Customer Service 24/7</h3>
          <p>Tim kami siap membantu kamu kapan saja. Chat langsung untuk bantuan cepat!</p>
        </div>
      </div>
    </div>

    <div class="swiper-pagination"></div>

    <div class="swiper-navigation">
      <button class="swiper-button swiper-button-prev">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button class="swiper-button swiper-button-next">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
    </div>
  </div>
</div>

<!-- PRODUK -->
<div class="section">
  <div class="section-header">
    <div class="section-icon">
      <i class="fa-solid fa-gamepad"></i>
    </div>
    <h2><?= $kategori_aktif ? $kategori_aktif : 'Semua Produk' ?></h2>
  </div>

  <div class="grid" id="productGrid">
    <?php 
    mysqli_data_seek($games, 0);
    while($g = mysqli_fetch_assoc($games)): 
    $colors = ['black', 'blue', 'red', 'green', 'purple', 'yellow'];
    $randomColor = $colors[array_rand($colors)];
    ?>
    <div class="card" data-name="<?= strtolower($g['nama_game']) ?>">
      <div class="card-image <?= $randomColor ?>">
        <img src="../uploads/<?= $g['gambar'] ?>" alt="<?= $g['nama_game'] ?>">
        <?php if($g['is_new']): ?>
          <div class="badge">NEW</div>
        <?php endif ?>
        <div class="status-badge">Tersedia</div>
      </div>
      <div class="card-body">
        <div class="card-category"><?= $g['kategori'] ?? 'Game' ?></div>
        <h3><?= $g['nama_game'] ?></h3>
        <p><?= $g['deskripsi'] ?></p>
        <div class="price">
          <?= $g['harga_terendah'] 
            ? "Rp " . number_format($g['harga_terendah'], 0, ',', '.') 
            : ""; ?>
        </div>
        <a href="order.php?game_id=<?= $g['id'] ?>" class="btn">
          Pilih Produk
        </a>
      </div>
    </div>
    <?php endwhile ?>
  </div>

  <div class="load-more">
    <button class="load-more-btn">
      <span>Tampilkan lainnya...</span>
      <i class="fa-solid fa-chevron-down"></i>
    </button>
  </div>
</div>

<script>
// Theme Toggle
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const html = document.documentElement;

// Load saved theme
const savedTheme = localStorage.getItem('theme') || 'dark';
html.setAttribute('data-theme', savedTheme);
updateThemeIcon(savedTheme);

themeToggle.addEventListener('click', () => {
  const currentTheme = html.getAttribute('data-theme');
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  
  html.setAttribute('data-theme', newTheme);
  localStorage.setItem('theme', newTheme);
  updateThemeIcon(newTheme);
});

function updateThemeIcon(theme) {
  if (theme === 'dark') {
    themeIcon.className = 'fa-solid fa-moon';
  } else {
    themeIcon.className = 'fa-solid fa-sun';
  }
}

// Tabs Scroll
const tabsScroll = document.getElementById('tabsScroll');
const scrollLeftBtn = document.getElementById('scrollLeft');
const scrollRightBtn = document.getElementById('scrollRight');

function updateScrollButtons() {
  const scrollLeft = tabsScroll.scrollLeft;
  const maxScroll = tabsScroll.scrollWidth - tabsScroll.clientWidth;
  
  if (maxScroll > 0) {
    scrollLeftBtn.classList.toggle('visible', scrollLeft > 0);
    scrollRightBtn.classList.toggle('visible', scrollLeft < maxScroll - 1);
  }
}

scrollLeftBtn.addEventListener('click', () => {
  tabsScroll.scrollBy({ left: -200, behavior: 'smooth' });
});

scrollRightBtn.addEventListener('click', () => {
  tabsScroll.scrollBy({ left: 200, behavior: 'smooth' });
});

tabsScroll.addEventListener('scroll', updateScrollButtons);
window.addEventListener('resize', updateScrollButtons);
updateScrollButtons();

// Search Modal
const searchModal = document.getElementById('searchModal');
const searchTrigger = document.getElementById('searchTrigger');
const closeModal = document.getElementById('closeModal');
const searchOverlay = document.getElementById('searchOverlay');
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
const cards = document.querySelectorAll('.card');

// Open modal
searchTrigger.addEventListener('click', () => {
  searchModal.classList.add('active');
  document.body.style.overflow = 'hidden';
  setTimeout(() => searchInput.focus(), 100);
});

// Close modal
function closeSearchModal() {
  searchModal.classList.remove('active');
  document.body.style.overflow = '';
  searchInput.value = '';
  showEmptyState();
}

closeModal.addEventListener('click', closeSearchModal);
searchOverlay.addEventListener('click', closeSearchModal);

// Keyboard shortcut (Cmd/Ctrl + K)
document.addEventListener('keydown', (e) => {
  if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
    e.preventDefault();
    searchModal.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(() => searchInput.focus(), 100);
  }
  
  if (e.key === 'Escape' && searchModal.classList.contains('active')) {
    closeSearchModal();
  }
});

// Show empty state
function showEmptyState() {
  searchResults.innerHTML = `
    <div class="search-empty">
      <div class="search-icon-large">
        <i class="fa fa-search"></i>
      </div>
      <p>Mulai mengetik untuk mencari...</p>
    </div>
  `;
}

// Show no results
function showNoResults() {
  searchResults.innerHTML = `
    <div class="no-results">
      <i class="fa-solid fa-magnifying-glass"></i>
      <p>Tidak ada hasil yang ditemukan</p>
    </div>
  `;
}

// Search functionality
searchInput.addEventListener('input', () => {
  const query = searchInput.value.toLowerCase().trim();
  
  if (!query) {
    showEmptyState();
    return;
  }
  
  // Get all products from cards
  const results = [];
  cards.forEach(card => {
    const name = card.dataset.name;
    const title = card.querySelector('h3').textContent;
    const desc = card.querySelector('p').textContent;
    
    // Search in name, title, and description
    if (name.includes(query) || title.toLowerCase().includes(query) || desc.toLowerCase().includes(query)) {
      const img = card.querySelector('img').src;
      const category = card.querySelector('.card-category') ? card.querySelector('.card-category').textContent : 'Produk';
      const price = card.querySelector('.price') ? card.querySelector('.price').textContent : '';
      const link = card.querySelector('a.btn').href;
      const badge = card.querySelector('.badge') ? card.querySelector('.badge').textContent : '';
      const statusBadge = card.querySelector('.status-badge') ? card.querySelector('.status-badge').textContent : '';
      
      results.push({ img, title, desc, link, category, price, badge, statusBadge });
    }
  });
  
  if (results.length === 0) {
    showNoResults();
    return;
  }
  
  // Display results
  searchResults.innerHTML = results.map(item => `
    <a href="${item.link}" class="search-result-item">
      <div class="search-result-image-wrapper">
        <img src="${item.img}" alt="${item.title}" class="search-result-img">
        ${item.badge ? `<span class="search-result-badge">${item.badge}</span>` : ''}
      </div>
      <div class="search-result-info">
        <div class="search-result-category">${item.category}</div>
        <h4>${item.title}</h4>
        <p>${item.desc}</p>
        ${item.price ? `<div class="search-result-price">${item.price}</div>` : ''}
      </div>
      <div class="search-result-arrow">
        <i class="fa-solid fa-arrow-right"></i>
      </div>
    </a>
  `).join('');
});

// Swiper
class ImageSwiper {
  constructor(container) {
    this.container = container;
    this.slides = container.querySelectorAll('.swiper-slide');
    this.currentIndex = 0;
    this.isAnimating = false;
    this.autoplayInterval = null;
    this.autoplayDelay = 5000;

    this.init();
  }

  init() {
    this.createPagination();
    this.attachEventListeners();
    this.startAutoplay();
  }

  createPagination() {
    const pagination = this.container.querySelector('.swiper-pagination');
    this.slides.forEach((_, index) => {
      const dot = document.createElement('div');
      dot.classList.add('pagination-dot');
      if (index === 0) dot.classList.add('active');
      dot.addEventListener('click', () => this.goToSlide(index));
      pagination.appendChild(dot);
    });
    this.dots = pagination.querySelectorAll('.pagination-dot');
  }

  attachEventListeners() {
    const prevBtn = this.container.querySelector('.swiper-button-prev');
    const nextBtn = this.container.querySelector('.swiper-button-next');

    prevBtn.addEventListener('click', () => this.prev());
    nextBtn.addEventListener('click', () => this.next());

    let touchStartX = 0;
    let touchEndX = 0;

    this.container.addEventListener('touchstart', (e) => {
      touchStartX = e.changedTouches[0].screenX;
    });

    this.container.addEventListener('touchend', (e) => {
      touchEndX = e.changedTouches[0].screenX;
      this.handleSwipe(touchStartX, touchEndX);
    });

    this.container.addEventListener('mouseenter', () => this.stopAutoplay());
    this.container.addEventListener('mouseleave', () => this.startAutoplay());
  }

  handleSwipe(startX, endX) {
    const threshold = 50;
    if (startX - endX > threshold) {
      this.next();
    } else if (endX - startX > threshold) {
      this.prev();
    }
  }

  goToSlide(index) {
    if (this.isAnimating || index === this.currentIndex) return;

    this.isAnimating = true;
    this.slides[this.currentIndex].classList.remove('active');
    this.dots[this.currentIndex].classList.remove('active');

    this.currentIndex = index;

    this.slides[this.currentIndex].classList.add('active');
    this.dots[this.currentIndex].classList.add('active');

    setTimeout(() => {
      this.isAnimating = false;
    }, 800);

    this.resetAutoplay();
  }

  next() {
    const nextIndex = (this.currentIndex + 1) % this.slides.length;
    this.goToSlide(nextIndex);
  }

  prev() {
    const prevIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
    this.goToSlide(prevIndex);
  }

  startAutoplay() {
    this.autoplayInterval = setInterval(() => {
      this.next();
    }, this.autoplayDelay);
  }

  stopAutoplay() {
    if (this.autoplayInterval) {
      clearInterval(this.autoplayInterval);
      this.autoplayInterval = null;
    }
  }

  resetAutoplay() {
    this.stopAutoplay();
    this.startAutoplay();
  }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  const swiperContainer = document.querySelector('.swiper-container');
  if (swiperContainer) {
    new ImageSwiper(swiperContainer);
  }
});
</script>

<script>
function closePopup() {
  document.getElementById('infoPopup').style.display = 'none';
}

// Auto show saat halaman load
window.addEventListener('load', () => {
  document.getElementById('infoPopup').style.display = 'flex';
});
</script>



<!-- FOOTER -->
<footer class="site-footer">
  <div class="footer-container">
    
    <!-- Brand -->
    <div class="footer-brand">
      <img src="../uploads/logo.png" alt="Gudang Barokah" class="footer-logo">
      <p>
        Kami Adalah Resseler Yang Selalu Memberikan Kualitas Terbaik Di Setiap Produknya
        dan Dengan Pelayanan CS Yang Gercep.
      </p>
    </div>

    <!-- Contact -->
    <div class="footer-section">
      <h4>Customer Service</h4>
      <a href="https://wa.me/6285888223922" target="_blank" class="footer-link wa">
        <i class="fa-brands fa-whatsapp"></i>
        0858-8822-3922
      </a>
    </div>

    <!-- Social -->
    <div class="footer-section">
      <h4>Social Media</h4>
      <a href="https://whatsapp.com/channel/0029VaZ0vor3gvWbZl2bqK35" target="_blank" class="footer-link">
        <i class="fa-brands fa-whatsapp"></i> WhatsApp Channel
      </a>
      <a href="https://www.instagram.com/raden_aja26" target="_blank" class="footer-link">
        <i class="fa-brands fa-whatsapp"></i> Owner
      </a>
      <a href="https://www.tiktok.com/@gudangbarokah_store?is_from_webapp=1&sender_device=pc" target="_blank" class="footer-link">
        <i class="fa-brands fa-tiktok"></i> TikTok
      </a>
    </div>

    <!-- Payment -->
    <div class="footer-section">
      <h4>Metode Pembayaran</h4>
      <img src="../uploads/iconQris.png" alt="QRIS" class="qris-logo">
    </div>

  </div>

  <div class="footer-bottom">
    © <?= date('Y') ?> Gudang Barokah. All rights reserved.
  </div>
</footer>


</body>
<!-- POPUP INFO -->
<div class="popup-overlay" id="infoPopup">
  <div class="popup-content">
    <button class="popup-close" onclick="closePopup()">
      <i class="fa-solid fa-xmark"></i>
    </button>

    <img src="../uploads/popup.jpg" alt="Info" class="popup-img">

    <h3>🔥 Promo Spesial Gudang Barokah</h3>
    <p>
      Jangan lewatkan promo terbaru kami!
      Klik tombol di bawah untuk info selengkapnya.
    </p>

    <a href="https://wa.me/6285888223922" target="_blank" class="popup-btn">
      Hubungi CS Sekarang
    </a>
  </div>
</div>

</html>