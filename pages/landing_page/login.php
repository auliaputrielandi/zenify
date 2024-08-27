<?php
require '../../database/db.php';
session_start();

// Cek apakah ada halaman sebelumnya yang diakses dan bukan halaman login
if (isset($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], 'login.php')) {
    $_SESSION['RedirectKe'] = $_SERVER['HTTP_REFERER'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_user, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verifikasi password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['id_user'] = $id;

            // Redirect ke halaman sebelumnya atau halaman default
            $URL = isset($_SESSION['RedirectKe']) ? $_SESSION['RedirectKe'] : '../wlb/tes-wlb.php';
            unset($_SESSION['RedirectKe']); // Hapus setelah digunakan
            header('Location: ' . $URL);
            exit();
        } else {
            echo "<script>
                alert('Password salah');
                window.location = 'login.php';
            </script>";
        }
    } else {
        echo "<script>
                alert('Akun belum terdaftar');
                window.location = 'login.php';
            </script>";
    }

    $stmt->close();
    $conn->close(); // Menutup koneksi database
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenify</title>
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="email" placeholder="Email" required>
            <br>
            <input type="password" name="password" placeholder="Password" required>
            <br>
            <button type="submit" name="login">Login</button>
        </form>
        <p style="font-size: 16px;">Belum punya akun? <span class="signup-link"
                onclick="location.href='signup.php'">Signup</span></p>
    </div>
</body>

</html>
