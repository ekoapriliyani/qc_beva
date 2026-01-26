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
        
        // Disarankan menggunakan password_verify jika password dihash
        if ($password === $row["password"]) {
            $_SESSION["login"] = true;
            $_SESSION["id_user"] = $row["id_user"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["role"] = $row["role"];

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Inspeksi QC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-maroon: #800000;
            --dark-maroon: #4d0000;
            --accent-gold: #ffd700;
            --white: #ffffff;
        }

        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            /* Background dengan gradasi maroon industrial */
            background: linear-gradient(135deg, var(--dark-maroon) 0%, var(--primary-maroon) 100%);
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            perspective: 1000px;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            text-align: center;
            transition: transform 0.3s ease;
        }

        /* Styling Logo */
        .logo-area {
            width: 80px;
            height: 80px;
            background: var(--primary-maroon);
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(128,0,0,0.3);
        }

        .logo-area i {
            font-size: 2.5rem;
            color: var(--white);
        }

        h2 {
            color: var(--dark-maroon);
            font-size: 1.8rem;
            margin-bottom: 5px;
            font-weight: 800;
        }

        p.subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        /* Form Styling */
        .form-group {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #eee;
            border-radius: 12px;
            outline: none;
            transition: 0.3s;
            font-size: 1rem;
        }

        .form-group input:focus {
            border-color: var(--primary-maroon);
            box-shadow: 0 0 8px rgba(128,0,0,0.1);
        }

        button {
            width: 100%;
            padding: 15px;
            background: var(--primary-maroon);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 10px rgba(128,0,0,0.2);
        }

        button:hover {
            background: var(--dark-maroon);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(128,0,0,0.3);
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            border-left: 5px solid #dc3545;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-text {
            margin-top: 25px;
            font-size: 0.8rem;
            color: #999;
        }

        /* Responsive adjustment for Mobile */
        @media (max-width: 480px) {
            .login-box {
                padding: 30px 20px;
            }
            h2 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <div class="logo-area">
                <!-- <i class="fas fa-shield-halved"></i> -->
                <img src="img/logo_beva.png" alt="logobeva">
            </div>

            <h2>QC SYSTEM</h2>
            <p class="subtitle">Quality Control Inspection Portal</p>

            <?php if(isset($error)) : ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i>
                    Email atau Password salah!
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Alamat Email" required autofocus>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Kata Sandi" required>
                </div>

                <button type="submit" name="login">
                    LOGIN SEKARANG <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                </button>
            </form>

            <p class="footer-text">&copy; 2026 Internal QC System - v2.0</p>
        </div>
    </div>

</body>
</html>