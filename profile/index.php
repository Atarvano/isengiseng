<?php
/**
 * User Profile Page
 * SMK Certification Quality
 * 
 * Displays user information with role badge and provides access to
 * change password and logout functionality.
 */

// Require authentication
require_once __DIR__ . '/../includes/auth_check.php';

// Include header
require_once __DIR__ . '/../includes/header.php';

// Get user info from session
$role = $_SESSION['user_role'] ?? 'cashier';
$username = $_SESSION['username'] ?? 'User';
$created_at = $_SESSION['created_at'] ?? 'Unknown';

// Get initials for avatar
$initials = strtoupper(substr($username, 0, 2));
?>

<!-- Page Header -->
<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="dashboard-title">Profil Saya</h1>
            <p class="dashboard-subtitle mb-0">Kelola informasi akun Anda</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge <?php echo $role === 'admin' ? 'bg-primary' : 'bg-success'; ?> px-3 py-2 rounded-pill">
                <i class="bi bi-shield-<?php echo $role === 'admin' ? 'check' : 'plus'; ?> me-1"></i>
                <?php echo ucfirst($role); ?>
            </span>
        </div>
    </div>
</div>

<!-- Profile Content -->
<div class="row">
    <!-- Profile Information -->
    <div class="col-lg-8 mb-4">
        <!-- Account Card -->
        <div class="card border-0 mb-4" style="box-shadow: var(--shadow-sm);">
            <div class="card-header">
                <i class="bi bi-person-circle me-2"></i>Informasi Akun
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                    <div class="profile-avatar me-4">
                        <?php echo $initials; ?>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="font-family: var(--font-display);"><?php echo htmlspecialchars($username); ?></h4>
                        <p class="text-muted mb-0">@<?php echo htmlspecialchars($username); ?></p>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="icon-box me-3" style="width: 48px; height: 48px; background: var(--emerald-50); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-person fs-4" style="color: var(--emerald-600);"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 fw-normal">Username</h6>
                                <p class="mb-0 fw-semibold"><?php echo htmlspecialchars($username); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="icon-box me-3" style="width: 48px; height: 48px; background: var(--coral-50); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-shield-<?php echo $role === 'admin' ? 'check' : 'plus'; ?> fs-4" style="color: var(--coral-600);"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 fw-normal">Role</h6>
                                <p class="mb-0 fw-semibold">
                                    <?php echo ucfirst($role); ?>
                                    <span class="badge <?php echo $role === 'admin' ? 'bg-primary' : 'bg-success'; ?> ms-2 rounded-pill">
                                        <?php echo ucfirst($role); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="icon-box me-3" style="width: 48px; height: 48px; background: var(--teal-50); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-calendar-event fs-4" style="color: var(--teal-600);"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 fw-normal">Member Sejak</h6>
                                <p class="mb-0 fw-semibold"><?php echo htmlspecialchars($created_at); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="icon-box me-3" style="width: 48px; height: 48px; background: var(--slate-100); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-activity fs-4" style="color: var(--slate-600);"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 fw-normal">Status</h6>
                                <p class="mb-0 fw-semibold">
                                    <span class="badge bg-success rounded-pill">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>Aktif
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Security Card -->
        <div class="card border-0" style="box-shadow: var(--shadow-sm);">
            <div class="card-header">
                <i class="bi bi-shield-lock me-2"></i>Keamanan
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h6 class="mb-1 fw-semibold">Kelola Password</h6>
                        <p class="text-muted mb-0 small">Ubah password Anda secara berkala untuk keamanan</p>
                    </div>
                    <a href="/auth/change_password.php" class="btn btn-primary">
                        <i class="bi bi-key me-2"></i>Ubah Password
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Logout & Help -->
    <div class="col-lg-4 mb-4">
        <!-- Logout Card -->
        <div class="card border-danger mb-3" style="box-shadow: var(--shadow-sm);">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </div>
            <div class="card-body">
                <p class="card-text mb-3">
                    Setelah selesai menggunakan sistem, pastikan untuk logout untuk melindungi akun Anda.
                </p>
                <form action="/auth/logout.php" method="POST" class="d-grid">
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Help Card -->
        <div class="card border-0" style="box-shadow: var(--shadow-sm); background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(16, 185, 129, 0.05) 100%); border: 1px solid rgba(6, 182, 212, 0.2);">
            <div class="card-header">
                <i class="bi bi-question-circle me-2 text-info"></i>Butuh Bantuan?
            </div>
            <div class="card-body">
                <p class="card-text small mb-3">
                    Hubungi administrator Anda jika mengalami kesulitan dengan akun atau memiliki pertanyaan tentang fitur sistem.
                </p>
                <div class="d-flex align-items-center gap-2 text-muted small">
                    <i class="bi bi-envelope"></i>
                    <span>support@kasirku.id</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
