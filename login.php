<?php
    include "service/database.php";
    session_start();
    
    $login_message ="";

    if (isset($_SESSION["is_login"])) {
        echo '<meta http-equiv="refresh" content="0;url=dashboard.php">';
    }

    if(isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM pengguna WHERE nama_pengguna='$username' AND password='$password'";
        $result = $db->query($sql);

        if($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $_SESSION["username"] = $data["nama_pengguna"];
            $_SESSION["is_login"] = $data["id_pengguna"]; // <--- ini kunci penting

            
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php">';

        }else { 
            $login_message ="Akun tidak ditemukan";
        }
        // $db->close();
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

    <h3>MASUKAN AKUN</h3>

    <i><?= $login_message?></i>

    <form action="login.php" method="POST">
        <input type="text" placeholder="username" name="username">
        <input type="password" placeholder="password" name="password">
        <button type="submit" name="login"> masuk</button>
    </form>
    
    <?php include "layout/footer.html";?>
</body>
</html>

