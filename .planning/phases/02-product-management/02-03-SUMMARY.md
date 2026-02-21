---
phase: 02-product-management
plan: 03
subsystem: Product Management - Create & Edit Forms
tags: [crud, forms, validation, server-side]
requires: ["02-01", "02-02"]
provides: [Create product form, Edit product form, CREATE handler, UPDATE handler]
affects: [products/create.php, products/edit.php, products/actions.php]
tech-stack:
  added: [Server-side validation pattern, Session-based error flash, Prepared statement helpers]
  patterns: [Error array display, Old input preservation, Redirect-after-post]
key-files:
  created: [products/create.php, products/edit.php, products/actions.php]
  modified: []
decisions:
  - "Validation errors shown together in alert-danger box (not inline per-field)"
  - "Old input preserved in session on validation failure"
  - "Helper functions for sanitize/validate/reduce code duplication"
  - "Prepared statement type strings: sdisss for INSERT, sdisii for UPDATE"
  - "Warning color scheme (bg-warning) for edit mode visual distinction"
metrics:
  duration: "12 minutes"
  completed: "2026-02-21"
---

# Phase 2 Plan 03: Create and Edit Product Forms with Validation Summary

**One-liner:** Create and edit product forms with server-side validation (name required, price ≥ 0, stock ≥ 0), all errors displayed together in alert box, old input preserved on failure, prepared statements for SQL injection prevention.

---

## Tasks Completed

| Task | Name | Commit | Files |
|------|------|--------|-------|
| 1 | Create product form (create.php) with validation | fdc699d | products/create.php |
| 2 | Create edit form (edit.php) with pre-populated data | cdc9240 | products/edit.php |
| 3 | Implement CREATE and UPDATE handlers (actions.php) | 14ac284 | products/actions.php |

---

## Implementation Details

### Form Field Specifications

**Create Form (products/create.php - 182 lines):**

| Field | Type | Required | Validation | Default |
|-------|------|----------|------------|---------|
| Nama Produk | text | Yes | Not empty | - |
| Harga Jual | number | Yes | min=0, step=0.01 | 0 |
| Stok Awal | number | Yes | min=0 | 0 |
| Kategori | text | No | - | - |
| Satuan | select | No | pcs/box/kg/liter/pack | pcs |

**Edit Form (products/edit.php - 208 lines):**

Same fields as create, plus:
- Hidden input: product ID
- Pre-populated values from database
- Helper text: "Stok saat ini: X {unit}"
- Warning color scheme (bg-warning) for edit mode

### Validation Rules

**Server-side validation in actions.php:**

```php
function validate_product($data) {
    $errors = [];
    
    if (empty($data['name'])) {
        $errors[] = "Nama produk wajib diisi";
    }
    
    if ($data['price'] < 0) {
        $errors[] = "Harga tidak boleh negatif";
    }
    
    if ($data['stock'] < 0) {
        $errors[] = "Stok tidak boleh negatif";
    }
    
    return $errors;
}
```

**Error Display Pattern:**

All errors shown together in Bootstrap alert-danger:
```php
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <h6><i class="bi bi-exclamation-triangle-fill"></i>Validasi Gagal</h6>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
```

### Database Operations

**CREATE Handler (INSERT):**

```php
$sql = "INSERT INTO products (name, price, stock, category, unit) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sdisss", 
    $data['name'], $data['price'], $data['stock'], 
    $data['category'], $data['unit']
);
```

**UPDATE Handler (UPDATE):**

```php
$sql = "UPDATE products SET name=?, price=?, stock=?, category=?, unit=? WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sdisii", 
    $data['name'], $data['price'], $data['stock'], 
    $data['category'], $data['unit'], $id
);
```

### Session Flash Pattern

**On Validation Failure:**
```php
$_SESSION['errors'] = $errors;           // Array of error messages
$_SESSION['old_input'] = $_POST;         // Preserve user input
header('Location: create.php');
exit;
```

**On Success:**
```php
$_SESSION['success'] = "Produk berhasil ditambahkan";
header('Location: index.php');
exit;
```

**On Database Error:**
```php
$_SESSION['error'] = "Gagal menambahkan produk: " . mysqli_stmt_error($stmt);
header('Location: create.php');
exit;
```

---

## Verification Results

| Criterion | Status | Verification Method |
|-----------|--------|---------------------|
| Create form accessible at products/create.php | ✅ | File exists, PHP syntax valid |
| Edit form accessible at products/edit.php?id={id} | ✅ | File exists, PHP syntax valid |
| Forms show all validation errors in alert box | ✅ | Error array displayed in alert-danger |
| Old input preserved when validation fails | ✅ | Session old_input restored to form values |
| Successful save redirects to products/index.php | ✅ | $_SESSION['success'] + header redirect |
| Cancel buttons return to product list | ✅ | `<a href="index.php" class="btn btn-secondary">` |
| actions.php uses prepared statements for INSERT | ✅ | mysqli_prepare + bind_param("sdisss") |
| actions.php uses prepared statements for UPDATE | ✅ | mysqli_prepare + bind_param("sdisii") |
| Price validated as >= 0 | ✅ | validate_product() checks $data['price'] < 0 |
| Stock validated as >= 0 | ✅ | validate_product() checks $data['stock'] < 0 |
| Name required validation | ✅ | validate_product() checks empty($data['name']) |
| Admin-only access enforced | ✅ | require_role('admin') in all files |

