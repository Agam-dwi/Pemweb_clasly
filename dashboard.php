<?php
    include 'service/database.php';
    session_start();
    if (isset($_POST['logout'])) {
        session_destroy();
        echo '<meta http-equiv="refresh" content="0;url=index.php">';
    }

    

    if (isset($_POST['create_jadwal'])) {
        $id_pengguna = $_SESSION['is_login']; // pastikan session ini diset di login.php
        $hari = $_POST['hari'];
        $jam = $_POST['jam'];
        $matakuliah = $_POST['matakuliah'];
        $kelas = $_POST['kelas'];
        $dosen = $_POST['dosen'];
        $ruangan = $_POST['ruangan'];

        $stmt = $db->prepare("INSERT INTO jadwal (id_pengguna, hari, jam, matakuliah, kelas, dosen, ruangan) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_pengguna, $hari, $jam, $matakuliah, $kelas, $dosen, $ruangan]);

        echo "<p>Jadwal berhasil ditambahkan!</p>";
    }



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "layout/header.html"?>

    <h3>Selamat datang <?= $_SESSION["username"] ?></h3>
    <form action="dashboard.php" method="POST">
        <button type="submit" name="logout">logout</button>
    </form>

    <?php include "layout/footer.html"?>

    <h2>Tambah Jadwal Kuliah</h2>
    <form method="POST">
        <label>Hari:</label>
        <select name="hari" required>
            <option value="">Pilih Hari</option>
            <option>Senin</option>
            <option>Selasa</option>
            <option>Rabu</option>
            <option>Kamis</option>
            <option>Jumat</option>
        </select><br>

        <label>Jam:</label>
        <input type="time" name="jam" required><br>

        <label>Mata Kuliah:</label>
        <input type="text" name="matakuliah" required><br>

        <label>Kelas:</label>
        <input type="text" name="kelas" required><br>

        <label>Dosen:</label>
        <input type="text" name="dosen" required><br>

        <label>Ruangan:</label>
        <input type="text" name="ruangan" required><br>

        <button type="submit" name="create_jadwal">Simpan Jadwal</button>
    </form>

    <?php include "layout/footer.html"?>
    
    <h2>Jadwal Kuliah Saya</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>Hari</th>
            <th>Jam</th>
            <th>Mata Kuliah</th>
            <th>Kelas</th>
            <th>Dosen</th>
            <th>Ruangan</th>
        </tr>
    <?php
    $id_pengguna = $_SESSION['is_login'];
    $stmt = $db->prepare("SELECT * FROM jadwal WHERE id_pengguna = ?");
    $stmt->execute([$id_pengguna]);
    $result = $stmt->get_result();
    $jadwals = [];
    
    while ($row = $result->fetch_assoc()) {
        $jadwals[] = $row;
    }


    foreach ($jadwals as $jadwal) {
        echo "<tr>
            <td>{$jadwal['hari']}</td>
            <td>{$jadwal['jam']}</td>
            <td>{$jadwal['matakuliah']}</td>
            <td>{$jadwal['kelas']}</td>
            <td>{$jadwal['dosen']}</td>
            <td>{$jadwal['ruangan']}</td>
        </tr>";
    }
    ?>
    </table>

<a href="test.html">abc</a>

<!-- Alarm sound -->
<audio id="alarmSound" src="assets/alarm.mp3" preload="auto"></audio>

<script>
    function checkReminder() {
        const now = new Date();
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const currentDay = days[now.getDay()];
        const currentTime = now.toTimeString().slice(0,5); // "HH:MM"

        fetch("reminder.php")
            .then(response => response.json())
            .then(data => {
                data.forEach(jadwal => {
                    if (jadwal.hari === currentDay && jadwal.jam === currentTime) {
                        document.getElementById("alarmSound").play();
                        alert("⏰ Jadwal dimulai sekarang: " + jadwal.matakuliah);
                    }
                });
            });
    }

    // Cek setiap 30 detik
    setInterval(checkReminder, 30000);
</script>


</body>
</html>