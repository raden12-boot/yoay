<?php
include "../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID PESANAN TIDAK VALID");
}

$id = $_GET['id'];

$o = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
    o.*,
    v.nama_variant,
    g.nama_game
FROM orders o
JOIN product_variants v ON o.variant_id = v.id
JOIN games g ON v.game_id = g.id
WHERE o.id='$id'
"));

if (!$o) {
    die("PESANAN TIDAK DITEMUKAN");
}
?>


<h2>Detail Pesanan</h2>

<table border="1" cellpadding="8">
<tr><td>Resi</td><td><?= $o['resi'] ?></td></tr>
<tr><td>Game</td><td><?= $o['nama_game'] ?></td></tr>
<tr><td>Varian</td><td><?= $o['nama_variant'] ?></td></tr>
<tr><td>Data Game</td><td><?= $o['user_input'] ?></td></tr>
<tr><td>Pengirim</td><td><?= $o['nama_pengirim'] ?></td></tr>
<tr><td>Pembayaran</td><td><?= $o['payment_method'] ?></td></tr>
<tr><td>Status</td><td><?= $o['status'] ?></td></tr>
<tr>
    <td>Bukti TF</td>
    <td>
        <img src="../uploads/<?= $o['bukti_tf'] ?>" width="300">
    </td>
</tr>
</table>

<br>
<a href="order_action.php?id=<?= $o['id'] ?>&action=SELESAI"
   onclick="return confirm('Selesaikan order?')">
   ✔ SELESAI
</a>

<a href="order_action.php?id=<?= $o['id'] ?>&action=BATAL"
   onclick="return confirm('Batalkan order?')">
   ✖ BATAL
</a>
