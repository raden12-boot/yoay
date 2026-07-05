<?php
include "../config/database.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan-penjualan.xls");

$q = mysqli_query($conn, "
SELECT 
    o.id,
    o.resi,
    g.nama_game,
    v.nama_variant,
    v.harga_awal,
    v.harga_jual,
    (v.harga_jual - v.harga_awal) AS keuntungan,
    o.user_input,
    o.nama_pengirim,
    o.payment_method,
    o.status,
    o.created_at
FROM orders o
JOIN product_variants v ON o.variant_id = v.id
JOIN games g ON v.game_id = g.id
ORDER BY o.created_at DESC
");

$total_modal = 0;
$total_jual  = 0;
$total_profit = 0;
?>

<table border="1" cellpadding="6">
<tr style="background:#ddd;font-weight:bold">
    <th>ID</th>
    <th>Resi</th>
    <th>Game</th>
    <th>Variant</th>
    <th>Harga Awal</th>
    <th>Harga Jual</th>
    <th>Keuntungan</th>
    <th>User Input</th>
    <th>Nama Pengirim</th>
    <th>Payment</th>
    <th>Status</th>
    <th>Tanggal</th>
</tr>

<?php while($r = mysqli_fetch_assoc($q)): 
    $total_modal  += $r['harga_awal'];
    $total_jual   += $r['harga_jual'];
    $total_profit += $r['keuntungan'];
?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['resi'] ?></td>
    <td><?= $r['nama_game'] ?></td>
    <td><?= $r['nama_variant'] ?></td>
    <td><?= number_format($r['harga_awal'],0,',','.') ?></td>
    <td><?= number_format($r['harga_jual'],0,',','.') ?></td>
    <td><?= number_format($r['keuntungan'],0,',','.') ?></td>
    <td><?= $r['user_input'] ?></td>
    <td><?= $r['nama_pengirim'] ?></td>
    <td><?= $r['payment_method'] ?></td>
    <td><?= $r['status'] ?></td>
    <td><?= $r['created_at'] ?></td>
</tr>
<?php endwhile; ?>

<!-- TOTAL -->
<tr style="background:#f5f5f5;font-weight:bold">
    <td colspan="4">TOTAL</td>
    <td><?= number_format($total_modal,0,',','.') ?></td>
    <td><?= number_format($total_jual,0,',','.') ?></td>
    <td><?= number_format($total_profit,0,',','.') ?></td>
    <td colspan="5"></td>
</tr>

</table>
