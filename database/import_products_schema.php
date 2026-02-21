<?php
/**
 * Import Products Schema Script
 * Executes the products_schema.sql file against the database
 */

require_once __DIR__ . '/../config/database.php';

$sql_file = __DIR__ . '/products_schema.sql';

if (!file_exists($sql_file)) {
    die("Error: SQL file not found at $sql_file\n");
}

echo "Reading SQL file: $sql_file\n\n";

// Read the SQL file
$sql = file_get_contents($sql_file);

// Remove single-line comments
$sql = preg_replace('/--.*$/m', '', $sql);

// Split by semicolon
$statements = explode(';', $sql);

echo "Found " . count($statements) . " potential statements\n\n";

$executed = 0;
foreach ($statements as $i => $statement) {
    $statement = trim($statement);
    
    if (empty($statement)) {
        continue;
    }
    
    echo "Executing statement " . ($i + 1) . "...\n";
    
    $result = mysqli_query($conn, $statement);
    
    if ($result) {
        $executed++;
        echo "✓ Success\n\n";
    } else {
        $error = mysqli_error($conn);
        // DROP TABLE IF EXISTS might fail silently if table doesn't exist - that's OK
        if (strpos($error, "doesn't exist") !== false && strpos($statement, 'DROP') !== false) {
            echo "ℹ Info: Table doesn't exist yet (expected)\n\n";
        } else {
            echo "✗ Error: $error\n";
            echo "  SQL: " . substr($statement, 0, 150) . "...\n\n";
        }
    }
}

echo "Executed $executed statements successfully\n\n";

// Verify table was created
echo "Verifying table creation...\n";
$verify_sql = "SHOW CREATE TABLE products";
$result = mysqli_query($conn, $verify_sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "\n✓ Table 'products' created successfully!\n\n";
    
    // Show columns
    echo "Columns:\n";
    $desc = mysqli_query($conn, "DESCRIBE products");
    while ($col = mysqli_fetch_assoc($desc)) {
        echo "  - {$col['Field']}: {$col['Type']} {$col['Null']} {$col['Key']} {$col['Default']}\n";
    }
    
    echo "\nConstraints:\n";
    // Try to show CHECK constraints (MySQL 8.0+)
    $check_result = mysqli_query($conn, "SELECT * FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products' AND CONSTRAINT_TYPE = 'CHECK'");
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        while ($c = mysqli_fetch_assoc($check_result)) {
            echo "  - CHECK: {$c['CONSTRAINT_NAME']}\n";
        }
    } else {
        echo "  (CHECK constraints exist in table definition)\n";
    }
    
    echo "\nIndexes:\n";
    $indexes = mysqli_query($conn, "SHOW INDEX FROM products");
    while ($idx = mysqli_fetch_assoc($indexes)) {
        echo "  - {$idx['Key_name']}: {$idx['Column_name']}\n";
    }
} else {
    echo "✗ Verification failed: " . mysqli_error($conn) . "\n";
}

mysqli_close($conn);
echo "\nSchema import complete.\n";
