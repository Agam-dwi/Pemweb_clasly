<?php
    include "service/database.php";
    session_start();

    $register_message = "";

    if (isset($_SESSION["is_login"])) {
        echo '<meta http-equiv="refresh" content="0;url=dashboard.php">';
    }

    if(isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            $sql = "INSERT INTO pengguna (nama_pengguna, password) VALUES ('$username', '$password')";

            if($db->query($sql)) {
                $register_message = "Daftar akun berhasil, silahkan login";
                echo '<meta http-equiv="refresh" content="0;url=login.php">';
            }else {
                echo "Daftar akun gagal, silahkan coba lagi";
            }
        } catch (mysqli_sql_exception) {
            $register_message = "username sudah digunakan, silahkan gunakan yang lain";
        }

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
    <?php include "layout/header.html";?>

    <h3>Daftar Akun</h3>

    <i> <?= $register_message ?> </i>
    <form action="register.php" method="POST">
        <input type="text" placeholder="username" name="username">
        <input type="password" placeholder="password" name="password">
        <button type="submit" name="register"> daftar sekarang</button>
    </form>
    
    <?php include "layout/footer.html";?>

    
</body>
</html>