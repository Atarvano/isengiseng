# Feature Landscape

**Domain:** Mini POS/Cashier System for Small Businesses
**Researched:** 2026-02-21
**Context:** Certification project (Junior Web Programmer) — PHP Native, MySQL, Bootstrap

## Table Stakes

Features users expect. Missing = product feels incomplete.

| Feature | Why Expected | Complexity | Notes |
|---------|--------------|------------|-------|
| **User Authentication** | Basic security requirement; separates cashier/admin access | Low | Session-based auth with password_hash() is standard |
| **Role-Based Access Control** | Cashiers shouldn't access admin functions; admins shouldn't process sales | Low | Two roles (Admin, Cashier) sufficient for this scope |
| **Product Management (CRUD)** | Core POS function — can't sell without products | Low | Create, read, update, delete products with name, price, stock |
| **Transaction Processing** | The primary purpose of a POS system | Medium | Add items to cart, calculate totals, process payment, reduce stock |
| **Sales Receipt/Invoice** | Legal requirement; customers need proof of purchase | Low | Printable receipt with transaction details, date, items, total |
| **Basic Sales Reporting** | Business owners need to see what sold and revenue | Low | Daily/weekly/monthly sales totals, transaction count |
| **Inventory Tracking** | Stock must decrease after sale; prevents overselling | Low | Automatic stock reduction on transaction completion |
| **Dashboard Overview** | At-a-glance business health visibility | Low | Today's sales, total transactions, low stock indicators |
| **Search/Filter Products** | Cashiers need to find products quickly during checkout | Low | Text search by product name, category filter |
| **Cart Management** | Add/remove items before finalizing transaction | Low | Temporary cart session, quantity adjustments |

## Differentiators

Features that set product apart. Not expected, but valued.

| Feature | Value Proposition | Complexity | Notes |
|---------|-------------------|------------|-------|
| **Chart.js Sales Visualization** | Visual trends are easier to understand than raw numbers | Low | Line/bar charts for daily/weekly sales trends |
| **Transaction Print Functionality** | Professional receipts build trust; useful for record-keeping | Low | Browser print dialog with styled receipt template |
| **Password Change Feature** | Security best practice; users expect ability to update credentials | Low | Self-service password update with validation |
| **User Management (Admin)** | Admins can add/remove cashiers without database access | Medium | CRUD for users, role assignment, status toggle |
| **Low Stock Alerts** | Prevents stockouts; proactive inventory management | Low | Visual indicator on dashboard when stock < threshold |
| **Sales Analytics Dashboard** | Data-driven decisions (top products, peak hours) | Medium | Best sellers, hourly sales distribution, revenue breakdown |
| **Transaction History Lookup** | Dispute resolution, customer inquiries, auditing | Low | Searchable transaction log with filter by date/status |
| **Category Management** | Better organization for stores with diverse products | Low | Product categories for filtering and reporting |

## Anti-Features

Features to explicitly NOT build for this certification scope.

| Anti-Feature | Why Avoid | What to Do Instead |
|--------------|-----------|-------------------|
| **Payment Gateway Integration** | Out of certification scope; adds unnecessary complexity | Use cash-only transactions; mark as "paid" without external API |
| **Barcode Scanning** | Requires hardware integration; manual selection is sufficient | Implement quick search and product lookup by name |
| **Multi-Store Support** | Single-store context; adds database complexity | Design for single location; can extend later if needed |
| **Customer Management/CRM** | Focus is on transactions, not customer relationships | Skip customer profiles, loyalty programs, contact tracking |
| **Inventory Alerts/Notifications** | Basic stock tracking is enough for certification | Show low stock on dashboard; skip email/SMS notifications |
| **Employee Time Tracking** | Not part of competency units; scope creep | Focus on transaction and inventory features only |
| **Loyalty Programs** | Adds complexity without core value for small POS | Skip points, rewards, tier systems |
| **Third-Party Integrations** | PHP Native scope; no external API integrations | Keep system self-contained; no QuickBooks, delivery apps |
| **Mobile POS Compatibility** | Desktop-first certification; responsive is enough | Use Bootstrap for responsiveness; don't build native mobile app |
| **Offline Mode** | Local development environment; always online | Assume stable connection; skip local storage sync logic |
| **Multi-Currency Support** | Single currency (IDR) for certification | Hardcode currency format; skip exchange rate logic |
| **Tax Configuration** | Adds complexity; not in competency requirements | Include tax in product price; skip tax calculation logic |
| **Discount/Promotion Management** | Scope creep; complicates transaction logic | Skip percentage discounts, coupon codes, happy hour pricing |
| **Gift Card Integration** | Not core to basic POS functionality | Skip gift card issuance, redemption, balance tracking |
| **Kitchen Display System** | Retail-focused, not restaurant POS | Skip order routing, kitchen tickets, course management |

