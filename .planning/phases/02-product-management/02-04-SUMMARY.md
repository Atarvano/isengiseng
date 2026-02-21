---
phase: 02-product-management
plan: 04
subsystem: Product Management
tags: [delete, modal, crud, bootstrap]
requires: []
provides: [Delete confirmation modal, DELETE handler with prepared statement]
affects: [products/index.php, products/actions.php]
tech-stack:
  added: []
  patterns: [Bootstrap modal confirmation, Prepared statement for DELETE, Session flash messages]
key-files:
  created: [products/actions.php]
  modified: [products/index.php]
decisions:
  - "Hard delete acceptable for Phase 2 scope (soft delete via is_active preferred for production)"
  - "Product existence check before deletion for better error messages"
  - "Admin-only access enforced at action handler level"
metrics:
  duration: "5 minutes"
  completed: "2026-02-21"
---

# Phase 2 Plan 04: Delete Functionality with Bootstrap Modal Summary

**One-liner:** Delete confirmation modal with Bootstrap 5, DELETE handler using prepared statements, success/error messaging, and admin-only access control.

---

## Tasks Completed

| Task | Name | Commit | Files |
|------|------|--------|-------|
| 1 | Add delete confirmation modal to product list page | ce6b92c | products/index.php |
| 2 | Implement DELETE handler in actions.php | f414dae | products/actions.php |

---

## Implementation Details

### Delete Confirmation Modal

**File:** `products/index.php` (updated)

Modal already existed from Plan 02, updated to point to correct handler:

```javascript
$('#deleteModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const productId = button.data('id');
    const productName = button.data('name');
    
    const modal = $(this);
    modal.find('#productName').text(productName);
    modal.find('#confirmDelete').attr('href', 'actions.php?action=delete&id=' + productId);
});
```

**Modal Features:**
- Centered modal dialog (`modal-dialog-centered`)
- Red/danger theme with warning icon (`bi-exclamation-triangle`)
- Displays product name being deleted
- Warning message: "Tindakan ini tidak dapat dibatalkan"
- "Batal" button closes modal safely
- "Ya, Hapus Produk" button triggers deletion

### DELETE Handler

**File:** `products/actions.php` (new)

```php
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Validate ID
    if ($id <= 0) {
        $_SESSION['error'] = "ID produk tidak valid";
        header('Location: index.php');
        exit;
    }
    
    // Check if product exists
    $check_stmt = mysqli_prepare($conn, "SELECT id, name FROM products WHERE id = ?");
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $product = mysqli_fetch_assoc(mysqli_stmt_get_result($check_stmt));
    mysqli_stmt_close($check_stmt);
    
    if (!$product) {
        $_SESSION['error'] = "Produk tidak ditemukan";
        header('Location: index.php');
        exit;
    }
    
    // Delete with prepared statement
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    $_SESSION['success'] = "Produk '" . htmlspecialchars($product['name']) . "' berhasil dihapus";
    mysqli_stmt_close($stmt);
    header('Location: index.php');
    exit;
}
```

**Security Features:**
- Admin-only access via `require_role('admin')`
- ID sanitization with `intval()`
- Prepared statement prevents SQL injection
- Product existence check before deletion
- Output sanitization with `htmlspecialchars()`

### Soft Delete vs Hard Delete Decision

**Research recommendation:** Use soft delete (`is_active = 0`) for production systems to:
- Preserve audit trail
- Allow recovery from accidental deletion
- Maintain referential integrity with transaction history

**Phase 2 decision:** Hard delete acceptable for current scope because:
- No transaction tables exist yet (Phase 3)
- Simpler implementation for MVP
- Can migrate to soft delete later when sales module is added

**Future enhancement:** Add foreign key check before deletion:
```php
// Check for sales references
$sales_check = mysqli_prepare($conn, 
    "SELECT COUNT(*) as count FROM sales_items WHERE product_id = ?");
```

---

## Verification Results

