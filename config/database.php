<?php
/**
 * Database Connection Configuration
 * 
 * Establishes MySQL connection using mysqli procedural style.
 * Used throughout the Mini Cashier application for database operations.
 * 
 * @link C:\laragon\www\QwenSertifikasi\database\schema.sql Database schema
 */

// Database configuration (Laragon defaults)
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'mini_cashier';

// Establish connection using mysqli procedural
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection and die with error message if fails
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Set charset to utf8mb4 for proper Unicode support
mysqli_set_charset($conn, 'utf8mb4');
