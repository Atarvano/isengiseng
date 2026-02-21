<?php
/**
 * Session Extension Endpoint
 * 
 * AJAX endpoint to extend user session timeout.
 * Called by session-manager.js when user clicks "Extend Session".
 * 
 * @link includes/auth_check.php Authentication middleware
 * @link includes/session_config.php Session configuration
 */

// Include authentication check (must be logged in)
require_once __DIR__ . '/../includes/auth_check.php';

// Set JSON response header
header('Content-Type: application/json');

// Update last activity timestamp to extend session
$_SESSION['last_activity'] = time();

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Session extended',
    'timeout' => SESSION_TIMEOUT
]);
