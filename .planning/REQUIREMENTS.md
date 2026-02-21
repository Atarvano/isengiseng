# Requirements: Mini Cashier Application

**Defined:** 2026-02-21
**Core Value:** Enable cashiers to process sales transactions quickly while providing administrators with inventory control and sales visibility.

## v1 Requirements

Requirements for certification completion. Each maps to roadmap phases.

### Landing & Authentication

- [x] **LAND-01**: Landing page displays with login/register links and app branding
- [x] **AUTH-01**: User can login with username and password
- [x] **AUTH-02**: Password is hashed using password_hash() before storage
- [x] **AUTH-03**: User can logout from any authenticated page
- [x] **AUTH-04**: User can change their password after login
- [x] **AUTH-05**: Session persists across page navigation

### Role Management

- [ ] **ROLE-01**: Admin role can access all admin features
- [ ] **ROLE-02**: Kasir (Cashier) role can only access transaction features
- [x] **ROLE-03**: Role check enforced server-side on every protected page

### Product Management (Admin Only)

- [x] **PROD-01**: Admin can view list of products (barang)
- [ ] **PROD-02**: Admin can add new product with name, price, stock
- [ ] **PROD-03**: Admin can edit existing product details
- [ ] **PROD-04**: Admin can delete products
- [x] **PROD-05**: Stock cannot be negative (validation)

### Transaction Processing (Cashier)

- [ ] **TRANS-01**: Cashier can view product list for selection
- [ ] **TRANS-02**: Cashier can add product to cart with quantity
- [ ] **TRANS-03**: Total price calculates automatically (price × quantity)
- [ ] **TRANS-04**: Multiple items can be added to single transaction
- [ ] **TRANS-05**: Stock reduces automatically when transaction is saved
- [ ] **TRANS-06**: Cannot complete transaction if stock is insufficient

### Dashboard & Reports (Admin)

- [ ] **DASH-01**: Dashboard displays total product count
- [ ] **DASH-02**: Dashboard displays total transaction count
- [ ] **DASH-03**: Sales chart shows daily sales using Chart.js
- [ ] **DASH-04**: Admin can print transaction report

### User Management (Admin Only)

- [ ] **USER-01**: Admin can view list of users
- [ ] **USER-02**: Admin can add new user with username, password, role
- [ ] **USER-03**: Admin can edit user details and role
- [ ] **USER-04**: Admin can delete users

## v2 Requirements

Deferred to future development.

### Enhanced Features

- **NOTIF-01**: Low stock alerts when product reaches minimum threshold
- **NOTIF-02**: Email receipt to customers
- **REPORT-01**: Export reports to PDF
- **REPORT-02**: Export reports to Excel/CSV
- **SEARCH-01**: Advanced search and filtering for products/transactions

## Out of Scope

Explicitly excluded. Documented to prevent scope creep.

| Feature | Reason |
|---------|--------|
| Payment gateway integration | Certification scope is basic POS only |
| Barcode scanning | Manual product selection sufficient |
| Multi-store support | Single store context |
| Customer management | Focus on transactions only |
| Inventory alerts | Basic stock tracking is enough |
| Restaurant features (table management, KDS) | Retail POS context |
| Loyalty programs | Out of certification scope |

## Traceability

Which phases cover which requirements. Updated during roadmap creation.

| Requirement | Phase | Status |
|-------------|-------|--------|
| LAND-01 | Phase 1 | Complete |
| AUTH-01 | Phase 1 | Complete |
| AUTH-02 | Phase 1 | Complete |
| AUTH-03 | Phase 1 | Complete |
| AUTH-04 | Phase 1 | Complete |
| AUTH-05 | Phase 1 | Complete |
| ROLE-01 | Phase 1 | Pending |
| ROLE-02 | Phase 1 | Pending |
| ROLE-03 | Phase 1 | Complete |
| PROD-01 | Phase 2 | Complete |
| PROD-02 | Phase 2 | Pending |
| PROD-03 | Phase 2 | Pending |
| PROD-04 | Phase 2 | Pending |
| PROD-05 | Phase 2 | Complete |
| TRANS-01 | Phase 3 | Pending |
| TRANS-02 | Phase 3 | Pending |
| TRANS-03 | Phase 3 | Pending |
| TRANS-04 | Phase 3 | Pending |
| TRANS-05 | Phase 3 | Pending |
| TRANS-06 | Phase 3 | Pending |
| DASH-01 | Phase 4 | Pending |
| DASH-02 | Phase 4 | Pending |
| DASH-03 | Phase 4 | Pending |
| DASH-04 | Phase 4 | Pending |
| USER-01 | Phase 5 | Pending |
| USER-02 | Phase 5 | Pending |
| USER-03 | Phase 5 | Pending |
| USER-04 | Phase 5 | Pending |

**Coverage:**
- v1 requirements: 28 total
- Mapped to phases: 28
- Unmapped: 0 ✓

---
*Requirements defined: 2026-02-21*
*Last updated: 2026-02-21 by GSD Executor (Plan 02 complete)*
