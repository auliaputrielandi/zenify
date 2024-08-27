<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenify</title>
    <link rel="stylesheet" href="../../assets/css/pilihan_tes.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container hero-section">
        <h3 class="section-title">Tes Terpopuler</h3>
        <p class="section-subtitle">Berikut adalah tes yang paling populer dan direkomendasikan. Isi sekarang dan
            temukan jawabannya!</p>

        <div class="row">
            <div class="col-md-6 d-flex align-items-stretch mb-4">
                <div class="card">
                    <img src="../../assets/images/a.png" alt="Work-Life Balance" class="img-fluid">
                    <div class="card-body">
                        <h5 class="card-title">Work-Life Balance</h5>
                        <div class="card-text-container">
                        <p class="card-text">
                            Tes Work-Life Balance adalah sebuah penilaian yang dirancang untuk mengukur
                            seberapa seimbang kehidupan seseorang antara tuntutan pekerjaan dan kehidupan pribadi.
                            Penasaran seberapa seimbang hidupmu? Yuk isi tes Work-Life Balance sekarang juga!
                        </p>
                        <a href="../wlb/petunjuk-wlb.php" class="btn btn-custom">Ikuti tes</a>
                     </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex align-items-stretch mb-4">
                <div class="card">
                    <img src="../../assets/images/b.png" alt="Stress & Burnout" class="img-fluid">
                    <div class="card-body">
                        <h5 class="card-title">Depresi, Kecemasan, dan Stres</h5>
                        <div class="card-text-container">
                        <p class="card-text">
                            Bosan merasa lelah, gelisah, atau sedih? Mungkin saatnya untuk mengecek kondisi mentalmu.
                            Tes ini adalah cara yang mudah dan cepat untuk mengetahui tingkat depresi, kecemasan, dan
                            stres yang kamu alami. Yuk, isi tes Depresi, Kecemasan, dan Stres sekarang!
                        </p>
                        <a href="../stres/petunjuk-stres.php" class="btn btn-custom">Ikuti tes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>