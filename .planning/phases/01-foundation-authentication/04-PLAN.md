---
phase: 01-foundation-authentication
plan: 04
type: execute
wave: 2
depends_on: [01, 02]
files_modified: [dashboard/admin/index.php, dashboard/cashier/index.php, includes/sidebar.php, includes/footer.php, profile/index.php]
autonomous: false
requirements: [ROLE-01, ROLE-02, ROLE-03]
user_setup: []

must_haves:
  truths:
    - "Admin sees all sidebar menu items including admin-only features"
    - "Cashier only sees transaction-related sidebar menu items"
    - "Role badge displays in sidebar header showing Admin or Cashier"
    - "Admin dashboard and Cashier dashboard are separate pages"
    - "Protected pages enforce role check server-side"
  artifacts:
    - path: "dashboard/admin/index.php"
      provides: "Admin dashboard home page"
      min_lines: 30
    - path: "dashboard/cashier/index.php"
      provides: "Cashier dashboard home page"
      min_lines: 30
    - path: "includes/sidebar.php"
      provides: "Role-filtered navigation sidebar"
      contains: "role badge, conditional menu items"
    - path: "includes/footer.php"
      provides: "Common footer with scripts"
      contains: "Bootstrap JS, session-manager.js"
    - path: "profile/index.php"
      provides: "Profile page with logout and change password"
      min_lines: 20
  key_links:
    - from: "dashboard/admin/index.php"
      to: "includes/role_check.php"
      via: "require_role('admin') call"
      pattern: "require_role.*admin"
    - from: "dashboard/cashier/index.php"
      to: "includes/auth_check.php"
      via: "auth_check include"
      pattern: "require_once.*auth_check"
    - from: "includes/sidebar.php"
      to: "$_SESSION['user_role']"
      via: "PHP conditional rendering"
      pattern: "if.*role.*===.*admin"
---

<objective>
Build role-based dashboard interfaces with filtered sidebar navigation, role badges, and separate admin/cashier home pages.

Purpose: Implement role-based access control UI that shows appropriate features based on user role while enforcing server-side authorization.

Output: Admin dashboard, Cashier dashboard, role-filtered sidebar component, profile page with logout.
</objective>

<execution_context>
@./.opencode/get-shit-done/workflows/execute-plan.md
@./.opencode/get-shit-done/templates/summary.md
</execution_context>

<context>
@.planning/PROJECT.md
@.planning/ROADMAP.md
@.planning/STATE.md
@.planning/phases/01-foundation-authentication/01-RESEARCH.md
@.planning/phases/01-foundation-authentication/01-CONTEXT.md
@.planning/research/STACK.md
@.planning/phases/01-foundation-authentication/01-01-SUMMARY.md
@.planning/phases/01-foundation-authentication/01-02-SUMMARY.md
</context>

<tasks>

<task type="auto">
  <name>Task 1: Create role-filtered sidebar navigation</name>
  <files>includes/sidebar.php</files>
  <action>
    Create includes/sidebar.php with Bootstrap 5 sidebar layout:
    - Sidebar header: "Mini Cashier" title + role badge (Admin=blue bg-primary, Cashier=green bg-success)
    - Navigation menu with:
      - Dashboard link (all roles)
      - Transactions link (all roles)
      - Products link (admin only - wrap in <?php if ($role === 'admin'): ?>)
      - Reports link (admin only)
      - Users link (admin only - Phase 5 feature, show placeholder)
      - Profile link (all roles)
    - Get role from $_SESSION['user_role'] at top
    - Add active class to current page link
    - Use Bootstrap Icons (CDN) for menu icons
  </action>
  <verify>
    1. Login as admin - should see all menu items including Products, Reports, Users
    2. Login as cashier - should only see Dashboard, Transactions, Profile
  </verify>
  <done>
    Sidebar displays role badge correctly. Admin sees all menu items. Cashier only sees transaction-related items. Role filtering uses server-side PHP conditionals.
  </done>
</task>

