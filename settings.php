
<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'logout') {
        session_destroy();
        header("Location: index.php");
        exit();
    }
    if ($_GET['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayarlar</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Base Styles (Only for Font) -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Green Theme Settings Page Styles */
        body { margin: 0; padding: 0; font-family: 'Poppins', sans-serif; background-color: #dcfce7; color: #166534; }
        .theme-settings { display: flex; flex-direction: column; align-items: center; min-height: 100vh; }
        .container { width: 100%; max-width: 500px; padding: 20px; box-sizing: border-box; }
        .settings-header { display: flex; align-items: center; justify-content: center; position: relative; width: 100%; padding: 20px 0; margin-bottom: 20px; }
        .settings-title { font-size: 1.5rem; font-weight: 700; color: #166534; margin: 0; }
        .settings-section { width: 100%; margin-bottom: 25px; }
        .section-title { font-size: 1rem; color: #166534; margin-bottom: 10px; font-weight: 700; padding-left: 5px; }
        .settings-card { background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02); }
        .settings-item { padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; font-weight: 600; color: #064e3b; font-size: 1rem; }
        .settings-item-link { display: block; padding: 18px 20px; text-decoration: none; color: #064e3b; font-weight: 600; font-size: 1rem; transition: background 0.2s; }
        .settings-item-link:hover { background: #f0fdf4; }
        .text-red { color: #ef4444; }
        .divider { height: 1px; background: #f0f0f0; margin: 0 20px; }
        /* Toggle Switch */
        .switch { position: relative; display: inline-block; width: 52px; height: 30px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e5e7eb; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ''; height: 26px; width: 26px; left: 2px; bottom: 2px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        input:checked+.slider { background-color: #22c55e; }
        input:checked+.slider:before { transform: translateX(22px); }
        /* Footer */
        .settings-footer { text-align: center; margin-top: 40px; padding-bottom: 20px; width: 100%; }
        .footer-brand { display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 800; color: #064e3b; margin-bottom: 8px; font-size: 1.1rem; text-decoration: none; }
        .footer-brand a { text-decoration: none; color: #000000; transition: color 0.3s ease; }
        .footer-brand a:hover { color: #6b7280; }
        .footer-logo { height: 24px; width: auto; }
        .footer-links { font-size: 0.8rem; color: #166534; opacity: 0.8; }
        .footer-links a { color: inherit; text-decoration: none; }
        @media (max-width: 768px) { .container { padding-top: 20px; } }
        @media (min-width: 768px) { .theme-settings { display: flex; align-items: center; justify-content: center; min-height: 100vh; } .theme-settings .container { padding-top: 0; width: 100%; max-width: 500px; background: transparent; min-height: 1000px; } .settings-header { position: absolute; top: 20px; width: 100%; max-width: 500px; background: transparent; } }
    </style>
</head>
<body class="theme-settings">
    <div class="container">
        <!-- Logo Area -->
        <div class="logo-container">
            <img src="assets/logo.png" alt="Logo" class="logo-image">
        </div>

        <!-- Header -->
        <div class="settings-header">
            <h1 class="settings-title">Ayarlar</h1>
        </div>

        <!-- Section: Benim Ağacım -->
        <div class="settings-section">
            <h2 class="section-title">Benim Ağacım</h2>
            <div class="settings-card">
                <div class="settings-item">
                    <span>Herkese Açık Ağaç</span>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Section: Innomytree -->
        <div class="settings-section">
            <h2 class="section-title">Innomytree</h2>
            <div class="settings-card">
                <a href="https://www.innomis.tr" class="settings-item-link" target="_blank">SSS</a>
                <div class="divider"></div>
                <a href="https://www.innomis.tr" class="settings-item-link" target="_blank">İletişim</a>
            </div>
        </div>

        <!-- Section: Hesabım -->
        <div class="settings-section">
            <h2 class="section-title">Hesabım</h2>
            <div class="settings-card">
                <a href="forgot_password.php" class="settings-item-link">Şifre Değiştir</a>
                <div class="divider"></div>
                <a href="settings.php?action=logout" class="settings-item-link">Çıkış Yap</a>
                <div class="divider"></div>
                <a href="settings.php?action=delete" onclick="return confirm('Hesabınızı silmek istediğinize emin misiniz?');" class="settings-item-link text-red">Hesabı Sil</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="settings-footer">
            <div class="footer-links">
                <a href="#">Şartlar ve Koşullar</a> • <a href="#">Gizlilik Politikası</a>
            </div>
        </div>
    </div>
</body>
</html>