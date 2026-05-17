<?php
require_once '../config/database.php';
requireLogin();

 $pdo = getConnection();
 $error = '';
 $success = '';

// Get announcement
 $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
 $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = ?");
 $stmt->execute([$id]);
 $announcement = $stmt->fetch();

if (!$announcement) {
    header('Location: announcements.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $content = sanitize($_POST['content'] ?? '');
    $date = $_POST['date'] ?? '';
    $status = sanitize($_POST['status'] ?? 'biasa');
    
    // Validation
    if (empty($title)) {
        $error = 'Judul pengumuman harus diisi!';
    } elseif (strlen($title) > 255) {
        $error = 'Judul maksimal 255 karakter!';
    } elseif (empty($content)) {
        $error = 'Isi pengumuman harus diisi!';
    } elseif (empty($date)) {
        $error = 'Tanggal harus diisi!';
    } elseif (!in_array($status, ['penting', 'biasa'])) {
        $error = 'Status tidak valid!';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE announcements SET title = ?, content = ?, date_created = ?, status = ? WHERE id = ?");
            if ($stmt->execute([$title, $content, $date, $status, $id])) {
                $success = 'Pengumuman berhasil diperbarui!';
                // Refresh data
                $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = ?");
                $stmt->execute([$id]);
                $announcement = $stmt->fetch();
            }
        } catch(PDOException $e) {
            $error = 'Gagal memperbarui pengumuman. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengumuman - Whyuskieee Admin</title>
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
        <header class="admin-header">
            <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
            <h2>Edit Pengumuman</h2>
            <a href="announcements.php" class="btn btn-secondary btn-sm">← Kembali</a>
        </header>
        
        <div class="form-container">
            <?php if ($error): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠</span>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <?php echo $success; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="announcement-form">
                <div class="form-group">
                    <label for="title">Judul Pengumuman *</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="<?php echo htmlspecialchars($announcement['title']); ?>"
                        placeholder="Masukkan judul pengumuman"
                        maxlength="255"
                        required
                    >
                    <span class="char-count"><span id="titleCount"><?php echo strlen($announcement['title']); ?></span>/255</span>
                </div>
                
                <div class="form-group">
                    <label for="content">Isi Pengumuman *</label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="6"
                        placeholder="Tulis isi pengumuman di sini..."
                        required
                    ><?php echo htmlspecialchars($announcement['content']); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Tanggal *</label>
                        <input 
                            type="date" 
                            id="date" 
                            name="date" 
                            value="<?php echo htmlspecialchars($announcement['date_created']); ?>"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="biasa" <?php echo $announcement['status'] === 'biasa' ? 'selected' : ''; ?>>
                                📄 Biasa
                            </option>
                            <option value="penting" <?php echo $announcement['status'] === 'penting' ? 'selected' : ''; ?>>
                                🔥 Penting
                            </option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="announcements.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <span>Update Pengumuman</span>
                        <span class="btn-arrow">→</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <script src="../assets/js/main.js"></script>
    <script>
        // Character counter
        const titleInput = document.getElementById('title');
        const titleCount = document.getElementById('titleCount');
        
        titleInput.addEventListener('input', function() {
            titleCount.textContent = this.value.length;
        });
    </script>
</body>
</html>