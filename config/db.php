<?php
// config/db.php
// Koneksi ke database film_ku

$host = 'localhost';
$db   = 'film_ku';  // nama database kamu
$user = 'root';     // default user XAMPP
$pass = '';         // default password XAMPP biasanya kosong

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // $pdo akan dipakai di file-file lain
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
