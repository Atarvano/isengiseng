<?php
/**
 * Login Page
 * 
 * Handles user authentication with secure credential validation.
 * Uses prepared statements and password_verify() for security.
 * 
 * @link config/database.php Database connection
 * @link includes/session_config.php Session initialization
 */

// Include required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session_config.php';

// Initialize variables
$error = '';
$username = '';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
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
                $error = 'Invalid username or password';
            }
        } else {
            $error = 'Invalid username or password';
        }
        
        mysqli_stmt_close($stmt);
    }
}

// Close database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mini Cashier</title>
    <!-- Bootstrap 5.3.8 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="auth-page-container">
        <div class="auth-page-card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-cart-check text-primary" viewBox="0 0 16 16">
                        <path d="M11.354 6.354a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"/>
                        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                    </svg>
                    <h2 class="card-title mt-2">Welcome Back</h2>
                    <p class="text-muted">Sign in to your account</p>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle me-2" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                        </svg>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="loginForm" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               placeholder="Enter your username"
                               value="<?php echo htmlspecialchars($username); ?>"
                               required
                               minlength="3">
                        <div class="invalid-feedback">Username must be at least 3 characters</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="Enter your password"
                               required
                               minlength="6">
                        <div class="invalid-feedback">Password must be at least 6 characters</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" id="submitBtn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right me-2" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0z"/>
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>
                        Sign In
                    </button>
                </form>
                
                <div class="auth-links">
                    <p class="mb-0 text-muted">Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
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
