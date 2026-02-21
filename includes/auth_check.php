<?php
/**
 * Authentication Middleware
 * 
 * Validates user authentication status on every protected page.
 * Must be included at the top of pages requiring login.
 * 
 * Behavior:
 * - Redirects unauthenticated users to login page
 * - Stores original URL for post-login redirect
 * - Enforces session timeout (2 hours)
 * - Updates last activity timestamp
 * 
 * @see includes/session_config.php Session security configuration
 * @see includes/role_check.php Role-based access control
 */

// Include session config first (starts session with security settings)
require_once 'session_config.php';

// Check if user is authenticated (user_id set in session)
if (!isset($_SESSION['user_id'])) {
    // Store current page URL for redirect after successful login
    // Use REQUEST_URI if available (web), otherwise default to current script
    $current_uri = $_SERVER['REQUEST_URI'] ?? $_SERVER['SCRIPT_NAME'] ?? '/';
    $_SESSION['redirect_after_login'] = $current_uri;
    
    // Redirect to login page
    header('Location: /auth/login.php');
    exit;
}

// Check session timeout
// If last activity was more than SESSION_TIMEOUT seconds ago, force re-login
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
    // Session expired - destroy session data
    session_unset();
    session_destroy();
    
    // Redirect to login with timeout indicator
    header('Location: /auth/login.php?timeout=1');
    exit;
}

// Update last activity timestamp for timeout tracking
$_SESSION['last_activity'] = time();
