<?php
/**
 * Admin Dashboard Home Page
 * SMK Certification Quality
 * 
 * Main dashboard for administrators with overview widgets and quick actions.
 */

// Require admin role - redirects with 403 if not admin
require_once __DIR__ . '/../../includes/role_check.php';
require_role('admin');

// Include header (includes auth_check.php)
require_once __DIR__ . '/../../includes/header.php';

// Get username
$username = $_SESSION['username'] ?? 'Admin';
?>

<!-- Page Header -->
<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="dashboard-title">Dashboard Admin</h1>
            <p class="dashboard-subtitle mb-0">Kelola sistem dengan akses penuh</p>
        </div>
        <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
            <span class="badge bg-primary px-3 py-2 rounded-pill">
                <i class="bi bi-shield-check me-1"></i>Admin
            </span>
            <span class="text-muted">
                <small><?php echo htmlspecialchars($username); ?></small>
            </span>
        </div>
    </div>
</div>

<!-- Welcome Alert -->
<div class="alert alert-info d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-info-circle-fill me-3 fs-3"></i>
    <div>
        <strong>Selamat datang, <?php echo htmlspecialchars($username); ?>!</strong>
        <p class="mb-0">Anda memiliki akses penuh ke semua fitur: produk, transaksi, laporan, dan manajemen pengguna.</p>
    </div>
</div>

<!-- Stats Widgets -->
<div class="row mb-4">
    <!-- Total Products -->
    <div class="col-md-4 mb-3">
        <div class="card border-0 h-100" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%); border: 1px solid rgba(16, 185, 129, 0.2);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2 fw-normal">Total Produk</h6>
                        <h2 class="mb-0 fw-bold" style="font-family: var(--font-display);">--</h2>
                    </div>
                    <div class="icon-box" style="width: 64px; height: 64px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(20, 184, 166, 0.15) 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-box-seam fs-3" style="color: var(--emerald-600);"></i>
                    </div>
                </div>
                <small class="text-muted mt-3 d-block">Coming in Phase 2</small>
            </div>
        </div>
    </div>
    
    <!-- Total Transactions -->
    <div class="col-md-4 mb-3">
        <div class="card border-0 h-100" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(249, 115, 22, 0.05) 100%); border: 1px solid rgba(16, 185, 129, 0.2);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2 fw-normal">Total Transaksi</h6>
                        <h2 class="mb-0 fw-bold" style="font-family: var(--font-display);">--</h2>
                    </div>
                    <div class="icon-box" style="width: 64px; height: 64px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(249, 115, 22, 0.15) 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-receipt fs-3" style="color: var(--emerald-600);"></i>
                    </div>
                </div>
                <small class="text-muted mt-3 d-block">Coming in Phase 3</small>
            </div>
        </div>
    </div>
    
    <!-- Today's Sales -->
    <div class="col-md-4 mb-3">
        <div class="card border-0 h-100" style="background: linear-gradient(135deg, rgba(249, 115, 22, 0.05) 0%, rgba(245, 158, 11, 0.05) 100%); border: 1px solid rgba(249, 115, 22, 0.2);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2 fw-normal">Penjualan Hari Ini</h6>
                        <h2 class="mb-0 fw-bold" style="font-family: var(--font-display);">Rp --</h2>
                    </div>
                    <div class="icon-box" style="width: 64px; height: 64px; background: linear-gradient(135deg, rgba(249, 115, 22, 0.15) 0%, rgba(245, 158, 11, 0.15) 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-cash-coin fs-3" style="color: var(--coral-600);"></i>
                    </div>
                </div>
                <small class="text-muted mt-3 d-block">Coming in Phase 3</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 mb-4" style="box-shadow: var(--shadow-sm);">
    <div class="card-header">
        <i class="bi bi-lightning-charge me-2 text-warning"></i>Aksi Cepat
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-3">
                <a href="/products/index.php" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Produk
                </a>
            </div>
            <div class="col-md-3">
                <a href="/transactions/index.php" class="btn btn-outline-success w-100 py-3">
                    <i class="bi bi-cart-plus me-2"></i>Transaksi Baru
                </a>
            </div>
            <div class="col-md-3">
                <a href="/reports/index.php" class="btn btn-outline-info w-100 py-3">
                    <i class="bi bi-graph-up me-2"></i>Lihat Laporan
                </a>
            </div>
            <div class="col-md-3">
                <a href="/profile/index.php" class="btn btn-outline-secondary w-100 py-3">
                    <i class="bi bi-person-gear me-2"></i>Pengaturan
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/../../includes/footer.php';
?>
