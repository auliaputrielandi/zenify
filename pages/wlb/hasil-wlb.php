<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}
// Fetch the nickname based on the work-life balance scores
// $nickname = getGeminiRecommendations($satisfaction_percentage, $time_percentage, $involvement_percentage);

// function getGeminiNickname($satisfaction_percentage, $time_percentage, $involvement_percentage)
// {
//     $api_key = 'AIzaSyDxlbLcuzMcIqVeIFWk0qfd0PmDqGxmnvw'; // Ganti dengan API key Gemini Anda
//     $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key;

//     $prompt = "Berdasarkan hasil tes work-life balance:
//     Satisfaction: {$satisfaction_percentage}%
//     Time: {$time_percentage}%
//     Involvement: {$involvement_percentage}%

//     Berikan 1 julukan yang positif, sesuai, bagus, dan unik.

//     Format dan contoh jawaban:
//     Si Pengatur Waktu Andal
//     Si Puas dan Berdedikasi
//     Si Produktif dan Berperan Aktif
//     Si Tepat Waktu dan Fokus
//     Si Efisien dan Berkontribusi Penuh
//     Si Harmonis dan Komitmen
//     Si Bahagia dalam Memberikan Kontribusi
//     Si Seimbang dalam Peran
//     Si Produktif";

//     $data = [
//         'prompt' => [
//             'parts' => [
//                 ['text' => $prompt]
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
//         error_log("Error fetching data from API: " . error_get_last()['message']);
//         return "Maaf, terjadi kesalahan saat menghasilkan rekomendasiiiii.";
//     }

//     $result = json_decode($response, true);
//     if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
//         return $result['candidates'][0]['content']['parts'][0]['text'];
//     } else {
//         error_log("Unexpected API response: " . $response);
//         return "Maaf, terjadi kesalahan saat menghasilkan rekomendasi.";
//     }
// }


// function getGeminiWLBAspectInterpretations($satisfaction_percentage, $time_percentage, $involvement_percentage)
// {
//     $api_key = 'AIzaSyDxlbLcuzMcIqVeIFWk0qfd0PmDqGxmnvw'; // Ganti dengan API key Gemini Anda
//     $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key;

//     $prompt = "Berikan interpretasi (maksimal 100 kata) untuk masing-masing aspek work-life balance berikut:
//     1. Kepuasan (Satisfaction): {$satisfaction_percentage}%
//     2. Waktu (Time): {$time_percentage}%
//     3. Keterlibatan (Involvement): {$involvement_percentage}%

//     Format jawaban:
//     Kepuasan: [interpretasi]
//     Waktu: [interpretasi]
//     Keterlibatan: [interpretasi]";

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
//             'maxOutputTokens' => 200,
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
//         return [
//             'Kepuasan' => 'Maaf, terjadi kesalahan saat menghasilkan interpretasi.',
//             'Waktu' => 'Maaf, terjadi kesalahan saat menghasilkan interpretasi.',
//             'Keterlibatan' => 'Maaf, terjadi kesalahan saat menghasilkan interpretasi.'
//         ];
//     }

//     $result = json_decode($response, true);
//     $content = $result['candidates'][0]['content']['parts'][0]['text'];

//     // Memisahkan interpretasi menjadi array
//     $interpretations = [
//         'Kepuasan' => '',
//         'Waktu' => '',
//         'Keterlibatan' => ''
//     ];

//     foreach (explode("\n", $content) as $line) {
//         $parts = explode(': ', $line, 2);
//         if (count($parts) == 2) {
//             $key = trim($parts[0]);
//             $value = trim($parts[1]);
//             if (array_key_exists($key, $interpretations)) {
//                 $interpretations[$key] = $value;
//             }
//         }
//     }

//     return $interpretations;

// }

// function getGeminiRecommendations($satisfaction_percentage, $time_percentage, $involvement_percentage)
// {
//     $api_key = 'AIzaSyDxlbLcuzMcIqVeIFWk0qfd0PmDqGxmnvw'; // Ganti dengan API key Gemini Anda
//     $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key;