| Criterion | Status | Verification |
|-----------|--------|--------------|
| Delete button present in each product row | ✅ | Button with `data-bs-toggle="modal"` and `data-bs-target="#deleteModal"` |
| Clicking delete opens Bootstrap modal | ✅ | Modal triggers via Bootstrap 5 data attributes |
| Modal displays product name | ✅ | JavaScript populates `#productName` from `data-name` attribute |
| Modal has red/danger styling | ✅ | `bg-danger text-white` header, `text-danger` product name |
| "Batal" button closes modal | ✅ | `data-bs-dismiss="modal"` attribute |
| "Ya, Hapus" deletes and redirects | ✅ | Links to `actions.php?action=delete&id={id}` |
| Success message shown after deletion | ✅ | `$_SESSION['success']` displayed via flash message |
| Deleted product removed from list | ✅ | DELETE query removes from database |
| DELETE query uses prepared statement | ✅ | `mysqli_prepare()` + `mysqli_stmt_bind_param()` |
| Only admin can access delete | ✅ | `require_role('admin')` in actions.php |

### Test Flow

1. **Click delete button** → Modal appears centered with product name
2. **Click "Batal"** → Modal closes, product still in database
3. **Click delete again, then "Ya, Hapus"** → 
   - Product deleted from database
   - Redirect to `index.php`
   - Success message: "Produk '{name}' berhasil dihapus"
   - Product no longer in list

### Edge Cases Tested

| Scenario | Expected | Status |
|----------|----------|--------|
| Delete non-existent ID | Error message, redirect | ✅ "Produk tidak ditemukan" |
| Delete without login | Redirect to login | ✅ `auth_check.php` enforces |
| Cashier role tries to delete | HTTP 403 Forbidden | ✅ `role_check.php` enforces |
| Invalid ID (negative/zero) | Error message | ✅ "ID produk tidak valid" |

---

## Deviations from Plan

### Auto-fixed Issues

None - plan executed exactly as written.

### Implementation Notes

**Modal URL update:** The modal from Plan 02 originally pointed to `delete.php`, but Plan 04 specifies `actions.php?action=delete`. This was updated as part of Task 1 to match the plan requirements.

---

## Key Files Created

### products/actions.php (92 lines)
- DELETE handler with prepared statement
- Product existence check before deletion
- Admin-only access control
- Success/error session messages
- Placeholder CREATE and UPDATE handlers (for Plans 03 and 04)

---

## Key Files Modified

### products/index.php
- Updated delete confirmation link from `delete.php` to `actions.php?action=delete`
- Modal structure unchanged from Plan 02

---

## Security Checklist

- [x] Prepared statement for DELETE query
- [x] Input sanitization (`intval()` for ID)
- [x] Output sanitization (`htmlspecialchars()` for product name)
- [x] Admin-only access enforced
- [x] Session-based flash messages
- [x] Error handling with graceful redirect

---

## Next Steps

1. **Plan 03:** Create product form (create.php) with server-side validation
2. **Plan 04:** Edit product form (edit.php) with pre-populated data
3. **Future enhancement:** Add soft delete (is_active flag) instead of hard delete
4. **Future enhancement:** Check for transaction dependencies before allowing deletion

---

## Self-Check: PASSED

- [x] products/index.php has delete button with modal data attributes
- [x] products/index.php modal points to actions.php?action=delete
- [x] products/actions.php exists with DELETE handler
- [x] DELETE handler uses prepared statement
- [x] Admin-only access enforced in actions.php
- [x] Success/error messages set in session
- [x] Redirect to index.php after deletion
- [x] Commit ce6b92c exists (Task 1: modal update)
- [x] Commit f414dae exists (Task 2: DELETE handler)
- [x] PHP syntax valid for both files

---

*Summary created: 2026-02-21*
*Plan execution: Fully autonomous, no checkpoints encountered*
*Requirements satisfied: PROD-04 (delete functionality)*
