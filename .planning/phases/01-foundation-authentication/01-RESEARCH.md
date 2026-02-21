# Phase 1: Foundation & Authentication - Research

**Researched:** 2026-02-21
**Domain:** PHP Native Authentication, Session Management, Role-Based Access Control
**Confidence:** HIGH

## Summary

This phase establishes the authentication foundation for a Mini Cashier POS system using PHP native procedural code with mysqli. The implementation covers landing page with login/register, session-based authentication with secure password hashing (BCrypt via `password_hash()`), and role-based access control distinguishing Admin vs Cashier roles.

Key technical areas: secure authentication flows, session security hardening, RBAC implementation patterns, and Bootstrap 5 dashboard UI with sidebar navigation.

**Primary recommendation:** Use PHP's built-in `password_hash()` with BCrypt, `$_SESSION` with security hardening (regenerate ID, secure cookies, HttpOnly), and implement server-side role checks on every protected page.

<user_constraints>
## User Constraints (from CONTEXT.md)

### Locked Decisions
- Split screen layout: branding/hero on left, login form on right — modern SaaS style
- Branding: Logo + "Mini Cashier" name + tagline ("Simple POS for Small Business")
- Light theme: white/light gray background, dark text — clean, professional
- Feature preview below form: bullet list highlighting core workflows (Products, Transactions, Reports)
- Full footer: copyright, docs, privacy, about links — complete SaaS feel
- Login and register on separate pages linked with basic anchor tags (no framework routing)
- Separate pages for login and register with basic links (no router)
- Real-time inline validation: validate on blur, show errors immediately
- No "Remember me" — session only, expires on browser close
- Registration fields: username + password only (minimal, no confirmation, no email)
- Session uses PHP $_SESSION
- On session expiry: show login modal overlay, then return to current page after re-auth
- 5-minute warning before session expires (modal with option to extend)
- Logout location: in profile page header (sidebar-based navigation, no navbar)
- Visible role badge showing "Admin" or "Cashier" in sidebar header (below username)
- Separate dashboards: Admin and Cashier see different home pages
- Dashboard content: same layout but different data scope (Admin sees all, Cashier sees their transactions only)
- Sidebar menu: filtered by role — Cashier only sees transaction-related items, Admin sees everything

### Claude's Discretion
- Session timeout duration (recommend 2 hours for POS workflow)
- Exact session extension behavior on "Stay logged in"
- Badge styling and color (recommend subtle color coding: Admin=blue, Cashier=green)
- Exact dashboard widget selection and layout
- Sidebar design and navigation patterns

### Deferred Ideas (OUT OF SCOPE)
- None — discussion stayed within phase scope

</user_constraints>

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|-----------------|
| LAND-01 | Landing page displays with login/register links and app branding | Bootstrap 5 split-screen layout, sidebar navigation templates |
| AUTH-01 | User can login with username and password | PHP native login with prepared statements, password_verify() |
| AUTH-02 | Password is hashed using password_hash() before storage | BCrypt via password_hash() PASSWORD_DEFAULT, research verified |
| AUTH-03 | User can logout from any authenticated page | Session destruction pattern, logout.php with session_destroy() |
| AUTH-04 | User can change their password after login | Password change flow with current password verification |
| AUTH-05 | Session persists across page navigation | $_SESSION with security hardening (regenerate_id, secure cookies) |
| ROLE-01 | Admin role can access all admin features | Server-side role check pattern, session-based role storage |
| ROLE-02 | Kasir (Cashier) role can only access transaction features | Role-filtered sidebar menu, server-side authorization checks |
| ROLE-03 | Role check enforced server-side on every protected page | Auth middleware pattern (require_auth.php include), role validation |

</phase_requirements>

## Standard Stack

### Core
| Library | Version | Purpose | Why Standard |
|---------|---------|---------|--------------|
| PHP | 8.2+ | Server-side language | Project requirement, native procedural |
| MySQLi | Procedural | Database operations | Project requirement, simpler than PDO |
| Bootstrap 5 | 5.3.x | CSS framework | Project decision, current stable |

