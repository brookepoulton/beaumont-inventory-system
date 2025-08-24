<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 11/2/2024
    * Time: 8:15 PM
 */
session_start();
require_once 'header.php';

$message = '';

// Handle form submission for restocking inventory
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['quantity'] as $itemID => $quantity) {
        if (!empty($quantity) && $quantity >= 0) {
            $userID = $_SESSION['userID'];

            // Fetch the current quantity of the item
            $sqlProduct = "SELECT quantity FROM product_inventory WHERE itemID = :itemID";
            $stmtProduct = $pdo->prepare($sqlProduct);
            $stmtProduct->bindValue(':itemID', $itemID, PDO::PARAM_INT);
            $stmtProduct->execute();
            $product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

            // Ensure product was found
            if ($product) {
                $currentQuantity = $product['quantity'];

                // Prepare and execute the SQL update statement
                $newQuantity = $currentQuantity + $quantity;
                $sqlUpdate = "UPDATE product_inventory SET quantity = :newQuantity WHERE itemID = :itemID";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->bindValue(':newQuantity', $newQuantity, PDO::PARAM_INT);
                $stmtUpdate->bindValue(':itemID', $itemID, PDO::PARAM_INT);

                if ($stmtUpdate->execute()) {
                    // Only log restocks with quantities greater than 0
                    if ($quantity > 0) {
                        $sqlLog = "INSERT INTO sales_data (date, quantity_sold, cost, FKItemID, FKUserID, type) 
                                   VALUES (CURRENT_TIMESTAMP, :quantity, 0, :itemID, :userID, 'restock')";
                        $stmtLog = $pdo->prepare($sqlLog);
                        $stmtLog->bindValue(':quantity', $quantity, PDO::PARAM_INT);
                        $stmtLog->bindValue(':itemID', $itemID, PDO::PARAM_INT);
                        $stmtLog->bindValue(':userID', $userID, PDO::PARAM_INT);
                        $stmtLog->execute();
                    }

                    $message = "Inventory updated successfully!";
                } else {
                    $message = "Failed to update inventory.";
                }
            } else {
                $message = "Product not found.";
            }
        }
    }
}

// Fetch only active products for the restock form
$sql = "SELECT itemID, name AS product_name, quantity 
        FROM product_inventory 
        WHERE status = 1";  // Filter by active status
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
checkLogin();
?>

<div class="container">
    <h1>Restock Inventory</h1>

    <h3>Restock Products</h3>

    <!-- Display message on form validation error -->
    <div id="error-message" class="error-message"></div>

    <?php if ($message): ?>
        <span class="success"><?php echo htmlspecialchars($message); ?></span>
    <?php endif; ?>
    <form name="restockProduct" id="restockProduct" method="post" action="<?php echo $currentFile; ?>" onsubmit="return validateRestockForm() && confirm('Are you sure you would like to submit?');">
        <table class="restock-table">
            <thead>
            <tr>
                <th>Product</th>
                <th>Available Quantity</th>
                <th>Quantity to Add</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    <td>
                        <input type="number" name="quantity[<?php echo $product['itemID']; ?>]" min="0" value="0" class="quantity-input">
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit" class="submit-btn">Submit Restock</button>
    </form>

    <h3>Restock Records</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Product</th>
            <th>Quantity Added</th>
            <th>User</th>
        </tr>
        <?php
        // Fetch restock records from sales_data where type is 'restock'
        $sql = "SELECT sd.date, pi.name AS product_name, sd.quantity_sold AS quantity_added, u.username 
                FROM sales_data sd 
                JOIN product_inventory pi ON sd.FKItemID = pi.itemID 
                JOIN users u ON sd.FKUserID = u.userID 
                WHERE sd.type = 'restock' 
                ORDER BY sd.date DESC";
        $stmt = $pdo->query($sql);
        $restockRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php foreach ($restockRecords as $restock): ?>
            <tr>
                <td><?php echo htmlspecialchars($restock['date']); ?></td>
                <td><?php echo htmlspecialchars($restock['product_name']); ?></td>
                <td><?php echo htmlspecialchars($restock['quantity_added']); ?></td>
                <td><?php echo htmlspecialchars($restock['username']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once 'footer.php'; ?>








