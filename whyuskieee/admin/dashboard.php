<?php
require_once '../config/database.php';
requireLogin();

 $pdo = getConnection();

// Get statistics
 $totalAnnouncements = $pdo->query("SELECT COUNT(*) FROM announcements")->fetchColumn();
 $importantCount = $pdo->query("SELECT COUNT(*) FROM announcements WHERE status = 'penting'")->fetchColumn();
 $normalCount = $pdo->query("SELECT COUNT(*) FROM announcements WHERE status = 'biasa'")->fetchColumn();
 $todayCount = $pdo->query("SELECT COUNT(*) FROM announcements WHERE DATE(created_at) = CURDATE()")->fetchColumn();

// Get recent announcements
 $stmt = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5");
 $recentAnnouncements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Whyuskieee Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-page">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="brand-mini">
                <span class="logo-icon-small">📢</span>
                <span class="brand-name">Whyuskieee</span>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item active">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="announcements.php" class="nav-item">
                <span class="nav-icon">📝</span>
                <span class="nav-text">Pengumuman</span>
            </a>
            <a href="add.php" class="nav-item">
                <span class="nav-icon">➕</span>
                <span class="nav-text">Tambah Baru</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="logout.php" class="nav-item logout-btn">
                <span class="nav-icon">🚪</span>
                <span class="nav-text">Logout</span>
            </a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="admin-header">
            <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
            <h2>Dashboard</h2>
            <div class="admin-info">
                <span class="admin-avatar">👤</span>
                <span class="admin-name"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
        </header>
        
        <!-- Stats Grid -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card stat-total">
                    <div class="stat-icon">📋</div>
                    <div class="stat-info">
                        <span class="stat-number"><?php echo $totalAnnouncements; ?></span>
                        <span class="stat-label">Total Pengumuman</span>
                    </div>
                    <div class="stat-deco"></div>
                </div>
                
                <div class="stat-card stat-important">
                    <div class="stat-icon">🔥</div>
                    <div class="stat-info">
                        <span class="stat-number"><?php echo $importantCount; ?></span>
                        <span class="stat-label">Penting</span>
                    </div>
                    <div class="stat-deco"></div>
                </div>
                
                <div class="stat-card stat-normal">
                    <div class="stat-icon">📄</div>
                    <div class="stat-info">
                        <span class="stat-number"><?php echo $normalCount; ?></span>
                        <span class="stat-label">Biasa</span>
                    </div>
                    <div class="stat-deco"></div>
                </div>
                
                <div class="stat-card stat-today">
                    <div class="stat-icon">📅</div>
                    <div class="stat-info">
                        <span class="stat-number"><?php echo $todayCount; ?></span>
                        <span class="stat-label">Hari Ini</span>
                    </div>
                    <div class="stat-deco"></div>
                </div>
            </div>
        </section>
        
        <!-- Quick Actions -->
        <section class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="actions-grid">
                <a href="add.php" class="action-card action-add">
                    <span class="action-icon">➕</span>
                    <span class="action-text">Tambah Pengumuman</span>
                </a>
                <a href="announcements.php" class="action-card action-manage">
                    <span class="action-icon">⚙️</span>
                    <span class="action-text">Kelola Pengumuman</span>
                </a>
                <a href="../index.php" target="_blank" class="action-card action-view">
                    <span class="action-icon">👁</span>
                    <span class="action-text">Lihat Website</span>
                </a>
            </div>
        </section>
        
        <!-- Recent Announcements -->
        <section class="recent-section">
            <div class="section-header">
                <h3>Pengumuman Terbaru</h3>
                <a href="announcements.php" class="view-all-link">Lihat Semua →</a>
            </div>
            
            <?php if (empty($recentAnnouncements)): ?>
            <div class="empty-state">
                <span class="empty-icon">📭</span>
                <p>Belum ada pengumuman</p>
                <a href="add.php" class="btn btn-primary">Buat Pengumuman Pertama</a>
            </div>
            <?php else: ?>
            <div class="recent-list">
                <?php foreach ($recentAnnouncements as $index => $announcement): ?>
                <div class="recent-item" style="animation-delay: <?php echo $index * 0.1; ?>s">
                    <div class="recent-status <?php echo $announcement['status']; ?>">
                        <?php echo $announcement['status'] === 'penting' ? '🔥' : '📄'; ?>
                    </div>
                    <div class="recent-content">
                        <h4><?php echo htmlspecialchars($announcement['title']); ?></h4>
                        <p><?php echo htmlspecialchars(substr($announcement['content'], 0, 80)); ?>...</p>
                    </div>
                    <div class="recent-date">
                        <?php echo date('d M Y', strtotime($announcement['date_created'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>
    </main>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>