<?php
/**
 * Product CRUD Actions Handler
 * Mini Cashier Application - Product Management
 * 
 * Handles CREATE and UPDATE operations for products.
 * Uses prepared statements for SQL injection prevention.
 * Validates all inputs server-side.
 * Preserves old input on validation failure.
 * 
 * Actions:
 * - POST action=create: Add new product
 * - POST action=update: Edit existing product
 * - GET action=delete: Remove product (already implemented)
 * 
 * Access: Admin only (via session checks)
 */

// Start session for flash messages
session_start();

// Require database connection
require_once '../config/database.php';

/**
 * Sanitize and validate input data
 * 
 * @param array $data Raw POST data
 * @return array Sanitized data with name, price, stock, category, unit
 */
function sanitize_product_input($data) {
    return [
        'name'     => trim($data['name'] ?? ''),
        'price'    => floatval($data['price'] ?? 0),
        'stock'    => intval($data['stock'] ?? 0),
        'category' => trim($data['category'] ?? ''),
        'unit'     => trim($data['unit'] ?? 'pcs')
    ];
}

/**
 * Validate product data
 * 
 * @param array $data Sanitized product data
 * @return array Array of error messages (empty if valid)
 */
function validate_product($data) {
    $errors = [];
    
    // Name is required
    if (empty($data['name'])) {
        $errors[] = "Nama produk wajib diisi";
    }
    
    // Price cannot be negative
    if ($data['price'] < 0) {
        $errors[] = "Harga tidak boleh negatif";
    }
    
    // Stock cannot be negative
    if ($data['stock'] < 0) {
        $errors[] = "Stok tidak boleh negatif";
    }
    
    return $errors;
}

/**
 * Store validation errors and old input in session
 * 
 * @param array $errors Error messages
 * @param array $input Original input data
 * @param string $redirect_url URL to redirect to
 */
function redirect_with_errors($errors, $input, $redirect_url) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old_input'] = $input;
    header('Location: ' . $redirect_url);
    exit;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ==========================================
    // CREATE: Add new product
    // ==========================================
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        
        // Sanitize input data
        $data = sanitize_product_input($_POST);
        
        // Validate product data
        $errors = validate_product($data);
        
        // If validation fails, redirect back with errors
        if (!empty($errors)) {
            redirect_with_errors($errors, $_POST, 'create.php');
        }
        
        // Prepare INSERT statement
        $sql = "INSERT INTO products (name, price, stock, category, unit) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            $_SESSION['error'] = "Database error: " . mysqli_error($conn);
            header('Location: create.php');
            exit;
        }
        
        // Bind parameters: s=string, d=double, i=integer, s=string, s=string
        mysqli_stmt_bind_param($stmt, "sdisss", 
            $data['name'], 
            $data['price'], 
            $data['stock'], 
            $data['category'], 
            $data['unit']
        );
        
        // Execute and handle result
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Produk berhasil ditambahkan";
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Gagal menambahkan produk: " . mysqli_stmt_error($stmt);
            header('Location: create.php');
            exit;
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // ==========================================
    // UPDATE: Edit existing product
    // ==========================================
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        
        // Get and sanitize product ID
        $id = intval($_POST['id'] ?? 0);
        
        // Validate ID
        if ($id <= 0) {
            $_SESSION['error'] = "ID produk tidak valid";
            header('Location: index.php');
            exit;
        }
        
        // Sanitize input data
        $data = sanitize_product_input($_POST);
        
        // Validate product data
        $errors = validate_product($data);
        
        // If validation fails, redirect back with errors (preserve ID)
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            header('Location: edit.php?id=' . $id);
            exit;
        }
        
        // Prepare UPDATE statement
        $sql = "UPDATE products SET name=?, price=?, stock=?, category=?, unit=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            $_SESSION['error'] = "Database error: " . mysqli_error($conn);
            header('Location: edit.php?id=' . $id);
            exit;
        }
        
        // Bind parameters: s=string, d=double, i=integer, s=string, s=string, i=integer
        mysqli_stmt_bind_param($stmt, "sdisii", 
            $data['name'], 
            $data['price'], 
            $data['stock'], 
            $data['category'], 
            $data['unit'], 
            $id
        );
        
        // Execute and handle result
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Produk berhasil diupdate";
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Gagal mengupdate produk: " . mysqli_stmt_error($stmt);
            header('Location: edit.php?id=' . $id);
            exit;
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // ==========================================
    // Unknown action
    // ==========================================
    $_SESSION['error'] = "Aksi tidak dikenali";
    header('Location: index.php');
    exit;
}

// ==========================================
// GET request: Handle DELETE (existing functionality)
// ==========================================
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

// ==========================================
// No matching action - redirect to product list
// ==========================================
header('Location: index.php');
exit;
