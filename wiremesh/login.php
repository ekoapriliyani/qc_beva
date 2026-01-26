<?php
session_start();
require 'functions.php';

// Jika sudah login, tendang ke index
if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST["login"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM t_users WHERE email = '$email'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password (menggunakan password_verify jika di-hash, atau plain text)
        // Disarankan: if (password_verify($password, $row["password"]))
        if ($password === $row["password"]) {
            // Set session
            $_SESSION["login"] = true;
            $_SESSION["id_user"] = $row["id_user"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["role"] = $row["role"]; // Kita simpan role (inspector/supervisor/manager)

            header("Location: index.php");
            exit;
        }
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login QC System</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        .login-box { width: 350px; margin: 100px auto; padding: 30px; background: #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .login-box h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .login-box input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .login-box button { width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .error-msg { color: #e74c3c; text-align: center; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body style="background-color: #f4f7f6;">
    <div class="login-box">
        <h2>QC System Login</h2>
        <?php if(isset($error)) : ?>
            <p class="error-msg">Email atau Password salah!</p>
        <?php endif; ?>
        <form action="" method="post">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>