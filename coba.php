<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

function getGeminiNickname($satisfaction_percentage, $time_percentage, $involvement_percentage)
{
    $api_key = 'AIzaSyDxlbLcuzMcIqVeIFWk0qfd0PmDqGxmnvw';
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key;

    $prompt = "Berdasarkan hasil tes work-life balance:
    Satisfaction: {$satisfaction_percentage}%
    Time: {$time_percentage}%
    Involvement: {$involvement_percentage}%

    Berikan 1 julukan yang positif, sesuai, bagus, dan unik.";

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

    return makeApiRequest($url, $data);
}

function getGeminiWLBAspectInterpretations($satisfaction_percentage, $time_percentage, $involvement_percentage)
{
    $api_key = 'AIzaSyDxlbLcuzMcIqVeIFWk0qfd0PmDqGxmnvw';
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key;

    $prompt = "Berikan interpretasi (maksimal 100 kata) untuk masing-masing aspek work-life balance berikut:
    1. Kepuasan (Satisfaction): {$satisfaction_percentage}%
    2. Waktu (Time): {$time_percentage}%
    3. Keterlibatan (Involvement): {$involvement_percentage}%";

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

    return makeApiRequest($url, $data);
}

function getGeminiRecommendations($satisfaction_percentage, $time_percentage, $involvement_percentage)
{
    $api_key = 'AIzaSyDxlbLcuzMcIqVeIFWk0qfd0PmDqGxmnvw';
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $api_key;

    $prompt = "Berdasarkan hasil tes work-life balance:
    Satisfaction: {$satisfaction_percentage}%
    Time: {$time_percentage}%
    Involvement: {$involvement_percentage}%

    Berikan 10 saran untuk meningkatkan work-life balance. Setiap saran maksimal 15 kata dan harus spesifik serta actionable.";

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

    return makeApiRequest($url, $data);
}

function makeApiRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return "Maaf, terjadi kesalahan saat menghasilkan respon dari API.";
    }

    $result = json_decode($response, true);
    return $result['candidates'][0]['content']['parts'][0]['text'];
}

include '../../database/db.php';

$id_user = $_SESSION['id_user'];

// Mengambil skor dari tabel wlb
$query = "SELECT satisfaction-balance, time-balance, involvement-balance FROM wlb WHERE id_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $satisfaction_balance = $row['satisfaction-balance'];
    $time_balance = $row['time-balance'];
    $involvement_balance = $row['involvement-balance'];

    $total = $satisfaction_balance + $time_balance + $involvement_balance;
    $satisfaction_percentage = ($total > 0) ? ($satisfaction_balance / $total) * 100 : 0;
    $time_percentage = ($total > 0) ? ($time_balance / $total) * 100 : 0;
    $involvement_percentage = ($total > 0) ? ($involvement_balance / $total) * 100 : 0;

    // Mengambil interpretasi dan rekomendasi dari API secara paralel
    $interpretations = json_decode(getGeminiWLBAspectInterpretations($satisfaction_percentage, $time_percentage, $involvement_percentage), true);
    $recommendations = getGeminiRecommendations($satisfaction_percentage, $time_percentage, $involvement_percentage);
    $nickname = getGeminiNickname($satisfaction_percentage, $time_percentage, $involvement_percentage);
} else {
    $satisfaction_percentage = $time_percentage = $involvement_percentage = 0;
}

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>

<body>
    <?php include '../landing_page/navbar.php'; ?>

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
            </div>
            <div class="col-md-6">
                <canvas id="resultChart"></canvas>
            </div>
        </div>
        <h4>Kepuasan (Satisfaction)</h4>
        <p>Dengan persentase <?php echo round($satisfaction_percentage, 2); ?>%, aspek ini menunjukkan:
            <?php echo htmlspecialchars($interpretations['Kepuasan']); ?>
        </p>
        <br>
        <h4>Waktu (Time)</h4>
        <p>Dengan persentase <?php echo round($time_percentage, 2); ?>%, aspek ini menunjukkan:
            <?php echo htmlspecialchars($interpretations['Waktu']); ?>
        </p>
        <br>
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
                <img src="../../assets/images/tes.png" alt="" class="img-hasil" style="max-width: 80%; height: auto; width: 350px;">
            </div>
            <div class="col-md-6">
                <?php
                $recommendations_array = explode("\n", $recommendations);
                echo '<ul class="recommendations-list">';
                foreach ($recommendations_array as $recommendation) {
                    echo '<li>' . htmlspecialchars($recommendation) . '</li>';
                }
                echo '</ul>';
                ?>
            </div>
        </div>
    </div>
    <script>
        const data = {
            labels: ['Satisfaction', 'Time', 'Involvement'],
            datasets: [{
                data: [<?php echo $satisfaction_percentage; ?>, <?php echo $time_percentage; ?>, <?php echo $involvement_percentage; ?>],
                backgroundColor: ['rgba(208, 67, 77, 0.9)', 'rgba(118, 67, 208, 0.9)', 'rgba(67, 208, 126, 0.9)'],
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 2
            }]
        };

        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        color: '#fff',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        formatter: (value, context) => {
                            return value.toFixed(2) + '%';
                        }
                    }
                }
            }
        };

        window.onload = () => {
            const ctx = document.getElementById('resultChart').getContext('2d');
            new Chart(ctx, config);
        };
    </script>
</body>

</html>