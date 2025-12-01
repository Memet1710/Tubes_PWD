<?php
// auth/login.php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        echo 'Email dan password wajib diisi. <a href="../views/login.html">Kembali</a>';
        exit;
    }

    // Ambil user berdasarkan email
    $stmt = $pdo->prepare("SELECT id_user, name, email, passwords, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Cek password
    if ($user && password_verify($password, $user['passwords'])) {
        // Simpan ke session
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['email']   = $user['email'];
        $_SESSION['role']    = $user['role'];

        header('Location: ../dashboard.php');
        exit;
    } else {
        echo 'Email atau password salah. <a href="../views/login.html">Kembali</a>';
        exit;
    }
} else {
    header('Location: ../views/login.html');
    exit;
}
