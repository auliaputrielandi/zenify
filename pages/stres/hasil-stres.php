<?php
// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}


// function getGeminiRecommendations($kategoriDepresi, $kategoriKecemasan, $kategoriStres)
// {
//     $api_key = 'AIzaSyDxlbLcuzMcIqVeIFWk0qfd0PmDqGxmnvw'; // Ganti dengan API key Gemini Anda
//     $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=' . $api_key;

//     $prompt = "Berdasarkan hasil tes DASS-21:
//     Depresi: {$kategoriDepresi}
//     Kecemasan: {$kategoriKecemasan}
//     Stres: {$kategoriStres}

//     Berikan 6 saran singkat untuk mengatasi Depresi, Kecemasan, dan Stres sesuai dengan hasil tingkat stres tadi. Setiap saran maksimal 10 kata dan harus spesifik, actionable, berdasarkan data yang valid, dan kata-kata yang mudah dipahami oleh user.

//     Format jawaban:
//     1. [Saran 1]
//     2. [Saran 2]
//     3. [Saran 3]
//     4. [Saran 4]
//     5. [Saran 5]
//     6. [Saran 6]";

//     $data = [
//         'contents' => [
//             [
//                 'parts' => [
//                     ['text' => $prompt]
//                 ]
//             ]
//         ],
//         'generationConfig' => [
//             'temperature' => 0.7,
//             'maxOutputTokens' => 150,
//         ]
//     ];

//     $options = [
//         'http' => [
//             'method' => 'POST',
//             'header' => 'Content-Type: application/json',
//             'content' => json_encode($data)
//         ]
//     ];

//     $context = stream_context_create($options);
//     $response = file_get_contents($url, false, $context);

//     if ($response === FALSE) {
//         return "Maaf, terjadi kesalahan saat menghasilkan rekomendasi.";
//     }

