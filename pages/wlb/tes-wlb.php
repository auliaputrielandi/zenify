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
    $satisfaction = intval($_POST['satisfaction-balance']);
    $time = intval($_POST['time-balance']);
    $involvement = intval($_POST['involvement-balance']);
    $id_user = $_SESSION['id_user'];

    // Periksa apakah sudah ada catatan untuk pengguna ini
    $query = "SELECT id_wlb FROM wlb WHERE id_user = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update catatan yang ada
        $query = "UPDATE wlb SET `satisfaction-balance` = ?, `time-balance` = ?, `involvement-balance` = ? WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiii", $satisfaction, $time, $involvement, $id_user);
        $stmt->execute();
    } else {
        // Insert catatan baru
        $query = "INSERT INTO wlb (id_user, `satisfaction-balance`, `time-balance`, `involvement-balance`) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiii", $id_user, $satisfaction, $time, $involvement);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    // Redirect ke halaman hasil setelah menyimpan
    header("Location: hasil_tes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenify</title>
    <link rel="stylesheet" href="../../assets/css/tes-wlb.css">
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
        const questions = [
            // Satisfaction Balance
            {
                question: "Apakah Anda tetap bersemangat untuk bekerja secara lebih efektif meskipun ada berbagai hal yang terjadi dalam kehidupan pribadi?",
                options: [
                    "Sangat bersemangat, saya tetap termotivasi tinggi untuk memberikan yang terbaik dalam pekerjaan, bahkan ketika menghadapi tantangan pribadi.",
                    "Cukup bersemangat, umumnya saya tetap antusias, namun terkadang hal pribadi dapat sedikit mempengaruhi kinerja saya.",
                    "Kadang-kadang, saya perlu usaha ekstra untuk tetap fokus pada pekerjaan ketika ada masalah pribadi.",
                    "Kurang bersemangat, masalah pribadi seringkali membuat saya sulit berkonsentrasi dan bekerja secara efektif.",
                    "Sama sekali tidak bersemangat, masalah pribadi sangat mengganggu dan membuat saya sulit untuk menjalankan tugas pekerjaan."
                ]
            },
            {
                question: "Apakah Anda puas dengan jumlah waktu yang dapat Anda dedikasikan untuk hobi dan minat pribadi Anda?",
                options: [
                    "Sangat puas, saya memiliki waktu yang lebih dari cukup untuk melakukan semua hobi dan minat pribadi saya.",
                    "Cukup puas, saya umumnya puas dengan waktu yang saya miliki, meskipun terkadang ingin lebih banyak waktu untuk beberapa aktivitas tertentu.",
                    "Biasa saja, saya merasa waktu yang saya miliki untuk hobi dan minat pribadi cukup terbatas, tetapi masih dapat saya nikmati.",
                    "Kurang puas, saya merasa waktu yang saya dedikasikan untuk hobi dan minat pribadi sangat terbatas, sehingga seringkali merasa frustasi.",
                    "Sama sekali tidak puas, saya hampir tidak memiliki waktu sama sekali untuk melakukan hal-hal yang saya sukai."
                ]
            },
            {
                question: "Bagaimana keseimbangan kehidupan dan pekerjaan mempengaruhi suasana hati dan motivasi Anda secara keseluruhan di tempat kerja?",
                options: [
                    "Sangat baik, keseimbangan yang baik antara kehidupan pribadi dan pekerjaan membuat saya merasa sangat bahagia dan termotivasi di tempat kerja.",
                    "Cukup baik, saya merasa cukup puas dengan keseimbangan hidup saya, namun terkadang pekerjaan dapat sedikit mempengaruhi suasana hati saya.",
                    "Biasa saja, saya merasa sulit untuk menyeimbangkan keduanya, dan hal ini kadang-kadang membuat saya merasa stres.",
                    "Kurang baik, ketidakseimbangan antara kehidupan pribadi dan pekerjaan membuat saya sering merasa lelah dan kurang bersemangat.",
                    "Sangat buruk, ketidakseimbangan yang ekstrem membuat saya merasa tertekan dan tidak bahagia di tempat kerja."
                ]
            },
            {
                question: "Seberapa sering Anda merasa kewalahan dengan tanggung jawab pekerjaan Anda?",
                options: [
                    "Sangat jarang bahkan hampir tidak pernah, saya merasa mampu mengelola beban kerja saya dengan baik.",
                    "Cukup jarang, terkadang saya merasa kewalahan, tetapi dapat segera mengatasinya.",
                    "Cukup sering, saya sering merasa kewalahan, terutama saat tenggat waktu mendekat.",
                    "Sangat sering, saya hampir selalu merasa kewalahan dengan jumlah pekerjaan yang harus saya selesaikan.",
                    "Selalu, saya merasa sangat kewalahan dan tidak mampu menyelesaikan semua tugas yang diberikan."
                ]
            },
            {
                question: "Apakah Anda puas dengan pekerjaan maupun urusan pribadi yang Anda lakukan?",
                options: [
                    "Sangat puas, semua aspek kehidupan saya berjalan dengan baik.",
                    "Cukup puas, secara umum saya merasa puas, namun ada beberapa hal kecil yang ingin saya ubah.",
                    "Biasa saja, saya merasa ada beberapa area dalam hidup saya yang perlu ditingkatkan.",
                    "Kurang puas, saya merasa tidak puas dengan beberapa aspek kehidupan saya, baik pekerjaan maupun pribadi.",
                    "Sangat tidak puas, saya merasa sangat tidak puas dengan kehidupan saya saat ini."
                ]
            },
            {
                question: "Apakah suasana dalam pekerjaan mendukung aktivitas yang Anda sukai dalam kehidupan pribadi?",
                options: [
                    "Sangat mendukung, lingkungan kerja saya sangat fleksibel dan mendukung saya untuk menyeimbangkan pekerjaan dan kehidupan pribadi.",
                    "Cukup mendukung, lingkungan kerja saya cukup mendukung, namun masih ada beberapa batasan.",
                    "Netral, lingkungan kerja tidak terlalu berpengaruh pada kehidupan pribadi saya.",
                    "Kurang mendukung, lingkungan kerja saya membuat sulit untuk menyeimbangkan pekerjaan dan kehidupan pribadi.",
                    "Sama sekali tidak mendukung, lingkungan kerja saya sangat menuntut dan tidak memberikan ruang untuk kehidupan pribadi."
                ]
            },
            {
                question: "Apakah Anda dapat menyikapi tekanan yang Anda dapat dari atasan agar pekerjaan Anda tidak terganggu?",
                options: [
                    "Ya, sangat baik, saya dapat mengelola tekanan dengan baik dan tetap fokus pada pekerjaan.",
                    "Ya, cukup baik, saya umumnya dapat mengatasi tekanan, namun terkadang merasa kewalahan.",
                    "Kadang-kadang, saya kesulitan untuk mengatasi tekanan, terutama saat beban kerja sangat tinggi.",
                    "Tidak terlalu baik, saya sering merasa tertekan dan sulit untuk berkonsentrasi pada pekerjaan.",
                    "Tidak, sama sekali, saya tidak dapat mengatasi tekanan dengan baik dan hal ini sangat mengganggu pekerjaan saya."
                ]
            },
            // Time Balance
            {
                question: "Apakah jadwal kerja Anda memungkinkan Anda menghabiskan waktu berkualitas bersama teman dan keluarga?",
                options: [
                    "Sangat memungkinkan, saya memiliki banyak waktu luang.",
                    "Cukup memungkinkan, namun masih ada ruang untuk perbaikan.",
                    "Terbatas, pekerjaan seringkali menjadi prioritas utama.",
                    "Tidak memungkinkan, pekerjaan memakan banyak waktu.",
                    "Sangat tidak memungkinkan."
                ]
            },
            {
                question: "Berapa banyak waktu yang Anda habiskan bersama teman dan keluarga setiap hari?",
                options: [
                    "Sangat banyak, saya memiliki banyak waktu berkualitas bersama teman dan keluarga setiap hari.",
                    "Cukup banyak, saya berusaha meluangkan waktu sebanyak mungkin untuk keluarga dan teman.",
                    "Sedikit, saya memiliki sedikit waktu luang untuk bersosialisasi.",
                    "Hampir tidak ada, saya jarang sekali bertemu dengan teman dan keluarga karena kesibukan.",
                    "Sama sekali tidak ada, saya tidak memiliki waktu sama sekali untuk bersosialisasi."
                ]
            },
            {
                question: "Seberapa sering Anda bekerja melampaui jam kerja yang ditentukan?",
                options: [
                    "Tidak pernah sama sekali, saya selalu pulang tepat waktu dan tidak pernah membawa pekerjaan ke rumah.",
                    "Sangat jarang, hanya pada keadaan darurat saya bekerja lembur.",
                    "Cukup sering, saya seringkali harus bekerja lembur untuk menyelesaikan tugas.",
                    "Sering sekali, saya hampir selalu bekerja lembur dan membawa pekerjaan ke rumah.",
                    "Selalu, pekerjaan saya menuntut saya untuk bekerja jauh melebihi jam kerja yang ditentukan."
                ]
            },
            {
                question: "Seberapa sering komunikasi di tempat kerja mengganggu waktu pribadi Anda?",
                options: [
                    "Tidak pernah, komunikasi kerja tidak pernah mengganggu waktu istirahat saya.",
                    "Sangat jarang, hanya dalam keadaan darurat saya menerima panggilan kerja di luar jam kerja.",
                    "Cukup sering, saya seringkali menerima panggilan atau pesan kerja di luar jam kerja.",
                    "Sering sekali, saya terus-menerus terhubung dengan pekerjaan, bahkan saat sedang libur.",
                    "Selalu, saya tidak memiliki batas yang jelas antara waktu kerja dan waktu pribadi."
                ]
            },
            {
                question: "Seberapa mudah bagi Anda untuk berhenti bekerja pada hari libur?",
                options: [
                    "Sangat mudah, saya dapat dengan mudah memisahkan waktu kerja dan waktu libur.",
                    "Cukup mudah, saya umumnya dapat bersantai saat libur, namun terkadang pikiran saya masih tertuju pada pekerjaan.",
                    "Agak sulit, saya merasa sulit untuk benar-benar lepas dari pekerjaan saat libur.",
                    "Sangat sulit, saya tidak dapat menikmati waktu libur karena terus memikirkan pekerjaan.",
                    "Tidak sama sekali, saya tidak bisa berhenti bekerja, bahkan saat hari libur."
                ]
            },
            {
                question: "Apakah Anda merasa bahwa mengambil cuti tahunan berdampak negatif pada pekerjaan atau tim Anda?",
                options: [
                    "Tidak sama sekali, saya merasa cuti tahunan justru memberikan dampak positif pada pekerjaan dan tim saya, karena saya kembali dengan semangat baru dan ide-ide segar.",
                    "Sangat jarang, hanya pada proyek yang sangat mendesak, cuti saya mungkin sedikit berdampak.",
                    "Cukup sering, saya khawatir pekerjaan menumpuk selama cuti dan sulit untuk mengejar ketertinggalan.",
                    "Sering sekali, saya merasa kehadiran saya sangat dibutuhkan sehingga tim kesulitan saat saya cuti.",
                    "Selalu, saya merasa mengambil cuti adalah beban bagi tim saya."
                ]
            },
            {
                question: "Apakah saat bekerja, Anda tidak lagi mengkhawatirkan berbagai hal lain yang perlu Anda lakukan di luar pekerjaan. (Contoh: hobi, mengurus orang tua)",
                options: [
                    "Ya, sepenuhnya, saat bekerja, saya dapat fokus sepenuhnya pada tugas-tugas saya.",
                    "Ya, sebagian besar, saya dapat mengelola pikiran saya untuk fokus pada pekerjaan, meskipun terkadang ada pikiran lain yang mengganggu.",
                    "Cukup sulit, saya seringkali memikirkan masalah pribadi saat bekerja.",
                    "Sangat sulit, saya sulit untuk fokus pada pekerjaan karena terus memikirkan hal-hal lain.",
                    "Tidak sama sekali, saya tidak dapat memisahkan masalah pribadi dengan pekerjaan."
                ]
            },
            // Involvement Balance
            {
                question: "Bisakah Anda menetapkan batasan yang tegas antara pekerjaan dan kehidupan pribadi?",
                options: [
                    "Sangat bisa, saya memiliki batasan yang jelas antara waktu kerja dan waktu pribadi, dan saya selalu mematuhinya.",
                    "Cukup bisa, saya berusaha untuk memisahkan keduanya, namun terkadang sulit untuk benar-benar lepas dari pekerjaan.",
                    "Agak sulit, saya seringkali membawa pekerjaan ke luar jam kerja.",
                    "Sangat sulit, saya merasa sulit untuk memisahkan kehidupan pribadi dan pekerjaan.",
                    "Tidak bisa, saya tidak memiliki batasan yang jelas antara keduanya, pekerjaan selalu menjadi prioritas."
                ]
            },
            {
                question: "Pernahkah Anda mengabaikan komitmen pribadi Anda karena pekerjaan?",
                options: [
                    "Tidak pernah, komitmen pribadi selalu saya utamakan, bahkan jika pekerjaan sedang sibuk.",
                    "Sangat jarang, hanya pada keadaan darurat saya terpaksa mengabaikan komitmen pribadi.",
                    "Cukup sering, saya seringkali harus menunda atau membatalkan rencana pribadi karena pekerjaan.",
                    "Sering sekali, pekerjaan selalu menjadi prioritas utama sehingga saya sering mengabaikan keluarga dan teman.",
                    "Selalu, saya selalu mengorbankan kehidupan pribadi demi pekerjaan."
                ]
            },
            {
                question: "Apakah pekerjaan Anda sangat menguras banyak tenaga sehingga menjadikan Anda tidak memiliki sisa tenaga lagi untuk melakukan kegiatan di luar kantor?",
                options: [
                    "Tidak sama sekali, saya masih memiliki banyak energi untuk melakukan hal-hal yang saya sukai di luar pekerjaan.",
                    "Sangat jarang, hanya pada proyek yang sangat padat saya merasa kelelahan.",
                    "Cukup sering, saya sering merasa lelah setelah bekerja dan sulit untuk melakukan aktivitas lain.",
                    "Sering sekali, pekerjaan membuat saya merasa sangat lelah sehingga saya tidak memiliki energi untuk bersosialisasi.",
                    "Selalu, saya merasa terlalu lelah untuk melakukan apapun selain tidur setelah bekerja."
                ]
            },
            {
                question: "Apakah Anda memiliki akses terhadap sumber daya yang membantu Anda mengelola stres terkait pekerjaan?",
                options: [
                    "Ya, sangat banyak. Perusahaan menyediakan berbagai fasilitas dan program untuk membantu karyawan mengelola stres.",
                    "Ya, cukup banyak. Ada beberapa fasilitas yang tersedia untuk membantu saya mengatasi stres.",
                    "Sedikit, fasilitas yang tersedia terbatas.",
                    "Hampir tidak ada, tidak ada fasilitas yang tersedia untuk membantu saya mengelola stres.",
                    "Tidak ada, saya harus mengelola stres sendiri tanpa bantuan apapun."
                ]
            },
            {
                question: "Apakah Anda dapat menerima kritikan yang diberikan kepada Anda tanpa menghilangkan fokus Anda dalam bekerja?",
                options: [
                    "Ya, sangat baik, saya dapat menerima kritik dengan terbuka dan menggunakannya untuk meningkatkan kinerja.",
                    "Ya, cukup baik, saya umumnya dapat menerima kritik, namun terkadang merasa tersinggung.",
                    "Agak sulit, saya merasa sulit untuk menerima kritik, terutama jika disampaikan dengan cara yang kasar.",
                    "Sangat sulit, saya merasa sangat sensitif terhadap kritik dan sulit untuk fokus setelah menerima kritik.",
                    "Tidak bisa, saya tidak dapat menerima kritik sama sekali."
                ]
            },
            {
                question: "Apakah kehidupan pribadi Anda memberikan kekuatan atau semangat dalam bekerja?",
                options: [
                    "Ya, sangat. Kehidupan pribadi saya adalah sumber energi dan motivasi terbesar saya untuk bekerja.",
                    "Ya, cukup. Kehidupan pribadi saya memberikan dukungan yang cukup untuk saya tetap semangat dalam bekerja.",
                    "Agak, kehidupan pribadi saya kadang-kadang memberikan semangat, namun tidak selalu konsisten.",
                    "Tidak begitu, kehidupan pribadi saya tidak terlalu berpengaruh pada semangat kerja saya.",
                    "Tidak sama sekali, kehidupan pribadi saya justru menjadi beban dan membuat saya sulit untuk fokus pada pekerjaan."
                ]
            },
            {
                question: "Apakah pekerjaan Anda tidak mendorong Anda untuk melakukan berbagai hal penting di luar pekerjaan?",
                options: [
                    "Ya, sangat. Pekerjaan saya sangat menyita waktu dan energi sehingga saya hampir tidak memiliki waktu untuk kegiatan lain.",
                    "Ya, cukup. Pekerjaan saya cukup menuntut sehingga sulit bagi saya untuk meluangkan waktu untuk hal-hal di luar pekerjaan.",
                    "Agak. Terkadang, pekerjaan saya membuat saya sulit untuk fokus pada kegiatan lain.",
                    "Tidak begitu, pekerjaan saya tidak terlalu mengganggu kegiatan saya di luar pekerjaan.",
                    "Tidak sama sekali, pekerjaan saya justru memberikan saya waktu luang yang cukup untuk melakukan hal-hal yang saya sukai."
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
                div.dataset.score = 4 - idx; //ini buat ngitung skor berdasarkan urutan opsi
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
            let satisfactionScore = 0;
            let timeScore = 0;
            let involvementScore = 0;

            for (let i = 0; i < 7; i++) {
                satisfactionScore += scores[i]?.score || 0;
            }
            for (let i = 7; i < 14; i++) {
                timeScore += scores[i]?.score || 0;
            }
            for (let i = 14; i < 21; i++) {
                involvementScore += scores[i]?.score || 0;
            }

            // Kirim data ke server
            const formData = new FormData();
            formData.append('satisfaction-balance', satisfactionScore);
            formData.append('time-balance', timeScore);
            formData.append('involvement-balance', involvementScore);

            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (response.ok) {
                    window.location.href = 'hasil-wlb.php';
                } else {
                    // alert('Gagal menyimpan hasil.');
                    window.location.href = 'hasil-wlb.php';
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