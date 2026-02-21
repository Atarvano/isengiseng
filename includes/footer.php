<?php
/**
 * Common footer component
 * 
 * Includes Bootstrap JS and session manager script
 * Used across all dashboard pages
 * 
 * @package MiniCashier
 */

// Get username from session
$username = $_SESSION['username'] ?? 'Guest';
?>

        </div><!-- /.main-content -->
    </div><!-- /.dashboard-wrapper -->

<!-- Footer -->
<footer class="footer mt-auto">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="text-muted">
                    <small>&copy; <?php echo date('Y'); ?> KasirKu POS. Dibuat untuk UMKM Indonesia.</small>
                </span>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="text-muted">
                    <small><i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($username); ?></small>
                </span>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Session Manager -->
<script src="/assets/js/session-manager.js"></script>

<!-- Custom Scripts -->
<?php if (isset($page_scripts)): ?>
    <?php foreach ($page_scripts as $script): ?>
        <script src="<?php echo htmlspecialchars($script); ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
