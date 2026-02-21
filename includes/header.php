<?php
/**
 * Common Header for Authenticated Pages
 * SMK Certification Quality
 * 
 * Includes top navbar with user profile, notifications, and hamburger menu.
 */

// Include authentication check (includes session_config.php)
require_once 'auth_check.php';

// Get user info
$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['user_role'] ?? 'cashier';
$initials = strtoupper(substr($username, 0, 2));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KasirKu - Dashboard</title>
    
    <!-- Google Fonts: Plus Jakarta Sans + Satoshi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Satoshi:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
        
        <!-- Main Content Wrapper -->
        <div class="main-content-wrapper">
            <!-- Top Navbar -->
            <nav class="top-navbar">
                <div class="top-navbar-left">
                    <!-- Mobile Menu Toggle -->
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <!-- Page Title - Dynamic based on current page -->
                    <div>
                        <h5 style="margin: 0; font-family: var(--font-display); font-weight: 700;">
                            <?php 
                            $page_title = basename(dirname($_SERVER['PHP_SELF']));
                            echo ucfirst(str_replace('_', ' ', $page_title));
                            ?>
                        </h5>
                        <small style="color: var(--slate-500); font-size: 0.85rem;">Kelola sistem dengan mudah</small>
                    </div>
                </div>
                
                <div class="top-navbar-right">
                    <!-- Notifications -->
                    <button class="nav-notification" title="Notifikasi">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge"></span>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="user-menu">
                        <div class="user-avatar">
                            <?php echo $initials; ?>
                        </div>
                        <div class="user-info">
                            <span class="user-name"><?php echo htmlspecialchars($username); ?></span>
                            <span class="user-role"><?php echo ucfirst($role); ?></span>
                        </div>
                    </div>
                </div>
            </nav>