//     $result = json_decode($response, true);
//     return $result['candidates'][0]['content']['parts'][0]['text'];
// }


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
    // $recommendations = getGeminiRecommendations($kategoriDepresi, $kategoriKecemasan, $kategoriStres);

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
    <div class="hasil-stres">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Hi <?php echo $nama; ?>!</h1>
                <p>Berikut adalah hasil tes depresi, kecemasan, dan stres mu</p>
            </div>
        </div>
    </div>
    <div class="hasil-stres-isi">
        <h2>Hasil Tes Anda</h2>
        <div class="table-hasil-stres">
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
            echo "<p>Perasaan cemas bisa muncul kapan saja dalam kehidupan sehari-hari. Pada tingkat ini, gejala cemas seperti gelisah, ketidaknyamanan, ketidaktenangan, dan perubahan emosional adalah hal yang normal dan dapat terjadi pada siapa saja.</p>";
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
            echo "<h4>Stres ringan</h4>";
            echo "<p>Stres ringan adalah stres yang tidak merusak aspek fisiologis. Stres ringan umumnya dirasakan oleh setiap orang misalnya lupa, ketiduran, dikritik, dan kemacetan. Stres ringan sering dialami pada kehidupan sehari-hari dan kondisi ini dapat membantu seseorang untuk waspada. Stres ringan tidak akan menimbulkan penyakit kecuali jika terjadi berkepanjangan.</p>";
        } elseif ($kategoriStres == "Sedang") {
            echo "<h4>Stres sedang</h4>";
            echo "<p> stres sedang yaitu situasi yang tidak terselesaikan dengan rekan, keluarga yang sakit, atau ketidakhadiran yang lama dari anggota keluarga. Gejala stres sedang yaitu sakit perut, mules, otot-otot terasa tegang, perasaan tegang, gangguan tidur, dan badan terasa ringan.</p>";
        } elseif ($kategoriStres == "Berat") {
            echo "<h4>Stres berat</h4>";
            echo "<p>Stres berat umumnya terjadi ketika seseorang mendapatkan tekanan yang berlebihan. Hal ini bisa dipicu oleh berbagai faktor, seperti masalah keluarga, kehilangan orang terkasih, beban pekerjaan, atau menderita penyakit kronis tertentu. Secara umum, stres berat dapat menimbulkan gejala tertentu, seperti sulit tidur, mudah marah, kelelahan, perubahan nafsu makan, mudah sakit, sulit berkonsentrasi, peningkatan detak jantung, berkeringat berlebihan.</p>";
        } elseif ($kategoriStres == "Sangat Berat") {
            echo "<h4>Stres sangat berat</h4>";
            echo "<p>Stres sangat berat adalah tingkat stres yang paling merusak. Ini dapat menyebabkan gangguan jiwa dan masalah fisik kronis. Gejalanya meliputi depresi berat, gangguan tidur yang parah, dan ketidakmampuan untuk berfungsi secara normal. Stres sangat berat mungkin terjadi akibat peristiwa traumatis yang luar biasa atau tekanan yang berkepanjangan.</p>";
        }
        ?>
        <br>
        <div class="garis"></div>
        <br>
        <center>
            <h4>Saran</h4>
        </center>
        <br>
        <br>
        <div class="row">
            <!-- Kolom untuk gambar -->
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <img src="../../assets/images/tes.png" alt="" class="img-hasil"
                    style="max-width: 80%; height: auto; width: 350px;">
            </div>

            <!-- Kolom untuk rekomendasi -->
            <div class="col-md-6">
                <div class="recommendations-hasil-stres" style="padding: 15px;">
                    <?php
                    if ($kategoriDepresi == "Normal") {
                        echo "<ul style='list-style-type: disc; padding-left: 20px;'>";
                            echo "<li>Berbicaralah dengan teman, keluarga, atau orang yanga anda percayai.</li>";
                            echo "<li>Terlibat dalam aktivitas fisik seperti berjalan kaki, bersepeda atau berolahraga ringan.</li>";
                        } elseif ($kategoriDepresi == "Ringan") {
                            echo "<li>Berbicaralah dengan teman, keluarga, atau orang yang anda percayai.</li>";
                            echo "<li>Praktikan teknik relaksasi, seperti meditasi atau yoga.</li>";
                            echo "<li>Terlibat dalam aktivitas fisik seperti berjalan kaki, bersepeda atau berolahraga ringan.</li>";
                        } elseif ($kategoriDepresi == "Sedang") {
                            echo "<li>Konsultasikan dengan profesional kesehatan mental untuk terapi kognitif perilaku (CBT) atau terapi lainnya.</li>";
                            echo "<li>Pertimbangkan penggunaan obat antidepresan, yang harus diresepkan oleh dokter.</li>";
                        } elseif ($kategoriDepresi == "Berat") {
                            echo "<li>Konsultasikan dengan profesional kesehatan mental untuk terapi kognitif perilaku (CBT) atau terapi lainnya.</li>";
                            echo "<li></li>";
                        } elseif ($kategoriDepresi == "Sangat Berat") {
                            echo "<li>Perawatan segera, hubungi layanan darurat atau konsultasikan konsultasikan dengan profesional kesehatan mental.</li>";
                            echo "<li>Terapi obat, dan dukungan sosial sangat penting.</li>";
                        }
                        echo "</ul>";
                        
                    if ($kategoriKecemasan == "Normal") {
                        echo "<ul style='list-style-type: disc; padding-left: 20px;'>";
                            echo "<li></li>";
                            echo "<li></li>";
                        } elseif ($kategoriKecemasan == "Ringan") {
                            echo "<li></li>";
                            echo "<li></li>";
                            echo "<li></li>";
                        } elseif ($kategoriKecemasan == "Sedang") {
                            echo "<li></li>";
                            echo "<li></li>";
                        } elseif ($kategoriKecemasan == "Berat") {
                            echo "<li></li>";
                            echo "<li></li>";
                        } elseif ($kategoriKecemasan == "Sangat Berat") {
                            echo "<li></li>";
                            echo "<li></li>";
                        }
                        echo "</ul>";

                    if ($kategoriStres == "Normal") {
                        echo "<ul style='list-style-type: disc; padding-left: 20px;'>";
                            echo "<li></li>";
                            echo "<li></li>";
                        } elseif ($kategoriStres == "Ringan") {
                            echo "<li></li>";
                            echo "<li></li>";
                            echo "<li></li>";
                        } elseif ($kategoriStres == "Sedang") {
                            echo "<li></li>";
                            echo "<li></li>";
                        } elseif ($kategoriStres == "Berat") {
                            echo "<li></li>";
                            echo "<li></li>";
                        } elseif ($kategoriStres == "Sangat Berat") {
                            echo "<li></li>";
                            echo "<li></li>";
                        }
                        echo "</ul>";
                            
                                
                    ?>


                    <!-- manggil saran dari AI -->
                    <?php
                    // $recommendations_array = explode("\n", $recommendations);
                    // echo "<ul style='list-style-type: disc; padding-left: 20px;'>";
                    // foreach ($recommendations_array as $recommendation) {
                    //     $recommendation = trim($recommendation);
                    //     if (!empty($recommendation) && strpos($recommendation, '.') !== false) {
                    //         $recommendation = substr($recommendation, strpos($recommendation, '.') + 1);
                    //         echo "<li>" . htmlspecialchars(trim($recommendation)) . "</li>";
                    //     }
                    // }
                    // echo "</ul>";
                    ?>
                </div>
                <p>Semoga saran di atas membantu Anda. Penting untuk diingat bahwa tes ini hanya sebagai panduan awal.
                    Jika Anda mengalami gejala yang
                    mengganggu, jangan ragu untuk berkonsultasi dengan profesional kesehatan mental!!😊😊</p>
            </div>
        </div>

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