# Architecture Patterns

**Domain:** PHP Native POS (Point of Sale) System
**Researched:** 2026-02-21

## Recommended Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        PRESENTATION LAYER                        │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐ │
│  │   Admin     │  │   Cashier   │  │    Public Pages         │ │
│  │   Module    │  │   Module    │  │  (Login, Landing)       │ │
│  │  /admin/    │  │ /cashier/   │  │    /public/             │ │
│  └─────────────┘  └─────────────┘  └─────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                        CONTROLLER LAYER                          │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Page Controllers (auth.php, products.php, pos.php)     │   │
│  │  - Handle HTTP requests                                  │   │
│  │  - Session validation                                    │   │
│  │  - Input validation                                      │   │
│  │  - Call model functions                                  │   │
│  │  - Load views                                            │   │
│  └─────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                          MODEL LAYER                             │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Model Functions (models/*.php)                         │   │
│  │  - Database queries (CRUD)                              │   │
│  │  - Business logic                                       │   │
│  │  - Data validation                                      │   │
│  └─────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                       DATABASE LAYER                             │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  MySQL Database                                         │   │
│  │  - users, barang, transaksi, detail_transaksi           │   │
│  └─────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

### Component Boundaries

| Component | Responsibility | Communicates With |
|-----------|---------------|-------------------|
| **Public Pages** | Landing page, login, register | Controller layer, session |
| **Admin Module** | User management, product CRUD, reports | Controller layer, database |
| **Cashier Module** | POS interface, transaction processing | Controller layer, database |
| **Controllers** | Request handling, validation, routing | Models, views, session |
| **Models** | Database operations, business logic | Database layer |
| **Views** | HTML/Bootstrap UI, Chart.js rendering | Browser, controller data |
| **Database** | Data persistence | Models only |

### Data Flow

**Authentication Flow:**
```
User Input → login.php → validate_credentials() → users table 
→ session_create() → redirect to role dashboard
```

**Transaction Flow:**
```
Cashier selects products → pos.php → calculate_total() 
→ begin_transaction() → insert transaksi → insert detail_transaksi 
→ update barang.stock → commit → generate receipt
```

**Product Management Flow:**
```
Admin form → products.php → validate_input() 
→ insert/update barang table → redirect to product list
```

**Reporting Flow:**
```
Dashboard request → dashboard.php → query sales aggregates 
→ return JSON → Chart.js renders visualization
```

## Patterns to Follow

### Pattern 1: Page Controller Pattern
**What:** Each page (login.php, pos.php, products.php) handles its own request lifecycle

**When:** Building PHP native applications without frameworks

**Example:**
```php
<?php
// pos.php
session_start();
require_once 'config/database.php';
require_once 'models/transaction.php';

// Validate cashier role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'cashier') {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $items = $_POST['items'];
    $total = $_POST['total'];
    
    // Process transaction
    $result = create_transaction($items, $total, $_SESSION['user_id']);
    
    if ($result['success']) {
        header('Location: receipt.php?id=' . $result['transaction_id']);
        exit;
    }
}

// Load products for selection
$products = get_all_products();
include 'views/pos_view.php';
```

### Pattern 2: Separated Query Logic
**What:** Database queries extracted into model functions, not inline in pages

**When:** Any database operation beyond simple SELECT

**Example:**
```php
// models/transaction.php
function create_transaction($items, $total, $user_id) {
    global $conn;
    
    mysqli_begin_transaction($conn);
    
    try {
        // Insert main transaction
        $stmt = mysqli_prepare($conn, 
            "INSERT INTO transaksi (total, user_id, tanggal) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "di", $total, $user_id);
        mysqli_stmt_execute($stmt);
        $transaction_id = mysqli_insert_id($conn);
        
        // Insert transaction details and update stock
        foreach ($items as $item) {
            $stmt = mysqli_prepare($conn,
                "INSERT INTO detail_transaksi (transaksi_id, barang_id, jumlah, subtotal) 
                 VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iiid", $transaction_id, $item['id'], $item['qty'], $item['subtotal']);
            mysqli_stmt_execute($stmt);
            
            // Reduce stock
            $stmt = mysqli_prepare($conn,
                "UPDATE barang SET stok = stok - ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ii", $item['qty'], $item['id']);
            mysqli_stmt_execute($stmt);
        }
        
        mysqli_commit($conn);
        return ['success' => true, 'transaction_id' => $transaction_id];
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
```

### Pattern 3: Role-Based Access Control
**What:** Session stores user role, each page validates access before execution

**When:** Multi-user systems with different permission levels

**Example:**
```php
// config/auth.php
function require_role($allowed_roles) {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        header('Location: dashboard.php');
        exit;
    }
}

// Usage in admin pages
require_once 'config/auth.php';
require_role(['admin']); // Only admin can access
```

### Pattern 4: Transaction Safety
**What:** Use MySQL transactions for operations that must succeed or fail together

**When:** Stock reduction + transaction recording, any multi-table write

**Example:**
```php
mysqli_begin_transaction($conn);
try {
    // Multiple operations
    mysqli_query($conn, $query1);
    mysqli_query($conn, $query2);
    mysqli_query($conn, $query3);
    
    mysqli_commit($conn);
} catch (Exception $e) {
    mysqli_rollback($conn);
    // Handle error
}
```

## Anti-Patterns to Avoid

### Anti-Pattern 1: Spaghetti Code in Views
**What:** Mixing database queries, business logic, and HTML in the same file

**Why bad:** Impossible to maintain, test, or reuse code

**Instead:**
```php
// ❌ BAD: All logic in view
<?php
$conn = mysqli_connect(...);
$query = "SELECT * FROM barang WHERE stok > 0";
$result = mysqli_query($conn, $query);
?>
<html>
<?php while($row = mysqli_fetch_assoc($result)): ?>
    <div><?= $row['nama_barang'] ?></div>
<?php endwhile; ?>
</html>

// ✅ GOOD: Separated concerns
<?php
// products.php (controller)
require_once 'models/product.php';
$products = get_available_products();
include 'views/products_view.php';
?>

<?php
// models/product.php
function get_available_products() {
    // Database logic here
}

// views/products_view.php
<html>
<?php foreach ($products as $product): ?>
    <div><?= htmlspecialchars($product['nama_barang']) ?></div>
<?php endforeach; ?>
</html>
```

### Anti-Pattern 2: No Input Validation
**What:** Trusting user input without sanitization or validation

**Why bad:** SQL injection, XSS attacks, data corruption

**Instead:**
```php
// ❌ BAD: Direct use of $_POST
$price = $_POST['harga'];
$query = "INSERT INTO barang SET harga = $price";

// ✅ GOOD: Validate and use prepared statements
$price = filter_input(INPUT_POST, 'harga', FILTER_VALIDATE_FLOAT);
if ($price === false || $price < 0) {
    $errors[] = 'Harga harus angka positif';
}

$stmt = mysqli_prepare($conn, "INSERT INTO barang (harga) VALUES (?)");
mysqli_stmt_bind_param($stmt, "d", $price);
```

### Anti-Pattern 3: Inline Password Storage
**What:** Storing passwords in plain text or weak hashing

**Why bad:** Security breach exposes all user credentials

**Instead:**
```php
// ❌ BAD: Plain text password
$password = $_POST['password'];
$query = "INSERT INTO users SET password = '$password'";

// ✅ GOOD: Use password_hash()
$password = $_POST['password'];
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($conn, "INSERT INTO users (password) VALUES (?)");
mysqli_stmt_bind_param($stmt, "s", $hashed);

// Verification
if (password_verify($input_password, $stored_hash)) {
    // Login successful
}
```

### Anti-Pattern 4: No Error Handling
**What:** Letting PHP errors display to users or silently failing

**Why bad:** Exposes system details, data inconsistency

**Instead:**
```php
// ❌ BAD: No error handling
mysqli_query($conn, $query);
echo "Data saved!";

// ✅ GOOD: Proper error handling
if (mysqli_query($conn, $query)) {
    $_SESSION['success'] = 'Data saved successfully';
    header('Location: products.php');
    exit;
} else {
    error_log("Database error: " . mysqli_error($conn));
    $_SESSION['error'] = 'Failed to save data. Please try again.';
    header('Location: products.php');
    exit;
}
```

## Scalability Considerations

| Concern | At 100 transactions | At 10K transactions | At 1M transactions |
|---------|---------------------|---------------------|-------------------|
| **Database queries** | Direct queries OK | Add indexes on foreign keys | Consider read replicas |
| **Session storage** | File-based sessions | File-based OK | Move to Redis/database |
| **Report generation** | Real-time queries | Cache daily reports | Pre-aggregate tables |
| **Product catalog** | Single table query | Add category indexes | Consider product search table |
| **Concurrent users** | No locking needed | Transaction locking critical | Queue system for high contention |

## Suggested Build Order (Component Dependencies)

```
Phase 1: Foundation
├── Database schema (users, barang tables)
├── Configuration (database connection, auth helpers)
└── Authentication (login, logout, session management)

Phase 2: Core CRUD
├── Product management (barang CRUD)
└── User management (admin only)

Phase 3: Transaction Processing
├── POS interface (cashier module)
├── Transaction recording (transaksi, detail_transaksi)
└── Stock reduction logic

Phase 4: Reporting
├── Dashboard statistics
├── Chart.js integration
└── Transaction reports/printing
```

**Build order rationale:**
1. **Database first** — All components depend on schema
2. **Authentication before anything** — Role-based access required for all modules
3. **Product CRUD before POS** — Cashiers need products to sell
4. **Transactions before reports** — Need data to visualize

## Sources

- **Webslesson POS System** (2024) — https://www.webslesson.info/2024/09/php-mysql-point-of-sale-pos-system-with-full-source-code.html
- **Retail POS System GitHub** (2025) — https://github.com/ruthuvarshan/retail-pos-system
- **PointShift POS System** (2025) — https://github.com/LeeDev428/point-shift_pos-system
- **iNetTutor POS Database Design** (2020) — https://www.inettutor.com/source-code/point-of-sale-system-database-design/
- **PHP Project Structure Best Practices** (2025-2026) — Multiple sources on modern PHP architecture without frameworks

## Confidence Assessment

| Area | Confidence | Notes |
|------|------------|-------|
| Component boundaries | HIGH | Verified across multiple POS implementations |
| Data flow patterns | HIGH | Standard MVC/page controller pattern well-documented |
| Build order | HIGH | Logical dependencies clear from database schema requirements |
| Anti-patterns | HIGH | Well-established PHP security and maintainability practices |
| Scalability notes | MEDIUM | Based on general PHP/MySQL best practices, not POS-specific |
