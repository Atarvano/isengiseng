<?php
/**
 * Role-filtered sidebar navigation component
 * SMK Certification Quality
 * 
 * Displays navigation menu items based on user role:
 * - Admin: sees all menu items (Dashboard, Transactions, Products, Reports, Users, Profile)
 * - Cashier: sees only transaction-related items (Dashboard, Transactions, Profile)
 */

// Get user role from session (set during login)
$role = $_SESSION['user_role'] ?? 'cashier';
$username = $_SESSION['username'] ?? 'User';
$initials = strtoupper(substr($username, 0, 2));

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <!-- Sidebar Brand -->
        <div class="sidebar-brand">
            <div class="sidebar-logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="28" height="28">
                    <defs>
                        <linearGradient id="sidebarLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#d1fae5;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <rect x="8" y="8" width="48" height="48" rx="12" fill="url(#sidebarLogoGradient)"/>
                    <path d="M20 32 L28 40 L44 24" stroke="#10b981" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
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
                <a class="nav-link disabled" href="#" title="Akan datang di Fase 5">
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
    
    <!-- Sidebar Footer -->
    <div style="padding: var(--space-lg); margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1);">
        <form action="/auth/logout.php" method="POST">
            <button type="submit" class="nav-link" style="background: none; border: none; color: rgba(255,255,255,0.75); width: 100%; text-align: left; cursor: pointer;">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</nav>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()" style="display: none;"></div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.toggle('show');
    
    if (sidebar.classList.contains('show')) {
        overlay.style.display = 'block';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.right = '0';
        overlay.style.bottom = '0';
        overlay.style.background = 'rgba(0,0,0,0.5)';
        overlay.style.zIndex = '99';
    } else {
        overlay.style.display = 'none';
    }
}
</script>
