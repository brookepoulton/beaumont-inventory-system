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

// Low-stock items query
$lowStockQuery = "SELECT pi.*, s.name AS supplier_name, s.contactInfo, c.name AS category_name 
                  FROM product_inventory pi
                  JOIN suppliers s ON pi.FKsupplierID = s.supplierID 
                  JOIN categories c ON pi.FKcategoryID = c.categoryID  -- Assuming FKcategoryID in product_inventory references categories
                  WHERE pi.quantity < 10";
$lowStockStmt = $pdo->query($lowStockQuery);
$lowStockItems = $lowStockStmt->fetchAll(PDO::FETCH_ASSOC);
checkLogin();
?>

<div class="user-dashboard">
    <h1>Welcome to the Employee Dashboard!</h1>
    <p style="font-size: 20px; font-weight: bold; color: #333; margin-left: 5px;">Here you can record sales, restock inventory, and stay updated.</p>

    <!-- Employee Actions -->
    <section class="actions">
        <a href="view_inventory.php" class="button">View Inventory</a>
        <a href="restock_inventory.php" class="button">Restock Inventory</a>
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

    <!-- Helpful Tips Section -->
    <section class="helpful-tips" style="border: 1px solid #ddd; padding: 20px; margin-top: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #333; font-size: 24px; margin-bottom: 15px; border-bottom: 2px solid #007BFF; padding-bottom: 10px;">Helpful Information</h2>
        <ul style="list-style-type: disc; padding-left: 20px; line-height: 1.6; font-size: 18px; color: #555;">
            <li style="margin-bottom: 10px;">💡 Remember to check inventory levels daily.</li>
            <li style="margin-bottom: 10px;">⚠️ Report any discrepancies in stock to your supervisor.</li>
            <li style="margin-bottom: 10px;">🔄 Follow proper procedures for restocking items.</li>
            <li style="margin-bottom: 10px;">📚 Stay updated with company policies and procedures.</li>
        </ul>
    </section>
</div>

<?php
require_once 'footer.php';
?>




