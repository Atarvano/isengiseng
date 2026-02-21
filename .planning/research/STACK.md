# Technology Stack

**Project:** Mini Cashier Application (POS System)
**Researched:** 2026-02-21

## Recommended Stack

### Core Framework

| Technology | Version | Purpose | Why |
|------------|---------|---------|-----|
| PHP | 8.3+ (8.4 recommended) | Server-side scripting | PHP 8.3 has active security support until Dec 2027; 8.4 raises default bcrypt cost from 10 to 12 for stronger password hashing. Required by certification. **Confidence: HIGH** (php.net) |
| None (Vanilla) | N/A | No framework | Certification requirement specifies "PHP Native procedural" — frameworks like Laravel/Symfony are explicitly excluded. **Confidence: HIGH** (PROJECT.md) |

### Database

| Technology | Version | Purpose | Why |
|------------|---------|---------|-----|
| MySQL | 8.0+ | Relational database | Required by certification. Works natively with Laragon. Use `mysqli` extension (procedural style) per project constraints. **Confidence: HIGH** (PROJECT.md) |
| mysqli | PHP bundled | Database API | Procedural style required. Supports prepared statements for SQL injection prevention. More straightforward than PDO for simple procedural code. **Confidence: HIGH** (PHP 8.3/8.4 docs) |

### Infrastructure

| Technology | Version | Purpose | Why |
|------------|---------|---------|-----|
| Laragon | Latest | Local development environment | Certification specifies Laragon (Windows, Apache, MySQL). Provides PHP 8.3+/8.4, MySQL 8.0, Apache out of the box. **Confidence: HIGH** (PROJECT.md) |
| Apache | 2.4+ | Web server | Bundled with Laragon. Session handling via `$_SESSION` works natively. **Confidence: HIGH** |

### Frontend Libraries

| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| Bootstrap | 5.3.8 | Responsive UI framework | **Always use** — required by certification. Provides grid system, components (forms, tables, modals, alerts), responsive utilities. CDN: `https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css`. **Confidence: HIGH** (getbootstrap.com, cdnjs.com) |
| Chart.js | 4.5.1 | Sales chart visualization | **Always use** — required for dashboard sales statistics. Simple canvas-based charts, zero-config responsive. CDN: `https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js`. **Confidence: HIGH** (chartjs.org, jsdelivr.net) |
| Popper.js | 2.11.8 | Tooltip/popover positioning | **Use if needed** — bundled with Bootstrap 5.3.8 JS bundle. Only needed if using tooltips/popovers. **Confidence: HIGH** (getbootstrap.com) |

### Security Libraries

| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| password_hash() | PHP native | Password hashing | **Always use** — PHP built-in. Uses bcrypt by default (cost=12 in PHP 8.4, cost=10 in 8.3). Never store plain text passwords. **Confidence: HIGH** (PHP 8.4 changelog, OWASP) |
| password_verify() | PHP native | Password verification | **Always use** — pairs with password_hash(). Timing-attack safe. **Confidence: HIGH** (PHP docs) |
| Prepared Statements | mysqli native | SQL injection prevention | **Always use** for user input. Never interpolate `$_POST/$_GET` directly into queries. Use `mysqli_prepare()`, `mysqli_stmt_bind_param()`, `mysqli_stmt_execute()`. **Confidence: HIGH** (PHP 8.3/8.4 docs, OWASP) |

## Alternatives Considered

| Category | Recommended | Alternative | Why Not |
|----------|-------------|-------------|---------|
| PHP Version | 8.4 | 8.3 | 8.3 is acceptable (security support until Dec 2027), but 8.4 has improved default bcrypt cost (12 vs 10). Use 8.4 if Laragon supports it. |
| PHP Version | 8.4 | 8.2 | 8.2 security support ends Dec 2026 (10 months from now). Too close to EOL for new projects. |
| Database API | mysqli (procedural) | PDO | PDO is more flexible (supports multiple databases), but certification specifies mysqli. Procedural style matches project constraints. |
| UI Framework | Bootstrap 5.3.8 | Bootstrap 4.x | Bootstrap 4 is EOL. Bootstrap 5 has better utilities, no jQuery dependency, improved grid. |
| Chart Library | Chart.js 4.5.1 | ApexCharts, Highcharts | Chart.js is required by certification. Also: MIT license, simpler API, smaller bundle size (~60KB vs 200KB+). |
| Password Hashing | password_hash() | Argon2id | Argon2id is OWASP recommended, but requires PHP compiled with libsodium. Bcrypt (PASSWORD_DEFAULT) is portable, secure enough for certification scope. |
| Session Storage | PHP native sessions | JWT tokens | JWT is overkill for single-server POS. Native sessions with proper security (regenerate ID, secure cookies) are sufficient. |

