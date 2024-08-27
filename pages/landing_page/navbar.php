<?php
require __DIR__ . '/../../database/db.php';
session_start(); // Pastikan session_start() aktif

$is_logged_in = isset($_SESSION['id_user']);
$username = '';

if ($is_logged_in) {
    $user_id = $_SESSION['id_user'];
    $sql = "SELECT username FROM users WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenify</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            border-bottom: 1px solid #ddd;
            padding: 1rem;
        }

        .navbar-brand {
            color: #E33F3F;
            font-weight: bold;
            font-size: 24px;
        }

        .nav-item .nav-link {
            color: #E33F3F;
            font-size: 18px;
            font-weight: bold;
        }

        .btn-danger {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand text-danger" href="../../index.php">Zenify</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profil.php">Hi <?php echo htmlspecialchars($username); ?></a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-danger" href="pages/landing_page/login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); // Close koneksi database ?>