### Supporting
| Library | Purpose | When to Use |
|---------|---------|-------------|
| PHP `password_hash()` | Password hashing (BCrypt) | Always for password storage |
| PHP `$_SESSION` | Session management | Auth state, user data |
| Bootstrap Icons | Icon set | UI elements, sidebar icons |

### Alternatives Considered
| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| password_hash() BCrypt | Argon2id | Argon2id is more modern but BCrypt is sufficient and project-decided |
| $_SESSION | JWT tokens | JWT is stateless but overkill for this simple POS; sessions work well |
| Bootstrap 5 | Tailwind CSS | Bootstrap has better component library for rapid dashboard dev |

**Installation:**
```bash
# No npm/package manager needed - PHP Native with Bootstrap 5 via CDN
# Bootstrap 5 CDN (for reference):
# https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css
# https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js
```

## Architecture Patterns

### Recommended Project Structure
```
QwenSertifikasi/
├── config/
│   └── database.php         # Database connection (mysqli_connect)
├── includes/
│   ├── auth_check.php       # Authentication middleware (session validation)
│   ├── role_check.php       # Role authorization middleware
│   ├── header.php           # Common header (session start, nav)
│   ├── sidebar.php          # Sidebar navigation (role-filtered)
│   └── footer.php           # Common footer
├── assets/
│   ├── css/
│   │   └── style.css        # Custom styles
│   └── js/
│       └── auth.js          # Client-side validation, session warning
├── auth/
│   ├── login.php            # Login page
│   ├── register.php         # Registration page
│   ├── logout.php           # Logout handler
│   └── change_password.php  # Password change form
├── dashboard/
│   ├── admin/               # Admin dashboard pages
│   │   └── index.php
│   └── cashier/             # Cashier dashboard pages
│       └── index.php
└── index.php                # Landing page (redirect or show login)
```

### Pattern 1: Authentication Middleware
**What:** Reusable include file that checks if user is logged in before allowing page access
**When to use:** Every protected page must include this at the top
**Example:**
```php
<?php
// includes/auth_check.php
// Source: https://phpdelusions.net/modern_php_example/registration
session_start();

if (!isset($_SESSION['user_id'])) {
    // Store current page for redirect after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: /auth/login.php');
    exit;
}

// Session timeout check (2 hours = 7200 seconds)
if (time() - ($_SESSION['last_activity'] ?? 0) > 7200) {
    session_unset();
    session_destroy();
    header('Location: /auth/login.php?timeout=1');
    exit;
}

// Update last activity time
$_SESSION['last_activity'] = time();
```

### Pattern 2: Role-Based Access Control
**What:** Server-side role verification before granting access to protected features
**When to use:** Admin-only pages, role-filtered navigation
**Example:**
```php
<?php
// includes/role_check.php
// Source: https://www.osohq.com/learn/rbac-role-based-access-control
require_once 'auth_check.php';

function require_role($required_role) {
    $user_role = $_SESSION['user_role'] ?? null;
    
    if ($user_role !== $required_role) {
        http_response_code(403);
        die('Access denied: insufficient permissions');
    }
}

// Usage on admin pages:
// require_role('admin');
```

### Pattern 3: Secure Password Hashing
**What:** Using PHP's built-in `password_hash()` with BCrypt for password storage
**When to use:** User registration, password change
**Example:**
```php
<?php
// Registration - hash password before storing
// Source: https://www.php.net/manual/en/function.password-hash.php
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// PASSWORD_DEFAULT uses BCrypt in PHP 8.x

// Login - verify password
// Source: https://www.php.net/manual/en/function.password-verify.php
if (password_verify($password, $stored_hash)) {
    // Password is correct
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];
}
```

