<?php
session_start();

// Kalau belum login, lempar ke login
if (!isset($_SESSION['id_user'])) {
    header('Location: views/login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - FilmKu</title>
</head>
<body>
    <h1>Halo, <?= htmlspecialchars($_SESSION['name']) ?></h1>
    <p>Selamat datang di aplikasi FilmKu.</p>

    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="user/profile.php">Profil Saya</a></li>
        <li><a href="film/list.php">Daftar Film</a></li>
        <li><a href="watchlist/list.php">Watchlist Saya</a></li>
        <li><a href="auth/logout.php">Logout</a></li>
    </ul>
</body>
</html>
