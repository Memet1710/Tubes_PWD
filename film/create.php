<?php
// film/create.php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $tahun = trim($_POST['tahun'] ?? '');

    if ($title === '') {
        echo "Judul wajib diisi. <a href='create.php'>Kembali</a>";
        exit;
    }

    if ($tahun !== '' && !ctype_digit($tahun)) {
        echo "Tahun harus berupa angka. <a href='create.php'>Kembali</a>";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO film (title, genre, tahun) VALUES (?, ?, ?)");
    $stmt->execute([$title, $genre, $tahun !== '' ? $tahun : null]);

    header('Location: list.php?msg=created');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Film</title>
</head>
<body>
    <h1>Tambah Film Baru</h1>

    <form action="" method="post">
        <div>
            <label>Judul</label><br>
            <input type="text" name="title" required>
        </div>
        <div>
            <label>Genre</label><br>
            <input type="text" name="genre">
        </div>
        <div>
            <label>Tahun</label><br>
            <input type="number" name="tahun" min="1900" max="2100">
        </div>

        <button type="submit">Simpan</button>
    </form>

    <p><a href="list.php">Kembali ke Daftar Film</a></p>
</body>
</html>
