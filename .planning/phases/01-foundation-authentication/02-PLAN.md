---
phase: 01-foundation-authentication
plan: 02
type: execute
wave: 1
depends_on: []
files_modified: [index.php, auth/login.php, auth/register.php, assets/js/auth-validation.js, assets/css/style.css]
autonomous: true
requirements: [LAND-01, AUTH-01, AUTH-02]
user_setup: []

must_haves:
  truths:
    - "Landing page displays with split-screen layout (branding left, login form right)"
    - "User can enter username and password in login form"
    - "Login submits to server and validates credentials"
    - "Password is hashed with password_hash() before storage in registration"
    - "Real-time validation shows errors on blur"
  artifacts:
    - path: "index.php"
      provides: "Landing page with split-screen layout"
      min_lines: 50
    - path: "auth/login.php"
      provides: "Login form with validation"
      exports: ["POST handler"]
    - path: "auth/register.php"
      provides: "Registration form with password hashing"
      exports: ["POST handler"]
    - path: "assets/js/auth-validation.js"
      provides: "Client-side validation on blur"
      contains: "blur event listeners"
    - path: "assets/css/style.css"
      provides: "Custom styling for landing page"
      contains: "split-screen layout styles"
  key_links:
    - from: "auth/login.php"
      to: "config/database.php"
      via: "database connection"
      pattern: "require_once.*database"
    - from: "auth/register.php"
      to: "config/database.php"
      via: "database connection"
      pattern: "require_once.*database"
    - from: "auth/login.php"
      to: "includes/session_config.php"
      via: "session initialization"
      pattern: "require_once.*session_config"
---

<objective>
Build landing page and authentication forms (login/register) with modern SaaS split-screen design and secure credential handling.

Purpose: Provide user-facing authentication entry point with professional design and secure password handling.

Output: Landing page with branding, login page with credential validation, registration page with password hashing, client-side validation script.
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
</context>

<tasks>

<task type="auto">
  <name>Task 1: Create landing page with split-screen layout</name>
  <files>index.php, assets/css/style.css</files>
  <action>
    Create index.php with Bootstrap 5 split-screen layout:
    - Left side (col-lg-6): Branding with logo placeholder, "Mini Cashier" title, tagline "Simple POS for Small Business", feature preview bullet list (Products, Transactions, Reports)
    - Right side (col-lg-6): Login form preview with "Login" and "Register" buttons linking to auth/login.php and auth/register.php
    - Light theme: white/light gray background (#f8f9fa), dark text (#212529)
    - Full footer with copyright, docs, privacy, about links
    - Use Bootstrap 5.3.8 CDN for CSS
    
    Create assets/css/style.css with:
    - Split-screen flexbox layout
    - Custom branding styles
    - Professional minimalist SaaS aesthetic
  </action>
  <verify>
    Open http://localhost in browser - should display split-screen landing page with branding on left, login/register links on right
  </verify>
  <done>
    Landing page renders with modern SaaS split-screen design. Branding visible on left, login/register CTAs on right. Footer complete with all links.
  </done>
</task>

<task type="auto">
  <name>Task 2: Create login page with credential validation</name>
  <files>auth/login.php, assets/js/auth-validation.js</files>
  <action>
    Create auth/login.php with:
    - Bootstrap 5 form: username input, password input, login button
    - Link to register.php below form
    - POST handler that:
      - Fetches user from database using prepared statement (SELECT id, username, password, role FROM users WHERE username = ?)
      - Uses password_verify() to check password
      - On success: calls session_regenerate_id(true), sets $_SESSION['user_id'], $_SESSION['username'], $_SESSION['user_role'], $_SESSION['last_activity']
      - Redirects to role-appropriate dashboard (/dashboard/admin/ or /dashboard/cashier/)
      - On failure: shows "Invalid username or password" error
    
    Create assets/js/auth-validation.js with:
    - Real-time validation on blur for username (min 3 chars) and password (min 6 chars)
    - Shows inline error messages below inputs
    - Disables submit button until valid
  </action>
  <verify>
    Test login flow:
    1. Create test user in database with password_hash('test123', PASSWORD_DEFAULT)
    2. Enter credentials - should redirect to dashboard
    3. Enter wrong password - should show error
  </verify>
  <done>
    Login page accepts valid credentials, redirects to correct dashboard by role, rejects invalid credentials with error message. Session regenerated on login.
  </done>
</task>

<task type="auto">
  <name>Task 3: Create registration page with password hashing</name>
  <files>auth/register.php</files>
  <action>
    Create auth/register.php with:
    - Bootstrap 5 form: username input, password input, register button
    - Link to login.php below form
    - POST handler that:
      - Validates username (min 3 chars) and password (min 6 chars)
      - Checks if username exists using prepared statement
      - Hashes password with password_hash($password, PASSWORD_DEFAULT) - uses BCrypt
      - Inserts user with role='cashier' default
      - Redirects to login.php?registered=1 on success
      - Shows validation errors inline
    
    Follow 01-RESEARCH.md "User Registration" pattern exactly
  </action>
  <verify>
    Test registration:
    1. Register new user with username "testuser" and password "test123"
    2. Check database - password should be BCrypt hash starting with $2y$
    3. Try registering duplicate username - should show error
  </verify>
  <done>
    Registration creates user with BCrypt password hash. Duplicate usernames rejected. New users default to cashier role. Redirects to login on success.
  </done>
</task>

</tasks>

<verification>
- [ ] index.php displays split-screen layout with branding and login/register links
- [ ] auth/login.php validates credentials using password_verify() and prepared statements
- [ ] auth/register.php hashes passwords with password_hash() PASSWORD_DEFAULT
- [ ] assets/js/auth-validation.js provides real-time validation on blur
- [ ] Session regenerated with session_regenerate_id(true) on successful login
- [ ] Users redirected to role-appropriate dashboard after login
</verification>

<success_criteria>
Authentication forms complete when: landing page displays properly, login validates credentials securely, registration hashes passwords with BCrypt, client-side validation provides immediate feedback. All forms use Bootstrap 5 styling and prepared statements.
</success_criteria>

<output>
After completion, create .planning/phases/01-foundation-authentication/01-02-SUMMARY.md with:
- Landing page screenshot description
- Login test results (success/failure cases)
- Registration test results (password hash verification)
- Validation behavior confirmation
- Any issues encountered
</output>
