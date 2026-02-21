<?php
/**
 * Login Page - KasirKu
 * 
 * Modern authentication with secure credential validation.
 * Professional design for Indonesian POS system.
 * 
 * @link config/database.php Database connection
 * @link includes/session_config.php Session initialization
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
    <title>Masuk - KasirKu</title>
    
    <!-- Google Fonts: DM Sans (Display) + Lexend (Body) - Modern Indonesian Tech -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;500;600;700&family=Lexend:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.8 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="auth-page-container">
        <!-- Decorative Elements -->
        <div class="auth-decoration">
            <div class="decoration-circle decoration-1"></div>
            <div class="decoration-circle decoration-2"></div>
            <div class="decoration-circle decoration-3"></div>
        </div>
        
        <!-- Login Card -->
        <div class="auth-card-modern">
            <div class="auth-card-header">
                <div class="brand-logo-small mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="logo-icon-small">
                        <defs>
                            <linearGradient id="logoGradientSmall" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#059669;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <rect x="8" y="8" width="48" height="48" rx="12" fill="url(#logoGradientSmall)"/>
                        <path d="M20 32 L28 40 L44 24" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
                <h1 class="auth-title">Selamat Datang Kembali</h1>
                <p class="auth-subtitle">Masuk untuk mengelola toko Anda</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert-modern alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert-modern alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="loginForm" class="auth-form" novalidate>
                <div class="form-group-modern">
                    <label for="username" class="form-label-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Username
                    </label>
                    <input type="text" 
                           class="form-control-modern" 
                           id="username" 
                           name="username" 
                           placeholder="Masukkan username"
                           value="<?php echo htmlspecialchars($username); ?>"
                           required
                           minlength="3"
                           autocomplete="username">
                    <div class="invalid-feedback-modern">Username minimal 3 karakter</div>
                </div>
                
                <div class="form-group-modern">
                    <label for="password" class="form-label-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Password
                    </label>
                    <input type="password" 
                           class="form-control-modern" 
                           id="password" 
                           name="password" 
                           placeholder="Masukkan password"
                           required
                           minlength="6"
                           autocomplete="current-password">
                    <div class="invalid-feedback-modern">Password minimal 6 karakter</div>
                </div>
                
                <div class="form-footer-modern">
                    <button type="submit" class="btn-primary-modern" id="submitBtn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        Masuk ke Dashboard
                    </button>
                </div>
            </form>
            
            <div class="auth-divider">
                <span>atau</span>
            </div>
            
            <div class="auth-footer-modern">
                <p>Belum punya akun? <a href="register.php" class="auth-link">Daftar gratis</a></p>
            </div>
        </div>
        
        <!-- Back to Home -->
        <a href="../index.php" class="back-to-home">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Kembali ke Beranda
        </a>
    </div>
    
    <!-- Bootstrap 5.3.8 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Auth Validation Script -->
    <script src="../assets/js/auth-validation.js"></script>
    <script>
        // Initialize validation for login form
        document.addEventListener('DOMContentLoaded', function() {
            initAuthValidation('loginForm', 'submitBtn');
        });
    </script>
</body>
</html>
