<?php
/**
 * Create Product Form
 * Mini Cashier Application - Product Management
 * 
 * Allows admin to add new products with server-side validation.
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

// Get old input and errors from session (set by actions.php on validation failure)
$oldInput = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['errors'] ?? [];

// Clear session values after reading
unset($_SESSION['old_input'], $_SESSION['errors']);
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 style="font-family: var(--font-display); font-weight: 700;">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Produk Baru
                </h2>
                <p class="text-muted mb-0">Isi form di bawah untuk menambahkan produk ke inventaris</p>
            </div>
            
            <!-- Create Product Form Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Formulir Produk</h5>
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
                        <input type="hidden" name="action" value="create">
                        
                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?= !empty($errors) && empty($oldInput['name']) ? 'is-invalid' : '' ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?= htmlspecialchars($oldInput['name'] ?? '') ?>"
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
                                           class="form-control <?= !empty($errors) && (isset($oldInput['price']) && $oldInput['price'] < 0) ? 'is-invalid' : '' ?>" 
                                           id="price" 
                                           name="price" 
                                           value="<?= htmlspecialchars($oldInput['price'] ?? '0') ?>"
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
                                    Stok Awal <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control <?= !empty($errors) && (isset($oldInput['stock']) && $oldInput['stock'] < 0) ? 'is-invalid' : '' ?>" 
                                       id="stock" 
                                       name="stock" 
                                       value="<?= htmlspecialchars($oldInput['stock'] ?? '0') ?>"
                                       min="0"
                                       required>
                                <div class="invalid-feedback">
                                    Stok tidak boleh negatif
                                </div>
                                <div class="form-text">Jumlah stok awal produk</div>
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
                                       value="<?= htmlspecialchars($oldInput['category'] ?? '') ?>"
                                       placeholder="Contoh: Minuman, Makanan, Elektronik">
                                <div class="form-text">Kategori produk (opsional)</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="unit" class="form-label">
                                    Satuan
                                </label>
                                <select class="form-select" id="unit" name="unit">
                                    <option value="pcs" <?= ($oldInput['unit'] ?? '') === 'pcs' ? 'selected' : '' ?>>Pcs (Pieces)</option>
                                    <option value="box" <?= ($oldInput['unit'] ?? '') === 'box' ? 'selected' : '' ?>>Box (Dus)</option>
                                    <option value="kg" <?= ($oldInput['unit'] ?? '') === 'kg' ? 'selected' : '' ?>>Kg (Kilogram)</option>
                                    <option value="liter" <?= ($oldInput['unit'] ?? '') === 'liter' ? 'selected' : '' ?>>Liter</option>
                                    <option value="pack" <?= ($oldInput['unit'] ?? '') === 'pack' ? 'selected' : '' ?>>Pack (Kemasan)</option>
                                </select>
                                <div class="form-text">Satuan pengukuran produk</div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="alert alert-info mt-4 mb-0" role="alert">
                <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Panduan Pengisian</h6>
                <ul class="mb-0 small">
                    <li>Tanda <span class="text-danger">*</span> menandakan field wajib diisi</li>
                    <li>Harga dalam Rupiah (Rp) tanpa titik atau koma</li>
                    <li>Stok awal adalah jumlah produk yang tersedia saat ini</li>
                    <li>Kategori membantu pengelompokan produk untuk laporan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