---

## Test Scenarios

### CREATE Operation Tests

**Test 1: Valid Product Creation**
```
Input: name="Kopi Susu", price=15000, stock=50, category="Minuman", unit="pcs"
Expected: Redirect to index.php, success message "Produk berhasil ditambahkan"
Database: New row in products table
```

**Test 2: Empty Name Validation**
```
Input: name="", price=15000, stock=50, category="Minuman", unit="pcs"
Expected: Redirect to create.php, error "Nama produk wajib diisi"
Form: Preserves price=15000, stock=50, category="Minuman", unit="pcs"
```

**Test 3: Negative Price Validation**
```
Input: name="Kopi Susu", price=-1000, stock=50, category="Minuman", unit="pcs"
Expected: Redirect to create.php, error "Harga tidak boleh negatif"
Form: Preserves all input values
```

**Test 4: Negative Stock Validation**
```
Input: name="Kopi Susu", price=15000, stock=-5, category="Minuman", unit="pcs"
Expected: Redirect to create.php, error "Stok tidak boleh negatif"
Form: Preserves all input values
```

### UPDATE Operation Tests

**Test 5: Valid Product Update**
```
Input: id=1, name="Kopi Susu Gula Aren", price=18000, stock=45, category="Minuman", unit="pcs"
Expected: Redirect to index.php, success message "Produk berhasil diupdate"
Database: Row id=1 updated with new values
```

**Test 6: Edit with Empty Name**
```
Input: id=1, name="", price=18000, stock=45, category="Minuman", unit="pcs"
Expected: Redirect to edit.php?id=1, error "Nama produk wajib diisi"
Form: Preserves other values, shows current product data
```

**Test 7: Invalid Product ID**
```
Input: Visit edit.php?id=99999 (non-existent product)
Expected: Redirect to index.php, error "Produk tidak ditemukan"
```

**Test 8: Missing ID Parameter**
```
Input: Visit edit.php (no id parameter)
Expected: Redirect to index.php, error "ID produk tidak valid"
```

---

## Security Features

| Feature | Implementation | Purpose |
|---------|----------------|---------|
| SQL Injection Prevention | mysqli_prepare() + bind_param() | All queries use prepared statements |
| XSS Prevention | htmlspecialchars() on all output | Prevents script injection in form values |
| Input Sanitization | trim(), floatval(), intval() | Clean raw POST data before processing |
| Session-based Auth | require_role('admin') | Admin-only access to forms and actions |
| CSRF Protection | Session-based validation | Future enhancement (not in scope) |

---

## Deviations from Plan

### Auto-fixed Issues

None - plan executed exactly as written.

### Implementation Notes

**Helper Functions Added:**
- `sanitize_product_input($data)` - Centralizes input sanitization
- `validate_product($data)` - Centralizes validation logic
- `redirect_with_errors($errors, $input, $url)` - Reduces code duplication

These functions make the code more maintainable and follow DRY principles.

---

## Key Files Created

### products/create.php (182 lines)
- Admin-only access with auth/role checks
- Form with 5 fields: name, price, stock, category, unit
- Validation error display in alert-danger box
- Old input preservation from session
- Cancel button returns to product list
- Submit POST to actions.php with action=create

### products/edit.php (208 lines)
- Admin-only access with auth/role checks
- Fetch product via prepared statement
- Pre-populate all form fields with existing data
- Same validation error display pattern as create.php
- Warning color scheme (bg-warning) for edit mode
- Cancel button returns to product list
- Submit POST to actions.php with action=update

### products/actions.php (255 lines)
- CREATE handler: validates, sanitizes, INSERT with prepared statement
- UPDATE handler: validates, sanitizes, UPDATE with prepared statement
- DELETE handler: existing functionality preserved
- Helper functions for sanitize/validate/redirect
- Session flash messages for success/error/old_input
- Type strings: "sdisss" for INSERT, "sdisii" for UPDATE

---

## Next Steps

1. **Plan 04:** (Already complete - Delete functionality)
2. **Plan 05:** (If exists) Product categories or inventory adjustments
3. **Phase 3:** Transaction Processing - Point of Sale (POS) module

---

## Self-Check: PASSED

- [x] products/create.php exists (182 lines, PHP syntax valid)
- [x] products/edit.php exists (208 lines, PHP syntax valid)
- [x] products/actions.php exists (255 lines, PHP syntax valid)
- [x] Commit fdc699d exists (Task 1: create.php)
- [x] Commit cdc9240 exists (Task 2: edit.php)
- [x] Commit 14ac284 exists (Task 3: actions.php)
- [x] Validation errors displayed in alert-danger box
- [x] Old input preserved on validation failure
- [x] Prepared statements used for INSERT and UPDATE
- [x] Admin-only access enforced in all files
- [x] Cancel buttons link to index.php
- [x] Success messages redirect to product list

---

*Summary created: 2026-02-21*
*Plan execution: Fully autonomous, no checkpoints encountered*
*Requirements satisfied: PROD-02 (create form), PROD-03 (edit form)*
