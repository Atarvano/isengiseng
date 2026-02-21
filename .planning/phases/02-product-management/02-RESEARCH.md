# Product Management CRUD Research

**Project:** Mini Cashier Application (UMKM POS System)  
**Research Date:** February 21, 2026  
**Confidence Level:** HIGH  

---

## Executive Summary

This research covers standard CRUD patterns for PHP native (procedural) with MySQLi prepared statements and Bootstrap 5 for the product management module. The patterns identified are industry-standard, security-focused, and optimized for Indonesian UMKM use cases.

**Key Findings:**
- **Prepared statements are mandatory** for SQL injection prevention (not optional)
- **DECIMAL(10,2) for price** fields, **INT UNSIGNED** for stock
- **CHECK constraints** available in MySQL 8.0+ for preventing negative stock
- **DataTables** is the de-facto standard for admin tables with sorting/searching/pagination
- **Bootstrap 5 modals** for delete confirmation (not JavaScript `confirm()`)
- **Server-side validation** with error array pattern for displaying all errors at once

---

## 1. Standard Stack Recommendations

### Core Technologies

| Technology | Version | Purpose | Why |
|------------|---------|---------|-----|
| PHP | 8.0+ | Backend logic | Procedural style matches project requirements, mysqli native support |
| MySQL | 8.0+ | Database | CHECK constraints support, improved performance |
| Bootstrap | 5.3.x | UI Framework | Modern, no jQuery dependency, built-in modal components |
| DataTables | 2.x | Table enhancement | Sorting, pagination, search out-of-the-box |
| jQuery | 3.7.x | DataTables dependency | Required by DataTables (Bootstrap 5 version) |

### Installation (CDN approach recommended for UMKM simplicity)

```html
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css">

<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.js"></script>
```

---

## 2. Database Schema Best Practices

### Product Table Schema

```sql
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Product Identification
    sku VARCHAR(50) UNIQUE,                      -- Stock Keeping Unit (optional)
    name VARCHAR(255) NOT NULL,                  -- Product name
    
    -- Pricing (ALWAYS use DECIMAL for money, NEVER FLOAT)
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,   -- Selling price
    cost DECIMAL(10,2) DEFAULT 0.00,             -- Cost price (for profit calculation)
    
    -- Inventory
    stock INT UNSIGNED NOT NULL DEFAULT 0,       -- Current stock (UNSIGNED prevents negative)
    min_stock INT UNSIGNED DEFAULT 0,            -- Minimum stock alert threshold
    
    -- Categorization
    category VARCHAR(100),                       -- Product category
    unit VARCHAR(20) DEFAULT 'pcs',              -- Unit: pcs, kg, liter, etc.
    
    -- Status
    is_active TINYINT(1) NOT NULL DEFAULT 1,     -- 1=active, 0=inactive/archived
    
    -- Audit Trail (REQUIRED for production systems)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,                              -- User ID who created
    updated_by INT                               -- User ID who last updated
    
    -- MySQL 8.0+ CHECK Constraints
    , CONSTRAINT chk_price_positive CHECK (price >= 0)
    , CONSTRAINT chk_stock_non_negative CHECK (stock >= 0)
    , CONSTRAINT chk_cost_non_negative CHECK (cost >= 0)
    
    -- Indexes for performance
    , INDEX idx_name (name)
    , INDEX idx_category (category)
    , INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Why These Data Types?

| Field | Type | Rationale |
|-------|------|-----------|
| `price` | `DECIMAL(10,2)` | Exact precision for money. Supports up to 99,999,999.99. FLOAT/DOUBLE cause rounding errors. |
| `stock` | `INT UNSIGNED` | UNSIGNED prevents negative values at DB level. Max 4,294,967,295. |
| `is_active` | `TINYINT(1)` | Boolean equivalent in MySQL. More efficient than VARCHAR. |
| `name` | `VARCHAR(255)` | Standard length. Covers 99% of product names. |
| `sku` | `VARCHAR(50)` | UNIQUE constraint prevents duplicates. Optional for simple UMKM. |

### Alternative for MySQL < 8.0 (No CHECK Constraints)

```sql
-- Without CHECK constraints, use TRIGGER
DELIMITER $$
CREATE TRIGGER before_product_insert
BEFORE INSERT ON products
FOR EACH ROW
BEGIN
    IF NEW.stock < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Stock cannot be negative';
    END IF;
    IF NEW.price < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Price cannot be negative';
    END IF;
