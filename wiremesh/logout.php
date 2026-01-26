<?php
// Awali dengan memulai session untuk mengenali session yang ingin dihapus
session_start();

// Hapus semua variabel session
$_SESSION = [];

// Hancurkan session di server
session_unset();
session_destroy();

// Opsional: Hapus cookie session jika ada (untuk keamanan ekstra)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Arahkan user kembali ke halaman login
header("Location: login.php");
exit;
?>