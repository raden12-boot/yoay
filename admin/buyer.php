<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Produk & Penjualan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --sidebar-width: 250px;
            --header-height: 60px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: var(--dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            transition: transform 0.3s ease;
            z-index: 100;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-header i {
            font-size: 24px;
            color: var(--primary);
        }

        .sidebar-header h2 {
            font-size: 1.3rem;
            color: var(--primary);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(67, 97, 238, 0.1);
            border-left-color: var(--primary);
            color: var(--primary);
        }

        .menu-item i {
            margin-right: 15px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
        }

        .header {
            height: var(--header-height);
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
        }

        .content {
            padding: 30px;
        }

        /* Card Styles */
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--dark);
        }

        /* Button Styles */
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-warning {
            background-color: var(--warning);
            color: white;
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background-color: rgba(67, 97, 238, 0.1);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
        }

        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .form-col {
            flex: 1;
            min-width: 200px;
        }

        /* Checkbox Styles */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .checkbox-group input {
            width: 18px;
            height: 18px;
        }

        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .table th {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e9ecef;
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .table tr:hover {
            background-color: rgba(67, 97, 238, 0.03);
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .table-actions .btn {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        /* Variant Table */
        .variant-section {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            display: none;
        }

        .variant-section.show {
            display: block;
        }

        .variant-table .table th, .variant-table .table td {
            padding: 10px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray);
        }

        .empty-state i {
            font-size: 60px;
            margin-bottom: 20px;
            color: #ddd;
        }

        .empty-state p {
            font-size: 1.1rem;
        }

        /* Badge */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge-primary {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .badge-success {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }

        .badge-warning {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning);
        }

        /* Kategori Grid */
        .kategori-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .kategori-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kategori-info h3 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .kategori-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }

        /* Export Button */
        .export-btn {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        /* Dashboard Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            text-align: center;
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .stat-card-primary .stat-icon { color: var(--primary); }
        .stat-card-success .stat-icon { color: var(--success); }
        .stat-card-warning .stat-icon { color: var(--warning); }
        .stat-card-danger .stat-icon { color: var(--danger); }
        .stat-card-secondary .stat-icon { color: var(--secondary); }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .form-row {
                flex-direction: column;
            }
            
            .form-col {
                min-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px 15px;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .table-actions {
                flex-direction: column;
                gap: 5px;
            }
            
            .kategori-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .header {
                padding: 0 15px;
            }
            
            .card {
                padding: 20px 15px;
            }
            
            .btn {
                padding: 8px 15px;
            }
            
            .kategori-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Page Styles */
        .page {
            display: none;
        }
        
        .page.active {
            display: block;
        }

        /* Tabs for Penjualan */
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .tab {
            padding: 12px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            transition: all 0.3s;
        }

        .tab:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        .tab.active {
            border-bottom-color: var(--primary);
            color: var(--primary);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Date Range Filter */
        .date-filter {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .date-filter label {
            font-weight: 500;
        }

        .date-filter input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .date-filter button {
            padding: 8px 15px;
        }
        
        /* Number Input Styling */
        input[type="number"] {
            -moz-appearance: textfield;
        }
        
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-store"></i>
            <h2>Manajemen Bisnis</h2>
        </div>
        <div class="sidebar-menu">
            <a href="#" class="menu-item active" data-page="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="menu-item" data-page="kategori">
                <i class="fas fa-tags"></i>
                <span>Kategori Produk</span>
            </a>
            <a href="#" class="menu-item" data-page="produk">
                <i class="fas fa-box"></i>
                <span>Semua Produk</span>
            </a>
            <a href="#" class="menu-item" data-page="penjualan">
                <i class="fas fa-cash-register"></i>
                <span>Transaksi Penjualan</span>
            </a>
            <a href="#" class="menu-item" data-page="mutasi">
                <i class="fas fa-exchange-alt"></i>
                <span>Mutasi Modal</span>
            </a>
            <div id="kategori-menu"></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
            <div class="header-title" id="page-title">Dashboard</div>
            <div>
                <button class="btn btn-outline" id="export-excel">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
            </div>
        </div>

        <div class="content">
            <!-- Dashboard Page -->
            <div class="page active" id="dashboard">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Dashboard Overview</h2>
                        <div class="date-filter">
                            <label>Periode:</label>
                            <input type="date" id="start-date">
                            <span>s/d</span>
                            <input type="date" id="end-date">
                            <button class="btn btn-primary btn-sm" id="apply-filter">Terapkan</button>
                            <button class="btn btn-outline btn-sm" id="reset-filter">Reset</button>
                        </div>
                    </div>
                    
                    <!-- Stats Grid -->
                    <div class="stats-grid">
                        <div class="stat-card stat-card-primary">
                            <div class="stat-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div class="stat-value" id="total-kategori">0</div>
                            <div class="stat-label">Total Kategori</div>
                        </div>
                        
                        <div class="stat-card stat-card-success">
                            <div class="stat-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="stat-value" id="total-produk">0</div>
                            <div class="stat-label">Total Produk</div>
                        </div>
                        
                        <div class="stat-card stat-card-warning">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-value" id="total-keuntungan-potensial">Rp 0</div>
                            <div class="stat-label">Keuntungan Potensial</div>
                        </div>
                        
                        <div class="stat-card stat-card-danger">
                            <div class="stat-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-value" id="total-penjualan">0</div>
                            <div class="stat-label">Total Penjualan</div>
                        </div>
                        
                        <div class="stat-card stat-card-secondary">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-value" id="total-pemasukan">Rp 0</div>
                            <div class="stat-label">Total Pemasukan</div>
                        </div>
                        
                        <div class="stat-card stat-card-primary">
                            <div class="stat-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="stat-value" id="total-pengeluaran">Rp 0</div>
                            <div class="stat-label">Total Pengeluaran</div>
                        </div>
                        
                        <div class="stat-card stat-card-success">
                            <div class="stat-icon">
                                <i class="fas fa-coins"></i>
                            </div>
                            <div class="stat-value" id="total-profit">Rp 0</div>
                            <div class="stat-label">Total Profit</div>
                        </div>
                        
                        <div class="stat-card stat-card-warning">
                            <div class="stat-icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="stat-value" id="total-mutasi">Rp 0</div>
                            <div class="stat-label">Total Mutasi Modal</div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title">Produk dengan Keuntungan Tertinggi</h2>
                                </div>
                                <div class="table-responsive">
                                    <table class="table" id="top-products">
                                        <thead>
                                            <tr>
                                                <th>Nama Produk</th>
                                                <th>Kategori</th>
                                                <th>Keuntungan/Unit</th>
                                                <th>Stok (Potensial)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="empty-state">
                                                    <i class="fas fa-box-open"></i>
                                                    <p>Belum ada produk</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-col">
                            <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title">Transaksi Terbaru</h2>
                                </div>
                                <div class="table-responsive">
                                    <table class="table" id="recent-transactions">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Produk</th>
                                                <th>Jual</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="empty-state">
                                                    <i class="fas fa-receipt"></i>
                                                    <p>Belum ada transaksi</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kategori Page -->
            <div class="page" id="kategori">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Kelola Kategori Produk</h2>
                        <button class="btn btn-primary" id="tambah-kategori-btn">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </button>
                    </div>
                    <div class="kategori-grid" id="kategori-list">
                        <!-- Kategori akan dimuat di sini -->
                        <div class="empty-state">
                            <i class="fas fa-tags"></i>
                            <p>Belum ada kategori</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk Page -->
            <div class="page" id="produk">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Kelola Semua Produk</h2>
                        <button class="btn btn-primary" id="tambah-produk-btn">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="produk-table">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Variant</th>
                                    <th>Harga Awal</th>
                                    <th>Harga Jual</th>
                                    <th>Keuntungan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="produk-list">
                                <tr>
                                    <td colspan="7" class="empty-state">
                                        <i class="fas fa-box-open"></i>
                                        <p>Belum ada produk</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Penjualan Page -->
            <div class="page" id="penjualan">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Transaksi Penjualan</h2>
                        <button class="btn btn-primary" id="tambah-penjualan-btn">
                            <i class="fas fa-plus"></i> Tambah Transaksi
                        </button>
                    </div>
                    
                    <!-- Tabs for Penjualan -->
                    <div class="tabs">
                        <div class="tab active" data-tab="transaksi">Transaksi Jualan</div>
                        <div class="tab" data-tab="rekap">Rekap Penjualan</div>
                    </div>
                    
                    <!-- Transaksi Tab -->
                    <div class="tab-content active" id="transaksi-tab">
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="penjualan-date">Tanggal</label>
                                    <input type="date" class="form-control" id="penjualan-date" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="penjualan-produk">Produk</label>
                                    <select class="form-control" id="penjualan-produk">
                                        <option value="">Pilih Produk</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="penjualan-qty">Jumlah</label>
                                    <input type="number" class="form-control" id="penjualan-qty" value="1" min="1">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="penjualan-harga-jual">Harga Jual per Unit</label>
                                    <input type="number" class="form-control" id="penjualan-harga-jual" min="0" step="1">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="penjualan-harga-modal">Harga Modal per Unit</label>
                                    <input type="number" class="form-control" id="penjualan-harga-modal" min="0" step="1">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="penjualan-pembayaran">Metode Pembayaran</label>
                                    <select class="form-control" id="penjualan-pembayaran">
                                        <option value="Cash">Cash</option>
                                        <option value="QRIS">QRIS</option>
                                        <option value="Transfer">Transfer Bank</option>
                                        <option value="E-Wallet">E-Wallet</option>
                                        <option value="Kredit">Kredit</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="penjualan-catatan">Catatan (Opsional)</label>
                                    <input type="text" class="form-control" id="penjualan-catatan" placeholder="Catatan tambahan">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label">Total</label>
                                    <div style="padding: 12px 0;">
                                        <p><strong>Total Jual:</strong> <span id="total-jual-display">Rp 0</span></p>
                                        <p><strong>Total Modal:</strong> <span id="total-modal-display">Rp 0</span></p>
                                        <p><strong>Total Profit:</strong> <span id="total-profit-display">Rp 0</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="text-align: right;">
                            <button class="btn btn-outline" id="reset-penjualan">Reset</button>
                            <button class="btn btn-primary" id="simpan-penjualan">Simpan Transaksi</button>
                        </div>
                        
                        <div class="table-responsive" style="margin-top: 30px;">
                            <table class="table" id="transaksi-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Qty</th>
                                        <th>Jual</th>
                                        <th>Modal</th>
                                        <th>Profit</th>
                                        <th>Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="transaksi-list">
                                    <tr>
                                        <td colspan="8" class="empty-state">
                                            <i class="fas fa-receipt"></i>
                                            <p>Belum ada transaksi</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Rekap Tab -->
                    <div class="tab-content" id="rekap-tab">
                        <div class="date-filter" style="margin-bottom: 30px;">
                            <label>Rekap Periode:</label>
                            <input type="date" id="rekap-start-date">
                            <span>s/d</span>
                            <input type="date" id="rekap-end-date">
                            <button class="btn btn-primary btn-sm" id="apply-rekap-filter">Terapkan</button>
                            <button class="btn btn-outline btn-sm" id="reset-rekap-filter">30 Hari Terakhir</button>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="card" style="text-align: center;">
                                    <div class="stat-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="stat-value" id="rekap-pemasukan">Rp 0</div>
                                    <div class="stat-label">Total Pemasukan</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="card" style="text-align: center;">
                                    <div class="stat-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="stat-value" id="rekap-pengeluaran">Rp 0</div>
                                    <div class="stat-label">Total Pengeluaran</div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="card" style="text-align: center;">
                                    <div class="stat-icon">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <div class="stat-value" id="rekap-profit">Rp 0</div>
                                    <div class="stat-label">Total Profit</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive" style="margin-top: 30px;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Metode Pembayaran</th>
                                        <th>Jumlah Transaksi</th>
                                        <th>Total Pemasukan</th>
                                        <th>Persentase</th>
                                    </tr>
                                </thead>
                                <tbody id="rekap-pembayaran">
                                    <tr>
                                        <td colspan="4" class="empty-state">
                                            <i class="fas fa-chart-pie"></i>
                                            <p>Belum ada data untuk direkap</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mutasi Page -->
            <div class="page" id="mutasi">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Mutasi Modal</h2>
                        <button class="btn btn-primary" id="tambah-mutasi-btn">
                            <i class="fas fa-plus"></i> Tambah Mutasi
                        </button>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="mutasi-date">Tanggal</label>
                                <input type="date" class="form-control" id="mutasi-date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="mutasi-jumlah">Jumlah</label>
                                <input type="number" class="form-control" id="mutasi-jumlah" min="0" step="1" placeholder="Jumlah uang">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="mutasi-tipe">Tipe Mutasi</label>
                                <select class="form-control" id="mutasi-tipe">
                                    <option value="masuk">Cash → Aplikasi</option>
                                    <option value="keluar">Aplikasi → Cash</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="mutasi-catatan">Catatan</label>
                        <input type="text" class="form-control" id="mutasi-catatan" placeholder="VIPayment, Tarik Tunai, Topup, dll">
                    </div>
                    
                    <div class="form-group" style="text-align: right;">
                        <button class="btn btn-outline" id="reset-mutasi">Reset</button>
                        <button class="btn btn-primary" id="simpan-mutasi">Simpan Mutasi</button>
                    </div>
                    
                    <div class="table-responsive" style="margin-top: 30px;">
                        <table class="table" id="mutasi-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Tipe</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="mutasi-list">
                                <tr>
                                    <td colspan="5" class="empty-state">
                                        <i class="fas fa-exchange-alt"></i>
                                        <p>Belum ada mutasi modal</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Halaman Kategori Spesifik -->
            <div class="page" id="kategori-detail">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title" id="kategori-detail-title">Kategori</h2>
                        <button class="btn btn-primary" id="tambah-produk-kategori-btn">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="produk-kategori-table">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Variant</th>
                                    <th>Harga Awal</th>
                                    <th>Harga Jual</th>
                                    <th>Keuntungan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="produk-kategori-list">
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <i class="fas fa-box-open"></i>
                                        <p>Belum ada produk dalam kategori ini</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div class="modal" id="kategori-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="card" style="width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto;">
            <div class="card-header">
                <h2 class="card-title" id="kategori-modal-title">Tambah Kategori</h2>
                <button class="btn btn-outline" id="close-kategori-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="kategori-form">
                <div class="form-group">
                    <label class="form-label" for="nama-kategori">Nama Kategori</label>
                    <input type="text" class="form-control" id="nama-kategori" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="deskripsi-kategori">Deskripsi (Opsional)</label>
                    <textarea class="form-control" id="deskripsi-kategori" rows="3"></textarea>
                </div>
                <div class="form-group" style="text-align: right;">
                    <button type="button" class="btn btn-outline" id="cancel-kategori">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal" id="produk-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="card" style="width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;">
            <div class="card-header">
                <h2 class="card-title" id="produk-modal-title">Tambah Produk</h2>
                <button class="btn btn-outline" id="close-produk-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="produk-form">
                <input type="hidden" id="produk-id">
                <input type="hidden" id="kategori-id-target">
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label" for="nama-produk">Nama Produk</label>
                            <input type="text" class="form-control" id="nama-produk" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label" for="kategori-produk">Kategori Produk</label>
                            <select class="form-control" id="kategori-produk" required>
                                <option value="">Pilih Kategori</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="punya-variant">
                    <label for="punya-variant">Produk ini mempunyai variant</label>
                </div>
                
                <!-- Variant Section -->
                <div class="variant-section" id="variant-section">
                    <h3 style="margin-bottom: 15px;">Variant Produk</h3>
                    <div class="table-responsive variant-table">
                        <table class="table" id="variant-table">
                            <thead>
                                <tr>
                                    <th>Nama Variant</th>
                                    <th>Harga Awal</th>
                                    <th>Harga Jual</th>
                                    <th>Keuntungan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="variant-list">
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 20px;">
                                        <button type="button" class="btn btn-outline" id="tambah-variant">
                                            <i class="fas fa-plus"></i> Tambah Variant
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Jika tidak ada variant -->
                <div id="harga-tunggal-section">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="harga-awal">Harga Awal</label>
                                <input type="number" class="form-control" id="harga-awal" min="0" step="1">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="harga-jual">Harga Jual</label>
                                <input type="number" class="form-control" id="harga-jual" min="0" step="1">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="keuntungan">Keuntungan</label>
                                <input type="text" class="form-control" id="keuntungan" readonly style="background-color: #f8f9fa;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group" style="text-align: right;">
                    <button type="button" class="btn btn-outline" id="cancel-produk">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        // Data Models
        let kategoriData = JSON.parse(localStorage.getItem('kategoriData')) || [];
        let produkData = JSON.parse(localStorage.getItem('produkData')) || [];
        let transaksiData = JSON.parse(localStorage.getItem('transaksiData')) || [];
        let mutasiData = JSON.parse(localStorage.getItem('mutasiData')) || [];
        
        // DOM Elements
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const sidebar = document.querySelector('.sidebar');
        const menuItems = document.querySelectorAll('.menu-item');
        const pages = document.querySelectorAll('.page');
        const pageTitle = document.getElementById('page-title');
        const kategoriMenu = document.getElementById('kategori-menu');
        
        // Dashboard Elements
        const totalKategoriEl = document.getElementById('total-kategori');
        const totalProdukEl = document.getElementById('total-produk');
        const totalKeuntunganPotensialEl = document.getElementById('total-keuntungan-potensial');
        const totalPenjualanEl = document.getElementById('total-penjualan');
        const totalPemasukanEl = document.getElementById('total-pemasukan');
        const totalPengeluaranEl = document.getElementById('total-pengeluaran');
        const totalProfitEl = document.getElementById('total-profit');
        const totalMutasiEl = document.getElementById('total-mutasi');
        
        const startDateFilter = document.getElementById('start-date');
        const endDateFilter = document.getElementById('end-date');
        const applyFilterBtn = document.getElementById('apply-filter');
        const resetFilterBtn = document.getElementById('reset-filter');
        
        const topProductsTable = document.getElementById('top-products').querySelector('tbody');
        const recentTransactionsTable = document.getElementById('recent-transactions').querySelector('tbody');
        
        // Kategori Page Elements
        const kategoriList = document.getElementById('kategori-list');
        const tambahKategoriBtn = document.getElementById('tambah-kategori-btn');
        const kategoriModal = document.getElementById('kategori-modal');
        const closeKategoriModal = document.getElementById('close-kategori-modal');
        const cancelKategori = document.getElementById('cancel-kategori');
        const kategoriForm = document.getElementById('kategori-form');
        const kategoriModalTitle = document.getElementById('kategori-modal-title');
        const namaKategoriInput = document.getElementById('nama-kategori');
        const deskripsiKategoriInput = document.getElementById('deskripsi-kategori');
        
        // Produk Page Elements
        const produkList = document.getElementById('produk-list');
        const tambahProdukBtn = document.getElementById('tambah-produk-btn');
        const produkModal = document.getElementById('produk-modal');
        const closeProdukModal = document.getElementById('close-produk-modal');
        const cancelProduk = document.getElementById('cancel-produk');
        const produkForm = document.getElementById('produk-form');
        const produkModalTitle = document.getElementById('produk-modal-title');
        const produkIdInput = document.getElementById('produk-id');
        const kategoriIdTarget = document.getElementById('kategori-id-target');
        const namaProdukInput = document.getElementById('nama-produk');
        const kategoriProdukSelect = document.getElementById('kategori-produk');
        const punyaVariantCheckbox = document.getElementById('punya-variant');
        const variantSection = document.getElementById('variant-section');
        const variantList = document.getElementById('variant-list');
        const tambahVariantBtn = document.getElementById('tambah-variant');
        const hargaTunggalSection = document.getElementById('harga-tunggal-section');
        const hargaAwalInput = document.getElementById('harga-awal');
        const hargaJualInput = document.getElementById('harga-jual');
        const keuntunganInput = document.getElementById('keuntungan');
        
        // Penjualan Page Elements
        const penjualanDateInput = document.getElementById('penjualan-date');
        const penjualanProdukSelect = document.getElementById('penjualan-produk');
        const penjualanQtyInput = document.getElementById('penjualan-qty');
        const penjualanHargaJualInput = document.getElementById('penjualan-harga-jual');
        const penjualanHargaModalInput = document.getElementById('penjualan-harga-modal');
        const penjualanPembayaranSelect = document.getElementById('penjualan-pembayaran');
        const penjualanCatatanInput = document.getElementById('penjualan-catatan');
        const totalJualDisplay = document.getElementById('total-jual-display');
        const totalModalDisplay = document.getElementById('total-modal-display');
        const totalProfitDisplay = document.getElementById('total-profit-display');
        const resetPenjualanBtn = document.getElementById('reset-penjualan');
        const simpanPenjualanBtn = document.getElementById('simpan-penjualan');
        const transaksiList = document.getElementById('transaksi-list');
        
        // Tabs for Penjualan
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');
        
        // Rekap Elements
        const rekapStartDate = document.getElementById('rekap-start-date');
        const rekapEndDate = document.getElementById('rekap-end-date');
        const applyRekapFilterBtn = document.getElementById('apply-rekap-filter');
        const resetRekapFilterBtn = document.getElementById('reset-rekap-filter');
        const rekapPemasukanEl = document.getElementById('rekap-pemasukan');
        const rekapPengeluaranEl = document.getElementById('rekap-pengeluaran');
        const rekapProfitEl = document.getElementById('rekap-profit');
        const rekapPembayaranTable = document.getElementById('rekap-pembayaran');
        
        // Mutasi Page Elements
        const mutasiDateInput = document.getElementById('mutasi-date');
        const mutasiJumlahInput = document.getElementById('mutasi-jumlah');
        const mutasiTipeSelect = document.getElementById('mutasi-tipe');
        const mutasiCatatanInput = document.getElementById('mutasi-catatan');
        const resetMutasiBtn = document.getElementById('reset-mutasi');
        const simpanMutasiBtn = document.getElementById('simpan-mutasi');
        const mutasiList = document.getElementById('mutasi-list');
        
        // Kategori Detail Page Elements
        const kategoriDetailPage = document.getElementById('kategori-detail');
        const kategoriDetailTitle = document.getElementById('kategori-detail-title');
        const produkKategoriList = document.getElementById('produk-kategori-list');
        const tambahProdukKategoriBtn = document.getElementById('tambah-produk-kategori-btn');
        
        // Export Button
        const exportExcelBtn = document.getElementById('export-excel');
        
        // Initialize App
        document.addEventListener('DOMContentLoaded', function() {
            initApp();
        });
        
        function initApp() {
            // Set default date values
            const today = new Date();
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(today.getDate() - 30);
            
            startDateFilter.value = formatDate(thirtyDaysAgo);
            endDateFilter.value = formatDate(today);
            
            rekapStartDate.value = formatDate(thirtyDaysAgo);
            rekapEndDate.value = formatDate(today);
            
            // Set default penjualan date to today
            penjualanDateInput.value = formatDate(today);
            mutasiDateInput.value = formatDate(today);
            
            // Load data
            loadKategori();
            loadProduk();
            loadTransaksi();
            loadMutasi();
            updateDashboard();
            updateKategoriMenu();
            
            // Event Listeners
            mobileMenuBtn.addEventListener('click', toggleSidebar);
            
            // Menu navigation
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    
                    // Update active menu item
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show selected page
                    showPage(page);
                    
                    // Close sidebar on mobile
                    if (window.innerWidth < 992) {
                        sidebar.classList.remove('active');
                    }
                });
            });
            
            // Kategori events
            tambahKategoriBtn.addEventListener('click', () => openKategoriModal());
            closeKategoriModal.addEventListener('click', () => closeModal(kategoriModal));
            cancelKategori.addEventListener('click', () => closeModal(kategoriModal));
            kategoriForm.addEventListener('submit', saveKategori);
            
            // Produk events
            tambahProdukBtn.addEventListener('click', () => openProdukModal());
            tambahProdukKategoriBtn.addEventListener('click', () => openProdukModal(null, true));
            closeProdukModal.addEventListener('click', () => closeModal(produkModal));
            cancelProduk.addEventListener('click', () => closeModal(produkModal));
            produkForm.addEventListener('submit', saveProduk);
            
            // Variant events
            punyaVariantCheckbox.addEventListener('change', toggleVariantSection);
            tambahVariantBtn.addEventListener('click', addVariantRow);
            
            // Harga calculation events
            hargaAwalInput.addEventListener('input', calculateKeuntungan);
            hargaJualInput.addEventListener('input', calculateKeuntungan);
            
            // Penjualan events
            penjualanProdukSelect.addEventListener('change', updateHargaFromProduk);
            penjualanQtyInput.addEventListener('input', updateTotalPenjualan);
            penjualanHargaJualInput.addEventListener('input', updateTotalPenjualan);
            penjualanHargaModalInput.addEventListener('input', updateTotalPenjualan);
            resetPenjualanBtn.addEventListener('click', resetFormPenjualan);
            simpanPenjualanBtn.addEventListener('click', simpanTransaksi);
            
            // Tab events
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Update active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show selected tab content
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                        if (content.id === `${tabId}-tab`) {
                            content.classList.add('active');
                        }
                    });
                    
                    // Load rekap data if rekap tab is active
                    if (tabId === 'rekap') {
                        loadRekapData();
                    }
                });
            });
            
            // Rekap events
            applyRekapFilterBtn.addEventListener('click', loadRekapData);
            resetRekapFilterBtn.addEventListener('click', function() {
                const today = new Date();
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(today.getDate() - 30);
                
                rekapStartDate.value = formatDate(thirtyDaysAgo);
                rekapEndDate.value = formatDate(today);
                loadRekapData();
            });
            
            // Mutasi events
            resetMutasiBtn.addEventListener('click', resetFormMutasi);
            simpanMutasiBtn.addEventListener('click', simpanMutasi);
            
            // Dashboard filter events
            applyFilterBtn.addEventListener('click', updateDashboard);
            resetFilterBtn.addEventListener('click', function() {
                const today = new Date();
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(today.getDate() - 30);
                
                startDateFilter.value = formatDate(thirtyDaysAgo);
                endDateFilter.value = formatDate(today);
                updateDashboard();
            });
            
            // Export event
            exportExcelBtn.addEventListener('click', exportToExcel);
        }
        
        // Sidebar Functions
        function toggleSidebar() {
            sidebar.classList.toggle('active');
        }
        
        function showPage(pageName) {
            // Hide all pages
            pages.forEach(page => {
                page.classList.remove('active');
            });
            
            // Show selected page
            const selectedPage = document.getElementById(pageName);
            if (selectedPage) {
                selectedPage.classList.add('active');
                
                // Update page title
                switch(pageName) {
                    case 'dashboard':
                        pageTitle.textContent = 'Dashboard';
                        updateDashboard();
                        break;
                    case 'kategori':
                        pageTitle.textContent = 'Kategori Produk';
                        loadKategoriList();
                        break;
                    case 'produk':
                        pageTitle.textContent = 'Semua Produk';
                        loadProdukList();
                        break;
                    case 'penjualan':
                        pageTitle.textContent = 'Transaksi Penjualan';
                        loadTransaksiList();
                        loadProdukToPenjualanSelect();
                        break;
                    case 'mutasi':
                        pageTitle.textContent = 'Mutasi Modal';
                        loadMutasiList();
                        break;
                    case 'kategori-detail':
                        // Title will be set by openKategoriDetail
                        break;
                }
            }
        }
        
        // Dashboard Functions
        function updateDashboard() {
            // Get filter values
            const startDate = startDateFilter.value ? new Date(startDateFilter.value) : null;
            const endDate = endDateFilter.value ? new Date(endDateFilter.value) : null;
            
            // Update counts
            totalKategoriEl.textContent = kategoriData.length;
            totalProdukEl.textContent = produkData.length;
            
            // Calculate total keuntungan potensial from produk
            let totalKeuntunganPotensial = 0;
            produkData.forEach(produk => {
                if (produk.variant && produk.variant.length > 0) {
                    totalKeuntunganPotensial += produk.variant.reduce((sum, v) => sum + (parseFloat(v.keuntungan) || 0), 0);
                } else {
                    totalKeuntunganPotensial += parseFloat(produk.keuntungan) || 0;
                }
            });
            
            totalKeuntunganPotensialEl.textContent = `Rp ${formatNumber(totalKeuntunganPotensial)}`;
            
            // Calculate penjualan stats based on date filter
            let totalPenjualan = 0;
            let totalPemasukan = 0;
            let totalPengeluaran = 0;
            let totalProfit = 0;
            let totalMutasi = 0;
            
            // Calculate from transaksi
            transaksiData.forEach(transaksi => {
                const transaksiDate = new Date(transaksi.tanggal);
                
                // Check if transaction is within date range
                if (startDate && endDate) {
                    if (transaksiDate < startDate || transaksiDate > endDate) {
                        return;
                    }
                }
                
                totalPenjualan += parseFloat(transaksi.jumlah) || 0;
                totalPemasukan += parseFloat(transaksi.totalJual) || 0;
                totalPengeluaran += parseFloat(transaksi.totalModal) || 0;
                totalProfit += parseFloat(transaksi.totalProfit) || 0;
            });
            
            // Calculate from mutasi
            mutasiData.forEach(mutasi => {
                const mutasiDate = new Date(mutasi.tanggal);
                
                // Check if mutation is within date range
                if (startDate && endDate) {
                    if (mutasiDate < startDate || mutasiDate > endDate) {
                        return;
                    }
                }
                
                totalMutasi += parseFloat(mutasi.jumlah) || 0;
                
                // For pengeluaran, only count mutasi masuk (cash -> app) as pengeluaran
                if (mutasi.tipe === 'masuk') {
                    totalPengeluaran += parseFloat(mutasi.jumlah) || 0;
                }
            });
            
            totalPenjualanEl.textContent = totalPenjualan;
            totalPemasukanEl.textContent = `Rp ${formatNumber(totalPemasukan)}`;
            totalPengeluaranEl.textContent = `Rp ${formatNumber(totalPengeluaran)}`;
            totalProfitEl.textContent = `Rp ${formatNumber(totalProfit)}`;
            totalMutasiEl.textContent = `Rp ${formatNumber(totalMutasi)}`;
            
            // Update top products table
            updateTopProductsTable();
            
            // Update recent transactions
            updateRecentTransactions();
        }
        
        function updateTopProductsTable() {
            // Create a copy of produkData with calculated totals for sorting
            const produkWithTotals = produkData.map(produk => {
                let totalKeuntungan = 0;
                
                if (produk.variant && produk.variant.length > 0) {
                    totalKeuntungan = produk.variant.reduce((sum, v) => sum + (parseFloat(v.keuntungan) || 0), 0);
                } else {
                    totalKeuntungan = parseFloat(produk.keuntungan) || 0;
                }
                
                return {
                    ...produk,
                    totalKeuntungan
                };
            });
            
            // Sort by keuntungan (descending)
            produkWithTotals.sort((a, b) => b.totalKeuntungan - a.totalKeuntungan);
            
            // Take top 5
            const topProducts = produkWithTotals.slice(0, 5);
            
            // Update table
            if (topProducts.length === 0) {
                topProductsTable.innerHTML = `
                    <tr>
                        <td colspan="4" class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>Belum ada produk</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            topProductsTable.innerHTML = '';
            
            topProducts.forEach(produk => {
                const kategori = kategoriData.find(k => k.id === produk.kategoriId);
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${produk.nama}</td>
                    <td>${kategori ? kategori.nama : 'Tidak ada kategori'}</td>
                    <td>Rp ${formatNumber(produk.totalKeuntungan)}</td>
                    <td>${produk.variant && produk.variant.length > 0 ? produk.variant.length + ' variant' : '1'}</td>
                `;
                
                topProductsTable.appendChild(row);
            });
        }
        
        function updateRecentTransactions() {
            // Sort transactions by date (newest first)
            const sortedTransaksi = [...transaksiData].sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));
            
            // Take top 5
            const recentTransaksi = sortedTransaksi.slice(0, 5);
            
            // Update table
            if (recentTransaksi.length === 0) {
                recentTransactionsTable.innerHTML = `
                    <tr>
                        <td colspan="4" class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <p>Belum ada transaksi</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            recentTransactionsTable.innerHTML = '';
            
            recentTransaksi.forEach(transaksi => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${transaksi.tanggal}</td>
                    <td>${transaksi.namaProduk} (${transaksi.jumlah}x)</td>
                    <td>Rp ${formatNumber(transaksi.totalJual)}</td>
                    <td>Rp ${formatNumber(transaksi.totalProfit)}</td>
                `;
                
                recentTransactionsTable.appendChild(row);
            });
        }
        
        // Kategori Functions
        function loadKategori() {
            // Load kategori to select dropdowns
            kategoriProdukSelect.innerHTML = '<option value="">Pilih Kategori</option>';
            
            kategoriData.forEach(kategori => {
                const option = document.createElement('option');
                option.value = kategori.id;
                option.textContent = kategori.nama;
                kategoriProdukSelect.appendChild(option);
            });
        }
        
        function loadKategoriList() {
            if (kategoriData.length === 0) {
                kategoriList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-tags"></i>
                        <p>Belum ada kategori</p>
                    </div>
                `;
                return;
            }
            
            kategoriList.innerHTML = '';
            
            kategoriData.forEach(kategori => {
                const produkCount = produkData.filter(p => p.kategoriId === kategori.id).length;
                
                const kategoriCard = document.createElement('div');
                kategoriCard.className = 'kategori-card';
                kategoriCard.innerHTML = `
                    <div class="kategori-info">
                        <h3>${kategori.nama}</h3>
                        <p>${produkCount} produk</p>
                        ${kategori.deskripsi ? `<p style="margin-top: 5px; font-size: 0.85rem;">${kategori.deskripsi}</p>` : ''}
                    </div>
                    <div class="table-actions">
                        <button class="btn btn-outline btn-view-kategori" data-id="${kategori.id}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline btn-edit-kategori" data-id="${kategori.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline btn-delete-kategori" data-id="${kategori.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                
                kategoriList.appendChild(kategoriCard);
            });
            
            // Add event listeners to buttons
            document.querySelectorAll('.btn-view-kategori').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    openKategoriDetail(id);
                });
            });
            
            document.querySelectorAll('.btn-edit-kategori').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    openKategoriModal(id);
                });
            });
            
            document.querySelectorAll('.btn-delete-kategori').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteKategori(id);
                });
            });
        }
        
        function openKategoriModal(id = null) {
            // Reset form
            kategoriForm.reset();
            
            if (id) {
                // Edit mode
                const kategori = kategoriData.find(k => k.id === id);
                if (!kategori) return;
                
                kategoriModalTitle.textContent = 'Edit Kategori';
                namaKategoriInput.value = kategori.nama;
                deskripsiKategoriInput.value = kategori.deskripsi || '';
                
                // Set data attribute for edit mode
                kategoriForm.setAttribute('data-edit-id', id);
            } else {
                // Add mode
                kategoriModalTitle.textContent = 'Tambah Kategori';
                kategoriForm.removeAttribute('data-edit-id');
            }
            
            kategoriModal.style.display = 'flex';
        }
        
        function saveKategori(e) {
            e.preventDefault();
            
            const nama = namaKategoriInput.value.trim();
            const deskripsi = deskripsiKategoriInput.value.trim();
            const editId = kategoriForm.getAttribute('data-edit-id');
            
            if (!nama) {
                alert('Nama kategori harus diisi');
                return;
            }
            
            if (editId) {
                // Update existing kategori
                const index = kategoriData.findIndex(k => k.id === editId);
                if (index !== -1) {
                    kategoriData[index].nama = nama;
                    kategoriData[index].deskripsi = deskripsi;
                }
            } else {
                // Add new kategori
                const newKategori = {
                    id: generateId(),
                    nama: nama,
                    deskripsi: deskripsi
                };
                
                kategoriData.push(newKategori);
            }
            
            // Save to localStorage
            localStorage.setItem('kategoriData', JSON.stringify(kategoriData));
            
            // Update UI
            loadKategori();
            loadKategoriList();
            updateKategoriMenu();
            updateDashboard();
            
            // Close modal
            closeModal(kategoriModal);
        }
        
        function deleteKategori(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus kategori ini? Produk dalam kategori ini akan dihapus juga.')) {
                return;
            }
            
            // Delete produk in this kategori
            produkData = produkData.filter(p => p.kategoriId !== id);
            
            // Delete kategori
            kategoriData = kategoriData.filter(k => k.id !== id);
            
            // Save to localStorage
            localStorage.setItem('kategoriData', JSON.stringify(kategoriData));
            localStorage.setItem('produkData', JSON.stringify(produkData));
            
            // Update UI
            loadKategori();
            loadKategoriList();
            loadProdukList();
            updateKategoriMenu();
            updateDashboard();
        }
        
        function updateKategoriMenu() {
            kategoriMenu.innerHTML = '';
            
            kategoriData.forEach(kategori => {
                const produkCount = produkData.filter(p => p.kategoriId === kategori.id).length;
                
                const menuItem = document.createElement('a');
                menuItem.href = '#';
                menuItem.className = 'menu-item';
                menuItem.setAttribute('data-page', 'kategori-detail');
                menuItem.innerHTML = `
                    <i class="fas fa-folder"></i>
                    <span>${kategori.nama} (${produkCount})</span>
                `;
                
                menuItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Update active menu item
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show kategori detail page
                    openKategoriDetail(kategori.id);
                    
                    // Close sidebar on mobile
                    if (window.innerWidth < 992) {
                        sidebar.classList.remove('active');
                    }
                });
                
                kategoriMenu.appendChild(menuItem);
            });
        }
        
        function openKategoriDetail(id) {
            const kategori = kategoriData.find(k => k.id === id);
            if (!kategori) return;
            
            // Update page title
            kategoriDetailTitle.textContent = `Produk: ${kategori.nama}`;
            pageTitle.textContent = kategori.nama;
            
            // Set kategori id target for adding new produk
            kategoriIdTarget.value = id;
            
            // Load produk for this kategori
            loadProdukByKategori(id);
            
            // Show kategori detail page
            showPage('kategori-detail');
        }
        
        // Produk Functions
        function loadProduk() {
            // This function loads produk data into dropdowns, etc.
        }
        
        function loadProdukList() {
            if (produkData.length === 0) {
                produkList.innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>Belum ada produk</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            produkList.innerHTML = '';
            
            produkData.forEach(produk => {
                const kategori = kategoriData.find(k => k.id === produk.kategoriId);
                const hasVariant = produk.variant && produk.variant.length > 0;
                
                let variantText = '-';
                let hargaAwal = produk.hargaAwal || 0;
                let hargaJual = produk.hargaJual || 0;
                let keuntungan = produk.keuntungan || 0;
                
                if (hasVariant) {
                    variantText = `${produk.variant.length} variant`;
                    
                    // Calculate totals from variants
                    hargaAwal = produk.variant.reduce((sum, v) => sum + (parseFloat(v.hargaAwal) || 0), 0);
                    hargaJual = produk.variant.reduce((sum, v) => sum + (parseFloat(v.hargaJual) || 0), 0);
                    keuntungan = produk.variant.reduce((sum, v) => sum + (parseFloat(v.keuntungan) || 0), 0);
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${produk.nama}</td>
                    <td><span class="badge badge-primary">${kategori ? kategori.nama : 'Tidak ada kategori'}</span></td>
                    <td>${variantText}</td>
                    <td>Rp ${formatNumber(hargaAwal)}</td>
                    <td>Rp ${formatNumber(hargaJual)}</td>
                    <td>Rp ${formatNumber(keuntungan)}</td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-outline btn-edit-produk" data-id="${produk.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline btn-delete-produk" data-id="${produk.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                produkList.appendChild(row);
            });
            
            // Add event listeners to buttons
            document.querySelectorAll('.btn-edit-produk').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    openProdukModal(id);
                });
            });
            
            document.querySelectorAll('.btn-delete-produk').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteProduk(id);
                });
            });
        }
        
        function loadProdukByKategori(kategoriId) {
            const produkByKategori = produkData.filter(p => p.kategoriId === kategoriId);
            
            if (produkByKategori.length === 0) {
                produkKategoriList.innerHTML = `
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>Belum ada produk dalam kategori ini</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            produkKategoriList.innerHTML = '';
            
            produkByKategori.forEach(produk => {
                const hasVariant = produk.variant && produk.variant.length > 0;
                
                let variantText = '-';
                let hargaAwal = produk.hargaAwal || 0;
                let hargaJual = produk.hargaJual || 0;
                let keuntungan = produk.keuntungan || 0;
                
                if (hasVariant) {
                    variantText = `${produk.variant.length} variant`;
                    
                    // Calculate totals from variants
                    hargaAwal = produk.variant.reduce((sum, v) => sum + (parseFloat(v.hargaAwal) || 0), 0);
                    hargaJual = produk.variant.reduce((sum, v) => sum + (parseFloat(v.hargaJual) || 0), 0);
                    keuntungan = produk.variant.reduce((sum, v) => sum + (parseFloat(v.keuntungan) || 0), 0);
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${produk.nama}</td>
                    <td>${variantText}</td>
                    <td>Rp ${formatNumber(hargaAwal)}</td>
                    <td>Rp ${formatNumber(hargaJual)}</td>
                    <td>Rp ${formatNumber(keuntungan)}</td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-outline btn-edit-produk" data-id="${produk.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline btn-delete-produk" data-id="${produk.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                produkKategoriList.appendChild(row);
            });
            
            // Add event listeners to buttons
            document.querySelectorAll('.btn-edit-produk').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    openProdukModal(id, true);
                });
            });
            
            document.querySelectorAll('.btn-delete-produk').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteProduk(id);
                });
            });
        }
        
        function openProdukModal(id = null, fromKategoriPage = false) {
            // Reset form
            produkForm.reset();
            variantList.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">
                        <button type="button" class="btn btn-outline" id="tambah-variant">
                            <i class="fas fa-plus"></i> Tambah Variant
                        </button>
                    </td>
                </tr>
            `;
            
            // Reset variant section
            variantSection.classList.remove('show');
            punyaVariantCheckbox.checked = false;
            hargaTunggalSection.style.display = 'block';
            
            // Set kategori if from kategori page
            if (fromKategoriPage && kategoriIdTarget.value) {
                kategoriProdukSelect.value = kategoriIdTarget.value;
            }
            
            if (id) {
                // Edit mode
                const produk = produkData.find(p => p.id === id);
                if (!produk) return;
                
                produkModalTitle.textContent = 'Edit Produk';
                produkIdInput.value = produk.id;
                namaProdukInput.value = produk.nama;
                kategoriProdukSelect.value = produk.kategoriId;
                
                // Check if produk has variant
                if (produk.variant && produk.variant.length > 0) {
                    punyaVariantCheckbox.checked = true;
                    toggleVariantSection();
                    
                    // Load variant data
                    loadVariantData(produk.variant);
                } else {
                    hargaAwalInput.value = produk.hargaAwal || 0;
                    hargaJualInput.value = produk.hargaJual || 0;
                    calculateKeuntungan();
                }
                
                // Set data attribute for edit mode
                produkForm.setAttribute('data-edit-id', id);
            } else {
                // Add mode
                produkModalTitle.textContent = 'Tambah Produk';
                produkIdInput.value = '';
                produkForm.removeAttribute('data-edit-id');
            }
            
            // Re-attach event listener for tambah variant button
            document.getElementById('tambah-variant').addEventListener('click', addVariantRow);
            
            produkModal.style.display = 'flex';
        }
        
        function loadVariantData(variants) {
            variantList.innerHTML = '';
            
            variants.forEach((variant, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="text" class="form-control variant-nama" value="${variant.nama || ''}" placeholder="Nama variant" required></td>
                    <td><input type="number" class="form-control variant-harga-awal" value="${variant.hargaAwal || 0}" min="0" step="1" required></td>
                    <td><input type="number" class="form-control variant-harga-jual" value="${variant.hargaJual || 0}" min="0" step="1" required></td>
                    <td><input type="text" class="form-control variant-keuntungan" value="${variant.keuntungan || 0}" readonly style="background-color: #f8f9fa;"></td>
                    <td>
                        <button type="button" class="btn btn-outline btn-remove-variant">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                variantList.appendChild(row);
                
                // Calculate keuntungan for this row
                const hargaAwalInput = row.querySelector('.variant-harga-awal');
                const hargaJualInput = row.querySelector('.variant-harga-jual');
                const keuntunganInput = row.querySelector('.variant-keuntungan');
                
                const calculateVariantKeuntungan = () => {
                    const hargaAwal = parseFloat(hargaAwalInput.value) || 0;
                    const hargaJual = parseFloat(hargaJualInput.value) || 0;
                    const keuntungan = hargaJual - hargaAwal;
                    keuntunganInput.value = keuntungan;
                };
                
                hargaAwalInput.addEventListener('input', calculateVariantKeuntungan);
                hargaJualInput.addEventListener('input', calculateVariantKeuntungan);
                
                // Calculate initial keuntungan
                calculateVariantKeuntungan();
            });
            
            // Add "Tambah Variant" row
            const addRow = document.createElement('tr');
            addRow.innerHTML = `
                <td colspan="5" style="text-align: center; padding: 20px;">
                    <button type="button" class="btn btn-outline" id="tambah-variant">
                        <i class="fas fa-plus"></i> Tambah Variant
                    </button>
                </td>
            `;
            variantList.appendChild(addRow);
            
            // Add event listener for remove buttons
            document.querySelectorAll('.btn-remove-variant').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('tr').remove();
                });
            });
            
            // Re-attach event listener for tambah variant button
            document.getElementById('tambah-variant').addEventListener('click', addVariantRow);
        }
        
        function toggleVariantSection() {
            if (punyaVariantCheckbox.checked) {
                variantSection.classList.add('show');
                hargaTunggalSection.style.display = 'none';
            } else {
                variantSection.classList.remove('show');
                hargaTunggalSection.style.display = 'block';
            }
        }
        
        function addVariantRow() {
            // Remove the "Tambah Variant" row if exists
            const addRow = document.querySelector('#variant-list tr:last-child');
            if (addRow && addRow.querySelector('#tambah-variant')) {
                addRow.remove();
            }
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" class="form-control variant-nama" placeholder="Nama variant" required></td>
                <td><input type="number" class="form-control variant-harga-awal" value="0" min="0" step="1" required></td>
                <td><input type="number" class="form-control variant-harga-jual" value="0" min="0" step="1" required></td>
                <td><input type="text" class="form-control variant-keuntungan" value="0" readonly style="background-color: #f8f9fa;"></td>
                <td>
                    <button type="button" class="btn btn-outline btn-remove-variant">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            variantList.appendChild(row);
            
            // Calculate keuntungan for this row
            const hargaAwalInput = row.querySelector('.variant-harga-awal');
            const hargaJualInput = row.querySelector('.variant-harga-jual');
            const keuntunganInput = row.querySelector('.variant-keuntungan');
            
            const calculateVariantKeuntungan = () => {
                const hargaAwal = parseFloat(hargaAwalInput.value) || 0;
                const hargaJual = parseFloat(hargaJualInput.value) || 0;
                const keuntungan = hargaJual - hargaAwal;
                keuntunganInput.value = keuntungan;
            };
            
            hargaAwalInput.addEventListener('input', calculateVariantKeuntungan);
            hargaJualInput.addEventListener('input', calculateVariantKeuntungan);
            
            // Add event listener for remove button
            row.querySelector('.btn-remove-variant').addEventListener('click', function() {
                this.closest('tr').remove();
            });
            
            // Add "Tambah Variant" row again
            const newAddRow = document.createElement('tr');
            newAddRow.innerHTML = `
                <td colspan="5" style="text-align: center; padding: 20px;">
                    <button type="button" class="btn btn-outline" id="tambah-variant">
                        <i class="fas fa-plus"></i> Tambah Variant
                    </button>
                </td>
            `;
            variantList.appendChild(newAddRow);
            
            // Re-attach event listener for tambah variant button
            document.getElementById('tambah-variant').addEventListener('click', addVariantRow);
        }
        
        function calculateKeuntungan() {
            const hargaAwal = parseFloat(hargaAwalInput.value) || 0;
            const hargaJual = parseFloat(hargaJualInput.value) || 0;
            const keuntungan = hargaJual - hargaAwal;
            keuntunganInput.value = keuntungan;
        }
        
        function saveProduk(e) {
            e.preventDefault();
            
            const id = produkIdInput.value || generateId();
            const nama = namaProdukInput.value.trim();
            const kategoriId = kategoriProdukSelect.value;
            const hasVariant = punyaVariantCheckbox.checked;
            
            if (!nama) {
                alert('Nama produk harus diisi');
                return;
            }
            
            if (!kategoriId) {
                alert('Kategori produk harus dipilih');
                return;
            }
            
            let variant = [];
            let hargaAwal = 0;
            let hargaJual = 0;
            let keuntungan = 0;
            
            if (hasVariant) {
                // Collect variant data
                const variantRows = variantList.querySelectorAll('tr');
                variantRows.forEach(row => {
                    const namaInput = row.querySelector('.variant-nama');
                    const hargaAwalInput = row.querySelector('.variant-harga-awal');
                    const hargaJualInput = row.querySelector('.variant-harga-jual');
                    const keuntunganInput = row.querySelector('.variant-keuntungan');
                    
                    if (namaInput && hargaAwalInput && hargaJualInput && keuntunganInput) {
                        const variantNama = namaInput.value.trim();
                        const variantHargaAwal = parseFloat(hargaAwalInput.value) || 0;
                        const variantHargaJual = parseFloat(hargaJualInput.value) || 0;
                        const variantKeuntungan = parseFloat(keuntunganInput.value) || 0;
                        
                        if (variantNama) {
                            variant.push({
                                nama: variantNama,
                                hargaAwal: variantHargaAwal,
                                hargaJual: variantHargaJual,
                                keuntungan: variantKeuntungan
                            });
                            
                            hargaAwal += variantHargaAwal;
                            hargaJual += variantHargaJual;
                            keuntungan += variantKeuntungan;
                        }
                    }
                });
                
                if (variant.length === 0) {
                    alert('Minimal harus ada 1 variant');
                    return;
                }
            } else {
                // Single product
                hargaAwal = parseFloat(hargaAwalInput.value) || 0;
                hargaJual = parseFloat(hargaJualInput.value) || 0;
                keuntungan = parseFloat(keuntunganInput.value) || 0;
            }
            
            const editId = produkForm.getAttribute('data-edit-id');
            
            if (editId) {
                // Update existing produk
                const index = produkData.findIndex(p => p.id === editId);
                if (index !== -1) {
                    produkData[index] = {
                        ...produkData[index],
                        nama,
                        kategoriId,
                        variant: hasVariant ? variant : [],
                        hargaAwal: hasVariant ? 0 : hargaAwal,
                        hargaJual: hasVariant ? 0 : hargaJual,
                        keuntungan: hasVariant ? 0 : keuntungan
                    };
                }
            } else {
                // Add new produk
                const newProduk = {
                    id,
                    nama,
                    kategoriId,
                    variant: hasVariant ? variant : [],
                    hargaAwal: hasVariant ? 0 : hargaAwal,
                    hargaJual: hasVariant ? 0 : hargaJual,
                    keuntungan: hasVariant ? 0 : keuntungan
                };
                
                produkData.push(newProduk);
            }
            
            // Save to localStorage
            localStorage.setItem('produkData', JSON.stringify(produkData));
            
            // Update UI
            loadProdukList();
            
            // If viewing a kategori detail page, update that too
            if (kategoriIdTarget.value) {
                loadProdukByKategori(kategoriIdTarget.value);
            }
            
            updateDashboard();
            
            // Close modal
            closeModal(produkModal);
        }
        
        function deleteProduk(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                return;
            }
            
            // Delete produk
            produkData = produkData.filter(p => p.id !== id);
            
            // Save to localStorage
            localStorage.setItem('produkData', JSON.stringify(produkData));
            
            // Update UI
            loadProdukList();
            
            // If viewing a kategori detail page, update that too
            if (kategoriIdTarget.value) {
                loadProdukByKategori(kategoriIdTarget.value);
            }
            
            updateDashboard();
        }
        
        // Penjualan Functions
        function loadProdukToPenjualanSelect() {
            penjualanProdukSelect.innerHTML = '<option value="">Pilih Produk</option>';
            
            produkData.forEach(produk => {
                if (produk.variant && produk.variant.length > 0) {
                    // Add each variant as separate option
                    produk.variant.forEach(variant => {
                        const option = document.createElement('option');
                        option.value = `${produk.id}|${variant.nama}`;
                        option.textContent = `${produk.nama} - ${variant.nama}`;
                        option.dataset.hargaJual = variant.hargaJual || 0;
                        option.dataset.hargaModal = variant.hargaAwal || 0;
                        penjualanProdukSelect.appendChild(option);
                    });
                } else {
                    // Add single product option
                    const option = document.createElement('option');
                    option.value = produk.id;
                    option.textContent = produk.nama;
                    option.dataset.hargaJual = produk.hargaJual || 0;
                    option.dataset.hargaModal = produk.hargaAwal || 0;
                    penjualanProdukSelect.appendChild(option);
                }
            });
        }
        
        function updateHargaFromProduk() {
            const selectedOption = penjualanProdukSelect.options[penjualanProdukSelect.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const hargaJual = selectedOption.dataset.hargaJual || 0;
                const hargaModal = selectedOption.dataset.hargaModal || 0;
                
                penjualanHargaJualInput.value = hargaJual;
                penjualanHargaModalInput.value = hargaModal;
                
                updateTotalPenjualan();
            }
        }
        
        function updateTotalPenjualan() {
            const qty = parseFloat(penjualanQtyInput.value) || 0;
            const hargaJual = parseFloat(penjualanHargaJualInput.value) || 0;
            const hargaModal = parseFloat(penjualanHargaModalInput.value) || 0;
            
            const totalJual = qty * hargaJual;
            const totalModal = qty * hargaModal;
            const totalProfit = totalJual - totalModal;
            
            totalJualDisplay.textContent = `Rp ${formatNumber(totalJual)}`;
            totalModalDisplay.textContent = `Rp ${formatNumber(totalModal)}`;
            totalProfitDisplay.textContent = `Rp ${formatNumber(totalProfit)}`;
        }
        
        function resetFormPenjualan() {
            penjualanProdukSelect.value = '';
            penjualanQtyInput.value = 1;
            penjualanHargaJualInput.value = '';
            penjualanHargaModalInput.value = '';
            penjualanCatatanInput.value = '';
            penjualanPembayaranSelect.value = 'Cash';
            
            totalJualDisplay.textContent = 'Rp 0';
            totalModalDisplay.textContent = 'Rp 0';
            totalProfitDisplay.textContent = 'Rp 0';
        }
        
        function simpanTransaksi() {
            const tanggal = penjualanDateInput.value;
            const produkValue = penjualanProdukSelect.value;
            const qty = parseFloat(penjualanQtyInput.value) || 0;
            const hargaJual = parseFloat(penjualanHargaJualInput.value) || 0;
            const hargaModal = parseFloat(penjualanHargaModalInput.value) || 0;
            const pembayaran = penjualanPembayaranSelect.value;
            const catatan = penjualanCatatanInput.value;
            
            if (!tanggal) {
                alert('Tanggal harus diisi');
                return;
            }
            
            if (!produkValue) {
                alert('Produk harus dipilih');
                return;
            }
            
            if (qty <= 0) {
                alert('Jumlah harus lebih dari 0');
                return;
            }
            
            if (hargaJual <= 0) {
                alert('Harga jual harus diisi');
                return;
            }
            
            // Parse produk info
            let namaProduk = '';
            if (produkValue.includes('|')) {
                // Variant product
                const [produkId, variantNama] = produkValue.split('|');
                const produk = produkData.find(p => p.id === produkId);
                namaProduk = produk ? `${produk.nama} - ${variantNama}` : produkValue;
            } else {
                // Regular product
                const produk = produkData.find(p => p.id === produkValue);
                namaProduk = produk ? produk.nama : produkValue;
            }
            
            const totalJual = qty * hargaJual;
            const totalModal = qty * hargaModal;
            const totalProfit = totalJual - totalModal;
            
            // Create new transaction
            const newTransaksi = {
                id: generateId(),
                tanggal,
                produkId: produkValue,
                namaProduk,
                jumlah: qty,
                hargaJualPerUnit: hargaJual,
                hargaModalPerUnit: hargaModal,
                totalJual,
                totalModal,
                totalProfit,
                pembayaran,
                catatan
            };
            
            transaksiData.push(newTransaksi);
            
            // Save to localStorage
            localStorage.setItem('transaksiData', JSON.stringify(transaksiData));
            
            // Update UI
            loadTransaksiList();
            updateDashboard();
            
            // Reset form
            resetFormPenjualan();
            
            alert('Transaksi berhasil disimpan!');
        }
        
        function loadTransaksi() {
            // Load transaksi data
        }
        
        function loadTransaksiList() {
            if (transaksiData.length === 0) {
                transaksiList.innerHTML = `
                    <tr>
                        <td colspan="8" class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <p>Belum ada transaksi</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Sort by date (newest first)
            const sortedTransaksi = [...transaksiData].sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));
            
            transaksiList.innerHTML = '';
            
            sortedTransaksi.forEach((transaksi, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${transaksi.tanggal}</td>
                    <td>${transaksi.namaProduk}</td>
                    <td>${transaksi.jumlah}</td>
                    <td>Rp ${formatNumber(transaksi.totalJual)}</td>
                    <td>Rp ${formatNumber(transaksi.totalModal)}</td>
                    <td>Rp ${formatNumber(transaksi.totalProfit)}</td>
                    <td><span class="badge badge-primary">${transaksi.pembayaran}</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-outline btn-sm btn-hapus-transaksi" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                transaksiList.appendChild(row);
            });
            
            // Add event listeners to delete buttons
            document.querySelectorAll('.btn-hapus-transaksi').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    hapusTransaksi(index);
                });
            });
        }
        
        function hapusTransaksi(index) {
            if (!confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                return;
            }
            
            transaksiData.splice(index, 1);
            localStorage.setItem('transaksiData', JSON.stringify(transaksiData));
            
            loadTransaksiList();
            updateDashboard();
            
            // If on rekap tab, reload rekap data
            if (document.querySelector('.tab[data-tab="rekap"]').classList.contains('active')) {
                loadRekapData();
            }
        }
        
        function loadRekapData() {
            const startDate = rekapStartDate.value ? new Date(rekapStartDate.value) : null;
            const endDate = rekapEndDate.value ? new Date(rekapEndDate.value) : null;
            
            let totalPemasukan = 0;
            let totalPengeluaran = 0;
            let totalProfit = 0;
            
            // Object to store payment method stats
            const paymentStats = {};
            
            // Calculate from transaksi
            transaksiData.forEach(transaksi => {
                const transaksiDate = new Date(transaksi.tanggal);
                
                // Check if transaction is within date range
                if (startDate && endDate) {
                    if (transaksiDate < startDate || transaksiDate > endDate) {
                        return;
                    }
                }
                
                totalPemasukan += parseFloat(transaksi.totalJual) || 0;
                totalPengeluaran += parseFloat(transaksi.totalModal) || 0;
                totalProfit += parseFloat(transaksi.totalProfit) || 0;
                
                // Update payment method stats
                const paymentMethod = transaksi.pembayaran || 'Cash';
                if (!paymentStats[paymentMethod]) {
                    paymentStats[paymentMethod] = {
                        count: 0,
                        total: 0
                    };
                }
                
                paymentStats[paymentMethod].count += 1;
                paymentStats[paymentMethod].total += parseFloat(transaksi.totalJual) || 0;
            });
            
            // Calculate from mutasi (only mutasi masuk as pengeluaran)
            mutasiData.forEach(mutasi => {
                const mutasiDate = new Date(mutasi.tanggal);
                
                // Check if mutation is within date range
                if (startDate && endDate) {
                    if (mutasiDate < startDate || mutasiDate > endDate) {
                        return;
                    }
                }
                
                if (mutasi.tipe === 'masuk') {
                    totalPengeluaran += parseFloat(mutasi.jumlah) || 0;
                }
            });
            
            // Update rekap stats
            rekapPemasukanEl.textContent = `Rp ${formatNumber(totalPemasukan)}`;
            rekapPengeluaranEl.textContent = `Rp ${formatNumber(totalPengeluaran)}`;
            rekapProfitEl.textContent = `Rp ${formatNumber(totalProfit)}`;
            
            // Update payment method table
            if (Object.keys(paymentStats).length === 0) {
                rekapPembayaranTable.innerHTML = `
                    <tr>
                        <td colspan="4" class="empty-state">
                            <i class="fas fa-chart-pie"></i>
                            <p>Belum ada data untuk direkap</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            rekapPembayaranTable.innerHTML = '';
            
            // Convert to array and sort by total descending
            const paymentArray = Object.entries(paymentStats).map(([method, stats]) => ({
                method,
                ...stats
            }));
            
            paymentArray.sort((a, b) => b.total - a.total);
            
            // Calculate total for percentage calculation
            const totalAllPayments = paymentArray.reduce((sum, item) => sum + item.total, 0);
            
            paymentArray.forEach(item => {
                const percentage = totalAllPayments > 0 ? (item.total / totalAllPayments * 100).toFixed(1) : 0;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.method}</td>
                    <td>${item.count}</td>
                    <td>Rp ${formatNumber(item.total)}</td>
                    <td>${percentage}%</td>
                `;
                
                rekapPembayaranTable.appendChild(row);
            });
        }
        
        // Mutasi Functions
        function resetFormMutasi() {
            mutasiDateInput.value = formatDate(new Date());
            mutasiJumlahInput.value = '';
            mutasiTipeSelect.value = 'masuk';
            mutasiCatatanInput.value = '';
        }
        
        function simpanMutasi() {
            const tanggal = mutasiDateInput.value;
            const jumlah = parseFloat(mutasiJumlahInput.value) || 0;
            const tipe = mutasiTipeSelect.value;
            const catatan = mutasiCatatanInput.value;
            
            if (!tanggal) {
                alert('Tanggal harus diisi');
                return;
            }
            
            if (jumlah <= 0) {
                alert('Jumlah harus lebih dari 0');
                return;
            }
            
            if (!catatan.trim()) {
                alert('Catatan harus diisi');
                return;
            }
            
            // Create new mutation
            const newMutasi = {
                id: generateId(),
                tanggal,
                jumlah,
                tipe,
                catatan
            };
            
            mutasiData.push(newMutasi);
            
            // Save to localStorage
            localStorage.setItem('mutasiData', JSON.stringify(mutasiData));
            
            // Update UI
            loadMutasiList();
            updateDashboard();
            
            // Reset form
            resetFormMutasi();
            
            alert('Mutasi modal berhasil disimpan!');
        }
        
        function loadMutasi() {
            // Load mutasi data
        }
        
        function loadMutasiList() {
            if (mutasiData.length === 0) {
                mutasiList.innerHTML = `
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fas fa-exchange-alt"></i>
                            <p>Belum ada mutasi modal</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Sort by date (newest first)
            const sortedMutasi = [...mutasiData].sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));
            
            mutasiList.innerHTML = '';
            
            sortedMutasi.forEach((mutasi, index) => {
                const tipeBadge = mutasi.tipe === 'masuk' ? 'badge-success' : 'badge-warning';
                const tipeLabel = mutasi.tipe === 'masuk' ? 'Cash → App' : 'App → Cash';
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${mutasi.tanggal}</td>
                    <td>Rp ${formatNumber(mutasi.jumlah)}</td>
                    <td><span class="badge ${tipeBadge}">${tipeLabel}</span></td>
                    <td>${mutasi.catatan}</td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-outline btn-sm btn-hapus-mutasi" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                mutasiList.appendChild(row);
            });
            
            // Add event listeners to delete buttons
            document.querySelectorAll('.btn-hapus-mutasi').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    hapusMutasi(index);
                });
            });
        }
        
        function hapusMutasi(index) {
            if (!confirm('Apakah Anda yakin ingin menghapus mutasi ini?')) {
                return;
            }
            
            mutasiData.splice(index, 1);
            localStorage.setItem('mutasiData', JSON.stringify(mutasiData));
            
            loadMutasiList();
            updateDashboard();
        }
        
        // Export Functions
        function exportToExcel() {
            // Prepare data for export
            const exportData = [];
            
            // Add produk data
            produkData.forEach(produk => {
                const kategori = kategoriData.find(k => k.id === produk.kategoriId);
                
                if (produk.variant && produk.variant.length > 0) {
                    // Export each variant as a separate row
                    produk.variant.forEach(variant => {
                        exportData.push({
                            'Tipe Data': 'Produk',
                            'Nama Produk': produk.nama,
                            'Kategori': kategori ? kategori.nama : 'Tidak ada kategori',
                            'Variant': variant.nama,
                            'Harga Awal': variant.hargaAwal,
                            'Harga Jual': variant.hargaJual,
                            'Keuntungan': variant.keuntungan
                        });
                    });
                } else {
                    // Export single product
                    exportData.push({
                        'Tipe Data': 'Produk',
                        'Nama Produk': produk.nama,
                        'Kategori': kategori ? kategori.nama : 'Tidak ada kategori',
                        'Variant': '-',
                        'Harga Awal': produk.hargaAwal || 0,
                        'Harga Jual': produk.hargaJual || 0,
                        'Keuntungan': produk.keuntungan || 0
                    });
                }
            });
            
            // Add transaksi data
            transaksiData.forEach(transaksi => {
                exportData.push({
                    'Tipe Data': 'Transaksi',
                    'Tanggal': transaksi.tanggal,
                    'Produk': transaksi.namaProduk,
                    'Jumlah': transaksi.jumlah,
                    'Harga Jual/Unit': transaksi.hargaJualPerUnit,
                    'Harga Modal/Unit': transaksi.hargaModalPerUnit,
                    'Total Jual': transaksi.totalJual,
                    'Total Modal': transaksi.totalModal,
                    'Total Profit': transaksi.totalProfit,
                    'Pembayaran': transaksi.pembayaran,
                    'Catatan': transaksi.catatan || '-'
                });
            });
            
            // Add mutasi data
            mutasiData.forEach(mutasi => {
                const tipeLabel = mutasi.tipe === 'masuk' ? 'Cash → App' : 'App → Cash';
                
                exportData.push({
                    'Tipe Data': 'Mutasi',
                    'Tanggal': mutasi.tanggal,
                    'Jumlah': mutasi.jumlah,
                    'Tipe': tipeLabel,
                    'Catatan': mutasi.catatan
                });
            });
            
            if (exportData.length === 0) {
                alert('Tidak ada data untuk diexport');
                return;
            }
            
            // Create worksheet
            const ws = XLSX.utils.json_to_sheet(exportData);
            
            // Create workbook
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Data Bisnis');
            
            // Generate Excel file
            XLSX.writeFile(wb, 'data-bisnis.xlsx');
        }
        
        // Utility Functions
        function generateId() {
            return Date.now().toString(36) + Math.random().toString(36).substr(2);
        }
        
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
        
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        
        function closeModal(modal) {
            modal.style.display = 'none';
        }
        
        // Initialize with sample data if empty
        function initializeSampleData() {
            if (kategoriData.length === 0) {
                kategoriData = [
                    { id: '1', nama: 'Elektronik', deskripsi: 'Produk elektronik rumah tangga' },
                    { id: '2', nama: 'Pakaian', deskripsi: 'Pakaian pria, wanita, dan anak' },
                    { id: '3', nama: 'Makanan', deskripsi: 'Makanan ringan dan bahan masakan' }
                ];
                localStorage.setItem('kategoriData', JSON.stringify(kategoriData));
            }
            
            if (produkData.length === 0) {
                produkData = [
                    { 
                        id: '1', 
                        nama: 'Smartphone X', 
                        kategoriId: '1', 
                        variant: [], 
                        hargaAwal: 3200000, 
                        hargaJual: 4599000, 
                        keuntungan: 1399000 
                    },
                    { 
                        id: '2', 
                        nama: 'Laptop Pro', 
                        kategoriId: '1', 
                        variant: [
                            { nama: 'RAM 8GB', hargaAwal: 8250000, hargaJual: 10499000, keuntungan: 2249000 },
                            { nama: 'RAM 16GB', hargaAwal: 10250000, hargaJual: 13499000, keuntungan: 3249000 }
                        ], 
                        hargaAwal: 0, 
                        hargaJual: 0, 
                        keuntungan: 0 
                    },
                    { 
                        id: '3', 
                        nama: 'Kaos Polos', 
                        kategoriId: '2', 
                        variant: [
                            { nama: 'Size S', hargaAwal: 45000, hargaJual: 79900, keuntungan: 34900 },
                            { nama: 'Size M', hargaAwal: 48000, hargaJual: 82900, keuntungan: 34900 },
                            { nama: 'Size L', hargaAwal: 52000, hargaJual: 87900, keuntungan: 35900 }
                        ], 
                        hargaAwal: 0, 
                        hargaJual: 0, 
                        keuntungan: 0 
                    }
                ];
                localStorage.setItem('produkData', JSON.stringify(produkData));
            }
            
            if (transaksiData.length === 0) {
                // Set dates to recent days
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(today.getDate() - 1);
                const twoDaysAgo = new Date(today);
                twoDaysAgo.setDate(today.getDate() - 2);
                
                transaksiData = [
                    {
                        id: '1',
                        tanggal: formatDate(today),
                        produkId: '1',
                        namaProduk: 'Smartphone X',
                        jumlah: 1,
                        hargaJualPerUnit: 4599000,
                        hargaModalPerUnit: 3200000,
                        totalJual: 4599000,
                        totalModal: 3200000,
                        totalProfit: 1399000,
                        pembayaran: 'Cash',
                        catatan: 'Pembeli langsung'
                    },
                    {
                        id: '2',
                        tanggal: formatDate(yesterday),
                        produkId: '2|RAM 8GB',
                        namaProduk: 'Laptop Pro - RAM 8GB',
                        jumlah: 1,
                        hargaJualPerUnit: 10499000,
                        hargaModalPerUnit: 8250000,
                        totalJual: 10499000,
                        totalModal: 8250000,
                        totalProfit: 2249000,
                        pembayaran: 'QRIS',
                        catatan: 'Pesanan online'
                    },
                    {
                        id: '3',
                        tanggal: formatDate(twoDaysAgo),
                        produkId: '3|Size M',
                        namaProduk: 'Kaos Polos - Size M',
                        jumlah: 3,
                        hargaJualPerUnit: 82900,
                        hargaModalPerUnit: 48000,
                        totalJual: 248700,
                        totalModal: 144000,
                        totalProfit: 104700,
                        pembayaran: 'Transfer',
                        catatan: 'Grosir'
                    }
                ];
                localStorage.setItem('transaksiData', JSON.stringify(transaksiData));
            }
            
            if (mutasiData.length === 0) {
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(today.getDate() - 1);
                
                mutasiData = [
                    {
                        id: '1',
                        tanggal: formatDate(yesterday),
                        jumlah: 5250000,
                        tipe: 'masuk',
                        catatan: 'VIPayment topup'
                    },
                    {
                        id: '2',
                        tanggal: formatDate(today),
                        jumlah: 2150000,
                        tipe: 'keluar',
                        catatan: 'Tarik tunai untuk modal'
                    }
                ];
                localStorage.setItem('mutasiData', JSON.stringify(mutasiData));
            }
        }
        
        // Initialize sample data
        initializeSampleData();
    </script>
</body>
</html>