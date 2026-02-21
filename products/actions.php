<?php
/**
 * Product Actions Handler
 * Mini Cashier Application - Product Management
 * 
 * Handles all CRUD actions for products:
 * - CREATE: Add new product
 * - UPDATE: Edit existing product
 * - DELETE: Remove product from database
 * 
 * Access: Admin only (enforced by auth_check and role_check)
 */

session_start();
require_once '../config/database.php';
require_once '../includes/auth_check.php';
require_once '../includes/role_check.php';
require_role('admin'); // Admin-only access for product actions

// ============================================================================
// DELETE: Remove product from database
// ============================================================================
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    // Sanitize product ID
    $id = intval($_GET['id']);
    
    // Validate ID is positive
    if ($id <= 0) {
        $_SESSION['error'] = "ID produk tidak valid";
        header('Location: index.php');
        exit;
    }
    
    // Optional: Check if product exists before deletion
    $check_stmt = mysqli_prepare($conn, "SELECT id, name FROM products WHERE id = ?");
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    $product = mysqli_fetch_assoc($check_result);
    mysqli_stmt_close($check_stmt);
    
    if (!$product) {
        $_SESSION['error'] = "Produk tidak ditemukan";
        header('Location: index.php');
        exit;
    }
    
    // Future enhancement: Check for transaction dependencies
    // Before allowing deletion, check if product has associated sales:
    // $sales_check = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM sales_items WHERE product_id = ?");
    // This would prevent accidental deletion of products with sales history
    // For Phase 2 scope, we allow deletion but note that soft delete (is_active = 0) is preferred for production
    
    // Delete product using prepared statement
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Produk '" . htmlspecialchars($product['name']) . "' berhasil dihapus";
    } else {
        $_SESSION['error'] = "Gagal menghapus produk: " . mysqli_stmt_error($stmt);
    }
    
    mysqli_stmt_close($stmt);
    header('Location: index.php');
    exit;
}

// ============================================================================
// CREATE: Add new product (placeholder for Plan 03)
// ============================================================================
if (isset($_POST['action']) && $_POST['action'] === 'create') {
    // To be implemented in Plan 03
    $_SESSION['error'] = "Fungsi tambah produk belum diimplementasikan";
    header('Location: create.php');
    exit;
}

// ============================================================================
// UPDATE: Edit product (placeholder for Plan 04)
// ============================================================================
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    // To be implemented in Plan 04
    $_SESSION['error'] = "Fungsi edit produk belum diimplementasikan";
    header('Location: edit.php?id=' . intval($_POST['id']));
    exit;
}

// If no action matched, redirect to product list
header('Location: index.php');
exit;
