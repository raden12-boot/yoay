<?php
session_start();
if (!isset($_SESSION['admin'])) exit;

include "../config/database.php";

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM games WHERE id=$id");

header("Location: games.php");