//     $prompt = "Berdasarkan hasil tes work-life balance:
//     Satisfaction: {$satisfaction_percentage}%
//     Time: {$time_percentage}%
//     Involvement: {$involvement_percentage}%

//     Berikan 10 saran untuk meningkatkan work-life balance. Setiap saran maksimal 15 kata dan harus spesifik serta actionable.

//     Format dan contoh jawaban:
//     1. [Luangkan waktu untuk melakukan aktivitas di luar pekerjaan, seperti berolahraga, berkumpul dengan keluarga, atau mengejar hobi]
//     2. [Prioritaskan tugas-tugas yang penting dan atur jadwal dengan baik]
//     3. [Hindari bekerja berlebihan dan berikan waktu untuk istirahat dan rekreasi]
//     4. [Karyawan harus berkomunikasi dengan atasan dan rekan kerja tentang kebutuhan pribadi mereka]
//     5. [Atur prioritas pekerjaan]
//     6. [Mengembangkan hobi baru]
//     7. [Berpartisipasi dalam kegiatan sosial]
//     8. [Mengatur waktu untuk relaksasi]
//     9. [Komunikasikan jika butuh bantuan]
//     10. [Hindari membawa pekerjaan ke rumah]";

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

$id_user = $_SESSION['id_user']; // Ambil id_user dari session

// Mengambil data work-life balance terbaru berdasarkan id_user
$query = "SELECT wlb.`satisfaction-balance`, wlb.`time-balance`, wlb.`involvement-balance`, users.username 
          FROM `wlb` 
          JOIN `users` ON wlb.`id_user` = users.`id_user` 
          WHERE wlb.`id_user` = ? 
          ORDER BY wlb.`id_wlb` DESC 
          LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['id_user']);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $satisfaction_balance = $row['satisfaction-balance'];
    $time_balance = $row['time-balance'];
    $involvement_balance = $row['involvement-balance'];

    // Menghitung total skor
    $total = $satisfaction_balance + $time_balance + $involvement_balance;

    // Menghitung persentase untuk setiap indikator
    $satisfaction_percentage = ($total > 0) ? ($satisfaction_balance / $total) * 100 : 0;
    $time_percentage = ($total > 0) ? ($time_balance / $total) * 100 : 0;
    $involvement_percentage = ($total > 0) ? ($involvement_balance / $total) * 100 : 0;
    // $interpretations = getGeminiWLBAspectInterpretations($satisfaction_percentage, $time_percentage, $involvement_percentage);
    // $recommendations = getGeminiRecommendations($satisfaction_percentage, $time_percentage, $involvement_percentage);
    // $nickname = getGeminiNickname($satisfaction_percentage, $time_percentage, $involvement_percentage);
} else {
    $satisfaction_percentage = $time_percentage = $involvement_percentage = 0;
}

//Menentukan hasil presentase dari ketiga indikator
// Menentukan hasil persentase gabungan dari ketiga indikator
$total_max_score = 84; // Anggap total maksimal skor adalah 84 (misalnya 28 per indikator)
$total_score = $satisfaction_balance + $time_balance + $involvement_balance;

// Menghitung persentase gabungan
$combined_percentage = ($total_max_score > 0) ? ($total_score / $total_max_score) * 100 : 0;