## Feature Dependencies

```
User Authentication → Role-Based Access Control (roles require authenticated users)
Product Management → Transaction Processing (can't sell without products)
Transaction Processing → Inventory Tracking (stock reduction happens after sale)
Transaction Processing → Sales Receipt (receipt generated from transaction data)
Sales Reporting → Dashboard Overview (reports feed dashboard metrics)
User Management → Role-Based Access Control (admin manages role assignments)
Product Management → Category Management (categories organize products)
Sales Reporting → Chart.js Visualization (charts display report data)
```

## MVP Recommendation

**Prioritize (Phase 1):**
1. **User Authentication + Role-Based Access** — Foundation for all other features
2. **Product Management (CRUD)** — Can't process transactions without products
3. **Transaction Processing** — Core value proposition of the POS system
4. **Basic Sales Reporting** — Provides immediate business value
5. **Dashboard Overview** — Gives users at-a-glance visibility

**Prioritize (Phase 2):**
6. **Chart.js Sales Visualization** — Enhances reporting with visual insights
7. **Transaction Print Functionality** — Professional receipt generation
8. **User Management (Admin)** — Complete the admin feature set
9. **Password Change Feature** — Security best practice

**Defer (if time-constrained):**
- **Sales Analytics Dashboard** — Nice to have; basic reporting is sufficient
- **Low Stock Alerts** — Can be added after core features work
- **Transaction History Lookup** — Basic reporting covers most use cases
- **Category Management** — Can use simple product list initially

## Feature Complexity Summary

| Complexity | Count | Features |
|------------|-------|----------|
| **Low** | 15 | Auth, RBAC, Product CRUD, Receipt, Basic Reports, Inventory, Dashboard, Search, Cart, Print, Password Change, Low Stock Alerts, Transaction History, Category Management |
| **Medium** | 3 | Transaction Processing, User Management, Sales Analytics |
| **High** | 0 | (None in scope) |

## Sources

- **The Retail Exec** — "The 12 Most Important POS Features Your Point-of-Sale Must Have in 2026" (Jan 2026) — [URL](https://theretailexec.com/payment-processing/pos-features/)
- **Quantic POS** — "44 Restaurant POS Features You Shouldn't Miss in 2026" (Jan 2026) — [URL](https://getquantic.com/restaurant-pos-system-features/)
- **Electronic Payments** — "The Best POS System Features for 2026" (Dec 2025) — [URL](https://electronicpayments.com/blog/key-pos-features-of-the-year/)
- **Forbes Advisor** — "Best POS Systems For Small Business Of 2026" (Jan 2026) — [URL](https://www.forbes.com/advisor/business/software/best-pos-system-for-small-business/)
- **TechRadar** — "Best POS system of 2026" (Feb 2026) — [URL](https://www.techradar.com/news/the-best-pos-system)
- **PROJECT.md** — Greenfield Cashier Application certification requirements

## Confidence Assessment

| Area | Confidence | Reason |
|------|------------|--------|
| Table Stakes | HIGH | Verified across multiple 2026 sources; aligns with certification requirements |
| Differentiators | HIGH | Features explicitly listed in PROJECT.md; common in small business POS |
| Anti-Features | HIGH | Explicitly marked "Out of Scope" in PROJECT.md; verified with industry standards |
| Dependencies | HIGH | Logical flow based on database schema requirements |
| MVP Recommendation | MEDIUM | Based on certification competency units; may need adjustment based on time |
