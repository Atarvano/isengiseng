# Roadmap: Mini Cashier Application

**Created:** 2026-02-21
**Depth:** Standard
**Mode:** Interactive

## Phases

- [ ] **Phase 1: Foundation & Authentication** - Users can access the system securely with role-based permissions
- [ ] **Phase 2: Product Management** - Admin can manage product inventory (CRUD operations)
- [ ] **Phase 3: Transaction Processing** - Cashier can process sales transactions with cart and stock management
- [ ] **Phase 4: Dashboard & Reporting** - Admin can view sales statistics and print transaction reports
- [ ] **Phase 5: User Management** - Admin can manage system users and their roles

## Phase Details

### Phase 1: Foundation & Authentication
**Goal**: Users can securely access the system with appropriate role-based permissions
**Depends on:** Nothing (first phase)
**Requirements:** LAND-01, AUTH-01, AUTH-02, AUTH-03, AUTH-04, AUTH-05, ROLE-01, ROLE-02, ROLE-03
**Success Criteria** (what must be TRUE):
  1. User sees landing page with login/register links and app branding
  2. User can log in with username/password and session persists across page navigation
  3. User can log out from any authenticated page
  4. User can change their password after logging in
  5. Admin sees all features, Cashier only sees transaction features (role-based access enforced)
**Plans:** 4 plans

Plans:
- [x] 01-01-PLAN.md — Database schema, session config, auth middleware, role middleware
- [x] 01-02-PLAN.md — Landing page, login form, registration form with password hashing
- [ ] 01-03-PLAN.md — Logout handler, password change, session timeout warning system
- [ ] 01-04-PLAN.md — Admin/cashier dashboards, role-filtered sidebar, profile page

### Phase 2: Product Management
**Goal**: Admin can manage product inventory with full CRUD operations
**Depends on:** Phase 1 (requires authentication and admin role)
**Requirements:** PROD-01, PROD-02, PROD-03, PROD-04, PROD-05
**Success Criteria** (what must be TRUE):
  1. Admin can view list of all products with name, price, and stock
  2. Admin can add new product with name, price, and stock
  3. Admin can edit existing product details
  4. Admin can delete products from inventory
  5. System rejects products with negative stock values
**Plans:** TBD

### Phase 3: Transaction Processing
**Goal**: Cashier can process sales transactions with automatic stock management
**Depends on:** Phase 1 (authentication), Phase 2 (products must exist to sell)
**Requirements:** TRANS-01, TRANS-02, TRANS-03, TRANS-04, TRANS-05, TRANS-06
**Success Criteria** (what must be TRUE):
  1. Cashier can view product list for selection during transaction
  2. Cashier can add products to cart with specified quantity
  3. Total price calculates automatically (price × quantity)
  4. Multiple items can be added to a single transaction
  5. Stock reduces automatically when transaction is saved
  6. System blocks transaction completion if stock is insufficient
**Plans:** TBD

### Phase 4: Dashboard & Reporting
**Goal**: Admin can view sales analytics and print transaction reports
**Depends on:** Phase 1 (authentication), Phase 3 (transactions must exist to report on)
**Requirements:** DASH-01, DASH-02, DASH-03, DASH-04
**Success Criteria** (what must be TRUE):
  1. Dashboard displays total product count
  2. Dashboard displays total transaction count
  3. Sales chart visualizes daily sales using Chart.js
  4. Admin can print transaction report
**Plans:** TBD

### Phase 5: User Management
**Goal**: Admin can manage system users and assign roles
**Depends on:** Phase 1 (authentication and admin role required)
**Requirements:** USER-01, USER-02, USER-03, USER-04
**Success Criteria** (what must be TRUE):
  1. Admin can view list of all users
  2. Admin can add new user with username, password, and role
  3. Admin can edit user details and change role
  4. Admin can delete users from the system
**Plans:** TBD

## Progress

| Phase | Plans Complete | Status | Completed |
|-------|----------------|--------|-----------|
| 1. Foundation & Authentication | 2/4 | In progress | Plan 01: Database & Middleware, Plan 02: Landing Page & Auth Forms |
| 2. Product Management | 0/0 | Not started | - |
| 3. Transaction Processing | 0/0 | Not started | - |
| 4. Dashboard & Reporting | 0/0 | Not started | - |
| 5. User Management | 0/0 | Not started | - |

---
*Last updated: 2026-02-21 by GSD Executor (Plan 02 complete)*
