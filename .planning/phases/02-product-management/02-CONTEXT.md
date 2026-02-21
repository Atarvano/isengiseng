# Phase 2: Product Management - Context

**Gathered:** 2026-02-21
**Status:** Ready for planning

<domain>
## Phase Boundary

Admin can manage product inventory with full CRUD operations: view product list, add new products, edit existing products, delete products, and validate stock values (no negative).
</domain>

<decisions>
## Implementation Decisions

### Product list layout
- Table-based layout (not cards)
- Detailed columns: ID, Name, Category, Price, Stock, Last Updated, Actions
- Sorting: click column headers to sort
- Filtering: search/filter bar above table
- Pagination: page numbers with Next/Prev buttons

### Form design
- Separate pages for add/edit (not modal or slide-out)
- Validation: show all errors on submit (not inline)
- After save: redirect back to product list
- Cancel button: prominent "Cancel" button next to Save

### Delete behavior
- Confirmation dialog required before delete
- Warning style: red warning icon, emphasizes irreversibility
- Delete constraint: allow deletion even if product has transactions, but show warning
- Delete button location: in table row Actions column

### Stock display
- Low stock warning: yes, visual indicator
- Warning threshold: Claude's discretion (reasonable default, e.g., 10 items)
- Warning style: badge/icon next to stock number
- Out of stock (0): Claude's discretion (can use same or separate indicator)

### Claude's Discretion
- Exact low stock threshold value (suggest 10)
- Out-of-stock indicator styling (same as low stock or distinct)
- Loading states for table and forms
- Error message wording
- Exact spacing, typography, and color choices

</decisions>

<specifics>
## Specific Ideas

No specific product references or "like X" moments mentioned. Open to standard admin UI patterns.
</specifics>

<deferred>
## Deferred Ideas

None — discussion stayed within phase scope.
</deferred>

---

*Phase: 02-product-management*
*Context gathered: 2026-02-21*
