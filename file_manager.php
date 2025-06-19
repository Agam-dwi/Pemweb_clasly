<?php
include 'service/database.php';
session_start();

if (!isset($_SESSION['is_login'])) {
    echo "<meta http-equiv='refresh' content='0;url=login.php'>";
    exit;
}

$id_pengguna = $_SESSION['is_login'];

// === Proses Upload File ===
if (isset($_POST['upload']) && isset($_FILES['file'])) {
    $fileName = $_FILES['file']['name'];
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $targetDir = "uploads/";
    $uniqueName = time() . "_" . basename($fileName);
    $targetPath = $targetDir . $uniqueName;

    if (move_uploaded_file($fileTmp, $targetPath)) {
        $stmt = $db->prepare("INSERT INTO file_upload (id_pengguna, nama_file, path_file, tipe_file, ukuran_file) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $id_pengguna, $fileName, $targetPath, $fileType, $fileSize);
        $stmt->execute();
        echo "<p>✅ Upload berhasil!</p>";
    } else {
        echo "<p>❌ Upload gagal.</p>";
    }
}

// === Proses Hapus File ===
if (isset($_GET['hapus'])) {
    $id_file = $_GET['hapus'];

    $stmt = $db->prepare("SELECT path_file FROM file_upload WHERE id_file = ? AND id_pengguna = ?");
    $stmt->bind_param("ii", $id_file, $id_pengguna);
    $stmt->execute();
    $result = $stmt->get_result();
    $file = $result->fetch_assoc();

    if ($file && file_exists($file['path_file'])) {
        unlink($file['path_file']); // Hapus fisik
    }

    $stmt = $db->prepare("DELETE FROM file_upload WHERE id_file = ? AND id_pengguna = ?");
    $stmt->bind_param("ii", $id_file, $id_pengguna);
    $stmt->execute();

    echo "<meta http-equiv='refresh' content='0;url=file_manager.php'>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Manager</title>
</head>
<body>
    <?php include "layout/header.html"; ?>

    <h2>Upload File</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit" name="upload">Upload</button>
    </form>

    <h2>File Saya</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>Nama File</th>
            <th>Tipe</th>
            <th>Ukuran</th>
            <th>Download</th>
            <th>Baca</th>
            <th>Hapus</th>
        </tr>
        <?php
        $stmt = $db->prepare("SELECT * FROM file_upload WHERE id_pengguna = ? ORDER BY tanggal_upload DESC");
        $stmt->bind_param("i", $id_pengguna);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($file = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$file['nama_file']}</td>
                <td>{$file['tipe_file']}</td>
                <td>" . round($file['ukuran_file'] / 1024, 2) . " KB</td>
                <td><a href='{$file['path_file']}' download>Download</a></td>
                <td><a href='{$file['path_file']}' target='_blank'>Baca</a></td>
                <td><a href='file_manager.php?hapus={$file['id_file']}' onclick='return confirm(\"Hapus file ini?\")'>Hapus</a></td>
            </tr>";
        }
        ?>
    </table>

    <?php include "layout/footer.html"; ?>
</body>
</html>
