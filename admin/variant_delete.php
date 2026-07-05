<?php
session_start();
if (!isset($_SESSION['admin'])) exit;

include "../config/database.php";

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM product_variants WHERE id=$id");

header("Location: ".$_SERVER['HTTP_REFERER']);