//Menentukan nickname
$nicknames = [
    'time' => ["Master Waktu", "Pengatur Jam", "Efisiensi Pro", "Manajer Waktu", "Ahli Pengelola Waktu", "Sang Efisien"],
    'satisfaction' => ["Pemburu Kepuasan", "Raja Kebahagiaan", "Pencari Senyum", "Penggembira Sejati", "Pencari Kebahagiaan", "Penyuka Kepuasan"],
    'involvement' => ["Penggerak Utama", "Aktivis Terdepan", "Pemain Kunci", "Inisiator", "Penggerak Dinamis", "Penggerak Aktif"],
    'time_satisfaction' => ["Arsitek Harmoni", "Seimbang Bahagia", "Pengatur Harmoni", "Harmoni Waktu", "Manajer Bahagia", "Penyeimbang Hidup"],
    'time_involvement' => ["Efisiensi Aktif", "Koordinator Dinamis", "Penggerak Waktu", "Manajer Aktif", "Pengatur Waktu", "Penggerak Efisien"],
    'satisfaction_involvement' => ["Penggembira Aktif", "Penyemangat", "Penggerak Bahagia", "Motivator", "Penggerak Positif", "Penyemangat Dinamis"],
    'balance' => ["Penyeimbang", "Zen Master", "Harmonis", "Pengatur Seimbang", "Maestro Seimbang", "Arsitek Kehidupan"]
];

if ($time_percentage > $satisfaction_percentage && $time_percentage > $involvement_percentage) {
    $nickname = $nicknames['time'][array_rand($nicknames['time'])];
} elseif ($satisfaction_percentage > $time_percentage && $satisfaction_percentage > $involvement_percentage) {
    $nickname = $nicknames['satisfaction'][array_rand($nicknames['satisfaction'])];
} elseif ($involvement_percentage > $time_percentage && $involvement_percentage > $satisfaction_percentage) {
    $nickname = $nicknames['involvement'][array_rand($nicknames['involvement'])];
} elseif ($time_percentage == $satisfaction_percentage && $time_percentage > $involvement_percentage) {
    $nickname = $nicknames['time_satisfaction'][array_rand($nicknames['time_satisfaction'])];
} elseif ($time_percentage == $involvement_percentage && $time_percentage > $satisfaction_percentage) {
    $nickname = $nicknames['time_involvement'][array_rand($nicknames['time_involvement'])];
} elseif ($satisfaction_percentage == $involvement_percentage && $satisfaction_percentage > $time_percentage) {
    $nickname = $nicknames['satisfaction_involvement'][array_rand($nicknames['satisfaction_involvement'])];
} elseif ($time_percentage == $satisfaction_percentage && $time_percentage == $involvement_percentage) {
    $nickname = $nicknames['balance'][array_rand($nicknames['balance'])];
} else {
    $nickname = "Unik";
}


$queryNama = "SELECT username FROM users WHERE id_user = ?";
$stmtNama = $conn->prepare($queryNama);

