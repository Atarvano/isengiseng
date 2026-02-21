<?php
/**
 * Cashier Dashboard Home Page
 * 
 * Main dashboard for cashiers with transaction-focused workflow.
 * Accessible by all authenticated users (admin can access too).
 * 
 * @package MiniCashier
 */

// Require authentication (any logged-in user can access)
require_once __DIR__ . '/../../includes/auth_check.php';

// Include header (includes auth_check.php)
require_once __DIR__ . '/../../includes/header.php';

// Get user info from session
$role = $_SESSION['user_role'] ?? 'cashier';
$username = $_SESSION['username'] ?? 'User';
?>

<!-- Main content area -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require_once __DIR__ . '/../../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Page Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Cashier Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="badge bg-success me-2">
                        <i class="bi bi-person-check me-1"></i><?php echo ucfirst($role); ?>
                    </span>
                    <span class="text-muted">
                        Welcome, <?php echo htmlspecialchars($username); ?>
                    </span>
                </div>
            </div>

            <!-- Welcome Section -->
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>
                    <strong>Welcome back, <?php echo htmlspecialchars($username); ?>!</strong>
                    <p class="mb-0">Ready to process transactions? Use the quick action button below to start a new sale.</p>
                </div>
            </div>

            <!-- Stats Widgets -->
            <div class="row mb-4">
                <!-- My Transactions Today -->
                <div class="col-md-6 mb-3">
                    <div class="card border-success h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted">My Transactions Today</h6>
                                    <h2 class="card-title mb-0">--</h2>
                                </div>
                                <i class="bi bi-receipt fs-1 text-success"></i>
                            </div>
                            <small class="text-muted">Track your daily sales count</small>
                        </div>
                    </div>
                </div>

                <!-- Today's Total -->
                <div class="col-md-6 mb-3">
                    <div class="card border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted">Today's Total Sales</h6>
                                    <h2 class="card-title mb-0">Rp --</h2>
                                </div>
                                <i class="bi bi-cash-stack fs-1 text-primary"></i>
                            </div>
                            <small class="text-muted">Your total sales for today</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-lightning-charge me-2"></i>Quick Actions
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <a href="/transactions/new.php" class="btn btn-success w-100 btn-lg">
                                        <i class="bi bi-cart-plus me-2"></i>New Transaction
                                    </a>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <a href="/transactions/index.php" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-list-ul me-1"></i>View Transactions
                                    </a>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <a href="/profile/index.php" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-person-gear me-1"></i>Profile Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Placeholder -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-clock-history me-2"></i>Recent Transactions
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Date/Time</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                No transactions yet. Start by creating a new transaction!
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/../../includes/footer.php';
?>
