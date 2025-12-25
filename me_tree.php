<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$tree_map = [
    'blue' => 'blue_isikli.png',
    'brown' => 'brown_isikli.png',
    'green' => 'green_isikli.png',
    'red' => 'red_isikli.png',
    'inno' => 'inno_isikli.png'
];
$tree_image = isset($tree_map[$user['tree_type']]) ? $tree_map[$user['tree_type']] : 'inno_isikli.png';

// Fetch messages
$stmt = $pdo->prepare("SELECT * FROM messages WHERE tree_id = ? ORDER BY created_at ASC");
$stmt->execute([$user_id]);
$messages = $stmt->fetchAll();

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
    <title><?php echo htmlspecialchars($user['name']); ?> </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/metree.css">
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
        <h2 class="sidebar-title">Merhaba, <?php echo htmlspecialchars($user['name']); ?>!</h2>

        <a href="https://www.innomis.tr" class="menu-item" target="_blank" style="text-decoration: none;">SSS</a>
        <a href="https://www.innomis.tr" class="menu-item" target="_blank" style="text-decoration: none;">İletişim</a>
        <a href="settings.php" class="menu-item" style="text-decoration: none;">Ayarlar</a>

        <div class="sidebar-footer">
            <div class="countdown-footer">Noel Geri Sayımı</div>
            <div class="countdown-footer-time" id="sidebarCountdown">...</div>
            <div class="social-icons">
                <a href="#" class="social-icon twitter">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="#" class="social-icon instagram">
                    <i class="bi bi-instagram"></i>
                </a>
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
                <?php
                $global_index = 0;
                foreach ($chunks as $page_index => $chunk):
                    ?>
                    <div class="tree-slide">
                        <div class="tree-container">
                            <img src="assets/isikliagaclar/<?php echo $tree_image; ?>" alt="Christmas Tree"
                                class="tree-img">

                            <?php foreach ($chunk as $index => $msg): ?>
                                <?php
                                // Calculate position based on index within the chunk (0-4)
                                $local_index = $index;
                                // Define positions for 5 ornaments
                                $positions = [
                                    ['top' => '26%', 'left' => '46%', 'right' => null],
                                    ['top' => '44%', 'left' => null, 'right' => '51%'],
                                    ['top' => '48%', 'left' => '53%', 'right' => null],
                                    ['top' => '63%', 'left' => null, 'right' => '54%'],
                                    ['top' => '65%', 'left' => '55%', 'right' => null]
                                ];

                                $pos = isset($positions[$local_index]) ? $positions[$local_index] : ['top' => '50%', 'left' => '50%', 'right' => null];
                                $ornament_src = "assets/süsler/" . htmlspecialchars($msg['ornament_type']) . ".webp";

                                // CSS stilini oluştur
                                $style = "position: absolute; top: " . $pos['top'] . ";";
                                if (isset($pos['left']) && $pos['left'] !== null) {
                                    $style .= " left: " . $pos['left'] . ";";
                                    $style .= " right: auto;";
                                } elseif (isset($pos['right']) && $pos['right'] !== null) {
                                    $style .= " right: " . $pos['right'] . ";";
                                    $style .= " left: auto;";
                                }
                                ?>
                                <img src="<?php echo $ornament_src; ?>" alt="Süs" class="tree-ornament"
                                    style="<?php echo $style; ?>" data-global-index="<?php echo $global_index; ?>"
                                    data-sender="<?php echo htmlspecialchars($msg['sender_name']); ?>"
                                    data-date="<?php echo date('M d, Y • h:i A', strtotime($msg['created_at'])); ?>">
                                <?php $global_index++; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="info-container">
                <div class="navigation">
                    <button class="popup-nav-btn prev" onclick="prevSlide()">‹</button>
                    <span class="page-number" id="pageIndicator">1/<?php echo count($chunks); ?></span>
                    <button class="popup-nav-btn next" onclick="nextSlide()">›</button>
                </div>

                <h1 class="title"><?php echo htmlspecialchars($user['name']); ?></h1>
            </div>
        </div>

        <button class="share-btn">
            <span class="share-icon">↗</span>
            Paylaşmak için linkinizi kopyalayın
        </button>

        <p class="footer-text">Daha fazla süs kazanmak için paylaşın!</p>
    </div>


    <div class="footer-credits"
        style="margin-top: 25px; text-align: center; font-size: 0.75rem; color: #C92A2A; opacity: 0.8; line-height: 1.6;">
        <div>
            UI Design by <a href="https://www.linkedin.com/in/furkan-utkay-demirbas/"
                style="color: inherit; text-decoration: none; font-weight: 700;">Furkan Demirbaş</a>
        </div>
        <div>
            Backend by <a href="https://oguzkaanekin.site" target="_blank"
                style="color: inherit; text-decoration: none; font-weight: 700;">oguzkaanekin</a>
        </div>
    </div>

    <div class="modal-overlay" id="messageModal">
        <div class="modal-card">
            <!-- Slider Navigation Buttons -->
            <button class="slider-nav-btn prev" onclick="prevOrnament()">‹</button>
            <button class="slider-nav-btn next" onclick="nextOrnament()">›</button>

            <div class="modal-top-icon-wrapper">
                <img src="" class="modal-top-icon" id="modalTopIcon" alt="Ornament">
            </div>

            <div class="dashed-box">
                <button class="modal-options-btn">⋮</button>

                <div class="sender-title" id="modalSender">Gönderen: ...</div>

                <div class="meta-info">
                    <span>🕯️</span> <span>🔒</span> <span id="modalDate">...</span>
                </div>

                <div class="meta-divider"></div>

                <div class="gift-container">
                    <img src="https://cdn-icons-png.flaticon.com/512/4213/4213651.png" class="gift-img" alt="Gift Box">
                    <div class="locked-message">Mesajlar Noel'de açılacak!</div>
                </div>
            </div>

            <!-- Close Button at the bottom -->
            <div class="modal-close-wrapper">
                <button class="modal-close-x-btn" onclick="closeModal()">×</button>
            </div>
        </div>
    </div>

    <script>
        // --- Geri Sayım ---
        function updateCountdown() {
            const christmas = new Date('December 25, 2025 00:00:00').getTime();
            const now = new Date().getTime();
            const distance = christmas - now;

            if (distance < 0) {
                const text = "Artık Okumaya Hazırsınız";
                document.getElementById('countdown').textContent = text;
                document.getElementById('sidebarCountdown').textContent = text;
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            const timeString = `${days}g ${hours.toString().padStart(2, '0')}s ${minutes.toString().padStart(2, '0')}d ${seconds.toString().padStart(2, '0')}sn`;
            document.getElementById('countdown').textContent = timeString;
            document.getElementById('sidebarCountdown').textContent = timeString;
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // --- Sidebar Logic ---
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
        menuBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);

        // --- Paylaş Butonu ---
        document.querySelector('.share-btn').addEventListener('click', function () {
            // Construct share link
            const shareLink = "<?php echo "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/friend_tree.php?id=" . $user_id; ?>";
            navigator.clipboard.writeText(shareLink).then(() => {
                const toast = document.getElementById('toast');
                toast.classList.add('show');
                setTimeout(() => { toast.classList.remove('show'); }, 3000);
            });
        });

        // --- Slider Logic ---
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

        // --- YENİ POPUP MANTIĞI ---
        const messageModal = document.getElementById('messageModal');
        const ornaments = document.querySelectorAll('.tree-ornament');
        let currentOrnamentIndex = 0;
        const totalOrnaments = ornaments.length;

        // Verileri topla
        const ornamentData = [
            <?php
            $target_date = strtotime('2025-12-25 00:00:00');
            $now = time();
            foreach ($messages as $msg):
                $is_revealed = ($now >= $target_date);
                // Use the raw message for json_encode, it handles escaping for JS. 
                // textContent in JS will handle HTML entity escaping for display.
                $content = $is_revealed ? $msg['message'] : "Mesajlar Noel'de açılacak!";
                $ornament_src = "assets/süsler/" . $msg['ornament_type'] . ".webp";
                $date_str = date('M d, Y • h:i A', strtotime($msg['created_at']));
                ?>
                {
                    src: <?php echo json_encode($ornament_src); ?>,
                    sender: <?php echo json_encode($msg['sender_name']); ?>,
                    date: <?php echo json_encode($date_str); ?>,
                    message: <?php echo json_encode($content); ?>,
                    revealed: <?php echo json_encode($is_revealed); ?>
                },
            <?php endforeach; ?>
        ];

        function openModal(index) {
            if (ornamentData.length === 0) return;
            currentOrnamentIndex = index;
            updateModalContent();
            messageModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function updateModalContent() {
            if (ornamentData.length === 0) return;
            const data = ornamentData[currentOrnamentIndex];
            const icon = document.getElementById('modalTopIcon');
            const sender = document.getElementById('modalSender');
            const date = document.getElementById('modalDate');
            const messageEl = document.querySelector('.locked-message');
            const giftImg = document.querySelector('.gift-img');

            if (icon) icon.src = data.src;
            if (sender) sender.textContent = 'Gönderen: ' + data.sender;
            if (date) date.textContent = data.date;
            if (messageEl) messageEl.textContent = data.message;

            if (data.revealed) {
                if (giftImg) giftImg.style.display = 'none';
            } else {
                if (giftImg) giftImg.style.display = 'block';
            }
        }

        function nextOrnament() {
            if (ornamentData.length === 0) return;
            currentOrnamentIndex = (currentOrnamentIndex + 1) % ornamentData.length;
            updateModalContent();
        }

        function prevOrnament() {
            if (ornamentData.length === 0) return;
            currentOrnamentIndex = (currentOrnamentIndex - 1 + ornamentData.length) % ornamentData.length;
            updateModalContent();
        }

        // Her süse tıklama olayı ekle
        ornaments.forEach((ornament) => {
            ornament.addEventListener('click', function () {
                const index = parseInt(this.getAttribute('data-global-index'));
                openModal(index);
            });
        });

        // Popup Kapatma Fonksiyonu
        function closeModal() {
            messageModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Popup dışına (overlay'e) tıklanırsa kapat
        messageModal.addEventListener('click', function (e) {
            if (e.target === messageModal) {
                closeModal();
            }
        });
    </script>
</body>

</html>