// Periksa apakah statement berhasil disiapkan
if ($stmtNama) {
    $stmtNama->bind_param("i", $id_user);
    $stmtNama->execute();
    $resultNama = $stmtNama->get_result();

    if ($resultNama->num_rows > 0) {
        $rowNama = $resultNama->fetch_assoc();
        $nama = $rowNama['username'];
    } else {
        $nama = "Nama tidak ditemukan";
    }

    $stmtNama->close();
} else {
    // Handle error jika statement tidak dapat disiapkan
    $nama = "Query gagal diproses";
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenify</title>
    <link rel="stylesheet" href="../../assets/css/hasil-wlb.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Chart.js Plugin untuk Data Labels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    


</head>

<body>
    <!-- Include Navbar -->
    <?php include '../landing_page/navbar.php'; ?>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 mb-4 mb-md-0">
                <h1>Hi <?php echo htmlspecialchars($nama); ?>!</h1>
                <p>Berikut adalah hasil tes work-life balance mu</p>
            </div>
        </div>
    </div>
    <div class="container-isi">
        <div class="row">
            <div class="col-12 col-md-6 text-center">
                <h2 style="color: #D0434D; text-align: center; margin-top: 115px;">
                    <?php echo $nickname ?>
                    <br>
                    <br>
                    <?php echo number_format($combined_percentage, 2) . "%" ?>
                </h2>
                <!-- <p>keterangan singkat</p> -->
            </div>
            <div class="col-12 col-md-6 text-center mb-4 mb-md-0">
                <canvas id="resultChart"></canvas>
            </div>
        </div>

        <!-- penjelasan hasil -->
        <!-- satisfaction-balance -->
        <h4>Kepuasan (Satisfaction)</h4>
        <!-- <p>Dengan persentase <?php echo round($satisfaction_percentage, 2); ?>%, spek ini menunjukkan: -->
            <?php
            if ($satisfaction_balance >= 0 && $satisfaction_balance <= 5) {
                echo "Anda merasa sangat tidak puas dengan keseimbangan antara pekerjaan dan kehidupan pribadi Anda. Mungkin pekerjaan Anda mengambil alih sebagian besar waktu dan energi Anda, sehingga Anda merasa tidak punya waktu untuk diri sendiri atau kegiatan yang Anda nikmati. Ini bisa membuat Anda merasa sangat stres dan lelah.";
            } elseif ($satisfaction_balance >= 6 && $satisfaction_balance <= 11) {
                echo "Anda merasa tidak puas dengan keseimbangan antara pekerjaan dan kehidupan pribadi Anda. Meskipun ada beberapa momen yang memuaskan, secara keseluruhan Anda merasa bahwa pekerjaan sering kali mengganggu kehidupan pribadi Anda. Ini bisa membuat Anda merasa tertekan dan sulit untuk menyeimbangkan kedua aspek ini.";
            } elseif ($satisfaction_balance >= 12 && $satisfaction_balance <= 17) {
                echo "Anda merasa cukup puas dengan keseimbangan antara pekerjaan dan kehidupan pribadi Anda. Ada saat-saat di mana pekerjaan mungkin mengambil lebih banyak waktu, tetapi secara keseluruhan Anda merasa mampu menyeimbangkan keduanya. Anda mungkin masih menghadapi beberapa tantangan, tetapi Anda bisa mengelolanya dengan cukup baik.";
            } elseif ($satisfaction_balance >= 18 && $satisfaction_balance <= 23) {
                echo "Anda merasa puas dengan keseimbangan antara pekerjaan dan kehidupan pribadi Anda. Anda mampu mengelola waktu dan energi Anda dengan baik antara pekerjaan dan aktivitas di luar pekerjaan. Anda merasa bahwa pekerjaan Anda tidak terlalu mengganggu kehidupan pribadi Anda dan Anda bisa menikmati keduanya.";
            } elseif ($satisfaction_balance >= 24 && $satisfaction_balance <= 28) {
                echo "Anda merasa sangat puas dengan keseimbangan antara pekerjaan dan kehidupan pribadi Anda. Anda memiliki kontrol yang baik atas jadwal Anda dan merasa bahagia dengan cara Anda mengelola waktu dan energi Anda. Pekerjaan dan kehidupan pribadi Anda saling mendukung, dan Anda jarang merasa bahwa pekerjaan mengganggu waktu pribadi Anda.";
            } else {
                echo "nilai tidak valid";
            }
            ?>
            <!-- <?php echo htmlspecialchars($interpretations['Kepuasan']); ?> -->
        </p>
        <br>

        <!-- time balance -->
        <h4>Waktu (Time)</h4>
        <!-- <p>Dengan persentase <?php echo round($time_percentage, 2); ?>%, aspek ini menunjukkan: -->
            <?php
            if ($time_balance >= 0 && $time_balance <= 5) {
                echo "Anda merasa sangat kesulitan mengatur waktu antara pekerjaan dan kehidupan pribadi. Pekerjaan mungkin menyita hampir seluruh waktu Anda, sehingga Anda merasa tidak punya waktu untuk diri sendiri atau keluarga. Ini bisa membuat Anda merasa sangat lelah dan stres.";
            } elseif ($time_balance >= 6 && $time_balance <= 11) {
                echo "Anda merasa sulit mengatur waktu antara pekerjaan dan kehidupan pribadi. Pekerjaan sering kali mengganggu waktu yang seharusnya bisa digunakan untuk diri sendiri atau keluarga. Ini bisa membuat Anda merasa tertekan dan merasa bahwa Anda tidak punya cukup waktu untuk bersantai atau menikmati aktivitas di luar pekerjaan.";
            } elseif ($time_balance >= 12 && $time_balance <= 17) {
                echo "Anda merasa cukup mampu mengatur waktu antara pekerjaan dan kehidupan pribadi. Meskipun ada saat-saat di mana pekerjaan mungkin mengambil lebih banyak waktu, secara keseluruhan Anda merasa mampu mengelola waktu Anda dengan baik. Anda mungkin masih perlu meningkatkan beberapa aspek, tetapi Anda sudah berada di jalur yang cukup baik.";
            } elseif ($time_balance >= 18 && $time_balance <= 23) {
                echo "Anda merasa mampu mengatur waktu antara pekerjaan dan kehidupan pribadi dengan baik. Anda bisa membagi waktu Anda secara efektif, memastikan bahwa pekerjaan tidak mengganggu waktu yang Anda butuhkan untuk diri sendiri atau keluarga. Anda merasa memiliki kendali atas jadwal Anda dan bisa menyeimbangkan tuntutan pekerjaan dengan kebutuhan pribadi Anda.";
            } elseif ($time_balance >= 24 && $time_balance <= 28) {
                echo "Anda merasa sangat mampu mengatur waktu antara pekerjaan dan kehidupan pribadi. Anda memiliki kontrol yang baik atas jadwal Anda dan bisa mengatur waktu Anda dengan cara yang mendukung kesejahteraan Anda. Anda merasa punya cukup waktu untuk pekerjaan, diri sendiri, keluarga, dan aktivitas yang Anda nikmati, menunjukkan manajemen waktu yang sangat efektif dan keseimbangan yang optimal.";
            } else {
                echo "nilai tidak valid";
            }
            ?>
            <!-- <?php echo htmlspecialchars($interpretations['Waktu']); ?> -->
        </p>
        <br>

        <!-- involvement balance -->
        <h4>Keterlibatan (Involvement)</h4>
        <!-- <p>Dengan persentase <?php echo round($involvement_percentage, 2); ?>%, aspek ini menunjukkan: -->
            <?php
            if ($involvement_balance >= 0 && $involvement_balance <= 5) {
                echo "Anda merasa sangat kurang terlibat dalam aktivitas baik di pekerjaan maupun dalam kehidupan pribadi. Anda mungkin merasa tidak punya cukup waktu atau energi untuk benar-benar terlibat dalam kedua aspek ini. Ini bisa membuat Anda merasa terisolasi dan kurang bersemangat.";
            } elseif ($involvement_balance >= 6 && $involvement_balance <= 11) {
                echo "Anda merasa kurang terlibat dalam aktivitas pekerjaan dan kehidupan pribadi. Salah satu aspek mungkin mendominasi yang lain, sehingga mengurangi keterlibatan Anda dalam aktivitas yang penting bagi Anda. Ini bisa membuat Anda merasa kurang puas dan kurang bersemangat dalam menjalani hari-hari Anda.";
            } elseif ($involvement_balance >= 12 && $involvement_balance <= 17) {
                echo "Anda merasa cukup terlibat dalam aktivitas pekerjaan dan kehidupan pribadi. Meskipun ada beberapa tantangan dalam menjaga keterlibatan yang seimbang, Anda merasa mampu berpartisipasi secara aktif dalam kedua aspek ini. Anda mungkin masih perlu meningkatkan beberapa aspek, tetapi Anda sudah berada di jalur yang cukup baik.";
            } elseif ($involvement_balance >= 18 && $involvement_balance <= 23) {
                echo "Anda merasa terlibat dalam aktivitas pekerjaan dan kehidupan pribadi. Anda mampu menjaga keterlibatan yang seimbang dan merasa bisa berpartisipasi secara penuh dalam kedua aspek ini. Anda merasa puas dengan tingkat keterlibatan Anda dan mampu mengelola tuntutan dari pekerjaan dan kehidupan pribadi dengan baik.";
            } elseif ($involvement_balance >= 24 && $involvement_balance <= 28) {
                echo "Anda merasa sangat terlibat dalam aktivitas pekerjaan dan kehidupan pribadi. Anda memiliki komitmen yang tinggi dan merasa bisa berpartisipasi secara penuh dan bermakna dalam kedua aspek ini. Anda merasa sangat puas dan termotivasi, baik di tempat kerja maupun di rumah, menunjukkan tingkat keterlibatan yang sangat tinggi dan keseimbangan yang optimal.";
            } else {
                echo "nilai tidak valid";
            }
            ?>
            <!-- <?php echo htmlspecialchars($interpretations['Keterlibatan']); ?> -->
        </p>

        <!-- Saran -->
        <br>
        <div class="garis"></div>
        <br>
        <center>
            <h4>Saran</h4>
        </center>
        <br>
        <br>
        <div class="row">
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <img src="../../assets/images/tes.png" alt="" class="img-hasil"
                    style="max-width: 80%; height: auto; width: 350px;">
            </div>
            <div class="col-md-6">
            <?php
            // satisfaction balance
            if ($satisfaction_balance >= 0 && $satisfaction_balance <= 5) {
                echo "<li>Menetapkan batasan yang jelas antara waktu kerja dan waktu pribadi.</li>";
                echo "<li>Jangan ragu untuk mendelegasikan tugas di tempat kerja dan di rumah.</li>";
            } elseif ($satisfaction_balance >= 6 && $satisfaction_balance <= 11) {
                echo "<li>Manajemen waktu yang lebih baik, seperti menggunakan teknik pomodoro atau eisenhower matrix untuk meningkatkan efisiensi.</li>";
                echo "<li>Luangkan waktu untuk aktivitas yang anda nikmati, seperti olahraga, membaca, atau hobi lainnya.</li>";
                echo "Diskusikan beban kerja dengan atasan untuk mencari solusi yang lebih baik.</li>";
            } elseif ($satisfaction_balance >= 12 && $satisfaction_balance <= 17) {
                echo "<li>Teruskan kebiasaan baik seperti olahraga teratur dan tidur yang cukup.</li>";
                echo "<li>Cobalah untuk tetap fleksibel dalam menghadapi perubahan yang mungkin terjadi dalam pekerjaan atau kehidupan pribadi.</li>";
            } elseif ($satisfaction_balance >= 18 && $satisfaction_balance <= 23) {
                echo "<li>Teruskan kebiasaan baik untuk aktivitas yang anda sukai.</li>";
                echo "<li>Gunakan waktu luang untuk pengembangan diri atau belajar hal baru.</li>";
            } elseif ($satisfaction_balance >= 24 && $satisfaction_balance <= 28) {
                echo "<li>Teruskan kebiasaan baik untuk aktivitas yang anda sukai.</li>";
                echo "<li>Gunakan waktu luang untuk pengembangan diri atau belajar hal baru.</li>";
            } else {
                echo "nilai tidak valid";
            }
        

           // time balance
            if ($time_balance >= 0 && $time_balance <= 5) {
                echo "<li>Pastikan untuk menjadwalkan waktu istirahat yang cukup dalam rutinitas harian anda.</li>";
                echo "<li>Identifikasi dan kurangi gangguan yang menghambat produktivitas anda.</li>";
                echo "<li>Tetapkan prioritas dengan fokus pada tugas yang paling penting dan mendesak terlebih dahulu.</li>";
            } elseif ($time_balance >= 6 && $time_balance <= 11) {
                echo "<li>Gunakan alat bantu atau aplikasi untuk manajemen waktu.</li>";
                echo "<li>Cobalah untuk tidak membawa pekerjaan ke rumah atau bekerja di luar jam kerja yang ditentukan.</li>";
                echo "<li>Luangkan waktu untuk aktivitas yang membantu anda bersantai dan melepaskan stres.</li>";
            } elseif ($time_balance >= 12 && $time_balance <= 17) {
                echo "<li>Teruskan kebiasaan manajemen waktu yang sudah efektif bagi anda.</li>";
            } elseif ($time_balance >= 18 && $time_balance <= 23) {
                echo "<li>Rencanakan liburan atau waktu istirahat secara berkala.</li>";
            } elseif ($time_balance >= 24 && $time_balance <= 28) {
                 echo "<li>Rencanakan liburan atau waktu istirahat secara berkala.</li>";
            } else {
                echo "nilai tidak valid";
            }
           
            // involvement balance
            if ($involvement_balance >= 0 && $involvement_balance <= 5) {
                echo "<li>Cobalah untuk menemukan aspek pekerjaan yang anda nikmati dan fokus pada itu.</li>";
                echo "<li>Luangkan waktu untuk berinteraksi dengan teman dan keluarga.</li>";
                echo "<li>Temukan hobi atau aktivitas baru yang dapat anda nikmati di luar pekerjaan.</li>";
            } elseif ($involvement_balance >= 6 && $involvement_balance <= 11) {
                echo "<li>Ikuti pelatihan atau kursus yang dapat meningkatkan keterampilan dan minat anda.</li>";
                echo "<li>Cobalah untuk menyeimbangkan aktivitas yang anda lakukan di tempat kerja dan di rumah untuk meningkatkan keterlibatan keduanya.</li>";
            } elseif ($involvement_balance >= 12 && $involvement_balance <= 17) {
                echo "<li>Teruskan kebiasaan yang membantu anda merasa terlibat dalam pekerjaan dan kehidupan pribadi.</li>";
            } elseif ($involvement_balance >= 18 && $involvement_balance <= 23) {
                echo "<li>Teruskan kebiasaan yang sudah membantu anda mencapai tingkat keterlibatan ini.</li>";
                echo "<li>Selalu waspada terhadap tanda-tanda kelelahan dan ambil tindakan segera jika diperlukan.</li>";
            } elseif ($involvement_balance >= 24 && $involvement_balance <= 28) {
                echo "<li>Terus nikmati keterlibatan anda dalam pekerjaan dan kehidupan pribadi.</li>";
                echo "<li>Gunakan keterlibatan anda untuk pengembangan diri atau belajar hal baru.</li>";
            } else {
                echo "nilai tidak valid";
            }
            ?>
                <?php
                // $recommendations_array = explode("\n", $recommendations);
                // echo "<ul>";
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
            <br>
            <p>Semoga saran di atas membantu Anda mencapai keseimbangan yang lebih baik dan meningkatkan
                kualitas hidup Anda secara keseluruhan. Selamat mencoba!!😊😊</p>
        </div>
    </div>

    <!-- Image Below Container -->
    <div class="bottom-image">
        <img src="../../assets/images/hasil2.jpg" alt="2" class="img-fluid">

    </div>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for Chart -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('resultChart').getContext('2d');
            var resultChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: [
                        'Satisfaction balance',
                        'Time balance',
                        'Involvement balance'
                    ],
                    datasets: [{
                        label: 'Hasil Tes Work-Life Balance',
                        data: [
                            <?php echo htmlspecialchars($satisfaction_percentage); ?>,
                            <?php echo htmlspecialchars($time_percentage); ?>,
                            <?php echo htmlspecialchars($involvement_percentage); ?>
                        ],
                        backgroundColor: [
                            '#FF5B64',
                            '#DE4F45',
                            '#FF7CA9',
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    return tooltipItem.label + ': ';
                                    //  + tooltipItem.raw.toFixed(2) + '%';
                                }
                            }
                        },
                        datalabels: {
                            color: '#333',
                            formatter: function (value, context) {
                                return context.chart.data.labels[context.dataIndex] ;
                                // + '\n' + value.toFixed(2) + '%';
                            },
                            anchor: 'center',
                            align: 'center',
                            font: {
                                weight: 'bold',
                                size: 10.5
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });
    </script>
</body>

</html>