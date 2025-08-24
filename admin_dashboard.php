<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/28/2024
    * Time: 11:58 AM
 */
session_start();
require_once 'header.php';
require_once 'connect.php';

// Gets total number of items
$totalItemsQuery = "SELECT COUNT(*) as total FROM product_inventory";
$totalItemsStmt = $pdo->query($totalItemsQuery);
$totalItems = $totalItemsStmt->fetchColumn();

// Low-stock items query
$lowStockQuery = "SELECT pi.*, s.name AS supplier_name, s.contactInfo, c.name AS category_name 
                  FROM product_inventory pi
                  JOIN suppliers s ON pi.FKsupplierID = s.supplierID 
                  JOIN categories c ON pi.FKcategoryID = c.categoryID  -- Assuming FKcategoryID in product_inventory references categories
                  WHERE pi.quantity < 10";
$lowStockStmt = $pdo->query($lowStockQuery);
$lowStockItems = $lowStockStmt->fetchAll(PDO::FETCH_ASSOC);
checkLogin(true);
?>

<div class="admin-dashboard">
    <h1>Welcome, Admin!</h1>

    <!-- Inventory Overview -->
    <section class="overview">
        <div class="overview-box">
            <h2>Total Inventory Items</h2>
            <p><?php echo htmlspecialchars($totalItems); ?> items</p>
        </div>
        <div class="overview-box">
            <h2>Low Stock Alerts</h2>
            <p><?php echo count($lowStockItems); ?> items need restocking</p>
        </div>
    </section>

    <!-- Actions -->
    <section class="actions">
        <a href="add_inventory.php" class="button">Add New Product</a>
        <a href="manage_inventory.php" class="button">View Full Inventory</a>
    </section>

    <!-- Low Stock Alerts Section -->
    <?php if (!empty($lowStockItems)): ?>
        <section class="low-stock">
            <h2>Low Stock Alerts</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Supplier Name</th>
                    <th>Contact Information</th>
                </tr>
                <?php foreach ($lowStockItems as $item): ?>
                    <tr style="background-color: #ffcccc;">
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($item['category_name']); ?></td> <!-- Display new Category Name -->
                        <td><?php echo htmlspecialchars($item['supplier_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['contactInfo']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
    <?php else: ?>
        <p class="success">All items are sufficiently stocked.</p>
    <?php endif; ?>
</div>

<?php
require_once 'footer.php';
?>


