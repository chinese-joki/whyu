<?php
require_once '../config/database.php';
requireLogin();

 $pdo = getConnection();

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = "Pengumuman berhasil dihapus!";
    }
}

// Get all announcements
 $stmt = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC");
 $announcements = $stmt->fetchAll();

 $success = $success ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengumuman - Whyuskieee Admin</title>
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
            <a href="dashboard.php" class="nav-item">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="announcements.php" class="nav-item active">
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
        <header class="admin-header">
            <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
            <h2>Kelola Pengumuman</h2>
            <a href="add.php" class="btn btn-primary btn-sm">+ Tambah Baru</a>
        </header>
        
        <?php if ($success): ?>
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <?php if (empty($announcements)): ?>
        <div class="empty-state">
            <span class="empty-icon">📭</span>
            <p>Belum ada pengumuman</p>
            <a href="add.php" class="btn btn-primary">Buat Pengumuman</a>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($announcements as $index => $item): ?>
                    <tr style="animation-delay: <?php echo $index * 0.05; ?>s">
                        <td><?php echo $index + 1; ?></td>
                        <td>
                            <div class="table-title">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </div>
                        </td>
                        <td><?php echo date('d M Y', strtotime($item['date_created'])); ?></td>
                        <td>
                            <span class="status-badge <?php echo $item['status']; ?>">
                                <?php echo $item['status'] === 'penting' ? '🔥 Penting' : '📄 Biasa'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit.php?id=<?php echo $item['id']; ?>" class="btn btn-edit" title="Edit">
                                    ✏️
                                </a>
                                <a href="announcements.php?delete=<?php echo $item['id']; ?>" 
                                   class="btn btn-delete" 
                                   title="Hapus"
                                   onclick="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                    🗑️
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </main>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>