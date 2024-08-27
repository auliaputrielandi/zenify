<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../landing_page/login.php");
    exit();
}

include '../../database/db.php';

// Cek apakah data POST sudah ada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dapatkan skor dari data POST
    $depresi = intval($_POST['depresi']);
    $kecemasan = intval($_POST['kecemasan']);
    $stres = intval($_POST['stres']);
    $id_user = $_SESSION['id_user'];

    // Periksa apakah sudah ada catatan untuk pengguna ini
    $query = "SELECT id_stres FROM stres WHERE id_user = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update catatan yang ada
        $query = "UPDATE stres SET depresi = ?, kecemasan = ?, stres = ? WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiii", $depresi, $kecemasan, $stres, $id_user);
        $stmt->execute();
    } else {
        // Insert catatan baru
        $query = "INSERT INTO stres (id_user, depresi, kecemasan, stres) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiii", $id_user, $depresi, $kecemasan, $stres);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    // Redirect ke halaman hasil setelah menyimpan
    header("Location: hasil-stres.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenify</title>
    <link rel="stylesheet" href="../../assets/css/tes-stres.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body>

    <!-- Include Navbar -->
    <?php include '../landing_page/navbar.php'; ?>

    <!-- Question Section -->
    <div class="question-container">
        <p id="question-counter" class="p"></p>
        <h3 id="question-title" class="question-title"></h3>

        <div id="answer-options" class="answer-options">
        </div>

        <div class="btn-navigation d-flex justify-content-between">
            <button id="prev-btn" class="btn btn-prev">Sebelumnya</button>
            <button id="next-btn" class="btn btn-next">Berikutnya</button>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        //DASS-21
        const questions = [
            // Depresi
            {
                question: "Saya sama sekali tidak dapat merasakan perasaan positif (contoh: merasa gembira, bangga, dsb).",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa sulit berinisiatif melakukan sesuatu",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa tidak ada lagi yang bisa saya harapkan",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa sedih dan tertekan",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya tidak bisa merasa antusias terhadap hal apapun",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa diri saya tidak berharga",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa hidup ini tidak berarti",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            // Kecemasan
            {
                question: "Saya merasa rongga mulut saya kering",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa kesulitan bernafas (misalnya seringkali terengah-engah atau tidak dapat bernafas padahal tidak melakukan aktivitas fisik sebelumnya). ",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa gemetar (misalnya pada tangan)",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa khawatir dengan situasi dimana saya mungkin menjadi panik dan mempermalukan diri sendiri",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa hampir panik",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya menyadari kondisi jantung saya (seperti meningkatnya atau melemahnya detak jantung) meskipun sedang tidak melakukan aktivitas fisik",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa ketakutan tanpa alasan yang jelas",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            // Stress
            {
                question: " Saya merasa sulit untuk beristirahat. ",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya cenderung menunjukkan reaksi berlebihan terhadap suatu situasi",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa energi saya terkuras karena terlalu cemas",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa gelisah",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya merasa sulit untuk merasa tenang",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Saya sulit untuk bersabar dalam menghadapi gangguan yang terjadi ketika sedang melakukan sesuatu",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },
            {
                question: "Perasaan saya mudah tergugah atau tersentuh",
                options: [
                    "Sering sekali",
                    "Lumayan sering",
                    "Kadang-kadang",
                    "Tidak pernah",
                ]
            },

        ];

        let currentQuestion = 0;
        let scores = [];

        const questionCounter = document.getElementById("question-counter");
        const questionTitle = document.getElementById("question-title");
        const answerOptions = document.getElementById("answer-options");
        const nextBtn = document.getElementById("next-btn");
        const prevBtn = document.getElementById("prev-btn");

        function loadQuestion(index) {
            questionCounter.innerText = `Soal ke ${index + 1} dari ${questions.length}`;
            questionTitle.innerText = questions[index].question;
            const options = questions[index].options;
            answerOptions.innerHTML = "";
            options.forEach((option, idx) => {
                const div = document.createElement("div");
                div.classList.add("answer-option");
                div.dataset.score = 3 - idx; //ini buat ngitung skor berdasarkan urutan opsi
                div.innerText = option;

                if (scores[index] && scores[index].index === idx) {
                    div.classList.add("answered");
                    div.classList.add("selected");
                }

                div.addEventListener("click", () => selectAnswer(div, idx));
                answerOptions.appendChild(div);
            });

            // Update button states
            prevBtn.disabled = currentQuestion === 0;
            nextBtn.disabled = !scores[currentQuestion];
        }

        function selectAnswer(element, index) {
            const options = answerOptions.children;
            for (let option of options) {
                option.classList.remove("selected");
                option.classList.add("answered");
            }
            element.classList.add("selected");

            // Update skor
            scores[currentQuestion] = { index, score: parseInt(element.dataset.score) };

            nextBtn.disabled = false;

            //next ke soal otomatis
            if (currentQuestion < questions.length - 1) {
                nextBtn.click();
            } else {
                calculateScore();
            }
        }

        function calculateScore() {
            // Menghitung skor
            let depresiScore = 0;
            let kecemasanScore = 0;
            let stressScore = 0;

            for (let i = 0; i < 7; i++) {
                depresiScore += scores[i]?.score || 0;
            }
            for (let i = 7; i < 14; i++) {
                kecemasanScore += scores[i]?.score || 0;
            }
            for (let i = 14; i < 21; i++) {
                stressScore += scores[i]?.score || 0;
            }

            // Kirim data ke server
            const formData = new FormData();
            formData.append('depresi', depresiScore);
            formData.append('kecemasan', kecemasanScore);
            formData.append('stres', stressScore);

            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (response.ok) {
                    window.location.href = 'hasil-stres.php';
                } else {
                    // alert('Gagal menyimpan hasil.');
                    window.location.href = 'hasil-stres.php';
                }
            });
        }

        nextBtn.addEventListener("click", () => {
            if (currentQuestion < questions.length - 1) {
                currentQuestion++;
                loadQuestion(currentQuestion);
            } else {
                calculateScore();
            }
        });

        prevBtn.addEventListener("click", () => {
            if (currentQuestion > 0) {
                currentQuestion--;
                loadQuestion(currentQuestion);
            }
        });

        loadQuestion(currentQuestion);
    </script>
</body>

</html>