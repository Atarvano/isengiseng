<?php
/**
 * Register Page - KasirKu
 * 
 * User registration with secure password hashing.
 * Creates new cashier accounts with BCrypt password storage.
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
    <title>Daftar - KasirKu</title>
    
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
        
        <!-- Register Card -->
        <div class="auth-card-modern">
            <div class="auth-card-header">
                <div class="brand-logo-small mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="logo-icon-small">
                        <defs>
                            <linearGradient id="logoGradientRegister" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#059669;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <rect x="8" y="8" width="48" height="48" rx="12" fill="url(#logoGradientRegister)"/>
                        <path d="M20 32 L28 40 L44 24" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
                <h1 class="auth-title">Buat Akun Gratis</h1>
                <p class="auth-subtitle">Mulai kelola toko Anda dalam 30 detik</p>
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
            
            <form method="POST" action="" id="registerForm" class="auth-form" novalidate>
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
                           placeholder="Pilih username"
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
                           placeholder="Minimal 6 karakter"
                           required
                           minlength="6"
                           autocomplete="new-password">
                    <div class="invalid-feedback-modern">Password minimal 6 karakter</div>
                </div>
                
                <div class="form-group-modern">
                    <label for="confirm_password" class="form-label-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Konfirmasi Password
                    </label>
                    <input type="password" 
                           class="form-control-modern" 
                           id="confirm_password" 
                           name="confirm_password" 
                           placeholder="Ulangi password"
                           required
                           minlength="6"
                           autocomplete="new-password">
                    <div class="invalid-feedback-modern">Password harus cocok</div>
                </div>
                
                <div class="benefits-list-modern">
                    <div class="benefit-item-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>Gratis selamanya, tanpa biaya tersembunyi</span>
                    </div>
                    <div class="benefit-item-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>Transaksi unlimited, tanpa batasan</span>
                    </div>
                    <div class="benefit-item-modern">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>Role-based: Admin & Kasir</span>
                    </div>
                </div>
                
                <div class="form-footer-modern">
                    <button type="submit" class="btn-primary-modern" id="submitBtn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="8.5" cy="7" r="4"/>
                            <line x1="20" y1="8" x2="20" y2="14"/>
                            <line x1="23" y1="11" x2="17" y2="11"/>
                        </svg>
                        Buat Akun Gratis
                    </button>
                </div>
            </form>
            
            <div class="auth-divider">
                <span>atau</span>
            </div>
            
            <div class="auth-footer-modern">
                <p>Sudah punya akun? <a href="login.php" class="auth-link">Masuk disini</a></p>
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
        // Initialize validation for register form
        document.addEventListener('DOMContentLoaded', function() {
            initAuthValidation('registerForm', 'submitBtn');
        });
    </script>
</body>
</html>
