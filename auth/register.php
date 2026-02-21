<?php
/**
 * Registration Page
 * 
 * Handles user registration with secure password hashing.
 * Uses password_hash() with PASSWORD_DEFAULT (BCrypt) for password storage.
 * 
 * @link config/database.php Database connection
 * @link includes/session_config.php Session initialization
 */

// Include required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session_config.php';

// Initialize variables
$error = '';
$success = false;
$username = '';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        // Check if username already exists using prepared statement
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_fetch_assoc($result)) {
            $error = 'Username already exists. Please choose a different username.';
        } else {
            // Hash password with password_hash() - uses BCrypt by default (PASSWORD_DEFAULT)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user with default role 'cashier'
            $insert_stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, 'cashier')");
            mysqli_stmt_bind_param($insert_stmt, "ss", $username, $hashed_password);
            
            if (mysqli_stmt_execute($insert_stmt)) {
                // Registration successful - redirect to login
                mysqli_stmt_close($insert_stmt);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header('Location: login.php?registered=1');
                exit;
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        
        mysqli_stmt_close($stmt);
    }
}

// Check for success message from redirect
if (isset($_GET['registered']) && $_GET['registered'] === '1') {
    $success = true;
}

// Close database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mini Cashier</title>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-person-plus text-primary" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664z"/>
                        <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                    </svg>
                    <h2 class="card-title mt-2">Create Account</h2>
                    <p class="text-muted">Register for Mini Cashier</p>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle me-2" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                        </svg>
                        Registration successful! Please log in with your credentials.
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle me-2" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                        </svg>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="registerForm" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               placeholder="Choose a username"
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
                               placeholder="Choose a password"
                               required
                               minlength="6">
                        <div class="invalid-feedback">Password must be at least 6 characters</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" id="submitBtn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus me-2" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664z"/>
                            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                        </svg>
                        Create Account
                    </button>
                </form>
                
                <div class="auth-links">
                    <p class="mb-0 text-muted">Already have an account? <a href="login.php">Sign in here</a></p>
                </div>
            </div>
        </div>
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
