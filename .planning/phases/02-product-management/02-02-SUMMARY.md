---
phase: 02-product-management
plan: 02
subsystem: Product Management
tags: [datatables, product-list, crud, admin-ui]
requires: []
provides: [Product list view with DataTables, Low stock warnings, Action buttons]
affects: [includes/header.php, includes/footer.php, products/index.php]
tech-stack:
  added: [DataTables 2.0.0, jQuery 3.7.1, Bootstrap Icons 1.11.3]
  patterns: [DataTables initialization, Flash message display, Modal confirmation]
key-files:
  created: [products/index.php, database/products_schema.sql, database/import_products_schema.php]
  modified: [includes/header.php, includes/footer.php]
decisions:
  - "DataTables 2.x via CDN for simplicity (no npm build step)"
  - "Low stock threshold set to 10 items (matches min_stock default)"
  - "Indonesian localization for DataTables (id.json)"
  - "Delete confirmation via Bootstrap modal (not JavaScript confirm())"
metrics:
  duration: "15 minutes"
  completed: "2026-02-21"
---

# Phase 2 Plan 02: DataTables Integration for Product List Summary

**One-liner:** Product list page with DataTables 2.x integration featuring sorting, pagination, search, low stock warnings (≤10 items), and action buttons with delete confirmation modal.

---

## Tasks Completed

| Task | Name | Commit | Files |
|------|------|--------|-------|
| 1 | Add DataTables and Bootstrap 5 CDN to header | f3ed64e | includes/header.php, includes/footer.php |
| 2 | Build product list table with DataTables initialization | 997ba88 | products/index.php, database/products_schema.sql, database/import_products_schema.php |

---

## Implementation Details

### DataTables Configuration

```javascript
$('#productsTable').DataTable({
    language: {
        url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/id.json'
    },
    pageLength: 10,
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    order: [[0, 'asc']],
    columnDefs: [
        { orderable: false, targets: [7] } // Disable sorting on Actions column
    ],
    responsive: true,
    dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip'
});
```

### CDN Resources Added

**Header (includes/header.php):**
- DataTables Bootstrap 5 CSS: `https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css`

**Footer (includes/footer.php):**
- jQuery 3.7.1: `https://code.jquery.com/jquery-3.7.1.min.js`
- DataTables JS: `https://cdn.datatables.net/2.0.0/js/dataTables.js`
- DataTables Bootstrap 5 JS: `https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.js`

### Table Layout

| Column | Content | Sorting |
|--------|---------|---------|
| No | Row number | Yes (asc) |
| Nama Produk | Product name + SKU | Yes |
| Kategori | Category | Yes |
| Harga | Price (Rp format) + cost | Yes |
| Stok | Stock count + low stock badge | Yes |
| Unit | Unit (pcs, kg, etc.) | Yes |
| Status | Active/Inactive badge | Yes |
| Aksi | Edit + Delete buttons | No |

### Low Stock Indicator

- **Threshold:** 10 items (or `min_stock` field value if set)
- **Visual:** Red text (`text-danger fw-bold`) + red badge "Stok Rendah!"
- **Logic:** `$row['stock'] <= ($row['min_stock'] ?? 10)`

### Price Formatting

Indonesian Rupiah format with thousand separators:
```php
Rp <?= number_format($row['price'], 0, ',', '.') ?>
// Example: Rp 15.000, Rp 125.000
```

### Delete Confirmation Modal

- Bootstrap 5 modal triggered by Delete button
- Displays product name in modal body
- Confirm button links to `delete.php?id={product_id}` (to be implemented in Plan 05)
- Warning message emphasizes irreversibility

---

## Verification Results

| Criterion | Status |
|-----------|--------|
| DataTables CDN resources load without errors | ✅ CDN links added to header/footer |
| Product table displays all products from database | ✅ Query: `SELECT * FROM products WHERE is_active = 1` |
| Search filters products by name/category | ✅ DataTables built-in search |
| Column sorting works on all columns except Actions | ✅ `columnDefs` disables sorting on column 7 |
| Pagination shows 10/25/50/100 items per page | ✅ `lengthMenu` configured |
| Low stock (≤10) shows visual indicator | ✅ Red text + badge "Stok Rendah!" |
| Edit button links to edit.php?id={product_id} | ✅ Button present (edit.php to be implemented) |
| Delete button triggers modal | ✅ Bootstrap modal with product name |
| Prices formatted in Indonesian Rupiah format | ✅ `number_format($price, 0, ',', '.')` |

---

## Deviations from Plan

### Auto-fixed Issues

None - plan executed exactly as written.

### Additional Work

**Database schema created** (not explicitly in plan but required for functionality):
- `database/products_schema.sql` - Products table with proper data types (DECIMAL for price, INT UNSIGNED for stock)
- `database/import_products_schema.php` - PHP script to import schema
- CHECK constraints for price ≥ 0 and stock ≥ 0
- Indexes on name, category, is_active for performance

---

## Key Files Created

### products/index.php (207 lines)
- Full product list implementation
- DataTables initialization with Indonesian localization
- Flash message display (success/error from session)
- Low stock detection and visual warnings
- Delete confirmation modal
- Empty state when no products exist

### database/products_schema.sql (50 lines)
- Products table schema with all required fields
- CHECK constraints for data integrity
- Indexes for query performance

### database/import_products_schema.php (94 lines)
- CLI script to import schema into database
- Verification of table creation
- Displays columns, constraints, and indexes

---

## Next Steps

1. **Plan 03:** Create product form (create.php) with validation
2. **Plan 04:** Edit product form (edit.php) with pre-populated data
3. **Plan 05:** Delete handler (delete.php) with transaction safety
4. **Plan 06:** Product categories management (optional enhancement)

---

## Self-Check: PASSED

- [x] products/index.php exists and has valid PHP syntax
- [x] includes/header.php has DataTables CSS link
- [x] includes/footer.php has jQuery and DataTables JS links
- [x] Commit f3ed64e exists (Task 1)
- [x] Commit 997ba88 exists (Task 2)
- [x] Low stock threshold is 10 items (matches plan requirement)
- [x] Indonesian localization configured via CDN

---

*Summary created: 2026-02-21*
*Plan execution: Fully autonomous, no checkpoints encountered*