<task type="auto">
  <name>Task 2: Create admin dashboard home page</name>
  <files>dashboard/admin/index.php</files>
  <action>
    Create dashboard/admin/index.php with:
    - Require role_check.php and call require_role('admin') at top
    - Include header.php, sidebar.php, footer.php
    - Main content area with:
      - Welcome message: "Welcome, {username}"
      - Role badge display
      - Placeholder widgets: "Total Products", "Total Transactions", "Today's Sales" (will implement in later phases)
      - Quick action buttons for admin features
    
    Use Bootstrap grid (container, row, col) for layout
  </action>
  <verify>
    1. Login as admin - should see admin dashboard with all widgets
    2. Login as cashier and try to access /dashboard/admin/ - should get 403 Access denied
  </verify>
  <done>
    Admin dashboard loads with welcome message and placeholder widgets. Cashier accessing admin dashboard receives 403 error from server-side role check.
  </done>
</task>

<task type="auto">
  <name>Task 3: Create cashier dashboard home page</name>
  <files>dashboard/cashier/index.php</files>
  <action>
    Create dashboard/cashier/index.php with:
    - Require auth_check.php at top (any authenticated user can access)
    - Include header.php, sidebar.php, footer.php
    - Main content area with:
      - Welcome message: "Welcome, {username}"
      - Role badge display
      - Placeholder widgets: "My Transactions Today", "Quick Transaction Button"
      - Task-focused layout for cashier workflow
    
    Use Bootstrap grid for consistent layout with admin dashboard
  </action>
  <verify>
    1. Login as cashier - should see cashier dashboard
    2. Login as admin and try to access /dashboard/cashier/ - should work (admin can access all)
  </verify>
  <done>
    Cashier dashboard loads with transaction-focused widgets. Admin can access both dashboards. Sidebar shows appropriate menu items for each role.
  </done>
</task>

<task type="auto">
  <name>Task 4: Create profile page with logout and change password</name>
  <files>profile/index.php</files>
  <action>
    Create profile/index.php with:
    - Require auth_check.php at top
    - Include header.php, sidebar.php, footer.php
    - Profile section showing:
      - Username
      - Role badge
      - Member since date (from created_at)
    - "Change Password" button linking to auth/change_password.php
    - "Logout" button in header area (as per 01-CONTEXT.md decision)
    - Logout button: form POST to auth/logout.php or direct link
    
    Style as sidebar-based navigation with logout in profile header area
  </action>
  <verify>
    1. Login and navigate to profile page
    2. Should see username, role badge, change password link, logout button
    3. Click logout - should redirect to landing page
  </verify>
  <done>
    Profile page displays user info and role. Logout button accessible from profile header. Change password link works. Both roles can access their profile.
  </done>
</task>

<task type="checkpoint:human-verify" gate="blocking">
  <what-built>Role-based dashboards with filtered sidebar navigation, admin/cashier home pages, profile page with logout</what-built>
  <how-to-verify>
    1. Login as admin:
       - Verify sidebar shows all menu items (Dashboard, Transactions, Products, Reports, Users, Profile)
       - Verify role badge shows "Admin" with blue styling
       - Verify admin dashboard displays with welcome message
    2. Login as cashier:
       - Verify sidebar shows only: Dashboard, Transactions, Profile
       - Verify role badge shows "Cashier" with green styling
       - Verify cashier dashboard displays
    3. Test role enforcement:
       - As cashier, try accessing /dashboard/admin/index.php directly - should get 403
       - As admin, access both dashboards - should work
    4. Test profile page:
       - Click logout - should redirect to landing page
  </how-to-verify>
  <resume-signal>Type "approved" if role-based access works correctly for both roles, or describe any issues</resume-signal>
</task>

</tasks>

<verification>
- [ ] includes/sidebar.php filters menu items based on $_SESSION['user_role']
- [ ] Role badge displays with correct color (Admin=blue, Cashier=green)
- [ ] dashboard/admin/index.php calls require_role('admin')
- [ ] dashboard/cashier/index.php accessible by all authenticated users
- [ ] Admin can access both dashboards, cashier only their own
- [ ] Cashier gets 403 when trying to access admin dashboard
- [ ] Profile page shows user info with logout button
- [ ] Logout works from profile page header
</verification>

<success_criteria>
Role-based access complete when: admin sees all features, cashier sees only transaction features, role badges display correctly, separate dashboards exist, server-side role checks block unauthorized access. Sidebar navigation filters items by role using PHP conditionals.
</success_criteria>

<output>
After completion, create .planning/phases/01-foundation-authentication/01-04-SUMMARY.md with:
- Admin dashboard test results
- Cashier dashboard test results
- Role enforcement test results
- User verification approval status
- Any issues encountered
</output>
