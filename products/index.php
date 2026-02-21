<?php
/**
 * Product List Page
 * Mini Cashier Application - Product Management
 * 
 * Displays all products in a DataTables table with sorting, pagination, and search.
 * Shows low stock warnings and action buttons for edit/delete.
 * 
 * Access: Admin only
 */

// Session configuration and auth checks
require_once '../includes/session_config.php';
require_once '../includes/auth_check.php';
require_once '../includes/role_check.php';
require_role('admin'); // Admin-only access for product management

require_once '../config/database.php';

// Fetch all active products ordered by creation date
$sql = "SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

// Handle query error
if (!$result) {
    $error_message = "Gagal mengambil data produk: " . mysqli_error($conn);
}
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Success/Error Flash Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1" style="font-family: var(--font-display); font-weight: 700;">
                        <i class="bi bi-box-seam me-2"></i>Daftar Produk
                    </h2>
                    <small class="text-muted">Kelola inventaris produk Anda</small>
                </div>
                <a href="create.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Produk
                </a>
            </div>
            
            <!-- Products Table Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="productsTable" class="table table-hover table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($error_message)): 
                                ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="alert alert-danger mb-0">
                                                <i class="bi bi-exclamation-circle me-2"></i>
                                                <?= htmlspecialchars($error_message) ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                elseif (mysqli_num_rows($result) > 0): 
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)): 
                                        // Low stock detection (threshold: 10 items)
                                        $is_low_stock = $row['stock'] <= ($row['min_stock'] ?? 10);
                                        $stock_class = $is_low_stock ? 'text-danger fw-bold' : '';
                                        $stock_badge = $is_low_stock ? '<span class="badge bg-danger ms-2">Stok Rendah!</span>' : '';
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($row['name']) ?></strong>
                                            <?php if (!empty($row['sku'])): ?>
                                                <br><small class="text-muted">SKU: <?= htmlspecialchars($row['sku']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['category'] ?? '-') ?></td>
                                        <td>
                                            <strong>Rp <?= number_format($row['price'], 0, ',', '.') ?></strong>
                                            <?php if ($row['cost'] > 0): ?>
                                                <br><small class="text-muted">Modal: Rp <?= number_format($row['cost'], 0, ',', '.') ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="<?= $stock_class ?>">
                                            <?= $row['stock'] ?>
                                            <?= $stock_badge ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['unit']) ?></td>
                                        <td>
                                            <span class="badge <?= $row['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= $row['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="edit.php?id=<?= $row['id'] ?>" 
                                                   class="btn btn-warning btn-sm"
                                                   title="Edit produk ini">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                 <button type="button" 
                                                         class="btn btn-danger btn-sm delete-btn"
                                                         data-id="<?= $row['id'] ?>"
                                                         data-name="<?= htmlspecialchars($row['name']) ?>"
                                                         title="Hapus produk ini">
                                                     <i class="bi bi-trash"></i> Hapus
                                                 </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                    endwhile;
                                else: 
                                ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="bi bi-inbox" style="font-size: 3rem; color: var(--slate-300);"></i>
                                            <h5 class="mt-3 text-muted">Belum ada produk</h5>
                                            <p class="text-muted">Mulai dengan menambahkan produk pertama Anda</p>
                                            <a href="create.php" class="btn btn-primary">
                                                <i class="bi bi-plus-lg me-1"></i>Tambah Produk
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal (outside main-content to avoid overflow:hidden) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="z-index: 1055;">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-2">Apakah Anda yakin ingin menghapus produk:</p>
                <h4 class="text-danger fw-bold" id="productName"></h4>
                <p class="text-muted small mt-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Tindakan ini tidak dapat dibatalkan. Produk akan dihapus dari sistem.
                </p>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Batal
                </button>
                <a href="#" id="confirmDelete" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>Ya, Hapus Produk
                </a>
            </div>
        </div>
    </div>
</div>

</div><!-- /.main-content -->
</main>

<?php include '../includes/footer.php'; ?>

<!-- DataTables Initialization with Indonesian Localization -->
<script>
$(document).ready(function() {
    // Initialize DataTable with Indonesian language
    $('#productsTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/id.json'
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [7] }
        ],
        responsive: true,
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
        drawCallback: function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }
    });
    
    // Handle delete button click - manually show modal
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        const productId = $(this).data('id');
        const productName = $(this).data('name');
        
        console.log('Delete button clicked:', { id: productId, name: productName });
        
        // Populate modal content
        $('#productName').text(productName);
        $('#confirmDelete').attr('href', 'actions.php?action=delete&id=' + productId);
        
        // Show modal using Bootstrap API with explicit options
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {
            backdrop: true,
            keyboard: true
        });
        
        // Force show the modal
        deleteModal.show();
        
        console.log('Modal shown:', deleteModal);
    });
});
</script>
