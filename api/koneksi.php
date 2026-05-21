<?php

$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : "";
$db = getenv('DB_NAME') ?: "toko_db";
$port = getenv('DB_PORT') ?: 3306;

// Menghubungkan ke database dengan port
$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>