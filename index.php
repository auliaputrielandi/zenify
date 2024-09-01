<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenify</title>
    <link rel="stylesheet" href="assets/css/beranda.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!--  Navbar -->
    <?php
    require 'database/db.php';

    // Check if a session is already active before calling session_start()
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

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
                font-size: 1.5rem;
            }

            .nav-item .nav-link {
                color: #E33F3F;
                font-size: 1rem;
                font-weight: bold;
            }

            .btn-danger {
                font-size: 1rem;
            }

            .navbar-nav .nav-item {
                display: flex;
                align-items: center;
            }

            .modal-content {
                border-radius: 15px;
            }

            .modal-header {
                border-bottom: 1px solid #ddd;
            }

            .modal-title {
                font-weight: bold;
                color: #E33F3F;
            }

            .modal-body {
                font-size: 1.1rem;
                color: #333;
            }

            .modal-footer {
                border-top: 1px solid #ddd;
            }

            .btn-danger {
                background-color: #E33F3F;
                border-color: #E33F3F;
            }

            .btn-danger:hover {
                background-color: #d12e2e;
                border-color: #d12e2e;
            }

            .btn-secondary {
                background-color: #6c757d;
                border-color: #6c757d;
            }

            .btn-secondary:hover {
                background-color: #5a6268;
                border-color: #545b62;
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
                            <li class="nav-item d-flex align-items-center">
                                <a class="nav-link me-2" href="profil.php"><?php echo htmlspecialchars($username); ?></a>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#logoutModal">
                                    Logout
                                </button>
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

        <!-- Logout Modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">Apakah yakin ingin Logout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <a class="btn btn-danger" href="pages/landing_page/logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>



        <!-- Main Content -->
        <div class="beranda">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h1 class="h1">Selamat Datang di Zenify!!</h1>
                    <p class="mt-4 mb-4">
                        Zenify adalah aplikasi tes berbasis web yang dirancang khusus untuk karyawan.
                        Dengan Zenify, Anda dapat memahami kondisi diri Anda melalui berbagai tes kepribadian,
                        seperti tes keseimbangan kerja dan kehidupan, tingkat stres, burnout, dan masih banyak lagi.
                        Temukan wawasan berharga untuk meningkatkan kesejahteraan Anda di tempat kerja.
                    </p>
                    <a class="btn btn-danger mt-3" href="pages/landing_page/pilihan_tes.php">Try Now</a>
                </div>
                <div class="col-md-5">
                    <!-- <img src="img/1.png" alt="Zenify Illustration" class="img-fluid"> -->
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>