<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}
// Fetch the nickname based on the work-life balance scores
// $nickname = getGeminiRecommendations($satisfaction_percentage, $time_percentage, $involvement_percentage);

function getGeminiNickname($satisfaction_percentage, $time_percentage, $involvement_percentage)
{
    $api_key = 'AIzaSyDxlbLcuzMcIqVeIFWk0qfd0PmDqGxmnvw'; // Ganti dengan API key Gemini Anda
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key;

    $prompt = "Berdasarkan hasil tes work-life balance:
    Satisfaction: {$satisfaction_percentage}%
    Time: {$time_percentage}%
    Involvement: {$involvement_percentage}%

    Berikan 1 julukan yang positif, sesuai, bagus, dan unik.

    Format dan contoh jawaban:
    Si Pengatur Waktu Andal
    Si Puas dan Berdedikasi
    Si Produktif dan Berperan Aktif
    Si Tepat Waktu dan Fokus
    Si Efisien dan Berkontribusi Penuh
    Si Harmonis dan Komitmen
    Si Bahagia dalam Memberikan Kontribusi
    Si Seimbang dalam Peran
    Si Produktif";


    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'maxOutputTokens' => 150,
        ]
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        return "Maaf, terjadi kesalahan saat menghasilkan rekomendasi.";
    }

    $result = json_decode($response, true);
    return $result['candidates'][0]['content']['parts'][0]['text'];
}

function getGeminiWLBAspectInterpretations($satisfaction_percentage, $time_percentage, $involvement_percentage)
{
    $api_key = 'AIzaSyDxlbLcuzMcIqVeIFWk0qfd0PmDqGxmnvw'; // Ganti dengan API key Gemini Anda
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key;

    $prompt = "Berikan interpretasi (maksimal 100 kata) untuk masing-masing aspek work-life balance berikut:
    1. Kepuasan (Satisfaction): {$satisfaction_percentage}%
    2. Waktu (Time): {$time_percentage}%
    3. Keterlibatan (Involvement): {$involvement_percentage}%

    Format jawaban:
    Kepuasan: [interpretasi]
    Waktu: [interpretasi]
    Keterlibatan: [interpretasi]";

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'maxOutputTokens' => 200,
        ]
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        return [
            'Kepuasan' => 'Maaf, terjadi kesalahan saat menghasilkan interpretasi.',
            'Waktu' => 'Maaf, terjadi kesalahan saat menghasilkan interpretasi.',
            'Keterlibatan' => 'Maaf, terjadi kesalahan saat menghasilkan interpretasi.'
        ];
    }

    $result = json_decode($response, true);
    $content = $result['candidates'][0]['content']['parts'][0]['text'];

    // Memisahkan interpretasi menjadi array
    $interpretations = [
        'Kepuasan' => '',
        'Waktu' => '',
        'Keterlibatan' => ''
    ];

    foreach (explode("\n", $content) as $line) {
        $parts = explode(': ', $line, 2);
        if (count($parts) == 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            if (array_key_exists($key, $interpretations)) {
                $interpretations[$key] = $value;
            }
        }
    }

    return $interpretations;

}

function getGeminiRecommendations($satisfaction_percentage, $time_percentage, $involvement_percentage)
{
    $api_key = 'AIzaSyDxlbLcuzMcIqVeIFWk0qfd0PmDqGxmnvw'; // Ganti dengan API key Gemini Anda
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key;

    $prompt = "Berdasarkan hasil tes work-life balance:
    Satisfaction: {$satisfaction_percentage}%
    Time: {$time_percentage}%
    Involvement: {$involvement_percentage}%

    Berikan 10 saran untuk meningkatkan work-life balance. Setiap saran maksimal 15 kata dan harus spesifik serta actionable.

    Format dan contoh jawaban:
    1. [Luangkan waktu untuk melakukan aktivitas di luar pekerjaan, seperti berolahraga, berkumpul dengan keluarga, atau mengejar hobi]
    2. [Prioritaskan tugas-tugas yang penting dan atur jadwal dengan baik]
    3. [Hindari bekerja berlebihan dan berikan waktu untuk istirahat dan rekreasi]
    4. [Karyawan harus berkomunikasi dengan atasan dan rekan kerja tentang kebutuhan pribadi mereka]
    5. [Atur prioritas pekerjaan]
    6. [Mengembangkan hobi baru]
    7. [Berpartisipasi dalam kegiatan sosial]
    8. [Mengatur waktu untuk relaksasi]
    9. [Komunikasikan jika butuh bantuan]
    10. [Hindari membawa pekerjaan ke rumah]";

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'maxOutputTokens' => 150,
        ]
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        return "Maaf, terjadi kesalahan saat menghasilkan rekomendasi.";
    }

    $result = json_decode($response, true);
    return $result['candidates'][0]['content']['parts'][0]['text'];
}


