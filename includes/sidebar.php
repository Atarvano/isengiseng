<?php
/**
 * Role-filtered sidebar navigation component
 * 
 * Displays navigation menu items based on user role:
 * - Admin: sees all menu items (Dashboard, Transactions, Products, Reports, Users, Profile)
 * - Cashier: sees only transaction-related items (Dashboard, Transactions, Profile)
 * 
 * @package MiniCashier
 */

// Get user role from session (set during login)
$role = $_SESSION['user_role'] ?? 'cashier';
$username = $_SESSION['username'] ?? 'User';

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<!-- Sidebar -->
<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse show">
    <div class="position-sticky pt-3">
        <!-- Sidebar Header -->
        <div class="sidebar-header px-3 pb-3 mb-3 border-bottom border-secondary">
            <h5 class="text-white mb-1">
                <i class="bi bi-cart4 me-2"></i>Mini Cashier
            </h5>
            <span class="badge <?php echo $role === 'admin' ? 'bg-primary' : 'bg-success'; ?>">
                <?php echo ucfirst($role); ?>
            </span>
        </div>

        <!-- Navigation Menu -->
        <ul class="nav flex-column">
            <!-- Dashboard - All Roles -->
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>" 
                   href="/dashboard/<?php echo $role; ?>/index.php">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>

            <!-- Transactions - All Roles -->
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'index' && strpos($_SERVER['PHP_SELF'], 'transactions') !== false ? 'active' : ''; ?>" 
                   href="/transactions/index.php">
                    <i class="bi bi-receipt me-2"></i>
                    Transactions
                </a>
            </li>

            <!-- Products - Admin Only -->
            <?php if ($role === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'index' && strpos($_SERVER['PHP_SELF'], 'products') !== false ? 'active' : ''; ?>" 
                   href="/products/index.php">
                    <i class="bi bi-box-seam me-2"></i>
                    Products
                </a>
            </li>
            <?php endif; ?>

            <!-- Reports - Admin Only -->
            <?php if ($role === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'index' && strpos($_SERVER['PHP_SELF'], 'reports') !== false ? 'active' : ''; ?>" 
                   href="/reports/index.php">
                    <i class="bi bi-graph-up me-2"></i>
                    Reports
                </a>
            </li>
            <?php endif; ?>

            <!-- Users - Admin Only (Phase 5 feature - placeholder) -->
            <?php if ($role === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" title="Coming in Phase 5">
                    <i class="bi bi-people me-2"></i>
                    Users <small class="text-muted">(Coming Soon)</small>
                </a>
            </li>
            <?php endif; ?>

            <!-- Profile - All Roles -->
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'index' && strpos($_SERVER['PHP_SELF'], 'profile') !== false ? 'active' : ''; ?>" 
                   href="/profile/index.php">
                    <i class="bi bi-person-circle me-2"></i>
                    Profile
                </a>
            </li>
        </ul>
    </div>
</nav>