END$$
DELIMITER ;
```

---

## 3. CRUD Architecture Patterns

### File Structure (Separation of Concerns)

```
project/
├── config/
│   └── database.php          # Database connection
├── includes/
│   ├── header.php            # HTML head, navbar
│   ├── footer.php            # Footer, scripts
│   └── functions.php         # Helper functions
├── products/
│   ├── index.php             # List all products (READ)
│   ├── create.php            # Add product form (CREATE)
│   ├── edit.php              # Edit product form (UPDATE)
│   ├── delete.php            # Delete handler (DELETE)
│   └── actions.php           # All CRUD actions (alternative pattern)
└── assets/
    ├── css/
    └── js/
```

### Pattern A: Separate Action Handler (Recommended)

**config/database.php**
```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mini_cashier');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to prevent encoding issues
mysqli_set_charset($conn, 'utf8mb4');
?>
```

**products/actions.php** (All CRUD operations in one file)
```php
<?php
session_start();
require_once '../config/database.php';

// CREATE: Add new product
if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category = trim($_POST['category']);
    $unit = trim($_POST['unit']);
    
    // Server-side validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Nama produk wajib diisi";
    }
    
    if ($price < 0) {
        $errors[] = "Harga tidak boleh negatif";
    }
    
    if ($stock < 0) {
        $errors[] = "Stok tidak boleh negatif";
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header('Location: create.php');
        exit;
    }
    
    // Prepared statement for INSERT
    $sql = "INSERT INTO products (name, price, stock, category, unit) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sdisss", $name, $price, $stock, $category, $unit);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Produk berhasil ditambahkan";
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['error'] = "Gagal menambahkan produk: " . mysqli_stmt_error($stmt);
        header('Location: create.php');
        exit;
    }
    
    mysqli_stmt_close($stmt);
}

// UPDATE: Edit product
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category = trim($_POST['category']);
    $unit = trim($_POST['unit']);
    
    // Validation
    $errors = [];
    if (empty($name)) $errors[] = "Nama produk wajib diisi";
    if ($price < 0) $errors[] = "Harga tidak boleh negatif";
    if ($stock < 0) $errors[] = "Stok tidak boleh negatif";
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header('Location: edit.php?id=' . $id);
        exit;
    }
    
    $sql = "UPDATE products SET name=?, price=?, stock=?, category=?, unit=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sdisii", $name, $price, $stock, $category, $unit, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Produk berhasil diupdate";
        header('Location: index.php');
        exit;
    }
    
    mysqli_stmt_close($stmt);
}

// DELETE: Remove product
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "DELETE FROM products WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Produk berhasil dihapus";
    } else {
        $_SESSION['error'] = "Gagal menghapus produk";
    }
    
    mysqli_stmt_close($stmt);
    header('Location: index.php');
    exit;
}
?>
```

---

## 4. Admin UI Patterns

### Product List with DataTables (index.php)

```php
<?php
session_start();
require_once '../config/database.php';

// Fetch products
$sql = "SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Success/Error Alerts -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-box-seam"></i> Daftar Produk</h2>
                <a href="create.php" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah Produk
                </a>
            </div>
            
            <!-- Product Table -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="productsTable" class="table table-hover table-striped">
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
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result)): 
                                    $stock_class = $row['stock'] <= $row['min_stock'] ? 'text-danger fw-bold' : 'text-success';
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['category'] ?? '-') ?></td>
                                    <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                                    <td class="<?= $stock_class ?>">
                                        <?= $row['stock'] ?>
                                        <?php if ($row['stock'] <= $row['min_stock']): ?>
                                            <span class="badge bg-danger">Stok Habis!</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['unit']) ?></td>
                                    <td>
                                        <span class="badge <?= $row['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $row['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-name="<?= htmlspecialchars($row['name']) ?>">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk:</p>
                <h4 class="text-danger" id="productName"></h4>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Ya, Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Initialization -->