### Pattern 4: Session Security Hardening
**What:** Multiple layers of protection against session hijacking and fixation
**When to use:** Login success, before storing session data
**Example:**
```php
<?php
// Source: https://securecodingpractices.com/php-session-security-best-practices-hijacking-fixation/

// Regenerate session ID to prevent fixation
session_regenerate_id(true);

// Set secure session parameters (before session_start)
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Only if using HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
```

### Anti-Patterns to Avoid
- **Storing passwords in plain text:** Always use `password_hash()`
- **Using MD5/SHA1 for passwords:** These are broken; use BCrypt via `password_hash()`
- **Putting session ID in URLs:** Always use cookies only
- **Not regenerating session ID on login:** Opens session fixation attacks
- **Client-side only role checks:** Always verify server-side
- **SELECT * in queries:** Only fetch needed columns

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Password hashing | Custom encryption | `password_hash()` | BCrypt handles salting, cost factors, timing attacks |
| Password verification | String comparison | `password_verify()` | Constant-time comparison prevents timing attacks |
| Session management | Custom cookie auth | `$_SESSION` | Built-in security, proper serialization, garbage collection |
| SQL queries | String concatenation | Prepared statements | Prevents SQL injection automatically |
| Form validation | Pure client-side | Server-side + client-side | Client-side can be bypassed; server-side is authoritative |

**Key insight:** Authentication and session management have well-tested PHP built-ins. Custom implementations introduce security vulnerabilities and edge cases that experts have already solved.

## Common Pitfalls

### Pitfall 1: Session Fixation
**What goes wrong:** Attacker sets victim's session ID before login, then hijacks session after authentication
**Why it happens:** Not regenerating session ID after successful login
**How to avoid:** Call `session_regenerate_id(true)` immediately after login success
**Warning signs:** Session ID stays same before and after authentication

### Pitfall 2: SQL Injection via Login Form
**What goes wrong:** Attacker bypasses authentication using `' OR '1'='1` style attacks
**Why it happens:** Using string concatenation instead of prepared statements
**How to avoid:** Always use mysqli prepared statements with bound parameters
**Warning signs:** `$_POST` values directly interpolated into SQL strings

### Pitfall 3: Incomplete Role Verification
**What goes wrong:** Cashier can access admin features by guessing URLs
**Why it happens:** Role check only in UI, not server-side
**How to avoid:** Every protected page includes `require_role()` check at top
**Warning signs:** Navigation hides admin links but pages are directly accessible

### Pitfall 4: Session Timeout UX
**What goes wrong:** User loses work when session expires without warning
**Why it happens:** No session expiry warning mechanism
**How to avoid:** JavaScript countdown with 5-minute warning modal, option to extend
**Warning signs:** Users complain about sudden logouts during active work

### Pitfall 5: Insecure Cookie Configuration
**What goes wrong:** Session cookie stolen via XSS or network sniffing
**Why it happens:** Missing HttpOnly, Secure, SameSite flags
**How to avoid:** Set `session.cookie_httponly=1`, `session.cookie_secure=1`, `session.cookie_samesite=Strict`
**Warning signs:** Cookies accessible via JavaScript, sent over HTTP

## Code Examples

### Database Connection (config/database.php)
```php
<?php
// Source: https://phpdelusions.net/mysqli
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'mini_cashier';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Set charset to prevent encoding issues
mysqli_set_charset($conn, 'utf8mb4');
```

### User Registration (auth/register.php)
```php
<?php
// Source: https://phpdelusions.net/modern_php_example/registration
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    $errors = [];
    if (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    if (empty($errors)) {
        // Check if username exists
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_fetch_assoc($result)) {
            $errors[] = 'Username already exists';
        } else {
            // Hash password and insert user
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, 'cashier')");
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashed);
            
            if (mysqli_stmt_execute($stmt)) {
                header('Location: login.php?registered=1');
                exit;
            } else {
                $errors[] = 'Registration failed';
            }
        }
        mysqli_stmt_close($stmt);
    }
}
```

