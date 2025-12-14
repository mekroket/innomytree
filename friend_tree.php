<?php
require_once 'includes/db.php';
$tree_owner_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$tree_owner_id]);
$owner = $stmt->fetch();

if (!$owner) {
    die("Ağaç bulunamadı!");
}

// Handle Message Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sender_name'])) {
    $sender = htmlspecialchars($_POST['sender_name']);
    $message = htmlspecialchars($_POST['message']);
    $ornament_path = htmlspecialchars($_POST['ornament_type']);
    
    // Extract filename 'sus1' from 'assets/süsler/sus1.webp'
    $ornament_name = pathinfo($ornament_path, PATHINFO_FILENAME);
    
    $stmt = $pdo->prepare("INSERT INTO messages (tree_id, sender_name, message, ornament_type) VALUES (?, ?, ?, ?)");
    $stmt->execute([$tree_owner_id, $sender, $message, $ornament_name]);
    
    echo json_encode(['status' => 'success']);
    exit;
}

// Fetch existing messages
$stmt = $pdo->prepare("SELECT * FROM messages WHERE tree_id = ? ORDER BY created_at ASC");
$stmt->execute([$tree_owner_id]);
$messages = $stmt->fetchAll();

// Tree Image Logic
$tree_map = [
    'blue' => 'blue_isikli.png',
    'brown' => 'brown_isikli.png',
    'green' => 'green_isikli.png',
    'red' => 'red_isikli.png',
    'inno' => 'inno_isikli.png'
];
$tree_image = isset($tree_map[$owner['tree_type']]) ? $tree_map[$owner['tree_type']] : 'inno_isikli.png';