<script>
$(document).ready(function() {
    // Initialize DataTable with Indonesian localization
    $('#productsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/id.json'
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [7] } // Disable sorting on Actions column
        ]
    });
    
    // Handle delete modal
    $('#deleteModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const productId = button.data('id');
        const productName = button.data('name');
        
        const modal = $(this);
        modal.find('#productName').text(productName);
        modal.find('#confirmDelete').attr('href', 'actions.php?action=delete&id=' + productId);
    });
});
</script>

<?php include '../includes/footer.php'; ?>
```

### Create Form with Validation (create.php)

```php
<?php
session_start();
require_once '../config/database.php';

// Get old input and errors from session
$oldInput = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['errors']);
?>

<?php include '../includes/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-plus-lg"></i> Tambah Produk Baru</h4>
                </div>
                <div class="card-body">
                    <!-- Display All Errors -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="actions.php" method="POST">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= !empty($errors) ? 'is-invalid' : '' ?>" 
                                   id="name" name="name" 
                                   value="<?= htmlspecialchars($oldInput['name'] ?? '') ?>"
                                   placeholder="Contoh: Kopi Susu Gula Aren" required>
                            <div class="invalid-feedback">
                                Nama produk wajib diisi
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control <?= !empty($errors) ? 'is-invalid' : '' ?>" 
                                       id="price" name="price" 
                                       value="<?= htmlspecialchars($oldInput['price'] ?? '0') ?>"
                                       min="0" step="0.01" required>
                                <div class="invalid-feedback">
                                    Harga tidak boleh negatif
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control <?= !empty($errors) ? 'is-invalid' : '' ?>" 
                                       id="stock" name="stock" 
                                       value="<?= htmlspecialchars($oldInput['stock'] ?? '0') ?>"
                                       min="0" required>
                                <div class="invalid-feedback">
                                    Stok tidak boleh negatif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <input type="text" class="form-control" 
                                       id="category" name="category" 
                                       value="<?= htmlspecialchars($oldInput['category'] ?? '') ?>"
                                       placeholder="Contoh: Minuman">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="unit" class="form-label">Satuan</label>
                                <select class="form-select" id="unit" name="unit">
                                    <option value="pcs" <?= ($oldInput['unit'] ?? '') === 'pcs' ? 'selected' : '' ?>>Pcs</option>
                                    <option value="box" <?= ($oldInput['unit'] ?? '') === 'box' ? 'selected' : '' ?>>Box</option>
                                    <option value="kg" <?= ($oldInput['unit'] ?? '') === 'kg' ? 'selected' : '' ?>>Kg</option>
                                    <option value="liter" <?= ($oldInput['unit'] ?? '') === 'liter' ? 'selected' : '' ?>>Liter</option>
                                    <option value="pack" <?= ($oldInput['unit'] ?? '') === 'pack' ? 'selected' : '' ?>>Pack</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
```

---

## 5. Stock Validation Patterns

### Pattern 1: Application-Level Validation (Required)

```php
// In actions.php before INSERT/UPDATE
$stock = intval($_POST['stock']);

if ($stock < 0) {
    $errors[] = "Stok tidak boleh negatif";
}

