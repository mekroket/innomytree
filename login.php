<?php
session_start();
require_once 'includes/db.php';

// Generate CAPTCHA if not present or on reload
if (!isset($_SESSION['captcha_num1']) || !isset($_SESSION['captcha_num2'])) {
    $_SESSION['captcha_num1'] = rand(1, 9);
    $_SESSION['captcha_num2'] = rand(1, 9);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $captcha_input = isset($_POST['captcha']) ? intval($_POST['captcha']) : 0;
    $captcha_sum = $_SESSION['captcha_num1'] + $_SESSION['captcha_num2'];

    if ($captcha_input !== $captcha_sum) {
        $error = "Güvenlik sorusu hatalı!";
        // Regenerate captcha on error
        $_SESSION['captcha_num1'] = rand(1, 9);
        $_SESSION['captcha_num2'] = rand(1, 9);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            // Clear captcha
            unset($_SESSION['captcha_num1']);
            unset($_SESSION['captcha_num2']);
            header("Location: me_tree.php");
            exit();
        } else {
            $error = "E-posta veya şifre hatalı!";
            // Regenerate captcha on error
            $_SESSION['captcha_num1'] = rand(1, 9);
            $_SESSION['captcha_num2'] = rand(1, 9);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innomytree - Giriş Yap</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Raleway:wght@400;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Base Styles -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="theme-login">
    <div class="container">
        <!-- Logo Area -->
        <div class="logo-container">
            <img src="assets/logo.png" alt="Logo" class="logo-image">
        </div>

        <main class="login-card">
            <div class="login-icon">
                <img src="assets/agaclar/greenagac.webp" alt="Tree Icon" class="tree-icon">
            </div>

            <h2 class="page-title">Tekrar Hoş Geldiniz!</h2>

            <form class="login-form" method="POST">
                <div class="input-group">
                    <input type="email" name="email" class="login-input" placeholder="Mail girin" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password" class="login-input" placeholder="Şifre girin" required>
                </div>

                <div class="input-group" style="display: flex; align-items: center; gap: 10px;">
                    <span style="color: #064e3b; font-weight: bold; font-size: 1.2rem; white-space: nowrap;">
                        <?php echo $_SESSION['captcha_num1'] . " + " . $_SESSION['captcha_num2'] . " = ?"; ?>
                    </span>
                    <input type="number" name="captcha" class="login-input" placeholder="Sonuç?" required style="text-align: center;">
                </div>

                <?php if(isset($error)) echo "<p style='color:red; margin-bottom:10px;'>$error</p>"; ?>

                <button type="submit" class="btn-login">Giriş Yap</button>
            </form>

            <a href="forgot_password.php" class="forgot-password">Şifremi Unuttum?</a>
        </main>
    </div>
</body>
</html>