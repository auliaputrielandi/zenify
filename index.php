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
    <!-- Include Navbar -->
    <?php include 'pages/landing_page/navbar.php'; ?>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Selamat Datang di Website Zenify!!</h1>
                <p class="mt-4 mb-4">
                    Zenify adalah aplikasi tes berbasis web yang dirancang khusus untuk karyawan. 
                    Dengan Zenify, Anda dapat memahami kondisi diri Anda melalui berbagai tes kepribadian, 
                    seperti tes keseimbangan kerja dan kehidupan, tingkat stres, burnout, dan masih banyak lagi. 
                    Temukan wawasan berharga untuk meningkatkan kesejahteraan Anda di tempat kerja.
                </p>
                <a class="btn btn-danger mt-3" href="pages/landing_page/pilihan_tes.php">Try Now</a>
            </div>
            <div class="col-md-6 text-center">
                <!-- <img src="img/1.png" alt="Zenify Illustration" class="img-fluid"> -->
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
