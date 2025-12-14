<?php
require_once 'includes/db.php';
$step = 1;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['check_email'])) {
        $email = $_POST['email'];
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $step = 2;
            $email_val = $email;
        } else {
            $error = "E-posta bulunamadı.";
        }
    } elseif (isset($_POST['reset_password'])) {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$password, $email]);
        $success = "Şifreniz güncellendi. Giriş yapabilirsiniz.";
        $step = 3;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innomytree - Şifre Değiştir</title>
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
            <?php if ($step == 1): ?>
                <h2 class="page-title">Şifremi Unuttum</h2>
                <form class="login-form" method="POST">
                    <div class="input-group">
                        <input type="email" name="email" class="login-input" placeholder="E-posta Adresiniz" required>
                    </div>
                    <?php if($error) echo "<p style='color:red; margin-bottom:10px;'>$error</p>"; ?>
                    <button type="submit" name="check_email" class="btn-login">Devam Et</button>
                </form>
            <?php elseif ($step == 2): ?>
                <h2 class="page-title">Yeni Şifre Belirle</h2>
                <form class="login-form" method="POST">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email_val); ?>">
                    <div class="input-group">
                        <input type="password" name="password" class="login-input" placeholder="Yeni Şifre" required minlength="8">
                    </div>
                    <button type="submit" name="reset_password" class="btn-login">Şifreyi Güncelle</button>
                </form>
            <?php elseif ($step == 3): ?>
                <h2 class="page-title">Başarılı!</h2>
                <p style="text-align:center; color:green; margin-bottom:20px;"><?php echo $success; ?></p>
                <a href="login.php" class="btn-login" style="text-decoration:none; display:flex; justify-content:center; align-items:center;">Giriş Yap</a>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>