<?php
session_start();
include "koneksi.php";

// Jika sudah login, langsung lempar ke index
if (isset($_SESSION['login'])) {
    header("Location: index.php"); exit;
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM t_users WHERE email = '$email'");

    // Cek Email
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Cek Password (Gunakan password_verify jika dihash, atau cek string biasa)
        // Disini saya asumsikan password masih plain text (disarankan pakai password_hash kedepannya)
        if ($password === $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_name'] = $row['name'];
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
    <title>Login - QC Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: #f8f9fc; 
            height: 100vh; 
            display: flex; 
            align-items: center;
            background-image: url("../img/bg2.png");
    background-size: cover;      /* Biar memenuhi layar */
    background-position: center; /* Posisi tengah */
    background-repeat: no-repeat;
        }
        .card-login { width: 400px; border-radius: 15px; border: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="card card-login shadow mx-auto">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="fas fa-paint-roller fa-3x text-primary"></i>
                <h4 class="mt-3 fw-bold">QC Project</h4>
                <p class="text-muted small">Silahkan login untuk masuk ke sistem</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2 small text-center">Email / Password salah!</div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>