## Installation

### Laragon Setup (Prerequisites)

```bash
# 1. Download and install Laragon from https://laragon.org/download/
# 2. Verify PHP version (should be 8.3+ or 8.4):
php -v

# 3. Verify mysqli extension is enabled:
php -m | grep mysqli

# 4. Start Laragon services (Apache + MySQL)
```

### Frontend Dependencies (CDN - No Installation Required)

Include in your HTML `<head>` and before `</body>`:

```html
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mini Cashier</title>
    
    <!-- Bootstrap 5.3.8 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
          crossorigin="anonymous">
  </head>
  <body>
    <!-- Your content here -->
    
    <!-- Bootstrap 5.3.8 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous"></script>
    
    <!-- Chart.js 4.5.1 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
  </body>
</html>
```

### Database Configuration

```php
<?php
// config/database.php
$host = 'localhost';
$username = 'root';
$password = ''; // Default Laragon MySQL password is empty
$database = 'mini_cashier';

// Create connection (procedural style)
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, 'utf8mb4');
?>
```

### Session Security Configuration

```php
<?php
// config/session.php

// Start session with security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
ini_set('session.use_strict_mode', 1);

session_start();

// Regenerate session ID on login to prevent fixation
function regenerate_session() {
    session_regenerate_id(true);
}
?>
```

## Version Verification Commands

```bash
# Check PHP version
php -v
# Expected: PHP 8.3.x or 8.4.x

# Check MySQL version
mysql --version
# Expected: mysql  Ver 8.0.x

# Check enabled PHP extensions
php -m
# Verify: mysqli, session, hash, openssl are listed
```

## What NOT to Use

| Technology | Why Avoid |
|------------|-----------|
| **Laravel, Symfony, CodeIgniter** | Certification explicitly requires "PHP Native" — no frameworks allowed. |
| **Composer dependencies** | Overkill for certification scope. All required libraries available via CDN. |
| **MySQLi object-oriented style** | Project constraints specify procedural style. Stick to `mysqli_connect()`, `mysqli_query()`, etc. |
| **mysql_* functions** | Removed in PHP 7.0. Deprecated since 2012. Use `mysqli_*` instead. |
| **Plain text passwords** | Security violation. Always use `password_hash()` and `password_verify()`. |
| **Direct SQL interpolation** | SQL injection risk. Always use prepared statements for user input. |
| **Bootstrap 3 or 4** | EOL versions. Bootstrap 5.3.8 is current stable with better features. |
| **Chart.js 3.x** | Older version. Chart.js 4.x has tree-shaking, better performance, new plugins. |
| **jQuery** | Unnecessary. Bootstrap 5 has no jQuery dependency. Vanilla JS is sufficient. |
| **JWT for authentication** | Over-engineering. Native PHP sessions are simpler and sufficient for single-server POS. |

## Sources

- **PHP Supported Versions**: https://www.php.net/supported-versions.php (HIGH confidence)
- **PHP 8.4 Password Hashing Changes**: https://php.watch/versions/8.4/password_hash-bcrypt-cost-increase (HIGH confidence)
- **Bootstrap 5.3 Documentation**: https://getbootstrap.com/docs/5.3/ (HIGH confidence)
- **Chart.js Documentation**: https://www.chartjs.org/docs/latest/ (HIGH confidence)
- **OWASP Password Storage**: https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html (HIGH confidence)
- **PHP mysqli Prepared Statements**: https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php (HIGH confidence)
- **PHP Security Best Practices 2026**: https://www.innoraft.ai/blog/secure-php-application-best-practices (MEDIUM confidence)

## Confidence Assessment

| Area | Confidence | Notes |
|------|------------|-------|
| PHP Version | HIGH | Verified from php.net official documentation |
| Bootstrap Version | HIGH | Verified from getbootstrap.com and cdnjs.com |
| Chart.js Version | HIGH | Verified from chartjs.org and jsdelivr.net |
| Security Practices | HIGH | Verified from OWASP and PHP official docs |
| mysqli Procedural | HIGH | Required by PROJECT.md constraints |
