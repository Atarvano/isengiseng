<?php
/**
 * User Profile Page
 * 
 * Displays user information with role badge and provides access to
 * change password and logout functionality.
 * Accessible by all authenticated users.
 * 
 * @package MiniCashier
 */

// Require authentication
require_once __DIR__ . '/../includes/auth_check.php';

// Include header
require_once __DIR__ . '/../includes/header.php';

// Get user info from session
$role = $_SESSION['user_role'] ?? 'cashier';
$username = $_SESSION['username'] ?? 'User';
$created_at = $_SESSION['created_at'] ?? 'Unknown';
?>

<!-- Main content area -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Page Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">My Profile</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="badge <?php echo $role === 'admin' ? 'bg-primary' : 'bg-success'; ?> me-2">
                        <i class="bi bi-shield-<?php echo $role === 'admin' ? 'check' : 'plus'; ?> me-1"></i>
                        <?php echo ucfirst($role); ?>
                    </span>
                </div>
            </div>

            <!-- Profile Card -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="bi bi-person-circle me-2"></i>Account Information
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <i class="bi bi-person fs-1 text-primary"></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($username); ?></h5>
                                    <p class="text-muted mb-0">Username</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <i class="bi bi-shield-<?php echo $role === 'admin' ? 'check' : 'plus'; ?> fs-4"></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="mb-1">
                                        <?php echo ucfirst($role); ?>
                                        <span class="badge <?php echo $role === 'admin' ? 'bg-primary' : 'bg-success'; ?> ms-2">
                                            <?php echo ucfirst($role); ?>
                                        </span>
                                    </h5>
                                    <p class="text-muted mb-0">Role</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <i class="bi bi-calendar-event fs-4"></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($created_at); ?></h5>
                                    <p class="text-muted mb-0">Member Since</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="bi bi-shield-lock me-2"></i>Security
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2 d-md-block">
                                <a href="/auth/change_password.php" class="btn btn-primary">
                                    <i class="bi bi-key me-2"></i>Change Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logout Card -->
                <div class="col-md-4">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                When you're done using the system, make sure to logout to protect your account.
                            </p>
                            <form action="/auth/logout.php" method="POST" class="d-grid">
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <i class="bi bi-question-circle me-2"></i>Need Help?
                        </div>
                        <div class="card-body">
                            <p class="card-text small">
                                Contact your administrator if you need assistance with your account or have questions about system features.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
