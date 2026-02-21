# Mini Cashier Application

## What This Is

A web-based point-of-sale (cashier) application for small businesses. Built as a certification project for Junior Web Programmer competency test. The system manages products, transactions, and provides sales reporting with role-based access control.

## Core Value

Enable cashiers to process sales transactions quickly while providing administrators with inventory control and sales visibility.

## Requirements

### Validated

(None yet — ship to validate)

### Active

- [ ] Landing page with login/register links
- [ ] User authentication with secure password hashing
- [ ] Role-based access (Admin and Cashier)
- [ ] Product management (CRUD)
- [ ] Transaction processing with automatic stock reduction
- [ ] Dashboard with sales statistics
- [ ] Sales chart visualization using Chart.js
- [ ] Transaction report printing
- [ ] Password change functionality
- [ ] User management (Admin only)

### Out of Scope

- [ ] Payment gateway integration — Certification scope is basic POS
- [ ] Barcode scanning — Manual product selection is sufficient
- [ ] Multi-store support — Single store context
- [ ] Customer management — Focus on transactions only
- [ ] Inventory alerts — Basic stock tracking is enough

## Context

**Certification Requirements:**
- Scheme: Junior Web Programmer
- Time limit: 300 minutes (5 hours)
- Tech stack: PHP Native (procedural), MySQL
- UI Framework: Bootstrap
- Charts: Chart.js
- Environment: Laragon (Windows, Apache, MySQL)

**Laragon Workflow:**
- Project location: `C:\laragon\www\QwenSertifikasi`
- Access URL: `http://localhost` or `http://QwenSertifikasi.test` (pretty URLs)
- Database: MySQL via phpMyAdmin at `http://localhost/phpmyadmin`
- Services: Start/Stop Apache & MySQL from Laragon Dashboard
- PHP Version: Switchable via Menu > PHP > Version
- Mail: Mailpit for email testing (if needed)

**Database Tables Required:**
- users (authentication & roles)
- barang (products)
- transaksi (transactions)
- detail_transaksi (transaction items)

**Competency Units:**
- J.620100.004.02 — Data Structures (table relationships)
- J.620100.016.01 — Best Practices (validation, hashing)
- J.620100.017.02 — Structured Programming (modular code)
- J.620100.019.02 — Library Usage (Bootstrap, Chart.js)
- J.620100.025.02 — Debugging
- J.620100.011.01 — Tool Installation (XAMPP/Laragon)
- J.620100.023.02 — Documentation

## Constraints

- **Tech Stack**: PHP Native procedural only — No OOP, no frameworks
- **Database**: MySQL via mysqli (procedural style)
- **UI**: Bootstrap 5 for responsive design
- **Charts**: Chart.js for sales visualization
- **Time**: 300 minutes for implementation
- **Security**: Passwords must be hashed with password_hash()
- **Server**: Laragon (Apache + MySQL on Windows)

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| PHP Native procedural | Certification requirement | — Pending |
| mysqli (procedural) | User preference, simpler than PDO | — Pending |
| Bootstrap 5 | Current stable version | — Pending |
| Session-based auth | Simple, works with PHP native | — Pending |
| password_hash() for passwords | PHP built-in, secure (BCrypt) | — Pending |

---
*Last updated: 2026-02-21 after initialization*
