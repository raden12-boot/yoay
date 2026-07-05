<?php
// DEBUG SCRIPT - Check Voucher System
// Akses: /admin/debug_voucher.php

session_start();
if (!isset($_SESSION['admin'])) {
    die("Login as admin first");
}

include "../config/database.php";

echo "<h2>🔍 Debug Voucher System</h2>";
echo "<style>
body { font-family: monospace; padding: 20px; background: #1a1d29; color: #fff; }
table { border-collapse: collapse; width: 100%; margin: 20px 0; background: #2a2e45; }
th, td { border: 1px solid #444; padding: 10px; text-align: left; }
th { background: #5b7cff; color: white; }
h3 { color: #5b7cff; margin-top: 30px; }
.success { color: #10b981; }
.error { color: #ef4444; }
</style>";

// 1. Check if tables exist
echo "<h3>1. Check Tables</h3>";
$tables = ['vouchers', 'voucher_usage', 'orders'];
foreach ($tables as $table) {
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($check) > 0) {
        echo "<span class='success'>✅ Table '$table' exists</span><br>";
    } else {
        echo "<span class='error'>❌ Table '$table' NOT FOUND!</span><br>";
    }
}

// 2. Check vouchers table structure
echo "<h3>2. Vouchers Table Structure</h3>";
$columns = mysqli_query($conn, "SHOW COLUMNS FROM vouchers");
echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
while ($col = mysqli_fetch_assoc($columns)) {
    echo "<tr>";
    echo "<td>{$col['Field']}</td>";
    echo "<td>{$col['Type']}</td>";
    echo "<td>{$col['Null']}</td>";
    echo "<td>{$col['Key']}</td>";
    echo "</tr>";
}
echo "</table>";

// 3. Check orders table for voucher columns
echo "<h3>3. Orders Table - Voucher Columns</h3>";
$order_cols = mysqli_query($conn, "SHOW COLUMNS FROM orders WHERE Field LIKE '%voucher%' OR Field LIKE '%diskon%' OR Field LIKE '%harga_%'");
if (mysqli_num_rows($order_cols) > 0) {
    echo "<table><tr><th>Field</th><th>Type</th><th>Null</th></tr>";
    while ($col = mysqli_fetch_assoc($order_cols)) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<span class='error'>❌ No voucher columns in orders table!</span><br>";
    echo "<p>Run this SQL:</p>";
    echo "<pre>ALTER TABLE orders
ADD COLUMN voucher_code VARCHAR(512) DEFAULT NULL,
ADD COLUMN diskon_voucher DECIMAL(10,2) DEFAULT 0,
ADD COLUMN harga_sebelum_diskon DECIMAL(10,2) DEFAULT NULL;</pre>";
}

// 4. List all vouchers
echo "<h3>4. All Vouchers</h3>";
$vouchers = mysqli_query($conn, "SELECT * FROM vouchers ORDER BY id DESC LIMIT 10");
if (mysqli_num_rows($vouchers) > 0) {
    echo "<table><tr><th>ID</th><th>Kode</th><th>Nama</th><th>Tipe</th><th>Nilai</th><th>Kuota</th><th>Status</th></tr>";
    while ($v = mysqli_fetch_assoc($vouchers)) {
        echo "<tr>";
        echo "<td>{$v['id']}</td>";
        echo "<td><strong>{$v['kode_voucher']}</strong></td>";
        echo "<td>{$v['nama_voucher']}</td>";
        echo "<td>{$v['tipe_diskon']}</td>";
        echo "<td>{$v['nilai_diskon']}</td>";
        echo "<td>{$v['kuota_terpakai']}/{$v['kuota_total']}</td>";
        echo "<td>{$v['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<span class='error'>❌ No vouchers found</span>";
}

// 5. Check voucher_usage table
echo "<h3>5. Voucher Usage Records</h3>";
$usage = mysqli_query($conn, "
    SELECT vu.*, v.kode_voucher, o.resi 
    FROM voucher_usage vu
    LEFT JOIN vouchers v ON vu.voucher_id = v.id
    LEFT JOIN orders o ON vu.order_id = o.id
    ORDER BY vu.id DESC 
    LIMIT 10
");

if (mysqli_num_rows($usage) > 0) {
    echo "<table><tr><th>ID</th><th>Voucher Code</th><th>Order Resi</th><th>Diskon</th><th>User</th><th>Created</th></tr>";
    while ($u = mysqli_fetch_assoc($usage)) {
        echo "<tr>";
        echo "<td>{$u['id']}</td>";
        echo "<td>{$u['kode_voucher']}</td>";
        echo "<td>{$u['resi']}</td>";
        echo "<td>Rp" . number_format($u['diskon_amount'], 0, ',', '.') . "</td>";
        echo "<td>{$u['user_identifier']}</td>";
        echo "<td>{$u['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<span class='error'>❌ No voucher usage records</span><br>";
    echo "<p>This means either:</p>";
    echo "<ul>";
    echo "<li>No one has used a voucher yet</li>";
    echo "<li>Voucher usage insert is failing</li>";
    echo "<li>Foreign key constraint issue</li>";
    echo "</ul>";
}

// 6. Check orders with vouchers
echo "<h3>6. Recent Orders with Vouchers</h3>";
$orders_with_voucher = mysqli_query($conn, "
    SELECT id, resi, voucher_code, diskon_voucher, harga_sebelum_diskon, created_at
    FROM orders 
    WHERE voucher_code IS NOT NULL
    ORDER BY id DESC
    LIMIT 10
");

if (mysqli_num_rows($orders_with_voucher) > 0) {
    echo "<table><tr><th>ID</th><th>Resi</th><th>Voucher</th><th>Diskon</th><th>Harga Asli</th><th>Created</th></tr>";
    while ($o = mysqli_fetch_assoc($orders_with_voucher)) {
        echo "<tr>";
        echo "<td>{$o['id']}</td>";
        echo "<td>{$o['resi']}</td>";
        echo "<td><strong>{$o['voucher_code']}</strong></td>";
        echo "<td>Rp" . number_format($o['diskon_voucher'], 0, ',', '.') . "</td>";
        echo "<td>Rp" . number_format($o['harga_sebelum_diskon'], 0, ',', '.') . "</td>";
        echo "<td>{$o['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<span class='error'>❌ No orders with vouchers found</span><br>";
    echo "<p>Check:</p>";
    echo "<ul>";
    echo "<li>Is voucher code being sent from order.php?</li>";
    echo "<li>Is order_store.php receiving voucher data?</li>";
    echo "<li>Check hidden input: voucher_code_final</li>";
    echo "</ul>";
}

// 7. Test voucher validation
echo "<h3>7. Test Voucher Validation</h3>";
$test_voucher = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM vouchers WHERE status = 'AKTIF' LIMIT 1"));

if ($test_voucher) {
    echo "<p>Testing voucher: <strong>{$test_voucher['kode_voucher']}</strong></p>";
    
    // Simulate validation
    $now = date('Y-m-d H:i:s');
    $is_active = $test_voucher['status'] == 'AKTIF';
    $is_valid_date = ($now >= $test_voucher['tanggal_mulai'] && $now <= $test_voucher['tanggal_berakhir']);
    $has_kuota = ($test_voucher['kuota_total'] === NULL || $test_voucher['kuota_terpakai'] < $test_voucher['kuota_total']);
    
    echo "<ul>";
    echo "<li>Status Active: " . ($is_active ? "<span class='success'>✅ YES</span>" : "<span class='error'>❌ NO</span>") . "</li>";
    echo "<li>Date Valid: " . ($is_valid_date ? "<span class='success'>✅ YES</span>" : "<span class='error'>❌ NO</span>") . "</li>";
    echo "<li>Has Kuota: " . ($has_kuota ? "<span class='success'>✅ YES</span>" : "<span class='error'>❌ NO</span>") . "</li>";
    echo "<li>Current Date: $now</li>";
    echo "<li>Valid From: {$test_voucher['tanggal_mulai']}</li>";
    echo "<li>Valid Until: {$test_voucher['tanggal_berakhir']}</li>";
    echo "<li>Kuota: {$test_voucher['kuota_terpakai']}/{$test_voucher['kuota_total']}</li>";
    echo "</ul>";
} else {
    echo "<span class='error'>❌ No active vouchers to test</span>";
}

// 8. Count statistics
echo "<h3>8. Statistics</h3>";
$stats = [
    'Total Vouchers' => mysqli_num_rows(mysqli_query($conn, "SELECT id FROM vouchers")),
    'Active Vouchers' => mysqli_num_rows(mysqli_query($conn, "SELECT id FROM vouchers WHERE status = 'AKTIF'")),
    'Total Usage Count' => mysqli_num_rows(mysqli_query($conn, "SELECT id FROM voucher_usage")),
    'Orders with Vouchers' => mysqli_num_rows(mysqli_query($conn, "SELECT id FROM orders WHERE voucher_code IS NOT NULL")),
];

echo "<table>";
foreach ($stats as $label => $value) {
    echo "<tr><td><strong>$label</strong></td><td>$value</td></tr>";
}
echo "</table>";

echo "<hr>";
echo "<p><a href='vouchers.php' style='color: #5b7cff;'>← Back to Vouchers</a></p>";
?>