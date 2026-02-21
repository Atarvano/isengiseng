---
phase: 02-product-management
plan: 01
subsystem: Product Management - Database
tags: [database, schema, products, mysql]
requires: []
provides: [Products table with constraints, Database schema file, Import script]
affects: [database/products_schema.sql, products/index.php]
tech-stack:
  added: [MySQL 8.0+ CHECK constraints, DECIMAL(10,2) for money, INT UNSIGNED for stock]
  patterns: [Schema-as-code, Constraint-based validation, Index optimization]
key-files:
  created: [database/products_schema.sql, database/import_products_schema.php]
  modified: [products/index.php]
decisions:
  - "DECIMAL(10,2) for price fields (never FLOAT for money)"
  - "INT UNSIGNED for stock (prevents negative at DB level)"
  - "CHECK constraints for price >= 0 and stock >= 0 (MySQL 8.0+)"
  - "Indexes on name, category, is_active for query performance"
  - "min_stock default threshold set to 10 items"
metrics:
  duration: "10 minutes"
  completed: "2026-02-21"
---

# Phase 2 Plan 01: Database Schema Setup for Products Module Summary

**One-liner:** Products table created with DECIMAL(10,2) for price, INT UNSIGNED for stock, CHECK constraints preventing negative values, and indexes for performance.

---

## Tasks Completed

| Task | Name | Commit | Files |
|------|------|--------|-------|
| 1 | Create products table schema with constraints | 997ba88 | database/products_schema.sql, database/import_products_schema.php |
| 2 | Verify database connection and create products directory | 997ba88 | products/index.php |
| 2a | [Auto-fix] Add auth checks to products/index.php | ff63057 | products/index.php |

---

## Implementation Details

### Products Table Schema

**File:** `database/products_schema.sql`

```sql
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    cost DECIMAL(10,2) DEFAULT 0.00,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    min_stock INT UNSIGNED DEFAULT 10,
    category VARCHAR(100),
    unit VARCHAR(20) DEFAULT 'pcs',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_price_positive CHECK (price >= 0),
    CONSTRAINT chk_stock_non_negative CHECK (stock >= 0),
    INDEX idx_name (name),
    INDEX idx_category (category),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Column Data Types Rationale

| Column | Type | Why |
|--------|------|-----|
| `price` | DECIMAL(10,2) | Exact precision for money. FLOAT causes rounding errors (0.1 + 0.2 ≠ 0.3) |
| `stock` | INT UNSIGNED | UNSIGNED prevents negative values at database level. Max 4,294,967,295 |
| `is_active` | TINYINT(1) | Boolean equivalent in MySQL, more efficient than VARCHAR |
| `name` | VARCHAR(255) | Standard length covering 99% of product names |
| `sku` | VARCHAR(50) UNIQUE | Optional stock keeping unit, unique constraint prevents duplicates |

### CHECK Constraints

**MySQL 8.0+ feature** - prevents invalid data at database level:

1. **chk_price_positive**: `CHECK (price >= 0)` - Prevents negative prices
2. **chk_stock_non_negative**: `CHECK (stock >= 0)` - Prevents negative stock

These are defense-in-depth: application-level validation is still required, but CHECK constraints provide an additional safety net.

### Indexes

| Index | Column | Purpose |
|-------|--------|---------|
| `idx_name` | name | Fast product search by name |
| `idx_category` | category | Filter/group by category |
| `idx_is_active` | is_active | Quick filtering of active products |

### Import Script

**File:** `database/import_products_schema.php`

PHP script to execute schema against database:
- Reads SQL file and parses statements
- Executes each statement with error handling
- Verifies table creation
- Displays columns, constraints, and indexes

Usage: `php database/import_products_schema.php`

### Products Directory Structure

Created `products/` directory with:
- `products/index.php` - Product list page (stub for Plan 02)

### Authentication Fix (Auto-fix Rule 2)

**Issue discovered:** products/index.php was missing authentication checks, allowing any user (or no user) to access the page.

**Fix applied:** Added security includes:
```php
require_once '../includes/session_config.php';
require_once '../includes/auth_check.php';
require_once '../includes/role_check.php';
require_role('admin'); // Admin-only access
```

This ensures:
- User must be logged in (auth_check.php)
- User must have admin role (role_check.php)
- Non-admin users get HTTP 403 Forbidden

---

## Verification Results

| Criterion | Status | Verification |
|-----------|--------|--------------|
| Products table exists | ✅ | `SHOW TABLES` shows 'products' |
| Price uses DECIMAL(10,2) | ✅ | `DESCRIBE products` shows decimal(10,2) |
| Stock uses INT UNSIGNED | ✅ | `DESCRIBE products` shows int unsigned |
| CHECK constraints exist | ✅ | `information_schema.TABLE_CONSTRAINTS` shows chk_price_positive, chk_stock_non_negative |
| Indexes created | ✅ | `SHOW INDEX FROM products` shows idx_name, idx_category, idx_is_active |
| products/index.php loads | ✅ | PHP syntax check passes |
| Admin-only access enforced | ✅ | require_role('admin') added |
| Database connection works | ✅ | Import script executed successfully |

---

## Database Verification Output

```
Columns:
  - id: int unsigned NO PRI 
  - sku: varchar(50) YES UNI 
  - name: varchar(255) NO MUL 
  - price: decimal(10,2) NO  0.00
  - cost: decimal(10,2) YES  0.00
  - stock: int unsigned NO  0
  - min_stock: int unsigned YES  10
  - category: varchar(100) YES MUL 
  - unit: varchar(20) YES  pcs
  - is_active: tinyint(1) NO MUL 1
  - created_at: timestamp YES  CURRENT_TIMESTAMP
  - updated_at: timestamp YES  CURRENT_TIMESTAMP

