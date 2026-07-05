<?php
session_start();
if(!isset($_SESSION['admin'])) exit;
include "../config/database.php";

$q = mysqli_query($conn,"
SELECT o.id, g.nama_game, v.nama_variant, o.nama_pengirim, o.status, o.created_at
FROM orders o
JOIN product_variants v ON o.variant_id=v.id
JOIN games g ON v.game_id=g.id
ORDER BY o.id DESC
");
?>

<h2>Kelola Order</h2>

<table border="1" cellpadding="8">
<tr>
  <th>ID</th>
  <th>Game</th>
  <th>Item</th>
  <th>Pengirim</th>
  <th>Status</th>
  <th>Aksi</th>
</tr>

<?php while($o=mysqli_fetch_assoc($q)): ?>
<tr>
  <td>#<?= $o['id'] ?></td>
  <td><?= $o['nama_game'] ?></td>
  <td><?= $o['nama_variant'] ?></td>
  <td><?= $o['nama_pengirim'] ?></td>
  <td><?= $o['status'] ?></td>
  <td>
    <a href="order_detail.php?id=<?= $o['id'] ?>">Detail</a>
  </td>
</tr>
<?php endwhile ?>
</table>
