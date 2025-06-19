<?php
include 'service/database.php';
session_start();

if (!isset($_SESSION['is_login'])) {
    echo "<meta http-equiv='refresh' content='0;url=login.php'>";
    exit;
}

$id_pengguna = $_SESSION['is_login'];

// Tambah catatan
if (isset($_POST['buat_catatan'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];

    $stmt = $db->prepare("INSERT INTO catatan (id_pengguna, judul, isi) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_pengguna, $judul, $isi);
    $stmt->execute();
}

// Hapus catatan
if (isset($_GET['hapus'])) {
    $id_catatan = $_GET['hapus'];
    $stmt = $db->prepare("DELETE FROM catatan WHERE id_catatan = ? AND id_pengguna = ?");
    $stmt->bind_param("ii", $id_catatan, $id_pengguna);
    $stmt->execute();
    echo "<meta http-equiv='refresh' content='0;url=catatan.php'>";
    exit;
}

// Update catatan
if (isset($_POST['update_catatan'])) {
    $id_catatan = $_POST['id_catatan'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];

    $stmt = $db->prepare("UPDATE catatan SET judul = ?, isi = ? WHERE id_catatan = ? AND id_pengguna = ?");
    $stmt->bind_param("ssii", $judul, $isi, $id_catatan, $id_pengguna);
    $stmt->execute();
    echo "<meta http-equiv='refresh' content='0;url=catatan.php'>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catatan Saya</title>
</head>
<body>
    <?php include "layout/header.html"; ?>
    <h2>Tambah Catatan</h2>
    <form method="POST">
        <input type="text" name="judul" placeholder="Judul Catatan" required><br>
        <textarea name="isi" placeholder="Isi catatan..." rows="5" cols="50"></textarea><br>
        <button type="submit" name="buat_catatan">Simpan</button>
    </form>

    <h2>Catatan Saya</h2>
    <?php
    $stmt = $db->prepare("SELECT * FROM catatan WHERE id_pengguna = ? ORDER BY tanggal_dibuat DESC");
    $stmt->bind_param("i", $id_pengguna);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($catatan = $result->fetch_assoc()) {
        // Jika mode edit
        if (isset($_GET['edit']) && $_GET['edit'] == $catatan['id_catatan']) {
            ?>
            <form method="POST">
                <input type="hidden" name="id_catatan" value="<?= $catatan['id_catatan'] ?>">
                <input type="text" name="judul" value="<?= htmlspecialchars($catatan['judul']) ?>" required><br>
                <textarea name="isi" rows="5" cols="50"><?= htmlspecialchars($catatan['isi']) ?></textarea><br>
                <button type="submit" name="update_catatan">Update</button>
                <a href="catatan.php">Batal</a>
            </form>
            <?php
        } else {
            echo "<div style='border:1px solid #ccc; padding:10px; margin:10px 0;'>
                <strong>{$catatan['judul']}</strong> ({$catatan['tanggal_dibuat']})<br>
                <p>{$catatan['isi']}</p>
                <a href='catatan.php?edit={$catatan['id_catatan']}'>Edit</a> |
                <a href='catatan.php?hapus={$catatan['id_catatan']}' onclick='return confirm(\"Yakin hapus catatan ini?\")'>Hapus</a>
            </div>";
        }
    }
    ?>

    <?php include "layout/footer.html"; ?>
</body>
</html>
