<?php
// user/profile.php
session_start();

// kalau belum login, lempar ke login
if (!isset($_SESSION['id_user'])) {
    header('Location: ../views/login.html');
    exit;
}

require_once '../config/db.php';

// ambil data user berdasarkan id di session
$id_user = $_SESSION['id_user'];

$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id_user = ?");
$stmt->execute([$id_user]);
$user = $stmt->fetch();

if (!$user) {
    echo "User tidak ditemukan.";
    exit;
}

// cek kalau ada pesan sukses
$success = isset($_GET['success']) && $_GET['success'] == 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
</head>
<body>
    <h1>Profil Saya</h1>

    <?php if ($success): ?>
        <p style="color: green;">Profil berhasil diperbarui.</p>
    <?php endif; ?>

    <form action="update_profile.php" method="post">
        <div>
            <label>Nama</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div>
            <label>Email</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <p>
        <a href="../dashboard.php">Kembali ke Dashboard</a>
    </p>
</body>
</html>
