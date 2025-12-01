<?php
// film/delete.php
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: ../views/login.html');
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Hanya admin yang boleh mengelola data film. <a href='../dashboard.php'>Kembali</a>";
    exit;
}

require_once '../config/db.php';

$id_film = $_GET['id'] ?? null;
if (!$id_film || !ctype_digit($id_film)) {
    echo "ID film tidak valid. <a href='list.php'>Kembali</a>";
    exit;
}

// hapus film (watchlist akan ikut terhapus kalau FK ON DELETE CASCADE)
$stmt = $pdo->prepare("DELETE FROM film WHERE id_film = ?");
$stmt->execute([$id_film]);

header('Location: list.php?msg=deleted');
exit;
