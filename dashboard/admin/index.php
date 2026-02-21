<?php
/**
 * Admin Dashboard Home Page
 * 
 * Main dashboard for administrators with overview widgets and quick actions.
 * Requires admin role - access denied for cashiers.
 * 
 * @package MiniCashier
 */

// Require admin role - redirects with 403 if not admin
require_once __DIR__ . '/../../includes/role_check.php';
require_role('admin');

// Include header (includes auth_check.php)
require_once __DIR__ . '/../../includes/header.php';
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
                <h1 class="h2">Admin Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="badge bg-primary me-2">
                        <i class="bi bi-shield-check me-1"></i>Admin
                    </span>
                    <span class="text-muted">
                        Welcome, <?php echo htmlspecialchars($username); ?>
                    </span>
                </div>
            </div>

            <!-- Welcome Section -->
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                <div>
                    <strong>Welcome back, <?php echo htmlspecialchars($username); ?>!</strong>
                    <p class="mb-0">You have full access to all system features including products, reports, and user management.</p>
                </div>
            </div>

            <!-- Stats Widgets -->
            <div class="row mb-4">
                <!-- Total Products -->
                <div class="col-md-4 mb-3">
                    <div class="card border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted">Total Products</h6>
                                    <h2 class="card-title mb-0">--</h2>
                                </div>
                                <i class="bi bi-box-seam fs-1 text-primary"></i>
                            </div>
                            <small class="text-muted">Coming in Phase 2</small>
                        </div>
                    </div>
                </div>

                <!-- Total Transactions -->
                <div class="col-md-4 mb-3">
                    <div class="card border-success h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted">Total Transactions</h6>
                                    <h2 class="card-title mb-0">--</h2>
                                </div>
                                <i class="bi bi-receipt fs-1 text-success"></i>
                            </div>
                            <small class="text-muted">Coming in Phase 3</small>
                        </div>
                    </div>
                </div>

                <!-- Today's Sales -->
                <div class="col-md-4 mb-3">
                    <div class="card border-warning h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted">Today's Sales</h6>
                                    <h2 class="card-title mb-0">Rp --</h2>
                                </div>
                                <i class="bi bi-cash-coin fs-1 text-warning"></i>
                            </div>
                            <small class="text-muted">Coming in Phase 3</small>
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
                                <div class="col-md-3 mb-2">
                                    <a href="/products/index.php" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-plus-lg me-1"></i>Add Product
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="/transactions/index.php" class="btn btn-outline-success w-100">
                                        <i class="bi bi-cart-plus me-1"></i>New Transaction
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="/reports/index.php" class="btn btn-outline-info w-100">
                                        <i class="bi bi-graph-up me-1"></i>View Reports
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="/profile/index.php" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-person-gear me-1"></i>Profile Settings
                                    </a>
                                </div>
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
