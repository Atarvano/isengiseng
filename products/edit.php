<?php
/**
 * Edit Product Form
 * Mini Cashier Application - Product Management
 * 
 * Allows admin to modify existing products with server-side validation.
 * Pre-populates form with current product data.
 * Shows all validation errors together after submit (not inline).
 * Preserves old input on validation failure.
 * 
 * Access: Admin only
 */

// Session configuration and auth checks
require_once '../includes/session_config.php';
require_once '../includes/auth_check.php';
require_once '../includes/role_check.php';
require_role('admin'); // Admin-only access for product management

require_once '../config/database.php';

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID produk tidak valid";
    header('Location: index.php');
    exit;
}

// Get and sanitize product ID
$id = intval($_GET['id']);

// Fetch product data using prepared statement
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Check if product exists
if (!$product) {
    $_SESSION['error'] = "Produk tidak ditemukan";
    header('Location: index.php');
    exit;
}

// Get errors from session (set by actions.php on validation failure)
$errors = $_SESSION['errors'] ?? [];

// Clear session errors after reading
unset($_SESSION['errors']);
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 style="font-family: var(--font-display); font-weight: 700;">
                    <i class="bi bi-pencil me-2"></i>Edit Produk
                </h2>
                <p class="text-muted mb-0">Ubah informasi produk <strong><?= htmlspecialchars($product['name']) ?></strong></p>
            </div>
            
            <!-- Edit Product Form Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Formulir Edit Produk</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Display Validation Errors (ALL errors together) -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Validasi Gagal</h6>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Product Form -->
                    <form action="actions.php" method="POST" novalidate>
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        
                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?= !empty($errors) && empty($product['name']) ? 'is-invalid' : '' ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?= htmlspecialchars($product['name']) ?>"
                                   placeholder="Contoh: Kopi Susu Gula Aren"
                                   required
                                   autofocus>
                            <div class="invalid-feedback">
                                Nama produk wajib diisi
                            </div>
                            <div class="form-text">Masukkan nama produk yang jelas dan deskriptif</div>
                        </div>
                        
                        <!-- Price and Stock Row -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">
                                    Harga Jual (Rp) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control <?= !empty($errors) && $product['price'] < 0 ? 'is-invalid' : '' ?>" 
                                           id="price" 
                                           name="price" 
                                           value="<?= htmlspecialchars($product['price']) ?>"
                                           min="0" 
                                           step="0.01"
                                           required>
                                </div>
                                <div class="invalid-feedback">
                                    Harga tidak boleh negatif
                                </div>
                                <div class="form-text">Harga jual per satuan produk</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">
                                    Stok <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control <?= !empty($errors) && $product['stock'] < 0 ? 'is-invalid' : '' ?>" 
                                       id="stock" 
                                       name="stock" 
                                       value="<?= htmlspecialchars($product['stock']) ?>"
                                       min="0"
                                       required>
                                <div class="invalid-feedback">
                                    Stok tidak boleh negatif
                                </div>
                                <div class="form-text">Stok saat ini: <strong><?= $product['stock'] ?> <?= htmlspecialchars($product['unit']) ?></strong></div>
                            </div>
                        </div>
                        
                        <!-- Category and Unit Row -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">
                                    Kategori
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="category" 
                                       name="category" 
                                       value="<?= htmlspecialchars($product['category'] ?? '') ?>"
                                       placeholder="Contoh: Minuman, Makanan, Elektronik">
                                <div class="form-text">Kategori produk (opsional)</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="unit" class="form-label">
                                    Satuan
                                </label>
                                <select class="form-select" id="unit" name="unit">
                                    <option value="pcs" <?= $product['unit'] === 'pcs' ? 'selected' : '' ?>>Pcs (Pieces)</option>
                                    <option value="box" <?= $product['unit'] === 'box' ? 'selected' : '' ?>>Box (Dus)</option>
                                    <option value="kg" <?= $product['unit'] === 'kg' ? 'selected' : '' ?>>Kg (Kilogram)</option>
                                    <option value="liter" <?= $product['unit'] === 'liter' ? 'selected' : '' ?>>Liter</option>
                                    <option value="pack" <?= $product['unit'] === 'pack' ? 'selected' : '' ?>>Pack (Kemasan)</option>
                                </select>
                                <div class="form-text">Satuan pengukuran produk</div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Update Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="alert alert-info mt-4 mb-0" role="alert">
                <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
                <ul class="mb-0 small">
                    <li>Tanda <span class="text-danger">*</span> menandakan field wajib diisi</li>
                    <li>Perubahan akan langsung tersimpan setelah klik "Update Produk"</li>
                    <li>Tanggal update akan tercatat otomatis di sistem</li>
                    <li>Klik "Batal" untuk kembali ke daftar produk tanpa menyimpan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
