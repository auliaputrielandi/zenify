<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

include '../../database/db.php';

// Mengambil data stres berdasarkan id_user
$id_user = $_SESSION['id_user'];

// Query untuk mengambil skor stres
$query = "SELECT depresi, kecemasan, stres FROM stres WHERE id_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Mengalikan skor dengan 2
    $depresi = $row['depresi'] * 2;
    $kecemasan = $row['kecemasan'] * 2;
    $stres = $row['stres'] * 2;

    // Menentukan kategori berdasarkan kriteria
    function getKategoriDepresi($score)
    {
        if ($score >= 0 && $score <= 9)
            return "Normal";
        if ($score >= 10 && $score <= 13)
            return "Ringan";
        if ($score >= 14 && $score <= 20)
            return "Sedang";
        if ($score >= 21 && $score <= 27)
            return "Berat";
        return "Sangat Berat";
    }

    function getKategoriKecemasan($score)
    {
        if ($score >= 0 && $score <= 7)
            return "Normal";
        if ($score >= 8 && $score <= 9)
            return "Ringan";
        if ($score >= 10 && $score <= 14)
            return "Sedang";
        if ($score >= 15 && $score <= 19)
            return "Berat";
        return "Sangat Berat";
    }

    function getKategoriStres($score)
    {
        if ($score >= 0 && $score <= 14)
            return "Normal";
        if ($score >= 15 && $score <= 18)
            return "Ringan";
        if ($score >= 19 && $score <= 25)
            return "Sedang";
        if ($score >= 26 && $score <= 33)
            return "Berat";
        return "Sangat Berat";
    }

    // Mendapatkan kategori untuk setiap skor
    $kategoriDepresi = getKategoriDepresi($depresi);
    $kategoriKecemasan = getKategoriKecemasan($kecemasan);
    $kategoriStres = getKategoriStres($stres);
} else {
    $depresi = $kecemasan = $stres = "Data tidak ditemukan";
    $kategoriDepresi = $kategoriKecemasan = $kategoriStres = "-";
}

// Query untuk mengambil nama user
$queryNama = "SELECT username FROM users WHERE id_user = ?";
$stmtNama = $conn->prepare($queryNama);
$stmtNama->bind_param("i", $id_user);
$stmtNama->execute();
$resultNama = $stmtNama->get_result();

if ($resultNama->num_rows > 0) {
    $rowNama = $resultNama->fetch_assoc();
    $nama = $rowNama['username'];
} else {
    $nama = "Nama tidak ditemukan";
}

