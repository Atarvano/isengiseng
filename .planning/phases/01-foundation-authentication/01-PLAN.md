---
phase: 01-foundation-authentication
plan: 01
type: execute
wave: 1
depends_on: []
files_modified: [config/database.php, includes/auth_check.php, includes/role_check.php, includes/session_config.php, database/schema.sql]
autonomous: true
requirements: [AUTH-02, AUTH-05, ROLE-03]
user_setup:
  - service: mysql
    why: "Database server for storing users and sessions"
    env_vars: []
    dashboard_config:
      - task: "Create database 'mini_cashier'"
        location: "phpMyAdmin or MySQL CLI"

must_haves:
  truths:
    - "Database connection can be established from PHP"
    - "Users table exists with proper schema for authentication"
    - "Session security configuration is applied before session_start"
    - "Auth check middleware redirects unauthenticated users"
    - "Role check middleware blocks unauthorized access server-side"
  artifacts:
    - path: "config/database.php"
      provides: "Database connection using mysqli procedural"
      exports: ["$conn variable"]
    - path: "includes/session_config.php"
      provides: "Session security hardening"
      contains: "session.cookie_httponly, session.use_only_cookies"
    - path: "includes/auth_check.php"
      provides: "Authentication middleware"
      exports: ["session validation logic"]
    - path: "includes/role_check.php"
      provides: "Role authorization middleware"
      exports: ["require_role function"]
    - path: "database/schema.sql"
      provides: "Database schema with users table"
      contains: "CREATE TABLE users"
  key_links:
    - from: "includes/auth_check.php"
      to: "includes/session_config.php"
      via: "require_once include"
      pattern: "require_once.*session_config"
    - from: "includes/role_check.php"
      to: "includes/auth_check.php"
      via: "require_once include"
      pattern: "require_once.*auth_check"
    - from: "config/database.php"
      to: "MySQL database"
      via: "mysqli_connect"
      pattern: "mysqli_connect.*host"
---

<objective>
Establish database foundation and authentication middleware for secure session-based auth with role-based access control.

Purpose: Create the technical foundation (database schema, secure session config, auth middleware) that all authenticated features depend on.

Output: Working database connection, users table with proper schema, session security configuration, authentication middleware, role-based access control middleware.
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
  <name>Task 1: Create database schema and connection config</name>
  <files>config/database.php, database/schema.sql</files>
  <action>
    Create database schema file with users table:
    - id: INT PRIMARY KEY AUTO_INCREMENT
    - username: VARCHAR(50) UNIQUE NOT NULL
    - password: VARCHAR(255) NOT NULL (for BCrypt hash)
    - role: ENUM('admin', 'cashier') DEFAULT 'cashier'
    - created_at: TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
    Create config/database.php using mysqli procedural:
    - Host: localhost, User: root, Pass: '' (Laragon default), DB: mini_cashier
    - Use mysqli_connect() with error handling
    - Set charset to utf8mb4
    - Die with connection error message if fails
    
    Reference: 01-RESEARCH.md "Database Connection" pattern
  </action>
  <verify>
    Run: php -r "require 'config/database.php'; echo mysqli_stat($conn);"
    Should output database connection status without errors
  </verify>
  <done>
    Database schema.sql exists with users table definition. config/database.php establishes working mysqli connection with utf8mb4 charset.
  </done>
</task>

<task type="auto">
  <name>Task 2: Create session security configuration</name>
  <files>includes/session_config.php</files>
  <action>
    Create includes/session_config.php with session hardening:
    - Set ini_set('session.cookie_httponly', 1) - prevents XSS cookie theft
    - Set ini_set('session.use_only_cookies', 1) - prevents session ID in URLs
    - Set ini_set('session.cookie_secure', 0) - 0 for HTTP (Laragon dev), 1 for production HTTPS
    - Set ini_set('session.use_strict_mode', 1) - prevents session fixation
    - Call session_start() after all ini_set calls
    - Set session timeout constant: SESSION_TIMEOUT = 7200 (2 hours)
  </action>
  <verify>
    Create test file that includes session_config.php and var_dump($_SESSION) - should start without errors
  </verify>
  <done>
    session_config.php exists with all security settings applied before session_start(). Session timeout constant defined as 7200 seconds.
  </done>
</task>

<task type="auto">
  <name>Task 3: Create authentication middleware</name>
  <files>includes/auth_check.php</files>
  <action>
    Create includes/auth_check.php that:
    - Requires session_config.php first
    - Checks if $_SESSION['user_id'] is set
    - If not set: stores current $_SERVER['REQUEST_URI'] in $_SESSION['redirect_after_login'], redirects to /auth/login.php
    - Checks session timeout: if time() - $_SESSION['last_activity'] > SESSION_TIMEOUT, destroy session and redirect with timeout=1
    - Updates $_SESSION['last_activity'] = time() on each request
    - Calls exit after redirects to prevent code execution
  </action>
  <verify>
    Create test protected page that includes auth_check.php at top. Access without session - should redirect to login.php
  </verify>
  <done>
    auth_check.php redirects unauthenticated users to login, stores redirect URL, enforces 2-hour session timeout, updates last_activity timestamp.
  </done>
</task>

<task type="auto">
  <name>Task 4: Create role-based access control middleware</name>
  <files>includes/role_check.php</files>
  <action>
    Create includes/role_check.php with require_role($required_role) function:
    - Requires auth_check.php first (ensures user is logged in)
    - Gets user role from $_SESSION['user_role']
    - Compares against $required_role parameter
    - If mismatch: sets http_response_code(403), dies with "Access denied: insufficient permissions"
    - If match: continues execution silently
    
    Follow pattern from 01-RESEARCH.md "Pattern 2: Role-Based Access Control"
  </action>
  <verify>
    Create test admin page that calls require_role('admin'). Access with cashier session - should return 403
  </verify>
  <done>
    role_check.php provides require_role() function that enforces server-side role verification. Blocks unauthorized users with 403 response.
  </done>
</task>

</tasks>

<verification>
- [ ] config/database.php connects to MySQL without errors
- [ ] database/schema.sql contains CREATE TABLE users with id, username, password, role, created_at
- [ ] includes/session_config.php sets cookie_httponly, use_only_cookies, use_strict_mode before session_start
- [ ] includes/auth_check.php redirects unauthenticated users and enforces session timeout
- [ ] includes/role_check.php blocks wrong roles with 403 status
- [ ] All middleware files use require_once for dependencies
</verification>

<success_criteria>
Database foundation complete when: connection works, users table schema defined, session security configured, auth middleware redirects properly, role middleware blocks unauthorized access. All files use mysqli procedural style and follow research patterns.
</success_criteria>

<output>
After completion, create .planning/phases/01-foundation-authentication/01-01-SUMMARY.md with:
- Database connection status
- Users table schema confirmation
- Session security settings applied
- Middleware test results
- Any issues encountered
</output>
