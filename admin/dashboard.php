<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_messages = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();

// Fetch all users with their message counts
$stmt = $pdo->query("SELECT u.*, COUNT(m.id) as message_count FROM users u LEFT JOIN messages m ON u.id = m.tree_id GROUP BY u.id ORDER BY u.created_at DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innomytree - Yönetim Paneli</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #064e3b;
            --secondary-color: #10b981;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-primary: #111827;
            --text-secondary: #6b7280;
        }
        body {
            background-color: var(--bg-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
        }
        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Navbar */
        .navbar {
            background-color: var(--card-bg) !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
        }
        .btn-logout {
            color: var(--text-secondary);
            border: 1px solid #e5e7eb;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            font-weight: 500;
        }
        .btn-logout:hover {
            background-color: #fee2e2;
            color: #ef4444;
            border-color: #fee2e2;
        }

        /* Stats Cards */
        .stat-card {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
            transition: transform 0.2s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Accordion / List */
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .accordion-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem !important;
            margin-bottom: 0.75rem;
            overflow: hidden;
            background: var(--card-bg);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .accordion-button {
            padding: 1.25rem;
            background: var(--card-bg);
            color: var(--text-primary);
            font-weight: 500;
            border: none;
            box-shadow: none !important;
        }
        .accordion-button:not(.collapsed) {
            background-color: #f0fdf4;
            color: var(--primary-color);
        }
        .accordion-button::after {
            background-size: 1rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-grow: 1;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: #d1fae5;
            color: #065f46;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
        }
        .user-details {
            display: flex;
            flex-direction: column;
        }
        .user-name {
            font-weight: 600;
            color: var(--text-primary);
        }
        .user-email {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        .msg-badge {
            background-color: #ecfdf5;
            color: #059669;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 1rem;
        }

        /* Table */
        .table-custom th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            font-weight: 600;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
        }
        .table-custom td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.9rem;
        }
        .table-custom tr:last-child td {
            border-bottom: none;
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
                padding: 1.25rem;
            }
            .user-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }
            .user-avatar {
                display: none; 
            }
            .msg-badge {
                margin-right: 0;
                margin-top: 0.5rem;
            }
            .accordion-button {
                padding: 1rem;
            }
            .table-responsive {
                border: 0;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../assets/logo.png" alt="Logo" height="32" class="d-inline-block align-text-top me-2">
                <span style="color: #064e3b;">Yönetim Paneli</span>
            </a>
            <a href="logout.php" class="btn-logout text-decoration-none">
                <i class="bi bi-box-arrow-right me-1"></i> <span class="d-none d-md-inline">Çıkış Yap</span>
            </a>
        </div>
    </nav>

    <div class="container" style="margin-top: 90px; padding-bottom: 40px;">
        <!-- Header -->
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h4 class="mb-0 fw-bold text-dark">Genel Bakış</h4>
                <p class="text-muted small mb-0">Sistem istatistikleri ve kullanıcı aktiviteleri</p>
            </div>
            <div class="col-auto">
                <span class="badge bg-white text-dark border py-2 px-3 rounded-pill">
                    <i class="bi bi-calendar3 me-1"></i> <?php echo date('d.m.Y'); ?>
                </span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row mb-5">
            <div class="col-6 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #ecfdf5; color: #059669;">
                        <i class="bi bi-tree"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_users; ?></div>
                    <div class="stat-label">Toplam Ağaç</div>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #eff6ff; color: #2563eb;">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_messages; ?></div>
                    <div class="stat-label">Toplam Mesaj</div>
                </div>
            </div>
        </div>

        <!-- Users List -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="section-title mb-0">
                <i class="bi bi-people"></i> Kullanıcılar ve Mesajlar
            </div>
            <span class="badge bg-secondary rounded-pill"><?php echo count($users); ?> Kullanıcı</span>
        </div>

        <div class="accordion" id="treesAccordion">
            <?php foreach ($users as $index => $user): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?php echo $user['id']; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $user['id']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $user['id']; ?>">
                            <div class="d-flex justify-content-between w-100 align-items-center">
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                                        <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                    </div>
                                </div>
                                <div class="msg-badge">
                                    <?php echo $user['message_count']; ?> Mesaj
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse<?php echo $user['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $user['id']; ?>" data-bs-parent="#treesAccordion">
                        <div class="accordion-body p-0">
                            <div class="p-3 bg-light border-bottom">
                                <div class="row g-2 text-sm">
                                    <div class="col-6 col-md-3">
                                        <small class="text-muted d-block">Ağaç Tipi</small>
                                        <span class="fw-medium text-dark"><?php echo htmlspecialchars($user['tree_type']); ?></span>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <small class="text-muted d-block">Kayıt Tarihi</small>
                                        <span class="fw-medium text-dark"><?php echo date('d.m.Y H:i', strtotime($user['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php
                            // Fetch messages
                            $msg_stmt = $pdo->prepare("SELECT * FROM messages WHERE tree_id = ? ORDER BY created_at DESC");
                            $msg_stmt->execute([$user['id']]);
                            $user_messages = $msg_stmt->fetchAll();
                            ?>

                            <?php if (count($user_messages) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-custom mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Gönderen</th>
                                                <th style="width: 45%;">Mesaj</th>
                                                <th style="width: 15%;">Süs</th>
                                                <th style="width: 15%;">Tarih</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($user_messages as $msg): ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold text-dark"><?php echo htmlspecialchars($msg['sender_name']); ?></div>
                                                    </td>
                                                    <td class="text-secondary">
                                                        <?php echo htmlspecialchars($msg['message']); ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border">
                                                            <?php echo htmlspecialchars($msg['ornament_type']); ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-muted small">
                                                        <?php echo date('d.m.Y', strtotime($msg['created_at'])); ?><br>
                                                        <?php echo date('H:i', strtotime($msg['created_at'])); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="p-4 text-center text-muted">
                                    <i class="bi bi-chat-square-dots display-6 d-block mb-2" style="opacity: 0.2;"></i>
                                    Henüz mesaj yok.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
