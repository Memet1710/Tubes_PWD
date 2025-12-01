<?php
// watchlist/add.php
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: ../views/login.html');
    exit;
}

require_once '../config/db.php';

$id_user = $_SESSION['id_user'];
$id_film = $_GET['id_film'] ?? null;

if (!$id_film || !ctype_digit($id_film)) {
    echo "ID film tidak valid. <a href='../film/list.php'>Kembali</a>";
    exit;
}

// cek apakah film ada
$stmt = $pdo->prepare("SELECT id_film FROM film WHERE id_film = ?");
$stmt->execute([$id_film]);
if (!$stmt->fetch()) {
    echo "Film tidak ditemukan. <a href='../film/list.php'>Kembali</a>";
    exit;
}

// cek apakah sudah ada di watchlist
$stmt = $pdo->prepare("SELECT id_watchlist FROM watchlist WHERE id_user = ? AND id_film = ?");
$stmt->execute([$id_user, $id_film]);
if ($stmt->fetch()) {
    header('Location: ../film/list.php?msg=exists');
    exit;
}

// tambah ke watchlist
$stmt = $pdo->prepare("INSERT INTO watchlist (id_user, id_film) VALUES (?, ?)");
$stmt->execute([$id_user, $id_film]);

header('Location: ../film/list.php?msg=added_watchlist');
// atau bisa: header('Location: list.php?msg=added');
exit;
