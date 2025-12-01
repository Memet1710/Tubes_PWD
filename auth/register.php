<?php
// auth/register.php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // 1. Validasi sederhana
    if ($name === '' || $email === '' || $password === '' || $confirm === '') {
        echo 'Semua field wajib diisi. <a href="../views/register.html">Kembali</a>';
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Format email tidak valid. <a href="../views/register.html">Kembali</a>';
        exit;
    }

    if ($password !== $confirm) {
        echo 'Konfirmasi password tidak sama. <a href="../views/register.html">Kembali</a>';
        exit;
    }

    if (strlen($password) < 6) {
        echo 'Password minimal 6 karakter. <a href="../views/register.html">Kembali</a>';
        exit;
    }

    // 2. Cek email sudah terdaftar atau belum
    $stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo 'Email sudah terdaftar. <a href="../views/register.html">Kembali</a>';
        exit;
    }

    // 3. Hash password dan simpan (kolom "passwords")
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, passwords, role)
        VALUES (?, ?, ?, 'user')
    ");
    $stmt->execute([$name, $email, $hashed]);

    // 4. Setelah sukses, arahkan ke halaman login
    header('Location: ../views/login.html');
    exit;
} else {
    header('Location: ../views/register.html');
    exit;
}