include '../../database/db.php';

// Mengambil data work-life balance berdasarkan id_user
$id_user = $_SESSION['id_user'];

// Query untuk mengambil skor dari tabel wlb
$query = "SELECT `satisfaction-balance`, `time-balance`, `involvement-balance` FROM `wlb` WHERE `id_user` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $satisfaction_balance = $row['satisfaction-balance'];
    $time_balance = $row['time-balance'];
    $involvement_balance = $row['involvement-balance'];

    // Menghitung total skor
    $total = $satisfaction_balance + $time_balance + $involvement_balance;

    // Menghitung persentase
    $satisfaction_percentage = ($total > 0) ? ($satisfaction_balance / $total) * 100 : 0;
    $time_percentage = ($total > 0) ? ($time_balance / $total) * 100 : 0;
    $involvement_percentage = ($total > 0) ? ($involvement_balance / $total) * 100 : 0;
    $interpretations = getGeminiWLBAspectInterpretations($satisfaction_percentage, $time_percentage, $involvement_percentage);
    $recommendations = getGeminiRecommendations($satisfaction_percentage, $time_percentage, $involvement_percentage);
    $nickname = getGeminiNickname($satisfaction_percentage, $time_percentage, $involvement_percentage);
} else {
    $satisfaction_percentage = $time_percentage = $involvement_percentage = 0;
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
            <div class="col-md-6">
                <h1>Hi <?php echo htmlspecialchars($nama); ?>!</h1>
                <p>Berikut adalah hasil tes work-life balance mu</p>
            </div>
        </div>
    </div>
    <div class="container-isi">
        <div class="row">
            <div class="col-md-6">
            <h2 style="color: #D0434D; text-align: center; margin-top: 115px;"><?php echo htmlspecialchars($nickname) ?></h2>
                <!-- <p>keterangan singkat</p> -->
            </div>
            <div class="col-md-6">
                <canvas id="resultChart"></canvas>
            </div>
        </div>
        <!-- satisfaction-balance -->
        <h4>Kepuasan (Satisfaction)</h4>
        <p>Dengan persentase <?php echo round($satisfaction_percentage, 2); ?>%, aspek ini menunjukkan:
            <?php echo htmlspecialchars($interpretations['Kepuasan']); ?>
        </p>
        <br>

        <!-- time balance -->
        <h4>Waktu (Time)</h4>
        <p>Dengan persentase <?php echo round($time_percentage, 2); ?>%, aspek ini menunjukkan:
            <?php echo htmlspecialchars($interpretations['Waktu']); ?>
        </p>
        <br>

        <!-- involvement-balance -->
        <h4>Keterlibatan (Involvement)</h4>
        <p>Dengan persentase <?php echo round($involvement_percentage, 2); ?>%, aspek ini menunjukkan:
            <?php echo htmlspecialchars($interpretations['Keterlibatan']); ?>
        </p>

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
                $recommendations_array = explode("\n", $recommendations);
                echo "<ul>";
                foreach ($recommendations_array as $recommendation) {
                    $recommendation = trim($recommendation);
                    if (!empty($recommendation) && strpos($recommendation, '.') !== false) {
                        $recommendation = substr($recommendation, strpos($recommendation, '.') + 1);
                        echo "<li>" . htmlspecialchars(trim($recommendation)) . "</li>";
                    }
                }
                echo "</ul>";
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
                                    return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                                }
                            }
                        },
                        datalabels: {
                            color: '#333',
                            formatter: function (value, context) {
                                return context.chart.data.labels[context.dataIndex] + '\n' + value.toFixed(2) + '%';
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