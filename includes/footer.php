<?php
/**
 * Common footer component
 * SMK Certification Quality
 * 
 * Includes Bootstrap JS and session manager script
 */

// Get username from session
$username = $_SESSION['username'] ?? 'Guest';
?>

            </main><!-- /.main-content -->
            
            <!-- Dashboard Footer -->
        <footer class="dashboard-footer">
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
    </div><!-- /.main-content-wrapper -->
</div><!-- /.dashboard-wrapper -->

<!-- jQuery 3.7.x (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.js"></script>

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
