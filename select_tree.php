<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $_SESSION['username'] = htmlspecialchars($_POST['username']);
}
if (!isset($_SESSION['username'])) {
    header("Location: name.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innomytree - Ağaç Seçimi</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Raleway:wght@400;700;800&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">

    <!-- Base Styles -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Page Specific Styles -->

</head>

<body class="theme-select-tree">

    <div class="container selection-container">
        <!-- Logo Area -->
        <div class="logo-container">
            <img src="assets/logo.png" alt="Logo" class="logo-image">
        </div>

        <main class="selection-card">
            <h2 class="page-title">Bir Ağaç Seç</h2>
            <p class="page-subtitle">Seçiminizi dikkatlice yapın, çünkü bir kez seçim<br>yaptığınızda bu karar
                değiştirilemez</p>

            <!-- Tree Slider -->
            <div class="slider-container">
                <button class="slider-btn prev-btn" id="prevBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </button>

                <div class="slider-window" id="sliderTrack">
                    <!-- Option 1 -->
                    <div class="tree-option active" data-tree="blue">
                        <img src="assets/agaclar/blueagac.webp" alt="Mavi Ağaç" class="tree-img">
                    </div>
                    <!-- Option 2 -->
                    <div class="tree-option" data-tree="brown">
                        <img src="assets/agaclar/brownagac.webp" alt="Kahverengi Ağaç" class="tree-img">
                    </div>
                    <!-- Option 3 -->
                    <div class="tree-option" data-tree="green">
                        <img src="assets/agaclar/greenagac.webp" alt="Yeşil Ağaç" class="tree-img">
                    </div>
                    <!-- Option 4 -->
                    <div class="tree-option" data-tree="red">
                        <img src="assets/agaclar/redagac.webp" alt="Kırmızı Ağaç" class="tree-img">
                    </div>
                    <!-- Option 5 -->
                    <div class="tree-option" data-tree="inno">
                        <img src="assets/agaclar/innoagac.webp" alt="Innomis Ağaç" class="tree-img">
                    </div>
                </div>

                <button class="slider-btn next-btn" id="nextBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </button>
            </div>

            <form action="email.php" method="POST" id="treeForm" style="width: 100%; display: flex; justify-content: center;">
                <input type="hidden" name="tree_type" id="treeTypeInput" value="blue">
                <button type="submit" class="btn-primary btn-continue" id="selectBtn">Devam</button>
            </form>
        </main>
    </div>

    <script>
        // Simple Slider Logic
        const track = document.getElementById('sliderTrack');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const options = document.querySelectorAll('.tree-option');
        const treeTypeInput = document.getElementById('treeTypeInput');
        let currentIndex = 0;

        function updateSlider() {
            // Hide all
            options.forEach(opt => opt.classList.remove('active'));
            // Show current
            options[currentIndex].classList.add('active');
            
            // Update hidden input
            const selectedTree = options[currentIndex].getAttribute('data-tree');
            treeTypeInput.value = selectedTree;
        }

        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % options.length;
            updateSlider();
        });

        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + options.length) % options.length;
            updateSlider();
        });
        
        // Initialize input value
        updateSlider();
    </script>
</body>

</html>
