<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $_SESSION['email'] = htmlspecialchars($_POST['email']);
}

if (!isset($_SESSION['username']) || !isset($_SESSION['tree_type']) || !isset($_SESSION['email'])) {
    header("Location: name.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
    $name = $_SESSION['username'];
    $email = $_SESSION['email'];
    $tree_type = $_SESSION['tree_type'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, tree_type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $tree_type]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header("Location: share.php");
        exit();
    } catch (PDOException $e) {
        $error = "Kayıt başarısız: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innomytree - Parola Belirle</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Raleway:wght@400;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Base Styles -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="theme-password">
    <div class="container">
        <!-- Logo Area -->
        <div class="logo-container">
            <img src="assets/logo.png" alt="Logo" class="logo-image">
        </div>

        <main class="password-card">
            <h2 class="page-title">Parola Belirle</h2>
            <p class="page-subtitle">En az 8 karakterden oluşan güvenli bir şifre belirleyin</p>

            <form action="password.php" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                <div class="input-group">
                    <input type="password" name="password" class="password-input" placeholder="Şifre Belirle" required minlength="8">
                </div>
                <?php if(isset($error)) echo "<p style='color:red; margin-bottom:10px;'>$error</p>"; ?>
                <button type="submit" class="btn-continue">Ağacımı Yap</button>
            </form>

            <p class="footer-text">
                Lütfen <a href="#">Kullanım Şartları</a> ve <a href="#">Gizlilik Politikası</a>, Innomytree hizmeti
                için temel olan şartları kabul ederek devam edin.
            </p>
        </main>
    </div>
</body>
</html>