### User Login (auth/login.php)
```php
<?php
// Source: https://securecodingpractices.com/php-session-security-best-practices-hijacking-fixation/
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Fetch user by username
    $stmt = mysqli_prepare($conn, "SELECT id, username, password, role FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if ($user && password_verify($password, $user['password'])) {
        // Regenerate session ID to prevent fixation
        session_regenerate_id(true);
        
        // Set session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        
        // Redirect to appropriate dashboard
        $redirect = $_SESSION['redirect_after_login'] ?? null;
        unset($_SESSION['redirect_after_login']);
        
        if ($redirect) {
            header('Location: ' . $redirect);
        } else {
            $dashboard = ($user['role'] === 'admin') ? '/dashboard/admin/' : '/dashboard/cashier/';
            header('Location: ' . $dashboard);
        }
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
```

### Session Timeout Warning (assets/js/auth.js)
```javascript
// Source: Pattern based on https://moldstud.com/articles/p-enhance-user-authentication-experience-with-php-sessions
const SESSION_TIMEOUT = 7200; // 2 hours in seconds
const WARNING_TIME = 300; // 5 minutes warning

let sessionTimer;
let warningTimer;

function startSessionTimers() {
    clearTimeout(sessionTimer);
    clearTimeout(warningTimer);
    
    // Show warning 5 minutes before expiry
    warningTimer = setTimeout(() => {
        showSessionWarningModal();
    }, (SESSION_TIMEOUT - WARNING_TIME) * 1000);
    
    // Auto-logout at expiry
    sessionTimer = setTimeout(() => {
        window.location.href = '/auth/login.php?timeout=1';
    }, SESSION_TIMEOUT * 1000);
}

function showSessionWarningModal() {
    const modal = new bootstrap.Modal(document.getElementById('sessionWarningModal'));
    modal.show();
}

function extendSession() {
    fetch('/auth/extend_session.php', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                startSessionTimers();
                bootstrap.Modal.getInstance(document.getElementById('sessionWarningModal')).hide();
            }
        });
}

// Reset timers on user activity
['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(event => {
    document.addEventListener(event, startSessionTimers, true);
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', startSessionTimers);
```

### Role-Filtered Sidebar (includes/sidebar.php)
```php
<?php
// Source: https://colorlib.com/wp/bootstrap-sidebar/
$role = $_SESSION['user_role'] ?? 'cashier';
?>
<nav class="sidebar">
    <div class="sidebar-header">
        <h4>Mini Cashier</h4>
        <span class="role-badge <?= $role ?>">
            <?= ucfirst($role) ?>
        </span>
    </div>
    
    <ul class="nav-menu">
        <!-- All roles see: Transactions -->
        <li class="nav-item">
            <a href="/transactions/" class="nav-link">
                <i class="bi bi-cart"></i> Transactions
            </a>
        </li>
        
        <!-- Admin only: Products -->
        <?php if ($role === 'admin'): ?>
        <li class="nav-item">
            <a href="/products/" class="nav-link">
                <i class="bi bi-box"></i> Products
            </a>
        </li>
        
        <!-- Admin only: Reports -->
        <li class="nav-item">
            <a href="/reports/" class="nav-link">
                <i class="bi bi-graph-up"></i> Reports
            </a>
        </li>
        
        <!-- Admin only: User Management -->
        <li class="nav-item">
            <a href="/users/" class="nav-link">
                <i class="bi bi-people"></i> Users
            </a>
        </li>
        <?php endif; ?>
        
        <!-- Profile (all roles) -->
        <li class="nav-item">
            <a href="/profile/" class="nav-link">
                <i class="bi bi-person"></i> Profile
            </a>
        </li>
    </ul>
</nav>
```

