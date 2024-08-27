<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
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
                <h2 style="color: #D0434D">fdfkdklfk</h2>
                <p>keterangan singkat</p>
            </div>
            <div class="col-md-6">
                <canvas id="resultChart"></canvas>
            </div>
        </div>
        <!-- satisfaction-balance -->
        <h4>Kepuasan (Satisfaction)</h4>
        <p>Dengan presentase <?php echo $satisfaction_percentage ?>%, aspek menunjukan</p>
        <br>

        <!-- time balance -->
        <h4>Waktu (Time)</h4>
        <p>Dengan presentase <?php echo $time_percentage ?>%, aspek menunjukan</p>
        <br>

        <!-- involvement-balance -->
        <h4>Keterlibatan (Involvement)</h4>
        <p>Dengan presentase <?php echo $involvement_percentage ?>%, aspek menunjukan</p>
        <br>

        <div class="garis">
            <center>
                <h4>Saran</h4>
            </center>
            <div class="row">
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <img src="../../assets/images/tes.png" alt="" class="img-hasil"
                        style="max-width: 100%; height: auto; width: 300px;">
                </div>
                <div class="col-md-6">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum
                        vestibulum. Cras venenatis euismod malesuada.</p>
                    <p>Fusce convallis, mauris imperdiet gravida bibendum, nisl turpis suscipit mauris, sed convallis
                        sapien nunc et ligula.</p>
                    <p>Sed accumsan magna est, et consequat sem volutpat ut.</p>
                </div>.gi
            </div>
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
                                size: 11
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });
    </script>
    <h2>eoko</h2>
    <h3>ekrk</h3>
</body>

</html>