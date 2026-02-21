<?php
/**
 * Session Security Configuration
 * 
 * Applies session hardening before starting session.
 * Must be included at the top of every protected page.
 * 
 * @see includes/auth_check.php Authentication middleware that uses this config
 */

// Prevent XSS cookie theft - JavaScript cannot access session cookie
ini_set('session.cookie_httponly', 1);

// Prevent session ID in URLs - cookies only
ini_set('session.use_only_cookies', 1);

// Cookie secure flag - 0 for HTTP (Laragon dev), 1 for production HTTPS
ini_set('session.cookie_secure', 0);

// Prevent session fixation attacks - reject uninitialized session IDs
ini_set('session.use_strict_mode', 1);

// Set SameSite cookie attribute for CSRF protection
ini_set('session.cookie_samesite', 'Strict');

// Start session after all security settings applied
session_start();

// Session timeout constant: 2 hours (7200 seconds)
// Recommended for POS workflow - balances security and usability
define('SESSION_TIMEOUT', 7200);
