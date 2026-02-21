# Phase 1: Foundation & Authentication - Context

**Gathered:** 2026-02-21
**Status:** Ready for planning

<domain>
## Phase Boundary

Users can securely access the system with role-based permissions. This phase delivers: landing page with login/register, session management, and role-based access control (Admin vs Cashier). Password change is included. Creating users (beyond self-registration) is Phase 5.

</domain>

<decisions>
## Implementation Decisions

### Landing Page Design
- Split screen layout: branding/hero on left, login form on right — modern SaaS style
- Branding: Logo + "Mini Cashier" name + tagline ("Simple POS for Small Business")
- Light theme: white/light gray background, dark text — clean, professional
- Feature preview below form: bullet list highlighting core workflows (Products, Transactions, Reports)
- Full footer: copyright, docs, privacy, about links — complete SaaS feel
- Login and register on separate pages linked with basic anchor tags (no framework routing)

### Auth Form Behavior
- Separate pages for login and register with basic links (no router)
- Real-time inline validation: validate on blur, show errors immediately
- No "Remember me" — session only, expires on browser close
- Registration fields: username + password only (minimal, no confirmation, no email)

### Session Experience
- Session uses PHP $_SESSION
- On session expiry: show login modal overlay, then return to current page after re-auth
- 5-minute warning before session expires (modal with option to extend)
- Logout location: in profile page header (sidebar-based navigation, no navbar)

### Role-Based UI
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

</decisions>

<specifics>
## Specific Ideas

- "SaaS modern professional minimalist" aesthetic
- Sidebar-based navigation (not navbar) — logout in profile page header
- Cashier sidebar shows only what they need to do — task-focused, no admin features visible
- Dashboard shows role-specific data but same widget structure

</specifics>

<deferred>
## Deferred Ideas

- None — discussion stayed within phase scope

</deferred>

---

*Phase: 01-foundation-authentication*
*Context gathered: 2026-02-21*
