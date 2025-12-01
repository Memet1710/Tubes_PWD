<?php
// user/update_profile.php
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: ../views/login.html');
    exit;
}

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');

    // validasi dasar
    if ($name === '' || $email === '') {
        echo 'Nama dan email wajib diisi. <a href="profile.php">Kembali</a>';
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Format email tidak valid. <a href="profile.php">Kembali</a>';
        exit;
    }

    // cek email tidak dipakai user lain
    $stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = ? AND id_user != ?");
    $stmt->execute([$email, $id_user]);
    if ($stmt->fetch()) {
        echo 'Email sudah dipakai user lain. <a href="profile.php">Kembali</a>';
        exit;
    }

    // update data user
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id_user = ?");
    $stmt->execute([$name, $email, $id_user]);

    // update juga session
    $_SESSION['name']  = $name;
    $_SESSION['email'] = $email;

    // kembali ke profile dengan pesan sukses
    header('Location: profile.php?success=1');
    exit;
} else {
    header('Location: profile.php');
    exit;
}
