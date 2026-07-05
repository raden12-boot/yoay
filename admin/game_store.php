<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
    $nama_game = mysqli_real_escape_string($conn, $_POST['nama_game']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $keperluan = mysqli_real_escape_string($conn, $_POST['keperluan']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi'] ?? ''); // Get description
    
    // Handle file upload
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $file = $_FILES['gambar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $allowed)) {
            $gambar = time() . '_' . $file['name'];
            $target = "../uploads/" . $gambar;
            
            if (!move_uploaded_file($file['tmp_name'], $target)) {
                die("Error uploading file");
            }
        } else {
            die("Invalid file type. Only JPG, PNG, GIF, WEBP allowed.");
        }
    } else {
        die("Please upload an image");
    }
    
    // Insert game with description
    $sql = "INSERT INTO games (nama_game, kategori, gambar, keperluan, deskripsi, has_variant, created_at) 
            VALUES ('$nama_game', '$kategori', '$gambar', '$keperluan', '$deskripsi', 1, NOW())";
    
    if (!mysqli_query($conn, $sql)) {
        die("Error inserting game: " . mysqli_error($conn));
    }
    
    $game_id = mysqli_insert_id($conn);
    
    // Insert variants if exists
    if (isset($_POST['nama_variant']) && is_array($_POST['nama_variant'])) {
        $variant_groups = $_POST['variant_group'];
        $nama_variants = $_POST['nama_variant'];
        $harga_awals = $_POST['harga_awal'];
        $harga_juals = $_POST['harga_jual'];
        $stoks = $_POST['stok'];
        
        for ($i = 0; $i < count($nama_variants); $i++) {
            // Skip empty rows
            if (empty($nama_variants[$i])) continue;
            
            $vg = mysqli_real_escape_string($conn, $variant_groups[$i]);
            $nv = mysqli_real_escape_string($conn, $nama_variants[$i]);
            $ha = (int)$harga_awals[$i];
            $hj = (int)$harga_juals[$i];
            $st = (int)$stoks[$i];
            
            $sql_variant = "INSERT INTO product_variants 
                           (game_id, variant_group, nama_variant, harga_awal, harga_jual, stok, created_at) 
                           VALUES 
                           ($game_id, '$vg', '$nv', $ha, $hj, $st, NOW())";
            
            mysqli_query($conn, $sql_variant);
        }
    }
    
    // Redirect to games list
    header("Location: games.php?success=1");
    exit;
    
} else {
    header("Location: game_add.php");
    exit;
}
?>