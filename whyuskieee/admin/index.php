<?php
require_once '../config/database.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

 $error = '';
 $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        try {
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Username atau password salah!';
            }
        } catch(PDOException $e) {
            $error = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Whyuskieee Announcement</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <!-- Decorative Elements -->
        <div class="deco-blob blob-1"></div>
        <div class="deco-blob blob-2"></div>
        <div class="deco-star star-1">★</div>
        <div class="deco-star star-2">✦</div>
        
        <div class="login-box">
            <div class="login-header">
                <div class="brand-logo">
                    <span class="logo-icon">📢</span>
                    <h1>Whyuskieee</h1>
                </div>
                <p class="subtitle">ADMIN PORTAL</p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠</span>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <span id="eye-icon">👁</span>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login">
                    <span>MASUK</span>
                    <span class="btn-arrow">→</span>
                </button>
            </form>
            
            <div class="login-footer">
                <a href="../index.php" class="back-link">
                    <span>←</span> Kembali ke Website
                </a>
            </div>
        </div>
        
        <div class="login-info">
            <p>Demo Login:</p>
            <code>admin / admin123</code>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = '👁';
            }
        }
    </script>
</body>
</html>