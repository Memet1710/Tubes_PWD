<?php
// film/list.php
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: ../views/login.html');
    exit;
}

require_once '../config/db.php';

// ambil semua film
$stmt = $pdo->query("SELECT id_film, title, genre, tahun FROM film ORDER BY id_film DESC");
$films = $stmt->fetchAll();

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// ambil pesan jika ada
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Film</title>
</head>
<body>
    <h1>Daftar Film</h1>

    <?php if ($msg === 'created'): ?>
        <p style="color:green;">Film berhasil ditambahkan.</p>
    <?php elseif ($msg === 'updated'): ?>
        <p style="color:green;">Film berhasil diubah.</p>
    <?php elseif ($msg === 'deleted'): ?>
        <p style="color:green;">Film berhasil dihapus.</p>
    <?php elseif ($msg === 'exists'): ?>
        <p style="color:red;">Film sudah ada di watchlist kamu.</p>
    <?php elseif ($msg === 'added_watchlist'): ?>
        <p style="color:green;">Film berhasil ditambahkan ke watchlist.</p>
    <?php endif; ?>

    <p>
        <a href="../dashboard.php">Kembali ke Dashboard</a>
    </p>

    <?php if ($isAdmin): ?>
        <p>
            <a href="create.php">+ Tambah Film Baru</a>
        </p>
    <?php endif; ?>

    <?php if (empty($films)): ?>
        <p>Belum ada film.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Genre</th>
                    <th>Tahun</th>
                    <?php if ($isAdmin): ?>
                        <th>Aksi (Admin)</th>
                    <?php endif; ?>
                    <th>Watchlist</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($films as $film): ?>
                    <tr>
                        <td><?= htmlspecialchars($film['title']) ?></td>
                        <td><?= htmlspecialchars($film['genre']) ?></td>
                        <td><?= htmlspecialchars($film['tahun']) ?></td>

                        <?php if ($isAdmin): ?>
                            <td>
                                <a href="edit.php?id=<?= $film['id_film'] ?>">Edit</a> |
                                <a href="delete.php?id=<?= $film['id_film'] ?>"
                                   onclick="return confirm('Yakin ingin menghapus film ini?')">
                                    Hapus
                                </a>
                            </td>
                        <?php endif; ?>

                        <td>
                            <a href="../watchlist/add.php?id_film=<?= $film['id_film'] ?>">
                                Tambah ke Watchlist
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