### Password Change (auth/change_password.php)
```php
<?php
// Source: https://www.php.net/manual/en/function.password-hash.php
require_once '../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    if ($new_password !== $confirm_password) {
        $errors[] = 'New passwords do not match';
    }
    
    if (strlen($new_password) < 6) {
        $errors[] = 'New password must be at least 6 characters';
    }
    
    if (empty($errors)) {
        // Verify current password
        $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        if (password_verify($current_password, $user['password'])) {
            // Update password
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $hashed, $_SESSION['user_id']);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Password changed successfully';
            } else {
                $errors[] = 'Failed to change password';
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = 'Current password is incorrect';
        }
    }
}
```

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| MD5/SHA1 for passwords | BCrypt via password_hash() | PHP 5.5+ (2013) | BCrypt is adaptive, salted, timing-safe |
| Manual salt + hash | password_hash() handles salting | PHP 5.5+ | No developer error in salt generation |
| String comparison for passwords | password_verify() | PHP 5.5+ | Constant-time comparison prevents timing attacks |
| Custom session handling | $_SESSION with hardened config | Ongoing | Built-in garbage collection, serialization |
| Client-side validation only | Server-side + client-side | Security best practice | Server-side is authoritative, cannot be bypassed |

**Deprecated/outdated:**
- `md5()` for passwords: Use `password_hash()` instead
- `sha1()` for passwords: Use `password_hash()` instead
- `crypt()` with weak algorithms: Use `password_hash()` with PASSWORD_DEFAULT
- Session ID in URLs: Use cookies only with `session.use_only_cookies=1`
- `mysql_*` functions: Removed in PHP 7, use `mysqli_*` procedural

## Open Questions

1. **Session timeout duration**
   - What we know: User decided 2 hours recommended for POS workflow
   - What's unclear: Optimal balance between security and UX for this specific context
   - Recommendation: Start with 2 hours (7200 seconds), adjust based on user feedback

2. **Session extension mechanism**
   - What we know: Should allow users to extend session before expiry
   - What's unclear: Should extension reset full timeout or add fixed time?
   - Recommendation: Reset to full timeout on extension (simpler UX)

3. **Role badge styling**
   - What we know: Should be visible in sidebar header
   - What's unclear: Exact color scheme and design
   - Recommendation: Admin=blue (primary), Cashier=green (success), subtle badge style

4. **Dashboard widget layout**
   - What we know: Same structure, different data scope
   - What's unclear: Which widgets for Phase 1 (before product/transaction features)
   - Recommendation: Minimal Phase 1 dashboard with welcome message, recent activity placeholder

## Sources

### Primary (HIGH confidence)
- **PHP Official Documentation** - https://www.php.net/manual/en/book.password.php - Password hashing API
- **PHP Official Documentation** - https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php - MySQLi prepared statements
- **OWASP** - https://securecodingpractices.com/php-session-security-best-practices-hijacking-fixation/ - Session security best practices
- **PHP Delusions** - https://phpdelusions.net/modern_php_example/registration - Modern PHP registration patterns

### Secondary (MEDIUM confidence)
- **WebSearch verified** - Multiple 2025 sources on PHP session security (webdevbyte.com, moldstud.com)
- **GitHub repositories** - 2025 PHP auth system examples (simple-login-php, PHP-User-System)
- **Bootstrap 5 Documentation** - https://coreui.io/bootstrap/docs/5.0/components/sidebar/ - Sidebar component patterns

### Tertiary (LOW confidence)
- **Medium articles** - General PHP authentication tutorials (verify against official docs)
- **YouTube tutorials** - PHP login system videos (code quality varies, use for inspiration only)

## Metadata

**Confidence breakdown:**
- Standard stack: HIGH - PHP native, mysqli, Bootstrap 5 are project-decided and well-documented
- Architecture: HIGH - Authentication patterns are mature, extensively documented in official PHP docs
- Pitfalls: HIGH - Session security, SQL injection prevention are well-understood problems

**Research date:** 2026-02-21
**Valid until:** 2027-02-21 (12 months - PHP authentication patterns are stable)
