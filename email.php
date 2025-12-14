<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tree_type'])) {
    $_SESSION['tree_type'] = htmlspecialchars($_POST['tree_type']);
}
if (!isset($_SESSION['username']) || !isset($_SESSION['tree_type'])) {
    header("Location: name.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innomytree - E-posta</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Raleway:wght@400;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Base Styles -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="theme-email">
    <div class="container">
        <!-- Logo Area -->
        <div class="logo-container">
            <img src="assets/logo.png" alt="Logo" class="logo-image">
        </div>

        <main class="email-card">
            <h2 class="page-title">E-posta Adresinizi</h2>
            <p class="page-subtitle">E-posta, ağacınızı yönetmek için kullanılacak</p>

            <form action="password.php" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                <div class="input-group">
                    <input type="email" name="email" class="email-input" placeholder="E-posta Adresiniz" required>
                </div>

                <button type="submit" class="btn-continue">DEVAM</button>
            </form>
        </main>
    </div>
</body>
</html>