// Additional business logic: check if stock reduction is valid
if ($action === 'reduce_stock') {
    $reduce_amount = intval($_POST['reduce_amount']);
    
    // First, get current stock
    $stmt = mysqli_prepare($conn, "SELECT stock FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    
    if ($product['stock'] < $reduce_amount) {
        $errors[] = "Stok tidak mencukupi. Stok saat ini: " . $product['stock'];
    }
}
```

### Pattern 2: Database-Level Constraint (Defense in Depth)

```sql
-- MySQL 8.0+ CHECK constraint
ALTER TABLE products 
ADD CONSTRAINT chk_stock_non_negative CHECK (stock >= 0);

-- Also add trigger for extra safety (optional but recommended)
DELIMITER $$
CREATE TRIGGER prevent_negative_stock_update
BEFORE UPDATE ON products
FOR EACH ROW
BEGIN
    IF NEW.stock < 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Stock cannot be negative';
    END IF;
END$$
DELIMITER ;
```

### Pattern 3: Transaction-Based Stock Reduction (For Sales)

```php
// When processing a sale that reduces stock
mysqli_begin_transaction($conn);

try {
    // 1. Check stock availability with FOR UPDATE (row locking)
    $stmt = mysqli_prepare($conn, "SELECT stock FROM products WHERE id = ? FOR UPDATE");
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    
    if ($product['stock'] < $quantity_to_sell) {
        throw new Exception("Stok tidak mencukupi");
    }
    
    // 2. Reduce stock
    $stmt = mysqli_prepare($conn, "UPDATE products SET stock = stock - ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $quantity_to_sell, $product_id);
    mysqli_stmt_execute($stmt);
    
    // 3. Record sale transaction
    $stmt = mysqli_prepare($conn, "INSERT INTO sales (...) VALUES (...)");
    // ... bind and execute
    
    // 4. Commit transaction
    mysqli_commit($conn);
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['error'] = $e->getMessage();
    header('Location: pos.php');
    exit;
}
```

---

## 6. Common Pitfalls to Avoid

### Critical Pitfalls

| Pitfall | What Goes Wrong | Prevention |
|---------|-----------------|------------|
| **No prepared statements** | SQL injection vulnerability | ALWAYS use `mysqli_prepare()` + `bind_param()` |
| **FLOAT for price** | Rounding errors (0.1 + 0.2 ≠ 0.3) | Use `DECIMAL(10,2)` for all money fields |
| **No server-side validation** | Invalid data in database | Validate on server even if client-side exists |
| **Direct DELETE on click** | Accidental data loss | Use Bootstrap modal for confirmation |
| **Negative stock allowed** | Inventory becomes incorrect | CHECK constraint + application validation |
| **No error display** | Users don't know what went wrong | Show ALL errors in alert box on submit |
| **No input sanitization on output** | XSS attacks | Use `htmlspecialchars()` on ALL output |
| **No audit trail** | Can't track who changed what | Add `created_at`, `updated_at`, `created_by` |

### Moderate Pitfalls

| Pitfall | What Goes Wrong | Prevention |
|---------|-----------------|------------|
| **No pagination** | Slow with 1000+ products | DataTables handles this automatically |
| **No search** | Hard to find products | DataTables includes search out-of-box |
| **Images stored in DB** | Database bloat, slow queries | Store file path only, images in `/uploads/` |
| **No soft delete** | Can't recover accidentally deleted | Use `is_active` flag instead of DELETE |
| **No stock alerts** | Run out of stock unexpectedly | Add `min_stock` field with visual warning |

### Minor Pitfalls

| Pitfall | What Goes Wrong | Prevention |
|---------|-----------------|------------|
| **No unit field** | Confusion between pcs/kg/box | Add `unit` VARCHAR field |
| **No category** | Can't filter/group products | Add `category` field with index |
| **No SKU** | Hard to track variants | Add optional `sku` UNIQUE field |

---

## 7. Complete Code Snippets

### Helper Functions (includes/functions.php)

```php
<?php
/**
 * Sanitize output for HTML display
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Format currency (Indonesian Rupiah)
 */
function format_rupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Redirect with flash message
 */
function redirect_with_message($url, $type, $message) {
    session_start();
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
    header('Location: ' . $url);
    exit;
}

/**
 * Display flash message (call in view)
 */
function display_flash_message() {
    session_start();
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'] ?? 'info';
        $message = $_SESSION['flash_message'];
        $alert_class = [
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'warning' => 'alert-warning',
            'info' => 'alert-info'
        ][$type] ?? 'alert-info';
        
        echo '<div class="alert ' . $alert_class . ' alert-dismissible fade show" role="alert">';
        echo e($message);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
        
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    }
}

/**
 * Validate stock is non-negative
 */
function validate_stock($stock) {
    if (!is_numeric($stock)) {
        return "Stok harus berupa angka";
    }
    if ($stock < 0) {
        return "Stok tidak boleh negatif";
    }
    if ($stock > 2147483647) {
        return "Stok terlalu besar";
    }
    return true;
}

/**
 * Validate price is positive
 */
function validate_price($price) {
    if (!is_numeric($price)) {
        return "Harga harus berupa angka";
    }
    if ($price < 0) {
        return "Harga tidak boleh negatif";
    }
    return true;
}
?>
```

### Edit Form (edit.php)

```php
<?php
session_start();
require_once '../config/database.php';

// Check if ID provided
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);

