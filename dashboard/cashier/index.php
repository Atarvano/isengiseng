<?php
/**
 * Cashier Dashboard Home Page
 * SMK Certification Quality
 * 
 * Main dashboard for cashiers with transaction-focused workflow.
 */

// Require authentication (any logged-in user can access)
require_once __DIR__ . '/../../includes/auth_check.php';

// Include header
require_once __DIR__ . '/../../includes/header.php';

// Get user info from session
$role = $_SESSION['user_role'] ?? 'cashier';
$username = $_SESSION['username'] ?? 'User';
?>

<!-- Page Header -->
<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="dashboard-title">Cashier Dashboard</h1>
            <p class="dashboard-subtitle mb-0">Kelola transaksi dengan cepat dan mudah</p>
        </div>
        <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
            <span class="badge bg-success px-3 py-2 rounded-pill">
                <i class="bi bi-person-check me-1"></i><?php echo ucfirst($role); ?>
            </span>
            <span class="text-muted">
                <small>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></small>
            </span>
        </div>
    </div>
</div>

<!-- Welcome Alert -->
<div class="alert alert-success d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-3 fs-3"></i>
    <div>
        <strong>Selamat datang kembali, <?php echo htmlspecialchars($username); ?>!</strong>
        <p class="mb-0">Siap untuk memproses transaksi? Gunakan tombol aksi cepat di bawah untuk memulai penjualan baru.</p>
    </div>
</div>

<!-- Stats Widgets -->
<div class="row mb-4">
    <!-- My Transactions Today -->
    <div class="col-md-6 mb-3">
        <div class="card border-0 h-100" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%); border: 1px solid rgba(16, 185, 129, 0.2);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2 fw-normal">Transaksi Saya Hari Ini</h6>
                        <h2 class="mb-0 fw-bold" style="font-family: var(--font-display);">--</h2>
                    </div>
                    <div class="icon-box" style="width: 64px; height: 64px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(20, 184, 166, 0.15) 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-receipt fs-3" style="color: var(--emerald-600);"></i>
                    </div>
                </div>
                <small class="text-muted mt-3 d-block">Track jumlah penjualan harian Anda</small>
            </div>
        </div>
    </div>
    
    <!-- Today's Total -->
    <div class="col-md-6 mb-3">
        <div class="card border-0 h-100" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(249, 115, 22, 0.05) 100%); border: 1px solid rgba(16, 185, 129, 0.2);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2 fw-normal">Total Penjualan Hari Ini</h6>
                        <h2 class="mb-0 fw-bold" style="font-family: var(--font-display);">Rp --</h2>
                    </div>
                    <div class="icon-box" style="width: 64px; height: 64px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(249, 115, 22, 0.15) 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-cash-stack fs-3" style="color: var(--emerald-600);"></i>
                    </div>
                </div>
                <small class="text-muted mt-3 d-block">Total penjualan Anda hari ini</small>
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
            <div class="col-md-4">
                <a href="/transactions/new.php" class="btn btn-success w-100 py-3 btn-lg">
                    <i class="bi bi-cart-plus me-2"></i>Transaksi Baru
                </a>
            </div>
            <div class="col-md-4">
                <a href="/transactions/index.php" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-list-ul me-2"></i>Lihat Transaksi
                </a>
            </div>
            <div class="col-md-4">
                <a href="/profile/index.php" class="btn btn-outline-secondary w-100 py-3">
                    <i class="bi bi-person-gear me-2"></i>Pengaturan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions Placeholder -->
<div class="card border-0" style="box-shadow: var(--shadow-sm);">
    <div class="card-header">
        <i class="bi bi-clock-history me-2 text-muted"></i>Transaksi Terbaru
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Tanggal/Waktu</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: var(--slate-100); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-inbox fs-2" style="color: var(--slate-400);"></i>
                            </div>
                            <p class="mb-0">Belum ada transaksi. Mulai dengan membuat transaksi baru!</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/../../includes/footer.php';
?>