// Chunk messages for pagination (5 per page)
$items_per_page = 5;
$chunks = array_chunk($messages, $items_per_page);
if (empty($chunks)) {
    $chunks = [[]];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($owner['name']); ?> - Innomytree</title>
    <meta name="description" content="<?php echo htmlspecialchars($owner['name']); ?> senin için bir yılbaşı ağacı oluşturdu! Ona bir mesaj bırak ve ağacını süsle.">
    <meta name="keywords" content="yılbaşı, ağaç, mesaj, 2025, noel, hediye, dijital ağaç, <?php echo htmlspecialchars($owner['name']); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($owner['name']); ?> - Innomytree">
    <meta property="og:description" content="<?php echo htmlspecialchars($owner['name']); ?> senin için bir yılbaşı ağacı oluşturdu! Ona bir mesaj bırak ve ağacını süsle.">
    <meta property="og:image" content="assets/logo.png">
    <meta property="og:url" content="https://innomytree.com/friend_tree.php?id=<?php echo $tree_owner_id; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/friend.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* Slider styles */
        .tree-wrapper {
            overflow: hidden;
            position: relative;
            display: block;
        }
        .tree-slider-track {
            display: flex;
            transition: transform 0.5s ease;
            width: 100%;
        }
        .tree-slide {
            min-width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .tree-container {
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
        }
        .info-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body>
    <!-- Logo Area -->
    <div class="logo-container">
        <img src="assets/logo.png" alt="Logo" class="logo-image">
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="close-btn" id="closeBtn">×</button>
        <h2 class="sidebar-title">Merhaba!</h2>
        <p style="color:white; text-align:center;">Bu <?php echo htmlspecialchars($owner['name']); ?>'in ağacı.</p>

        <a href="https://www.innomis.tr" class="menu-item" target="_blank">SSS</a>
        <a href="https://www.innomis.tr" class="menu-item" target="_blank">İletişim</a>
        <a href="index.php" class="menu-item">Kendi Ağacını Yap</a>

        <div class="sidebar-footer">
            <div class="countdown-footer">Noel Geri Sayımı</div>
            <div class="countdown-footer-time" id="sidebarCountdown">...</div>
            <div class="social-icons">
                <a href="#" class="social-icon twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="social-icon instagram"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </div>

    <div class="toast" id="toast">
        <span class="toast-icon">🔗</span> Link Kopyalandı!
    </div>

    <div class="header" id="countdown">Yükleniyor...</div>

    <button class="menu-btn">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="container">
        <div class="tree-wrapper">
            <div class="tree-slider-track" id="sliderTrack">
                <?php foreach ($chunks as $page_index => $chunk): ?>
                <div class="tree-slide">
                    <div class="tree-container">
                        <img src="assets/isikliagaclar/<?php echo $tree_image; ?>" alt="Christmas Tree" class="tree-img">
                        
                        <?php foreach ($chunk as $index => $msg): ?>
                            <?php 
                                // Calculate position based on index within the chunk (0-4)
                                $local_index = $index; 
                                // Define positions for 5 ornaments
                                $positions = [
                                    ['top' => '22%', 'left' => '45%'],
                                    ['top' => '38%', 'left' => '30%'],
                                    ['top' => '42%', 'left' => '58%'],
                                    ['top' => '60%', 'left' => '25%'],
                                    ['top' => '65%', 'left' => '55%']
                                ];
                                $pos = isset($positions[$local_index]) ? $positions[$local_index] : ['top' => '50%', 'left' => '50%'];
                                $ornament_src = "assets/süsler/" . htmlspecialchars($msg['ornament_type']) . ".webp";
                            ?>
                            <div class="tree-ornament" style="position: absolute; top: <?php echo $pos['top']; ?>; left: <?php echo $pos['left']; ?>;"
                                 onclick="showLockedMessage()">
                                <img src="<?php echo $ornament_src; ?>" alt="Süs" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="info-container">
                <div class="navigation">
                    <button class="nav-btn" onclick="prevSlide()">‹</button>
                    <span class="page-number" id="pageIndicator">1/<?php echo count($chunks); ?></span>
                    <button class="nav-btn" onclick="nextSlide()">›</button>
                </div>

                <h1 class="title"><?php echo htmlspecialchars($owner['name']); ?> </h1>
            </div>
        </div>

        <button class="share-btn" onclick="startDecoration()">
            <span class="share-icon"></span>
            Süslemeye Başla
        </button>

        <p class="footer-text">Daha fazla süs kazanmak için paylaşın!</p>
    </div>

    <!-- Message Display Modal (Locked) -->
    <div class="modal-overlay" id="lockedModal">
        <div class="modal-card">
            <div class="dashed-box" style="min-height: 200px; justify-content: center; align-items: center;">
                <div class="gift-container">
                    <div class="gift-img" style="font-size: 50px;">🎁</div>
                    <div class="locked-message">Bu mesaj Noel'de açılacak!</div>
                </div>
            </div>
            <div class="modal-close-wrapper">
                <button class="modal-close-x-btn" onclick="document.getElementById('lockedModal').classList.remove('active')">×</button>
            </div>
        </div>
    </div>

    <!-- Ornament Selection Modal -->
    <div class="selection-modal" id="selectionModal">
        <div class="selection-content">
            <h2 class="selection-title">Bir Süs Seç!</h2>
            <div class="ornament-grid" id="ornamentGrid"></div>
            <div class="selection-buttons">
                <button class="btn-back" onclick="closeSelection()">←</button>
                <button class="btn-continue" onclick="continueToMessage()">Devam</button>
            </div>
        </div>
    </div>

    <!-- Message Form Modal -->
    <div class="selection-modal" id="messageFormModal">
        <div class="selection-content">
            <h2 class="selection-title" style="color: #4CAF50;"><?php echo htmlspecialchars($owner['name']); ?></h2>
            <h3 style="color: white; margin-bottom: 20px;">Bir mesaj bırak!</h3>

            <div class="selected-ornament">
                <div id="selectedOrnamentDisplay"></div>
            </div>

            <div class="message-form-modal">
                <div class="form-group">
                    <label class="form-label">Gönderen:</label>
                    <input type="text" class="form-input" id="senderName" placeholder="İsim veya Adınız" maxlength="15">
                    <div class="char-count"><span id="nameCount">0</span>/15</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mesajınızı buraya yazın!</label>
                    <div class="hint">En az 10 karakter, en fazla 500.</div>
                    <textarea class="form-textarea" id="messageText" placeholder="Mesajınızı yazın..."></textarea>
                    <div class="char-count"><span id="messageCount">0</span>/500</div>
                </div>
            </div>

            <div class="selection-buttons">
                <button class="btn-back" onclick="backToSelection()">←</button>
                <button class="btn-send" id="sendBtn" onclick="sendMessage()" disabled>Süsle!</button>
            </div>
        </div>
    </div>

    <script>
        const ornaments = [
            'assets/süsler/sus1.webp',
            'assets/süsler/sus2.webp',
            'assets/süsler/sus3.webp',
            'assets/süsler/sus4.webp',
            'assets/süsler/sus5.webp'
        ];
        let selectedOrnament = null;
        
        // Slider Logic
        let currentSlide = 0;
        const totalSlides = <?php echo count($chunks); ?>;
        const track = document.getElementById('sliderTrack');
        const pageIndicator = document.getElementById('pageIndicator');

        function updateSlider() {
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
            pageIndicator.textContent = `${currentSlide + 1}/${totalSlides}`;
        }

        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                updateSlider();
            }
        }

        function prevSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                updateSlider();
            }
        }
        
        // Go to last slide initially if there are messages
        if (totalSlides > 1) {
             currentSlide = totalSlides - 1;
             updateSlider();
        }

        function updateCountdown() {
            const christmas = new Date('December 25, 2025 00:00:00').getTime();
            const now = new Date().getTime();
            const distance = christmas - now;
            
            if (distance < 0) {
                 const text = "Artık Okumaya Hazırsınız";
                 const countdownEl = document.getElementById('countdown');
                 const sidebarCountdownEl = document.getElementById('sidebarCountdown');
                 if (countdownEl) countdownEl.textContent = text;
                 if (sidebarCountdownEl) sidebarCountdownEl.textContent = text;
                 return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            const timeString = `${days}g ${hours.toString().padStart(2, '0')}s ${minutes.toString().padStart(2, '0')}d ${seconds.toString().padStart(2, '0')}sn`;

            const countdownEl = document.getElementById('countdown');
            const sidebarCountdownEl = document.getElementById('sidebarCountdown');

            if (countdownEl) countdownEl.textContent = timeString;
            if (sidebarCountdownEl) sidebarCountdownEl.textContent = timeString;
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // Sidebar
        const menuBtn = document.querySelector('.menu-btn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const closeBtn = document.getElementById('closeBtn');

        function openSidebar() {
            sidebar.classList.add('open');
            sidebarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }
        if (menuBtn) menuBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

        function startDecoration() {
            // Removed limit check
            initOrnamentGrid();
            document.getElementById('selectionModal').classList.add('active');
        }

        function initOrnamentGrid() {
            const grid = document.getElementById('ornamentGrid');
            grid.innerHTML = '';
            ornaments.forEach((ornament, index) => {
                const div = document.createElement('div');
                div.className = 'ornament-option';
                div.innerHTML = `<img src="${ornament}" alt="Süs" style="width: 60px; height: 60px; object-fit: contain;">`;
                div.onclick = () => selectOrnament(ornament, div);
                grid.appendChild(div);
            });
        }

        function selectOrnament(ornament, element) {
            document.querySelectorAll('.ornament-option').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            selectedOrnament = ornament;
        }

        function closeSelection() {
            document.getElementById('selectionModal').classList.remove('active');
            selectedOrnament = null;
        }

        function continueToMessage() {
            if (!selectedOrnament) {
                alert('Lütfen bir süs seçin!');
                return;
            }
            document.getElementById('selectionModal').classList.remove('active');
            document.getElementById('selectedOrnamentDisplay').innerHTML = `<img src="${selectedOrnament}" alt="Seçilen Süs" style="width: 80px; height: 80px; object-fit: contain;">`;
            document.getElementById('messageFormModal').classList.add('active');
        }

        function backToSelection() {
            document.getElementById('messageFormModal').classList.remove('active');
            document.getElementById('selectionModal').classList.add('active');
        }

        document.getElementById('senderName').addEventListener('input', function () {
            document.getElementById('nameCount').textContent = this.value.length;
            validateForm();
        });

        document.getElementById('messageText').addEventListener('input', function () {
            const length = this.value.length;
            document.getElementById('messageCount').textContent = length;
            if (length > 500) {
                this.value = this.value.substring(0, 500);
                document.getElementById('messageCount').textContent = 500;
            }
            validateForm();
        });

        function validateForm() {
            const name = document.getElementById('senderName').value;
            const message = document.getElementById('messageText').value;
            document.getElementById('sendBtn').disabled = !(name.length > 0 && message.length >= 10 && message.length <= 500);
        }

        function sendMessage() {
            const name = document.getElementById('senderName').value;
            const message = document.getElementById('messageText').value;
            
            const formData = new FormData();
            formData.append('sender_name', name);
            formData.append('message', message);
            formData.append('ornament_type', selectedOrnament);

            fetch('friend_tree.php?id=<?php echo $tree_owner_id; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    const toast = document.getElementById('toast');
                    toast.innerHTML = '<span class="toast-icon"></span> Süs eklendi!';
                    toast.classList.add('show');
                    setTimeout(() => {
                        toast.classList.remove('show');
                        location.reload();
                    }, 500); // Reduced delay to 500ms
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Bir hata oluştu.');
            });
        }

        function showLockedMessage() {
            document.getElementById('lockedModal').classList.add('active');
        }
    </script>
</body>
</html>