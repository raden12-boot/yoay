<?php
include "../config/database.php";

$game_id = $_GET['game_id'] ?? $_GET['game'] ?? '';

if (!$game_id || !is_numeric($game_id)) {
    die("Produk tidak ditemukan");
}

// ambil data game
$game = mysqli_query($conn, "
    SELECT * FROM games 
    WHERE id='$game_id'
");

if (mysqli_num_rows($game) == 0) {
    die("Produk tidak ditemukan");
}

$g = mysqli_fetch_assoc($game);

// ambil variant
$variants = mysqli_query($conn,"
    SELECT * FROM product_variants 
    WHERE game_id='$game_id'
");
?>
<!DOCTYPE html>
<html>
<head>
<title><?= $g['nama_game'] ?></title>
</head>
<body>

<h2><?= $g['nama_game'] ?></h2>

<table border="1" cellpadding="8">
<tr>
  <th>Item</th>
  <th>Harga</th>
  <th>Aksi</th>
</tr>

<?php if(mysqli_num_rows($variants) > 0): ?>
    <?php while($v = mysqli_fetch_assoc($variants)): ?>
    <tr>
      <td><?= $v['nama_variant'] ?></td>
      <td>Rp<?= number_format($v['harga_jual'],0,',','.') ?></td>
      <td>
        <a href="order.php?variant_id=<?= $v['id'] ?>">Beli</a>
      </td>
    </tr>
    <?php endwhile ?>
<?php else: ?>
<tr>
  <td colspan="3">Variant belum tersedia</td>
</tr>
<?php endif ?>

</table>

</body>
</html>
