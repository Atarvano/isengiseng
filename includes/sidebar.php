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
<nav class="sidebar">
    <div class="sidebar-header">
        <!-- Sidebar Brand -->
        <div class="sidebar-brand">
            <div class="sidebar-logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="28" height="28">
                    <defs>
                        <linearGradient id="sidebarLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#059669;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <rect x="8" y="8" width="48" height="48" rx="12" fill="url(#sidebarLogoGradient)"/>
                    <path d="M20 32 L28 40 L44 24" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                </svg>
            </div>
            <h5 class="sidebar-title mb-0">KasirKu</h5>
        </div>
        
        <!-- Role Badge -->
        <span class="role-badge <?php echo $role; ?>">
            <i class="bi bi-<?php echo $role === 'admin' ? 'shield-check' : 'person-check'; ?>"></i>
            <?php echo ucfirst($role); ?>
        </span>
    </div>
    
    <!-- Navigation Menu -->
    <div class="sidebar-nav">
        <ul class="nav flex-column">
            <!-- Dashboard - All Roles -->
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>" 
                   href="/dashboard/<?php echo $role; ?>/index.php">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Transactions - All Roles -->
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'transactions') !== false ? 'active' : ''; ?>" 
                   href="/transactions/index.php">
                    <i class="bi bi-receipt"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            
            <!-- Products - Admin Only -->
            <?php if ($role === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'products') !== false ? 'active' : ''; ?>" 
                   href="/products/index.php">
                    <i class="bi bi-box-seam"></i>
                    <span>Produk</span>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Reports - Admin Only -->
            <?php if ($role === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'reports') !== false ? 'active' : ''; ?>" 
                   href="/reports/index.php">
                    <i class="bi bi-graph-up"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Users - Admin Only (Phase 5 - placeholder) -->
            <?php if ($role === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" title="Coming in Phase 5">
                    <i class="bi bi-people"></i>
                    <span>Pengguna <small class="text-white-50">(Segera)</small></span>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Profile - All Roles -->
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'profile') !== false ? 'active' : ''; ?>" 
                   href="/profile/index.php">
                    <i class="bi bi-person-circle"></i>
                    <span>Profil</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
