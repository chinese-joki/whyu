<?php
require_once 'config/database.php';

 $pdo = getConnection();

// Get all announcements
 $stmt = $pdo->query("SELECT * FROM announcements ORDER BY date_created DESC, created_at DESC");
 $announcements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whyuskieee Announcement</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="user-page">
    <!-- Decorative Background -->
    <div class="bg-pattern"></div>
    <div class="floating-shapes">
        <div class="shape shape-1">▲</div>
        <div class="shape shape-2">●</div>
        <div class="shape shape-3">■</div>
        <div class="shape shape-4">◆</div>
    </div>
    
    <!-- Header -->
    <header class="user-header">
        <div class="header-content">
            <div class="brand">
                <span class="brand-icon">📢</span>
                <h1>Whyuskieee</h1>
            </div>
            <nav class="header-nav">
                <a href="admin/index.php" class="admin-link">
                    <span>🔐</span> Admin
                </a>
            </nav>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h2 class="hero-title">
                <span class="title-line">Pengumuman</span>
                <span class="title-highlight">Terbaru</span>
            </h2>
            <p class="hero-subtitle">Tetap update dengan informasi terkini dari Whyuskieee</p>
            
            <!-- Search & Filter -->
            <div class="search-filter">
                <div class="search-box">
                    <span class="search-icon">🔍</span>
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Cari pengumuman..."
                        autocomplete="off"
                    >
                    <button class="search-clear" id="clearSearch" style="display: none;">×</button>
                </div>
                
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">
                        Semua
                    </button>
                    <button class="filter-btn filter-important" data-filter="penting">
                        🔥 Penting
                    </button>
                    <button class="filter-btn filter-normal" data-filter="biasa">
                        📄 Biasa
                    </button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Announcements Grid -->
    <main class="announcements-section">
        <?php if (empty($announcements)): ?>
        <div class="empty-state">
            <span class="empty-icon">📭</span>
            <h3>Belum Ada Pengumuman</h3>
            <p>Pengumuman akan muncul di sini setelah ditambahkan oleh admin.</p>
        </div>
        <?php else: ?>
        <div class="announcements-grid" id="announcementsGrid">
            <?php foreach ($announcements as $index => $item): ?>
            <article 
                class="announcement-card <?php echo $item['status']; ?>"
                data-title="<?php echo htmlspecialchars(strtolower($item['title'])); ?>"
                data-content="<?php echo htmlspecialchars(strtolower($item['content'])); ?>"
                data-status="<?php echo $item['status']; ?>"
                style="animation-delay: <?php echo $index * 0.1; ?>s"
            >
                <div class="card-header">
                    <span class="card-status <?php echo $item['status']; ?>">
                        <?php echo $item['status'] === 'penting' ? '🔥 Penting' : '📄 Biasa'; ?>
                    </span>
                    <span class="card-date">
                        <?php echo date('d M Y', strtotime($item['date_created'])); ?>
                    </span>
                </div>
                
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p class="card-excerpt">
                        <?php echo htmlspecialchars(substr($item['content'], 0, 150)); ?>
                        <?php echo strlen($item['content']) > 150 ? '...' : ''; ?>
                    </p>
                </div>
                
                <div class="card-footer">
                    <button class="btn btn-detail" onclick="openModal(<?php echo $item['id']; ?>)">
                        Baca Selengkapnya →
                    </button>
                </div>
                
                <!-- Modal Data -->
                <template class="modal-data">
                    <div class="modal-content-inner">
                        <div class="modal-header">
                            <span class="modal-status <?php echo $item['status']; ?>">
                                <?php echo $item['status'] === 'penting' ? '🔥 Penting' : '📄 Biasa'; ?>
                            </span>
                            <span class="modal-date">
                                <?php echo date('d M Y', strtotime($item['date_created'])); ?>
                            </span>
                        </div>
                        <h2 class="modal-title"><?php echo htmlspecialchars($item['title']); ?></h2>
                        <div class="modal-body">
                            <?php echo nl2br(htmlspecialchars($item['content'])); ?>
                        </div>
                    </div>
                </template>
            </article>
            <?php endforeach; ?>
        </div>
        
        <!-- No Results -->
        <div class="no-results" id="noResults" style="display: none;">
            <span class="empty-icon">🔍</span>
            <h3>Tidak Ditemukan</h3>
            <p>Tidak ada pengumuman yang cocok dengan pencarian Anda.</p>
        </div>
        <?php endif; ?>
    </main>
    
    <!-- Modal -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-container">
            <button class="modal-close" onclick="closeModal()">×</button>
            <div id="modalContent"></div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="user-footer">
        <div class="footer-content">
            <p>© 2025 <strong>Whyuskieee Announcement</strong>. All rights reserved.</p>
            <p class="footer-tagline">Built with ❤️ using Neubrutalism Design</p>
        </div>
    </footer>
    
    <script src="assets/js/main.js"></script>
</body>
</html>