
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// Construct share link. Adjust path if necessary.
$path = dirname($_SERVER['PHP_SELF']);
if ($path == '/' || $path == '\\') $path = '';
$share_link = "http://" . $_SERVER['HTTP_HOST'] . $path . "/friend_tree.php?id=" . $user_id;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innomytree - Paylaş</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Raleway:wght@400;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Base Styles -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="theme-share">
    <div class="container">
        <!-- Logo Area -->
        <div class="logo-container">
            <img src="assets/logo.png" alt="Logo" class="logo-image">
        </div>

        <main class="share-card">
            <div class="content-wrapper">
                <div class="text-section">
                    

                    <a href="me_tree.php" class="btn-link-tree">
                        <!-- Tree Emoji -->
                        <span style="font-size: 1.5rem; margin-right: 0.5rem;"></span>
                        Ağacıma Git
                    </a>
                </div>

                <div class="phone-container">
                    <img src="assets/phone/phone.png" alt="Phone Mockup" class="phone-img">
                </div>
            </div>
        </main>
    </div>
</body>
</html>