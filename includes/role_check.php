<?php
/**
 * Role-Based Access Control Middleware
 * 
 * Provides require_role() function for server-side role verification.
 * Must be included on pages that require specific user roles.
 * 
 * Usage:
 *   require_once 'includes/role_check.php';
 *   require_role('admin'); // Blocks non-admin users with 403
 * 
 * @see includes/auth_check.php Authentication check (called first)
 * @link https://www.osohq.com/learn/rbac-role-based-access-control RBAC pattern reference
 */

// Require auth_check first to ensure user is logged in
require_once 'auth_check.php';

/**
 * Require specific role for page access
 * 
 * Blocks users without the required role with HTTP 403 Forbidden.
 * Must be called after user is authenticated.
 * 
 * @param string $required_role The role required to access the page ('admin' or 'cashier')
 * @return void Continues silently if role matches, exits with 403 if not
 */
function require_role($required_role) {
    // Get user role from session
    $user_role = $_SESSION['user_role'] ?? null;
    
    // Compare against required role
    if ($user_role !== $required_role) {
        // Set HTTP 403 Forbidden status
        http_response_code(403);
        
        // Exit with access denied message
        die('Access denied: insufficient permissions');
    }
}
