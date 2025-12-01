<?php
// watchlist/delete.php
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: ../views/login.html');
    exit;
}

require_once '../config/db.php';

$id_user      = $_SESSION['id_user'];
$id_watchlist = $_GET['id'] ?? null;

if (!$id_watchlist || !ctype_digit($id_watchlist)) {
    echo "ID watchlist tidak valid. <a href='list.php'>Kembali</a>";
    exit;
}

// hapus hanya jika milik user yang sedang login
$stmt = $pdo->prepare("DELETE FROM watchlist WHERE id_watchlist = ? AND id_user = ?");
$stmt->execute([$id_watchlist, $id_user]);

header('Location: list.php?msg=deleted');
exit;
