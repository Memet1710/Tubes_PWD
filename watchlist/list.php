<?php
// watchlist/list.php
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: ../views/login.html');
    exit;
}

require_once '../config/db.php';

$id_user = $_SESSION['id_user'];

$stmt = $pdo->prepare("
    SELECT w.id_watchlist, w.created_at,
           f.id_film, f.title, f.genre, f.tahun
    FROM watchlist w
    JOIN film f ON w.id_film = f.id_film
    WHERE w.id_user = ?
    ORDER BY w.created_at DESC
");
$stmt->execute([$id_user]);
$items = $stmt->fetchAll();

$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Watchlist Saya</title>
</head>
<body>
    <h1>Watchlist Saya</h1>

    <?php if ($msg === 'deleted'): ?>
        <p style="color:green;">Film dihapus dari watchlist.</p>
    <?php endif; ?>

    <p>
        <a href="../dashboard.php">Kembali ke Dashboard</a> |
        <a href="../film/list.php">Lihat Daftar Film</a>
    </p>

    <?php if (empty($items)): ?>
        <p>Watchlist kamu masih kosong. Tambahkan film dari daftar film.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Genre</th>
                    <th>Tahun</th>
                    <th>Ditambahkan Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td><?= htmlspecialchars($item['genre']) ?></td>
                        <td><?= htmlspecialchars($item['tahun']) ?></td>
                        <td><?= htmlspecialchars($item['created_at']) ?></td>
                        <td>
                            <a href="delete.php?id=<?= $item['id_watchlist'] ?>"
                               onclick="return confirm('Hapus dari watchlist?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