Constraints:
  - CHECK: chk_price_positive
  - CHECK: chk_stock_non_negative

Indexes:
  - PRIMARY: id
  - sku: sku
  - idx_name: name
  - idx_category: category
  - idx_is_active: is_active
```

---

## Deviations from Plan

### Auto-fixed Issues

**1. [Rule 2 - Missing Critical Security] Added authentication checks to products/index.php**
- **Found during:** Task 2 verification
- **Issue:** products/index.php had no authentication - accessible without login
- **Fix:** Added session_config.php, auth_check.php, role_check.php includes with require_role('admin')
- **Files modified:** products/index.php
- **Commit:** ff63057

### Plan Execution Note

The database schema (Task 1) and products directory structure (Task 2) were created together in a single commit (997ba88) during initial execution. The auth fix was applied separately (ff63057) upon discovering the security gap.

---

## Key Files Created

### database/products_schema.sql (50 lines)
- Products table definition with all required columns
- CHECK constraints for data integrity
- Indexes for query performance
- utf8mb4 charset for Unicode support

### database/import_products_schema.php (94 lines)
- CLI script to import schema into MySQL
- Automatic verification of table creation
- Displays table structure, constraints, and indexes
- Error handling for each SQL statement

### products/index.php (234 lines)
- Product list page with DataTables integration
- Admin-only access control
- Flash message display for success/error
- Low stock warnings (≤10 items)
- Delete confirmation modal
- Empty state when no products exist

---

## Next Steps

1. **Plan 02:** ✅ Complete - DataTables integration for product list
2. **Plan 03:** Create product form (create.php) with server-side validation
3. **Plan 04:** Edit product form (edit.php) with pre-populated data
4. **Plan 05:** Delete handler (delete.php) with transaction safety

---

## Self-Check: PASSED

- [x] database/products_schema.sql exists with CREATE TABLE products
- [x] database/import_products_schema.php exists and runs successfully
- [x] Products table exists in database (verified via PHP script)
- [x] DECIMAL(10,2) used for price and cost columns
- [x] INT UNSIGNED used for stock and min_stock columns
- [x] CHECK constraints chk_price_positive and chk_stock_non_negative exist
- [x] Indexes idx_name, idx_category, idx_is_active created
- [x] products/index.php includes auth checks (session_config, auth_check, role_check)
- [x] products/index.php requires admin role
- [x] Commit 997ba88 exists (schema + initial page)
- [x] Commit ff63057 exists (auth fix)

---

*Summary created: 2026-02-21*
*Plan execution: Autonomous with auto-fix for missing auth checks*
*Requirements satisfied: PROD-01 (database schema), PROD-05 (products list page)*
