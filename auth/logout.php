<?php
/**
 * Logout Handler
 * 
 * Destroys user session and redirects to landing page.
 * Called when user clicks logout from any authenticated page.
 * 
 * Behavior:
 * - Requires session configuration
 * - Unsets all session variables
 * - Destroys session completely
 * - Deletes session cookie
 * - Redirects to index.php with logged_out parameter
 */

// Include session config to access current session
require_once '../includes/session_config.php';

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Delete the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to landing page with logged_out indicator
header('Location: /index.php?logged_out=1');
exit;
