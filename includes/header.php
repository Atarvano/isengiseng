<?php
/**
 * Common Header for Authenticated Pages
 * 
 * Included at the top of every protected page.
 * Handles authentication check and renders HTML header with navigation.
 * 
 * @see includes/session_config.php Session security configuration
 * @see includes/auth_check.php Authentication middleware
 */

// Include authentication check (includes session_config.php)
require_once 'auth_check.php';

// Get user info
$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['user_role'] ?? 'cashier';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KasirKu - Dashboard</title>
    
    <!-- Google Fonts: DM Sans (Display) + Lexend (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;500;600;700&family=Lexend:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Main Content Area -->
        <div class="main-content">
