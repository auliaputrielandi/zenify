<?php
require '../../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if ($password == $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "<script>
                alert('Email sudah digunakan. Silakan gunakan email lain.');
                window.location = 'signup.php';
                </script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss",$email, $username, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>
                alert('Signup berhasil!'); 
                window.location = 'login.php';
                </script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        echo "<script>
            alert('Konfirmasi password tidak sesuai');
            window.location = 'signup.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenify</title>
    <link rel="stylesheet" href="../../assets/css/signup.css">
</head>

<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <form action="" method="POST">
            <!-- <input type="text" name="email" placeholder="Email" required><br><br> -->
            <input type="text" name="email" placeholder="Email" required><br><br>
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="password" name="password" placeholder="Create Password" required><br><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
            <button type="submit" value="SIGNUP" name="submit">Signup</button>
        </form>
    </div>
</body>

</html>