$stmt->close();
$stmtNama->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenify</title>
    <link rel="stylesheet" href="../../assets/css/hasil-stres.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <!-- Include Navbar -->
    <?php include '../landing_page/navbar.php'; ?>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Hi <?php echo $nama; ?>!</h1>
                <p>Berikut adalah hasil tes depresi, kecemasan, dan stres mu</p>
            </div>
        </div>
    </div>
    <div class="container-isi">
        <h2>Hasil Tes Anda</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Score</th>
                        <th>Tingkat Keparahan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Depresi</td>
                        <td><?php echo $depresi; ?></td>
                        <td><?php echo $kategoriDepresi; ?></td>
                    </tr>
                    <tr>
                        <td>Kecemasan</td>
                        <td><?php echo $kecemasan; ?></td>
                        <td><?php echo $kategoriKecemasan; ?></td>
                    </tr>
                    <tr>
                        <td>Stres</td>
                        <td><?php echo $stres; ?></td>
                        <td><?php echo $kategoriStres; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <br>
        <br>
        <?php
        // keterangan depresi
        if ($kategoriDepresi == "Normal") {
            echo "<h4>Depresi normal</h4>";
            echo "<p>Depresi normal adalah kondisi di mana seseorang mengalami perasaan sedih atau kecewa, tetapi gejalanya tidak mengganggu aktivitas sehari-hari atau hubungan sosial.</p>";
        } elseif ($kategoriDepresi == "Ringan") {
            echo "<h4>Depresi ringanl</h4>";
            echo "<p>Depresi ringan adalah salah satu jenis depresi dengan gejala yang tidak terlalu intens atau berat. Meski demikian, kondisi ini tetap perlu ditangani dengan tepat agar tidak semakin memburuk yang berisiko memengaruhi kualitas hidup penderitanya</p>";
        } elseif ($kategoriDepresi == "Sedang") {
            echo "<h4>Depresi sedang</h4>";
            echo "<p>Skor Depresi Anda dalam kategori Sedang. Gejala depresi mungkin cukup mengganggu aktivitas harian Anda.</p>";
        } elseif ($kategoriDepresi == "Berat") {
            echo "<h4>Depresi berat</h4>";
            echo "<p>Depresi ini adalah bentuk depresi yang serius dan mengganggu kehidupan sehari-hari. Gejalanya melampaui depresi ringan dan sedang, termasuk perasaan putus asa, hilang harapan, dan bahkan berpikir tentang bunuh diri. Depresi ini adalah gangguan mental yang serius dan membutuhkan penanganan tenaga ahli atau profesional.</p>";
        } elseif ($kategoriDepresi == "Sangat Berat") {
            echo "<h4>Depresi sangat berat</h4>";
            echo "<p>Depresi ini adalah bentuk depresi yang paling serius dan mengganggu kehidupan sehari-hari. Gejalanya melampaui depresi ringan dan sedang, termasuk perasaan putus asa, hilang harapan, dan bahkan berpikir tentang bunuh diri. Depresi ini adalah gangguan mental yang serius dan membutuhkan penanganan tenaga ahli atau profesional.</p>";
        }

        // keterangan kecemasan
        if ($kategoriKecemasan == "Normal") {
            echo "<h4>Kecemasan normal</h4>";
            echo "<p>Perasaan cemas bisa muncul kapan saja dalam kehidupan sehari-hari. Pada tingkat ini, gejala cemas seperti gelisah, ketidaknyamanan, ketidaktenangan, dan perubahan emosional adalah hal yang normal dan dapat terjadi pada siapa saja. Meskipun disebut normal, rasa cemas pada tingkatan tertentu dapat menjadi tanda adanya masalah serius, seperti gangguan kecemasan.</p>";
        } elseif ($kategoriKecemasan == "Ringan") {
            echo "<h4>Kecemasan ringan</h4>";
            echo "<p>Pada tingkat ini, seseorang mungkin mengalami ketegangan dan perasaan tidak nyaman, tetapi masih dapat mengatasi cemas dengan baik.</p>";
        } elseif ($kategoriKecemasan == "Sedang") {
            echo "<h4>Kecemasan sedang</h4>";
            echo "<p>Pada tingkat ini, gejala cemas lebih kuat dan mempengaruhi konsentrasi serta kesejahteraan emosional.</p>";
        } elseif ($kategoriKecemasan == "Berat") {
            echo "<h4>Kecemasan berat</h4>";
            echo "<p>Pada tingkat ini, gejala cemas sangat mengganggu dan memerlukan perhatian lebih serius.</p>";
        } elseif ($kategoriKecemasan == "Sangat Berat") {
            echo "<h4>Kecemasan sangat berat</h4>";
            echo "<p>Pada tingkat ini, cemas dapat menghambat fungsi sehari-hari dan memerlukan intervensi profesional.</p>";
        }

        // keterangan stres
        if ($kategoriStres == "Normal") {
            echo "<h4>Stres normal</h4>";
            echo "<p>Stres normal adalah keadaan ketika seseorang merasa tertekan dalam batas wajar. Ini adalah reaksi alami terhadap situasi tertentu, seperti tantangan atau perubahan dalam hidup. Stres normal tidak merusak aspek fisiologis dan umumnya dialami oleh setiap orang, misalnya lupa, ketiduran, dikritik, atau kemacetan lalu lintas.</p>";
        } elseif ($kategoriStres == "Ringan") {
            echo "<h4>Stres ringanl</h4>";
            echo "<p>Stres ringan juga tidak merusak aspek fisiologis dan biasanya dirasakan oleh banyak orang. Contohnya termasuk situasi sehari-hari seperti kecilnya masalah atau ketidaknyamanan, seperti lupa membawa kunci atau terlambat ke pertemuan.</p>";
        } elseif ($kategoriStres == "Sedang") {
            echo "<h4>Stres sedang</h4>";
            echo "<p>Stres sedang memiliki dampak yang lebih signifikan pada individu. Gejalanya meliputi perasaan gelisah, ketegangan, dan mungkin gangguan tidur. Situasi yang menyebabkan stres sedang bisa lebih kompleks, seperti masalah pekerjaan atau hubungan.</p>";
        } elseif ($kategoriStres == "Berat") {
            echo "<h4>Stres berat</h4>";
            echo "<p>Stres berat dapat memengaruhi kesejahteraan fisik dan mental. Gejalanya meliputi kecemasan yang intens, gangguan tidur, dan perubahan perilaku. Stres berat dapat disebabkan oleh peristiwa traumatis atau tekanan yang berkepanjangan, seperti kehilangan orang yang dicintai atau masalah keuangan yang serius.</p>";
        } elseif ($kategoriStres == "Sangat Berat") {
            echo "<h4>Stres sangat berat</h4>";
            echo "<p>Stres sangat berat adalah tingkat stres yang paling merusak. Ini dapat menyebabkan gangguan jiwa dan masalah fisik kronis. Gejalanya meliputi depresi berat, gangguan tidur yang parah, dan ketidakmampuan untuk berfungsi secara normal. Stres sangat berat mungkin terjadi akibat peristiwa traumatis yang luar biasa atau tekanan yang berkepanjangan.</p>";
        }
        ?>
        <br>
        <div class="garis">
            <center><h4>Saran</h4></center>
            <img src="../../assets/images/tes.png" alt="" class="img-hasil">
        </div>


    </div>

    <!-- Image Below Container -->
    <div class="bottom-image">
        <img src="../../assets/images/hasil2.jpg" alt="2" class="img-fluid">
    </div>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>