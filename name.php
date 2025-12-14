<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innomytree - Adın Ne?</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Raleway:wght@400;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Base Styles -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="theme-name">
    <div class="container">
        <!-- Logo Area -->
        <div class="logo-container">
            <img src="assets/logo.png" alt="Logo" class="logo-image">
        </div>

        <main class="name-card">
            <h2 class="page-title">Adın Ne?</h2>
            <p class="page-subtitle">Arkadaşların tarafından tanınabilir bir şey<br>Sonradan değiştirilemez!</p>

            <form action="select_tree.php" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                <div class="input-group">
                    <input type="text" name="username" class="name-input" placeholder="Adınız" maxlength="20" required>
                </div>

                <button type="submit" class="btn-continue">Devam</button>
            </form>
        </main>
    </div>
</body>
</html>
