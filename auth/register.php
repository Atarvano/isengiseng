<?php
/**
 * Register Page - KasirKu
 * SMK Certification Quality
 * 
 * Modern 50:50 split layout with illustration and registration form.
 */

// Include required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session_config.php';

// Initialize variables
$error = '';
$success = '';
$username = '';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Mohon lengkapi semua field';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } elseif ($password !== $confirm_password) {
        $error = 'Password tidak cocok';
    } else {
        // Check if username exists using prepared statement
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Username sudah digunakan. Silakan pilih username lain.';
        } else {
            // Hash password with BCrypt using password_hash()
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user with default role 'cashier'
            $insert = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, 'cashier')");
            mysqli_stmt_bind_param($insert, "ss", $username, $password_hash);
            
            if (mysqli_stmt_execute($insert)) {
                // Redirect to login with success message
                header('Location: login.php?registered=1');
                exit;
            } else {
                $error = 'Terjadi kesalahan. Silakan coba lagi.';
            }
        }
        
        mysqli_stmt_close($stmt);
    }
}

// Close database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Gratis - KasirKu</title>
    
    <!-- Google Fonts: Plus Jakarta Sans + Satoshi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Satoshi:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.8 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Styles -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="auth-split-container">
        <!-- Left Panel: Illustration -->
        <div class="auth-illustration-panel">
            <a href="../index.php" class="back-to-home">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Beranda
            </a>
            
            <div class="illustration-content">
                <div class="illustration-graphic">
                    <!-- Registration/Success Illustration SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" fill="none">
                        <!-- Background Circle -->
                        <circle cx="100" cy="100" r="90" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                        
                        <!-- Document/Form -->
                        <rect x="60" y="50" width="80" height="100" rx="6" fill="rgba(255,255,255,0.15)" stroke="white" stroke-width="2"/>
                        
                        <!-- Form Lines -->
                        <rect x="70" y="65" width="60" height="8" rx="2" fill="rgba(255,255,255,0.3)"/>
                        <rect x="70" y="80" width="60" height="8" rx="2" fill="rgba(255,255,255,0.2)"/>
                        <rect x="70" y="95" width="40" height="8" rx="2" fill="rgba(255,255,255,0.2)"/>
                        
                        <!-- Checkmark Circle -->
                        <circle cx="140" cy="130" r="28" fill="#10b981" stroke="white" stroke-width="2"/>
                        <path d="M128 130 L136 138 L152 122" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        
                        <!-- User Icon -->
                        <circle cx="100" cy="75" r="12" fill="rgba(16,185,129,0.3)" stroke="#10b981" stroke-width="2"/>
                        <path d="M85 95 Q100 85 115 95" stroke="#10b981" stroke-width="2" fill="none"/>
                        
                        <!-- Success Stars -->
                        <path d="M50 40 L52 46 L58 46 L53 50 L55 56 L50 52 L45 56 L47 50 L42 46 L48 46 Z" fill="#f97316" opacity="0.8"/>
                        <path d="M160 50 L162 56 L168 56 L163 60 L165 66 L160 62 L155 66 L157 60 L152 56 L158 56 Z" fill="#f97316" opacity="0.6"/>
                        
                        <!-- Decorative Elements -->
                        <circle cx="35" cy="120" r="8" fill="rgba(16,185,129,0.3)"/>
                        <circle cx="170" cy="160" r="10" fill="rgba(249,115,22,0.2)"/>
                        <circle cx="40" cy="160" r="6" fill="rgba(20,184,166,0.25)"/>
                    </svg>
                </div>
                
                <h2 class="illustration-title">Mulai Gratis</h2>
                <p class="illustration-text">
                    Bergabunglah dengan ribuan UMKM Indonesia. 
                    Kelola bisnis lebih cerdas, tingkatkan penjualan, 
                    dan pantau pertumbuhan Anda.
                </p>
            </div>
        </div>
        
        <!-- Right Panel: Registration Form -->
        <div class="auth-form-panel">
            <div class="auth-form-container">
                <div class="auth-form-header">
                    <div class="auth-form-logo">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                            <defs>
                                <linearGradient id="formLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#d1fae5;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <rect x="8" y="8" width="48" height="48" rx="12" fill="url(#formLogoGradient)"/>
                            <path d="M20 32 L28 40 L44 24" stroke="#10b981" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        </svg>
                    </div>
                    <h1 class="auth-form-title">Buat Akun Gratis</h1>
                    <p class="auth-form-subtitle">Mulai kelola toko Anda dalam 30 detik</p>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="alert-modern alert-error">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="registerForm" class="auth-form" novalidate>
                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="bi bi-person"></i>
                            Username
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               placeholder="Pilih username"
                               value="<?php echo htmlspecialchars($username); ?>"
                               required
                               minlength="3"
                               autocomplete="username">
                        <div class="invalid-feedback">Username minimal 3 karakter</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i>
                            Password
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="Minimal 6 karakter"
                               required
                               minlength="6"
                               autocomplete="new-password">
                        <div class="invalid-feedback">Password minimal 6 karakter</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">
                            <i class="bi bi-lock-fill"></i>
                            Konfirmasi Password
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="confirm_password" 
                               name="confirm_password" 
                               placeholder="Ulangi password"
                               required
                               minlength="6"
                               autocomplete="new-password">
                        <div class="invalid-feedback">Password harus cocok</div>
                    </div>
                    
                    <div class="benefits-list">
                        <div class="benefit-item-modern">
                            <i class="bi bi-check-circle-fill" style="color: var(--emerald-500);"></i>
                            <span>Gratis selamanya, tanpa biaya tersembunyi</span>
                        </div>
                        <div class="benefit-item-modern">
                            <i class="bi bi-check-circle-fill" style="color: var(--emerald-500);"></i>
                            <span>Transaksi unlimited, tanpa batasan</span>
                        </div>
                        <div class="benefit-item-modern">
                            <i class="bi bi-check-circle-fill" style="color: var(--emerald-500);"></i>
                            <span>Role-based: Admin & Kasir</span>
                        </div>
                    </div>
                    
                    <div class="form-footer-modern">
                        <button type="submit" class="btn-submit" id="submitBtn" disabled>
                            <i class="bi bi-person-plus"></i>
                            Buat Akun Gratis
                        </button>
                    </div>
                </form>
                
                <div class="auth-divider">
                    <span>atau</span>
                </div>
                
                <div class="auth-form-footer">
                    <p>Sudah punya akun? <a href="login.php" class="auth-link">Masuk disini</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5.3.8 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auth Validation Script -->
    <script>
        // Simple form validation
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        function validateForm() {
            const usernameValid = usernameInput.value.length >= 3;
            const passwordValid = passwordInput.value.length >= 6;
            const confirmPasswordValid = confirmPasswordInput.value.length >= 6 && 
                                        passwordInput.value === confirmPasswordInput.value;
            
            if (usernameValid && passwordValid && confirmPasswordValid) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
        
        usernameInput.addEventListener('input', validateForm);
        passwordInput.addEventListener('input', validateForm);
        confirmPasswordInput.addEventListener('input', validateForm);
    </script>
</body>
</html>
