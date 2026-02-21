<?php
require_once '../includes/session_config.php';
require_once '../includes/auth_check.php';
require_once '../includes/role_check.php';
require_role('admin');
require_once '../config/database.php';

$result = mysqli_query($conn, "SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC");

echo "<!DOCTYPE html>
<html>
<head>
    <title>Debug Table</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css' rel='stylesheet'>
</head>
<body class='p-5'>
    <h1>Debug: Table Structure</h1>
    <p>Row count: " . mysqli_num_rows($result) . "</p>
    
    <table id='productsTable' class='table table-hover table-striped mb-0'>
        <thead class='table-dark'>
            <tr>";
                echo "<th>No</th>";
                echo "<th>Nama Produk</th>";
                echo "<th>Kategori</th>";
                echo "<th>Harga</th>";
                echo "<th>Stok</th>";
                echo "<th>Unit</th>";
                echo "<th>Status</th>";
                echo "<th>Aksi</th>";
            echo "</tr>
        </thead>
        <tbody>";
            
if (mysqli_num_rows($result) > 0) {
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($row['category'] ?? '-') . "</td>";
        echo "<td><strong>Rp " . number_format($row['price'], 0, ',', '.') . "</strong></td>";
        echo "<td>" . $row['stock'] . "</td>";
        echo "<td>" . htmlspecialchars($row['unit']) . "</td>";
        echo "<td><span class='badge bg-success'>Aktif</span></td>";
        echo "<td><button class='btn btn-danger btn-sm'>Hapus</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8' class='text-center'>No products</td></tr>";
}

echo "    </tbody>
    </table>
    
    <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
    <script src='https://cdn.datatables.net/2.0.0/js/dataTables.js'></script>
    <script src='https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.js'></script>
    
    <script>
    \$(document).ready(function() {
        // Count columns
        const headers = \$('#productsTable thead th').length;
        const firstRow = \$('#productsTable tbody tr:first td').length;
        console.log('Headers:', headers, 'First row cells:', firstRow);
        
        \$('#productsTable').DataTable({
            pageLength: 10
        });
    });
    </script>
</body>
</html>";

mysqli_close($conn);
