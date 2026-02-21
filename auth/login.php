<?php
/**
 * Login Page - KasirKu
 * SMK Certification Quality
 * 
 * Modern 50:50 split layout with illustration and login form.
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
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Mohon isi username dan password';
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, "SELECT id, username, password, role FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            // Verify password using password_verify()
            if (password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /dashboard/admin/');
                } else {
                    header('Location: /dashboard/cashier/');
                }
                exit;
            } else {
                $error = 'Username atau password salah';
            }
        } else {
            $error = 'Username atau password salah';
        }
        
        mysqli_stmt_close($stmt);
    }
}

// Check for success messages from registration
if (isset($_GET['registered'])) {
    $success = 'Akun berhasil dibuat! Silakan login dengan kredensial Anda.';
}

// Close database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Akun - KasirKu</title>
    
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
                    <!-- POS System Illustration SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" fill="none">
                        <!-- Background Circle -->
                        <circle cx="100" cy="100" r="90" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                        
                        <!-- Computer Monitor -->
                        <rect x="50" y="60" width="100" height="70" rx="4" fill="rgba(255,255,255,0.15)" stroke="white" stroke-width="2"/>
                        <rect x="55" y="65" width="90" height="55" rx="2" fill="rgba(255,255,255,0.1)"/>
                        
                        <!-- Screen Content - Simple Chart -->
                        <polyline points="65,105 80,95 95,100 110,85 125,90" stroke="#10b981" stroke-width="2" fill="none"/>
                        <circle cx="80" cy="95" r="3" fill="#10b981"/>
                        <circle cx="110" cy="85" r="3" fill="#10b981"/>
                        
                        <!-- Monitor Stand -->
                        <rect x="90" y="130" width="20" height="15" fill="rgba(255,255,255,0.2)"/>
                        <rect x="75" y="145" width="50" height="6" rx="2" fill="rgba(255,255,255,0.25)"/>
                        
                        <!-- Shopping Cart -->
                        <path d="M140 120 L150 120 L155 140 L145 140 Z" fill="rgba(249,115,22,0.6)" stroke="#fb923c" stroke-width="1.5"/>
                        <circle cx="135" cy="130" r="8" fill="rgba(249,115,22,0.3)" stroke="#fb923c" stroke-width="1.5"/>
                        <circle cx="155" cy="130" r="8" fill="rgba(249,115,22,0.3)" stroke="#fb923c" stroke-width="1.5"/>
                        
                        <!-- Checkmark Badge -->
                        <circle cx="160" cy="80" r="18" fill="#10b981" stroke="white" stroke-width="2"/>
                        <path d="M152 80 L158 86 L168 74" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        
                        <!-- Decorative Elements -->
                        <circle cx="40" cy="50" r="8" fill="rgba(16,185,129,0.3)"/>
                        <circle cx="170" cy="160" r="12" fill="rgba(249,115,22,0.2)"/>
                        <circle cx="30" cy="150" r="6" fill="rgba(20,184,166,0.25)"/>
                    </svg>
                </div>
                
                <h2 class="illustration-title">Selamat Datang Kembali</h2>
                <p class="illustration-text">
                    Kelola bisnis Anda lebih efisien dengan sistem kasir modern. 
                    Mulai hari ini dengan transaksi yang lebih cepat dan laporan yang akurat.
                </p>
            </div>
        </div>
        
        <!-- Right Panel: Login Form -->
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
                    <h1 class="auth-form-title">Masuk ke Akun</h1>
                    <p class="auth-form-subtitle">Masukkan kredensial Anda untuk mengakses dashboard</p>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="alert-modern alert-error">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert-modern alert-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="loginForm" class="auth-form" novalidate>
                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="bi bi-person"></i>
                            Username
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               placeholder="Masukkan username"
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
                               placeholder="Masukkan password"
                               required
                               minlength="6"
                               autocomplete="current-password">
                        <div class="invalid-feedback">Password minimal 6 karakter</div>
                    </div>
                    
                    <div class="form-footer-modern">
                        <button type="submit" class="btn-submit" id="submitBtn" disabled>
                            <i class="bi bi-box-arrow-in-right"></i>
                            Masuk ke Dashboard
                        </button>
                    </div>
                </form>
                
                <div class="auth-divider">
                    <span>atau</span>
                </div>
                
                <div class="auth-form-footer">
                    <p>Belum punya akun? <a href="register.php" class="auth-link">Daftar gratis</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5.3.8 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auth Validation Script -->
    <script>
        // Simple form validation
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        
        function validateForm() {
            const usernameValid = usernameInput.value.length >= 3;
            const passwordValid = passwordInput.value.length >= 6;
            
            if (usernameValid && passwordValid) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
        
        usernameInput.addEventListener('input', validateForm);
        passwordInput.addEventListener('input', validateForm);
    </script>
</body>
</html>