// Fetch product data
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    $_SESSION['error'] = "Produk tidak ditemukan";
    header('Location: index.php');
    exit;
}

mysqli_stmt_close($stmt);

// Get errors from session
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>

<?php include '../includes/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Produk</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= e($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="actions.php" method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= e($product['name']) ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       value="<?= $product['price'] ?>" min="0" step="0.01" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="stock" name="stock" 
                                       value="<?= $product['stock'] ?>" min="0" required>
                                <div class="form-text">Stok saat ini: <strong><?= $product['stock'] ?></strong></div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="category" name="category" 
                                       value="<?= e($product['category']) ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="unit" class="form-label">Satuan</label>
                                <select class="form-select" id="unit" name="unit">
                                    <option value="pcs" <?= $product['unit'] === 'pcs' ? 'selected' : '' ?>>Pcs</option>
                                    <option value="box" <?= $product['unit'] === 'box' ? 'selected' : '' ?>>Box</option>
                                    <option value="kg" <?= $product['unit'] === 'kg' ? 'selected' : '' ?>>Kg</option>
                                    <option value="liter" <?= $product['unit'] === 'liter' ? 'selected' : '' ?>>Liter</option>
                                    <option value="pack" <?= $product['unit'] === 'pack' ? 'selected' : '' ?>>Pack</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-warning">Update Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
```

---

## 8. Security Checklist

Before deploying to production:

- [ ] All SQL queries use prepared statements
- [ ] All output uses `htmlspecialchars()`
- [ ] File uploads validate MIME type and extension
- [ ] Session regeneration on login
- [ ] CSRF tokens on all forms
- [ ] Input validation on server-side (not just client)
- [ ] Error messages don't leak sensitive info
- [ ] Database user has minimal required privileges
- [ ] `display_errors = Off` in production
- [ ] HTTPS enabled for all traffic

---

## Sources

| Source | Confidence | Notes |
|--------|------------|-------|
| Tutorial Republic - Prepared Statements | HIGH | Official PHP documentation patterns |
| DataTables.net - Bootstrap 5 Examples | HIGH | Official DataTables documentation |
| MySQL 8.0 Reference Manual | HIGH | Official MySQL documentation |
| DCodeMania CRUD Tutorial (2025) | MEDIUM | Recent practical example |
| DEV Community - Database Schema Design | MEDIUM | Community best practices |
| Stack Overflow - Negative Stock Prevention | MEDIUM | Community solutions |

---

## Roadmap Implications for Phase 2

**Recommended Implementation Order:**

1. **PROD-01: Database Setup** - Create products table with constraints
2. **PROD-02: List Products** - DataTables integration with sorting/search
3. **PROD-03: Add Product** - Form with server-side validation
4. **PROD-04: Edit Product** - Pre-populated form with validation
5. **PROD-05: Delete Product** - Bootstrap modal confirmation

**Research Flags:**
- Phase 2 is standard CRUD - unlikely to need deeper research
- Stock validation during sales (Phase 3: Transactions) will need transaction handling research
- Image upload (if added later) will need file security research
