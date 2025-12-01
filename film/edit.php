<?php
// film/edit.php
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

// ambil data film
$stmt = $pdo->prepare("SELECT id_film, title, genre, tahun FROM film WHERE id_film = ?");
$stmt->execute([$id_film]);
$film = $stmt->fetch();

if (!$film) {
    echo "Film tidak ditemukan. <a href='list.php'>Kembali</a>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $tahun = trim($_POST['tahun'] ?? '');

    if ($title === '') {
        echo "Judul wajib diisi. <a href='edit.php?id=" . $film['id_film'] . "'>Kembali</a>";
        exit;
    }

    if ($tahun !== '' && !ctype_digit($tahun)) {
        echo "Tahun harus berupa angka. <a href='edit.php?id=" . $film['id_film'] . "'>Kembali</a>";
        exit;
    }

    $stmt = $pdo->prepare("UPDATE film SET title = ?, genre = ?, tahun = ? WHERE id_film = ?");
    $stmt->execute([
        $title,
        $genre,
        $tahun !== '' ? $tahun : null,
        $film['id_film']
    ]);

    header('Location: list.php?msg=updated');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Film</title>
</head>
<body>
    <h1>Edit Film</h1>

    <form action="" method="post">
        <div>
            <label>Judul</label><br>
            <input type="text" name="title" value="<?= htmlspecialchars($film['title']) ?>" required>
        </div>
        <div>
            <label>Genre</label><br>
            <input type="text" name="genre" value="<?= htmlspecialchars($film['genre']) ?>">
        </div>
        <div>
            <label>Tahun</label><br>
            <input type="number" name="tahun" min="1900" max="2100"
                   value="<?= htmlspecialchars($film['tahun']) ?>">
        </div>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <p><a href="list.php">Kembali ke Daftar Film</a></p>
</body>
</html>
