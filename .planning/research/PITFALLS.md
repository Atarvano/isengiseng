# Domain Pitfalls: PHP Native POS Systems

**Domain:** Point of Sale (POS) / Cashier Applications
**Researched:** 2026-02-21
**Context:** PHP Native (procedural), MySQL, Bootstrap, Chart.js

---

## Critical Pitfalls

Mistakes that cause rewrites, data corruption, or security breaches.

### 1. Missing Database Transactions for Stock Operations

**What goes wrong:** Processing a sale without wrapping stock deduction and transaction creation in a database transaction. When two cashiers process sales simultaneously, or when a payment fails mid-process, inventory becomes inconsistent (negative stock, oversold items).

**Why it happens:** 
- Developers treat each query as independent
- No understanding of ACID properties
- "It works in testing" mentality (single-user tests don't reveal race conditions)
- PHP Native procedural code often lacks transaction awareness

**Consequences:**
- Inventory shows negative quantities
- Products sold that don't exist in stock
- Financial reports don't match actual inventory
- Requires manual database correction or complete rewrite

**Prevention:**
```php
// Use mysqli transaction for all stock operations
mysqli_begin_transaction($conn);
try {
    // Check stock
    $result = mysqli_query($conn, "SELECT stock FROM barang WHERE id = $product_id FOR UPDATE");
    $row = mysqli_fetch_assoc($result);
    
    if ($row['stock'] < $quantity) {
        throw new Exception("Insufficient stock");
    }
    
    // Deduct stock
    mysqli_query($conn, "UPDATE barang SET stock = stock - $quantity WHERE id = $product_id");
    
    // Create transaction record
    mysqli_query($conn, "INSERT INTO transaksi (...) VALUES (...)");
    
    // Create transaction details
    mysqli_query($conn, "INSERT INTO detail_transaksi (...) VALUES (...)");
    
    mysqli_commit($conn);
} catch (Exception $e) {
    mysqli_rollback($conn);
    // Handle error
}
```

**Detection:**
- [ ] Code has `INSERT`/`UPDATE` without `mysqli_begin_transaction()`
- [ ] Stock updates happen after transaction is already committed
- [ ] No `FOR UPDATE` locking on stock reads
- [ ] Testing only done with single user

**Phase to Address:** **Phase 2 - Core Transaction Processing** (when implementing transaction creation)

---

### 2. SQL Injection via Direct Query Concatenation

**What goes wrong:** Building SQL queries by concatenating `$_POST` or `$_GET` values directly into query strings instead of using prepared statements.

**Why it happens:**
- Tutorial code from 2010-2015 still circulating uses this pattern
- Perceived complexity of prepared statements
- "It's just an internal app" false security
- Certification projects often copy unsafe patterns from outdated sources

**Consequences:**
- Complete database compromise
- Data exfiltration (customer data, sales records, credentials)
- Database deletion or ransom
- Certification failure (security is a competency requirement)

**Prevention:**
```php
// WRONG - vulnerable to SQL injection
$username = $_POST['username'];
$query = "SELECT * FROM users WHERE username = '$username'";

// CORRECT - prepared statement
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
```

**Detection:**
- [ ] Any `$_POST`, `$_GET`, or `$_REQUEST` directly in query strings
- [ ] Variables interpolated into SQL with `"SELECT ... $variable"`
- [ ] No `mysqli_prepare()` or `mysqli_stmt_bind_param()` in codebase

**Phase to Address:** **Phase 1 - Authentication & Database Setup** (from first query written)

---

### 3. Plain Text or Weak Password Storage

**What goes wrong:** Storing passwords as plain text, MD5, or SHA1 hashes instead of using `password_hash()` with bcrypt or Argon2.

**Why it happens:**
- Old tutorials still teach MD5
- Misunderstanding of "encryption" vs "hashing"
- Belief that salting manually is sufficient
- Copy-pasting legacy authentication code

**Consequences:**
- Credential theft in any data breach
- Rainbow table attacks recover passwords instantly
- Admin accounts compromised → full system takeover
- Violation of J.620100.016.01 (Best Practices competency)

**Prevention:**
```php
// Registration - hash password
$hashed = password_hash($password, PASSWORD_DEFAULT); // Uses bcrypt by default
mysqli_query($conn, "INSERT INTO users (password, ...) VALUES ('$hashed', ...)");

// Login - verify password
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (password_verify($password, $user['password'])) {
    // Success
} else {
    // Invalid credentials
}
```

**Detection:**
- [ ] `md5()`, `sha1()`, or `crypt()` without proper salt in authentication code
- [ ] Password column in database is 32 characters (MD5) instead of 60+ (bcrypt)
- [ ] No use of `password_hash()` and `password_verify()`

**Phase to Address:** **Phase 1 - Authentication & Database Setup**

---

### 4. Session Fixation & Hijacking Vulnerabilities

**What goes wrong:** Not regenerating session IDs after login, allowing attackers to fixate a session ID before authentication and hijack the session after user logs in.

**Why it happens:**
- Session security is invisible until exploited
- PHP's default session handling feels "automatic"
- No visible warning when session is vulnerable
- Focus on functionality over security in certification projects

**Consequences:**
- Attacker gains authenticated access as any user (including admin)
- Full account takeover without knowing credentials
- Unauthorized transactions, data modification
- Role escalation (cashier → admin)

**Prevention:**
```php
// At the VERY beginning of every protected page
session_start();

// CRITICAL: Regenerate session ID after login
if ($login_successful) {
    session_regenerate_id(true); // true deletes old session file
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
}

// Additional hardening
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access
ini_set('session.cookie_secure', 1);   // HTTPS only (if using HTTPS)
ini_set('session.use_strict_mode', 1); // Reject uninitialized session IDs
```

**Detection:**
- [ ] No `session_regenerate_id()` call after successful login
- [ ] Session ID visible in URL (using `?PHPSESSID=...`)
- [ ] No `session.cookie_httponly` configuration
- [ ] Session timeout not enforced

**Phase to Address:** **Phase 1 - Authentication & Database Setup**

---

### 5. No Input Validation Before Processing

**What goes wrong:** Trusting user input without validation, leading to invalid data in database, broken reports, or application crashes.

**Why it happens:**
- "Users won't enter negative quantities" assumption
- Browser validation relied upon (can be bypassed)
- Validation seen as "nice to have" instead of essential
- Time pressure in certification context

**Consequences:**
- Negative quantities in transactions
- Zero or negative prices
- Broken sales calculations
- Chart.js visualization failures (null/invalid data)
- Database constraint violations

**Prevention:**
```php
// Validate ALL inputs before database operations
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
if ($quantity === false || $quantity <= 0) {
    $_SESSION['error'] = "Quantity must be a positive number";
    header('Location: transaction.php');
    exit;
}

$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
if ($price === false || $price < 0) {
    $_SESSION['error'] = "Invalid price";
    exit;
}

// For product IDs
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
if ($product_id === false) {
    // Handle invalid ID
}
```

**Detection:**
- [ ] `$_POST` values used directly in calculations
- [ ] No `filter_input()` or `filter_var()` validation
- [ ] Database accepts negative stock/quantities (no CHECK constraints)

**Phase to Address:** **Phase 3 - Product & Transaction Management**

---

## Moderate Pitfalls

Mistakes that cause maintenance headaches, bugs, or technical debt.

### 6. Spaghetti Code: Mixed Business Logic & Presentation

**What goes wrong:** PHP logic, HTML markup, and SQL queries all in the same file without separation. Makes debugging, testing, and modification extremely difficult.

**Why it happens:**
- PHP Native procedural style encourages inline code
- "Simple is better" taken too far
- No understanding of separation of concerns
- Tutorial examples often show this anti-pattern

**Consequences:**
- 500+ line files with logic scattered throughout
- Impossible to test business logic independently
- HTML changes risk breaking database queries
- Certification assessors can't follow code flow (J.620100.017.02 competency risk)

**Prevention:**
```php
// Structure: config/ → includes/ → pages/

// config/database.php
$conn = mysqli_connect('localhost', 'user', 'pass', 'dbname');

// includes/functions.php
function getProducts($conn) {
    $result = mysqli_query($conn, "SELECT * FROM barang");
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function createTransaction($conn, $data) {
    // Business logic here
}

// pages/transactions.php (presentation only)
require_once '../config/database.php';
require_once '../includes/functions.php';

$products = getProducts($conn);
// HTML with minimal PHP for output
```

**Detection:**
- [ ] Files with >200 lines mixing HTML and PHP
- [ ] SQL queries inside HTML blocks
- [ ] No `includes/` or `functions/` directory structure

**Phase to Address:** **Phase 1 - Project Structure Setup**

---

### 7. No Error Handling or Logging

**What goes wrong:** Using `or die()` for errors, showing raw MySQL errors to users, or silently failing without logging.

**Why it happens:**
- Quick debugging left in production code
- "Users don't need to see errors"
- No logging infrastructure set up
- Error reporting disabled entirely

**Consequences:**
- Users see database structure in error messages (security risk)
- No way to debug production issues
- Failed transactions with no audit trail
- Certification assessors see poor debugging practices

**Prevention:**
```php
// Development: show errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Production: log errors, don't display
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/app.log');

// Handle query errors gracefully
$result = mysqli_query($conn, $query);
if (!$result) {
    error_log("Database error: " . mysqli_error($conn));
    $_SESSION['error'] = "System error. Please try again.";
    header('Location: dashboard.php');
    exit;
}

// Use try-catch with transactions
try {
    mysqli_begin_transaction($conn);
    // ... operations
    mysqli_commit($conn);
} catch (Exception $e) {
    mysqli_rollback($conn);
    error_log("Transaction failed: " . $e->getMessage());
    $_SESSION['error'] = "Transaction failed";
}
```

**Detection:**
- [ ] `or die()` anywhere in codebase
- [ ] `display_errors = 1` in production
- [ ] No `error_log()` calls
- [ ] Raw `mysqli_error()` shown to users

**Phase to Address:** **Phase 1 - Project Structure Setup**

---

### 8. Missing Role-Based Access Control (RBAC) Enforcement

**What goes wrong:** Hiding admin links from cashiers but not checking roles on the server side. Cashiers can access admin pages by typing URL directly.

**Why it happens:**
- Frontend-only access control (hide buttons)
- Trust that users won't manually navigate
- Each page doesn't verify session role
- "Security through obscurity" mindset

**Consequences:**
- Cashiers can access user management
- Unauthorized price modifications
- Sales report manipulation
- Complete bypass of intended permissions

**Prevention:**
```php
// includes/auth.php - include at top of EVERY protected page
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// For admin-only pages
function requireAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        $_SESSION['error'] = "Access denied";
        header('Location: ../dashboard.php');
        exit;
    }
}

// In admin/users.php
require_once '../includes/auth.php';
requireAdmin(); // Must call this
```

**Detection:**
- [ ] Pages without session check at the top
- [ ] No role verification on admin pages
- [ ] Access control only in navigation menus (frontend)

**Phase to Address:** **Phase 1 - Authentication & Database Setup**

---

## Minor Pitfalls

Annoyances that reduce polish and user experience.

### 9. No Form Resubmission Protection

**What goes wrong:** Browser warns "Confirm Form Resubmission" when refreshing after POST. Users accidentally create duplicate transactions.

**Why it happens:**
- Direct POST response without redirect
- Not implementing Post-Redirect-Get (PRG) pattern
- Focus on functionality over UX

**Consequences:**
- Duplicate transactions on refresh
- Confused users
- Inventory discrepancies
- Poor certification demo experience

**Prevention:**
```php
// After processing POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process transaction
    createTransaction($conn, $_POST);
    
    // Redirect to prevent resubmission
    header('Location: transactions.php?success=1');
    exit; // CRITICAL: exit after header redirect
}

// Show success message if redirected
if (isset($_GET['success'])) {
    echo "Transaction created successfully";
}
```

**Detection:**
- [ ] POST handlers that render HTML directly
- [ ] No `header('Location: ...')` after form processing
- [ ] Browser shows "Confirm Form Resubmission" on refresh

**Phase to Address:** **Phase 3 - Product & Transaction Management**

---

### 10. Hardcoded Configuration Values

**What goes wrong:** Database credentials, site URLs, or constants scattered throughout codebase instead of centralized config.

**Why it happens:**
- Quick prototyping
- Not thinking about deployment
- Copy-paste development

**Consequences:**
- Must edit multiple files to change database
- Credentials accidentally committed to version control
- Different environments (dev/prod) require code changes
- Certification environment switch becomes painful

**Prevention:**
```php
// config.php (single source of truth)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pos_certification');
define('BASE_URL', 'http://localhost/pos/');

// Include everywhere
require_once 'config.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
```

**Detection:**
- [ ] `'localhost'` or database passwords in multiple files
- [ ] No `config.php` or `database.php` include file
- [ ] Absolute URLs hardcoded in links

**Phase to Address:** **Phase 1 - Project Structure Setup**

---

## Phase-Specific Warnings

| Phase Topic | Likely Pitfall | Mitigation |
|-------------|---------------|------------|
| **Authentication Setup** | SQL injection, weak passwords, session fixation | Use prepared statements from first query, `password_hash()`, `session_regenerate_id()` |
| **Database Design** | Missing transactions, no foreign keys | Design with `FOR UPDATE` locking in mind, add FK constraints |
| **Transaction Processing** | Race conditions, no atomicity | Wrap in `mysqli_begin_transaction()`/`commit()`/`rollback()` |
| **Product Management** | Missing input validation | Validate ALL inputs with `filter_input()` before DB operations |
| **Dashboard/Reports** | Hardcoded dates, no error handling | Use dynamic date ranges, wrap queries in try-catch |
| **Role Management** | Frontend-only access control | Server-side role checks on EVERY protected page |
| **Print Functionality** | Direct POST response | Implement Post-Redirect-Get pattern |

---

## Confidence Assessment

| Pitfall | Confidence | Sources |
|---------|------------|---------|
| Missing transactions → race conditions | HIGH | Multiple concurrency sources, Laravel/PostgreSQL docs |
| SQL injection via concatenation | HIGH | OWASP, PHP security best practices 2025 |
| Weak password storage | HIGH | OWASP Password Storage Cheat Sheet, PHP 2025 guides |
| Session fixation | HIGH | PHP session security articles 2025 |
| Input validation gaps | MEDIUM | General web dev best practices |
| Spaghetti code | HIGH | PHP clean code articles 2025-2026 |
| Error handling gaps | MEDIUM | PHP security guides |
| RBAC enforcement | MEDIUM | Common web app security patterns |
| Form resubmission | HIGH | Standard web dev pattern (PRG) |
| Hardcoded config | MEDIUM | General software engineering practices |

---

## Sources

- OWASP Password Storage Cheat Sheet (2024)
- PHP Security Best Practices 2025 - Zestminds
- "How to Prevent SQL Injection in PHP Applications" - Invicti (2025)
- "Master PHP Session Security Best Practices" - SecureCodingPractices (2025)
- "How to Handle Race Conditions in PostgreSQL Functions" - OneUptime (2026)
- "Prevent Race Condition in OpenCart with 5 Proven Strategies" - PentestTesting (2025)
- "Best Practices for Writing Clean and Efficient PHP Code" - Crest Infotech (2026)
- "PHP Password Hashing: Bcrypt, Argon2, and Best Practices" - DevStackTips (2025)
- "Idempotent Payment & Order APIs" - Medium (2026)
- "Financial Ledger in PHP: Double-Entry, Reconciliation, and Reports" - Medium (2025)
- PHP.net Manual: Prepared Statements, Transactions
- "15 Common POS Mistakes Businesses Make" - POSApt (2025)
- "6 Common POS Mistakes" - OneHub POS (2025)
