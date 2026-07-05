<?php
// SIMPLE VOUCHER TEST - order_simple_test.php
// Upload ke /user/ dan akses langsung untuk test

include "../config/database.php";

// Auto-detect variant yang ada
$variant_query = mysqli_query($conn,"
    SELECT v.*, g.nama_game, g.gambar, g.keperluan, g.kategori, g.id as game_id
    FROM product_variants v
    JOIN games g ON v.game_id = g.id
    ORDER BY v.id DESC
    LIMIT 1
");

if (mysqli_num_rows($variant_query) == 0) {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>No Variants</title>";
    echo "<style>body{font-family:Arial;padding:40px;background:#1a1d29;color:#fff;}</style></head><body>";
    echo "<h2 style='color: red;'>❌ Tidak ada variant/produk di database!</h2>";
    echo "<p>Tambahkan produk terlebih dahulu.</p>";
    echo "<p><a href='../admin/game_add.php' style='color:#5b7cff;'>➕ Tambah Produk Baru</a></p>";
    echo "<p><a href='../admin/games.php' style='color:#5b7cff;'>📦 Lihat Semua Produk</a></p>";
    echo "</body></html>";
    exit;
}

$v = mysqli_fetch_assoc($variant_query);

// Show available variants
echo "<div style='background: #2a2e45; padding: 20px; margin: 20px; border-radius: 10px; color: #fff;'>";
echo "<h3>📦 Variant yang tersedia:</h3>";
$all_variants = mysqli_query($conn, "
    SELECT v.id, v.nama_variant, v.harga_jual, g.nama_game
    FROM product_variants v
    JOIN games g ON v.game_id = g.id
    LIMIT 10
");
echo "<ul>";
while ($av = mysqli_fetch_assoc($all_variants)) {
    echo "<li>ID: {$av['id']} - {$av['nama_game']} - {$av['nama_variant']} (Rp" . number_format($av['harga_jual'], 0, ',', '.') . ")</li>";
}
echo "</ul>";
echo "<p><strong>Test menggunakan:</strong> ID {$v['id']} - {$v['nama_game']} - {$v['nama_variant']}</p>";
echo "</div>";

// Process form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<h2>✅ POST Data Received:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    if (!empty($_POST['voucher_code'])) {
        echo "<h3 style='color: green;'>✅ Voucher Code Received: " . $_POST['voucher_code'] . "</h3>";
        
        // Test insert
        $test_resi = "TEST" . time();
        $voucher_code = strtoupper($_POST['voucher_code']);
        $diskon = (float)$_POST['diskon_value'];
        $harga_final = (float)$_POST['harga_final'];
        
        mysqli_query($conn, "
            INSERT INTO orders 
            (resi, variant_id, user_input, nama_pengirim, payment_method, bukti_tf, status, voucher_code, diskon_voucher, harga_sebelum_diskon)
            VALUES
            ('$test_resi', {$v['id']}, 'TEST', 'TEST USER', 'QRIS', 'test.jpg', 'PENDING', '$voucher_code', $diskon, {$v['harga_jual']})
        ");
        
        $order_id = mysqli_insert_id($conn);
        
        if ($order_id) {
            echo "<h3 style='color: green;'>✅ Order Created: ID = $order_id</h3>";
            
            // Insert voucher usage
            $voucher_id_query = mysqli_query($conn, "SELECT id FROM vouchers WHERE kode_voucher = '$voucher_code'");
            if (mysqli_num_rows($voucher_id_query) > 0) {
                $voucher_row = mysqli_fetch_assoc($voucher_id_query);
                $voucher_id = $voucher_row['id'];
                
                $insert = mysqli_query($conn, "
                    INSERT INTO voucher_usage 
                    (voucher_id, order_id, user_identifier, diskon_amount, harga_sebelum_diskon, harga_setelah_diskon)
                    VALUES
                    ($voucher_id, $order_id, 'TEST USER', $diskon, {$v['harga_jual']}, $harga_final)
                ");
                
                if ($insert) {
                    echo "<h3 style='color: green;'>✅ Voucher Usage Inserted!</h3>";
                    
                    // Update kuota
                    mysqli_query($conn, "UPDATE vouchers SET kuota_terpakai = kuota_terpakai + 1 WHERE id = $voucher_id");
                    echo "<h3 style='color: green;'>✅ Kuota Updated!</h3>";
                } else {
                    echo "<h3 style='color: red;'>❌ Voucher Usage Insert Failed: " . mysqli_error($conn) . "</h3>";
                }
            }
        }
        
        echo "<br><a href='order_simple_test.php'>Test Again</a> | <a href='../admin/debug_voucher.php'>Check Debug</a>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Simple Voucher Test</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            padding: 40px; 
            background: #1a1d29; 
            color: #fff;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #2a2e45;
            padding: 30px;
            border-radius: 10px;
        }
        h2 { color: #5b7cff; margin-bottom: 20px; }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, button {
            width: 100%;
            padding: 12px;
            border: 1px solid #444;
            border-radius: 5px;
            background: #1a1d29;
            color: #fff;
            font-size: 16px;
        }
        button {
            background: #5b7cff;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
        }
        button:hover {
            background: #7c3aed;
        }
        .info {
            background: rgba(91, 124, 255, 0.1);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #5b7cff;
        }
        .price {
            font-size: 24px;
            color: #5b7cff;
            font-weight: bold;
        }
        #result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            display: none;
        }
        .success { background: rgba(16, 185, 129, 0.2); color: #10b981; display: block; }
        .error { background: rgba(239, 68, 68, 0.2); color: #ef4444; display: block; }
    </style>
</head>
<body>

<div class="container">
    <h2>🧪 Simple Voucher Test</h2>
    
    <div class="info">
        <strong>Test Product:</strong><br>
        <?= $v['nama_game'] ?> - <?= $v['nama_variant'] ?><br>
        <span class="price">Rp<?= number_format($v['harga_jual'], 0, ',', '.') ?></span>
    </div>

    <form method="POST" id="testForm">
        <div class="form-group">
            <label>Masukkan Kode Voucher:</label>
            <input type="text" name="voucher_code" id="voucherInput" placeholder="Contoh: EPEPDIDADA" required style="text-transform: uppercase;">
        </div>

        <button type="button" onclick="checkVoucher()">🔍 Check Voucher</button>

        <div id="result"></div>

        <input type="hidden" name="diskon_value" id="diskonValue" value="0">
        <input type="hidden" name="harga_final" id="hargaFinal" value="<?= $v['harga_jual'] ?>">
        <input type="hidden" name="variant_id" value="<?= $v['id'] ?>">

        <button type="submit" id="submitBtn" style="display: none;">✅ Submit Test Order</button>
    </form>

    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #444;">
        <a href="../admin/debug_voucher.php" style="color: #5b7cff;">📊 Check Debug Dashboard</a>
    </div>
</div>

<script>
const hargaAsli = <?= $v['harga_jual'] ?>;
const gameId = <?= $v['game_id'] ?>;

function checkVoucher() {
    const voucherCode = document.getElementById('voucherInput').value.trim().toUpperCase();
    const resultDiv = document.getElementById('result');
    
    if (!voucherCode) {
        resultDiv.className = 'error';
        resultDiv.textContent = '❌ Masukkan kode voucher!';
        return;
    }

    resultDiv.className = '';
    resultDiv.style.display = 'block';
    resultDiv.textContent = '⏳ Checking...';

    fetch('../admin/check_voucher.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `kode_voucher=${voucherCode}&game_id=${gameId}&harga=${hargaAsli}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('API Response:', data);
        
        if (data.success) {
            resultDiv.className = 'success';
            resultDiv.innerHTML = `
                ✅ Voucher Valid!<br>
                Diskon: Rp${data.hemat}<br>
                Harga Final: Rp${formatRupiah(data.harga_final)}
            `;
            
            document.getElementById('diskonValue').value = data.diskon;
            document.getElementById('hargaFinal').value = data.harga_final;
            document.getElementById('submitBtn').style.display = 'block';
            
            console.log('✅ Hidden inputs set:', {
                diskon: data.diskon,
                harga_final: data.harga_final
            });
        } else {
            resultDiv.className = 'error';
            resultDiv.textContent = '❌ ' + data.message;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.className = 'error';
        resultDiv.textContent = '❌ Error: ' + error.message;
    });
}

function formatRupiah(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

document.getElementById('voucherInput').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>

</body>
</html>