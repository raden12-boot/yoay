<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id = (int)$_POST['id'];
    $nama_game = mysqli_real_escape_string($conn, $_POST['nama_game']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $keperluan = mysqli_real_escape_string($conn, $_POST['keperluan']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi'] ?? ''); // Get description
    
    // Handle image upload (optional)
    $gambar_update = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $file = $_FILES['gambar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $allowed)) {
            // Delete old image
            $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM games WHERE id=$id"));
            if ($old && file_exists("../uploads/" . $old['gambar'])) {
                unlink("../uploads/" . $old['gambar']);
            }
            
            $gambar = time() . '_' . $file['name'];
            $target = "../uploads/" . $gambar;
            
            if (move_uploaded_file($file['tmp_name'], $target)) {
                $gambar_update = ", gambar='$gambar'";
            }
        }
    }
    
    // Update game with description
    $sql = "UPDATE games 
            SET nama_game='$nama_game', 
                kategori='$kategori', 
                keperluan='$keperluan',
                deskripsi='$deskripsi'
                $gambar_update 
            WHERE id=$id";
    
    if (!mysqli_query($conn, $sql)) {
        die("Error updating game: " . mysqli_error($conn));
    }
    
    // Update existing variants
    if (isset($_POST['nama_variant']) && is_array($_POST['nama_variant'])) {
        foreach ($_POST['nama_variant'] as $variant_id => $nama) {
            $vg = mysqli_real_escape_string($conn, $_POST['variant_group'][$variant_id]);
            $nv = mysqli_real_escape_string($conn, $nama);
            $ha = (int)$_POST['harga_awal'][$variant_id];
            $hj = (int)$_POST['harga_jual'][$variant_id];
            $st = (int)$_POST['stok'][$variant_id];
            
            $sql_update = "UPDATE product_variants 
                          SET variant_group='$vg',
                              nama_variant='$nv',
                              harga_awal=$ha,
                              harga_jual=$hj,
                              stok=$st
                          WHERE id=$variant_id";
            
            mysqli_query($conn, $sql_update);
        }
    }
    
    // Insert new variants
    if (isset($_POST['nama_variant_new']) && is_array($_POST['nama_variant_new'])) {
        $variant_groups_new = $_POST['variant_group_new'];
        $nama_variants_new = $_POST['nama_variant_new'];
        $harga_awals_new = $_POST['harga_awal_new'];
        $harga_juals_new = $_POST['harga_jual_new'];
        $stoks_new = $_POST['stok_new'];
        
        for ($i = 0; $i < count($nama_variants_new); $i++) {
            // Skip empty rows
            if (empty($nama_variants_new[$i])) continue;
            
            $vg = mysqli_real_escape_string($conn, $variant_groups_new[$i]);
            $nv = mysqli_real_escape_string($conn, $nama_variants_new[$i]);
            $ha = (int)$harga_awals_new[$i];
            $hj = (int)$harga_juals_new[$i];
            $st = (int)$stoks_new[$i];
            
            $sql_insert = "INSERT INTO product_variants 
                          (game_id, variant_group, nama_variant, harga_awal, harga_jual, stok, created_at) 
                          VALUES 
                          ($id, '$vg', '$nv', $ha, $hj, $st, NOW())";
            
            mysqli_query($conn, $sql_insert);
        }
    }
    
    // Redirect back to games list
    header("Location: games.php?updated=1");
    exit;
    
} else {
    header("Location: games.php");
    exit;
}
?>