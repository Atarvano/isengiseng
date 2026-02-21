---
phase: 01-foundation-authentication
plan: 03
type: execute
wave: 2
depends_on: [01, 02]
files_modified: [auth/logout.php, auth/change_password.php, auth/extend_session.php, includes/header.php, assets/js/session-manager.js]
autonomous: false
requirements: [AUTH-03, AUTH-04, AUTH-05]
user_setup: []

must_haves:
  truths:
    - "User can logout from any authenticated page"
    - "User can change their password after logging in"
    - "Session persists across page navigation"
    - "Session warning modal appears 5 minutes before expiry"
    - "User can extend session before timeout"
  artifacts:
    - path: "auth/logout.php"
      provides: "Logout handler with session destruction"
      exports: ["session_destroy logic"]
    - path: "auth/change_password.php"
      provides: "Password change form with verification"
      exports: ["POST handler"]
    - path: "includes/header.php"
      provides: "Common header with session check"
      contains: "auth_check include"
    - path: "assets/js/session-manager.js"
      provides: "Session timeout warning and extension"
      contains: "setTimeout for warning and expiry"
  key_links:
    - from: "auth/logout.php"
      to: "includes/session_config.php"
      via: "session initialization"
      pattern: "require_once.*session_config"
    - from: "auth/change_password.php"
      to: "includes/auth_check.php"
      via: "authentication check"
      pattern: "require_once.*auth_check"
    - from: "assets/js/session-manager.js"
      to: "auth/extend_session.php"
      via: "fetch API call"
      pattern: "fetch.*extend_session"
---

<objective>
Implement session management features: logout functionality, password change, session timeout warning with extension capability.

Purpose: Complete authentication lifecycle with secure logout, password management, and user-friendly session handling.

Output: Logout handler, password change page, session extension endpoint, session timeout warning system.
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
  <name>Task 1: Create logout handler and common header</name>
  <files>auth/logout.php, includes/header.php</files>
  <action>
    Create auth/logout.php that:
    - Requires session_config.php
    - Unsets all $_SESSION variables with session_unset()
    - Destroys session with session_destroy()
    - Deletes session cookie with setcookie()
    - Redirects to index.php with ?logged_out=1 parameter
    
    Create includes/header.php that:
    - Requires session_config.php and auth_check.php at top
    - Opens HTML structure with Bootstrap 5 CDN
    - Includes navigation placeholder
    - Will be included by all authenticated pages
  </action>
  <verify>
    1. Login to app
    2. Navigate to auth/logout.php
    3. Should redirect to index.php
    4. Try accessing protected page - should redirect back to login
  </verify>
  <done>
    Logout destroys session completely and redirects to landing page. Header includes auth check on every authenticated page.
  </done>
</task>

<task type="auto">
  <name>Task 2: Create password change functionality</name>
  <files>auth/change_password.php</files>
  <action>
    Create auth/change_password.php with:
    - Requires auth_check.php at top (must be logged in)
    - Form with: current_password, new_password, confirm_password inputs
    - POST handler that:
      - Fetches current user's password hash from database
      - Verifies current_password with password_verify()
      - Validates new_password (min 6 chars) and matches confirm_password
      - Hashes new password with password_hash()
      - Updates database with prepared statement (UPDATE users SET password = ? WHERE id = ?)
      - Shows success or error message
    
    Follow 01-RESEARCH.md "Password Change" pattern
  </action>
  <verify>
    Test password change flow:
    1. Login with test user
    2. Navigate to change_password.php
    3. Enter wrong current password - should error
    4. Enter correct current + new password - should succeed
    5. Logout and login with new password - should work
  </verify>
  <done>
    Password change verifies current password, validates new password, updates hash in database. User can login with new password immediately.
  </done>
</task>

<task type="auto">
  <name>Task 3: Create session timeout warning system</name>
  <files>assets/js/session-manager.js, auth/extend_session.php</files>
  <action>
    Create assets/js/session-manager.js with:
    - SESSION_TIMEOUT = 7200 (2 hours), WARNING_TIME = 300 (5 minutes)
    - startSessionTimers() function that sets:
      - warningTimer: shows Bootstrap modal WARNING_TIME before expiry
      - sessionTimer: redirects to login.php?timeout=1 at expiry
    - Event listeners on mousedown, keydown, scroll, touchstart to reset timers
    - extendSession() function that fetches /auth/extend_session.php and resets timers
    
    Create auth/extend_session.php that:
      - Requires session_config.php and auth_check.php
      - Updates $_SESSION['last_activity'] = time()
      - Returns JSON { success: true }
  </action>
  <verify>
    1. Login and open browser console
    2. Wait for session warning modal (or test with reduced timeout)
    3. Click "Extend Session" - should reset timer
    4. Let timeout expire - should redirect to login
  </verify>
  <done>
    Session warning modal appears 5 minutes before expiry. User can extend session. Auto-logout occurs at timeout. Activity resets timer.
  </done>
</task>

<task type="checkpoint:human-verify" gate="blocking">
  <what-built>Complete session management: logout, password change, session timeout warning with extension</what-built>
  <how-to-verify>
    1. Login with test credentials
    2. Click logout - verify redirect to landing page
    3. Login again, navigate to change password
    4. Test password change with wrong current password (should fail)
    5. Test password change with correct data (should succeed)
    6. Login with new password (should work)
    7. Wait for session warning modal or test with modified timeout
    8. Click "Extend Session" - verify timer resets
  </how-to-verify>
  <resume-signal>Type "approved" if all session features work correctly, or describe any issues</resume-signal>
</task>

</tasks>

<verification>
- [ ] auth/logout.php destroys session and redirects to index.php
- [ ] includes/header.php includes auth_check.php on all authenticated pages
- [ ] auth/change_password.php verifies current password before updating
- [ ] Password change uses password_hash() for new password
- [ ] Session warning modal appears 5 minutes before expiry
- [ ] extend_session.php updates last_activity and returns success JSON
- [ ] Session timeout redirects to login with timeout=1 parameter
</verification>

<success_criteria>
Session management complete when: logout works from any page, password change verifies current password and hashes new password, session timeout warning appears with extension option, session persists across navigation. All session operations use secure patterns from middleware.
</success_criteria>

<output>
After completion, create .planning/phases/01-foundation-authentication/01-03-SUMMARY.md with:
- Logout test results
- Password change test results
- Session timeout behavior
- User verification approval status
- Any issues encountered
</output>
