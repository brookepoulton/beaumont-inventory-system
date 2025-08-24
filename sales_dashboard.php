<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 11/2/2024
    * Time: 7:04 PM
 */
session_start();
require_once 'header.php';

$message = '';

// Handle form submission for recording sales
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare to insert sales data
    foreach ($_POST['quantity'] as $itemID => $quantity) {
        if (!empty($quantity) && $quantity > 0) {
            $userID = $_SESSION['userID'];

            // Fetch the current quantity and price of the item
            $sqlProduct = "SELECT price, quantity FROM product_inventory WHERE itemID = :itemID";
            $stmtProduct = $pdo->prepare($sqlProduct);
            $stmtProduct->bindValue(':itemID', $itemID, PDO::PARAM_INT);
            $stmtProduct->execute();
            $product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

            // Ensure product was found and check available quantity
            if ($product) {
                $price = $product['price'];
                $currentQuantity = $product['quantity'];

                // Check if sufficient quantity is available
                if ($currentQuantity >= $quantity) {
                    // Prepare and execute the SQL insert statement with 'sale' type
                    $sqlInsert = "INSERT INTO sales_data (date, quantity_sold, cost, FKItemID, FKUserID, type) 
                                  VALUES (NOW(), :quantity, :price, :itemID, :userID, 'sale')";
                    $stmtInsert = $pdo->prepare($sqlInsert);
                    $stmtInsert->bindValue(':quantity', $quantity, PDO::PARAM_INT);
                    $stmtInsert->bindValue(':price', $price, PDO::PARAM_STR);
                    $stmtInsert->bindValue(':itemID', $itemID, PDO::PARAM_INT);
                    $stmtInsert->bindValue(':userID', $userID, PDO::PARAM_INT);

                    if ($stmtInsert->execute()) {
                        // Update the quantity in table
                        $newQuantity = $currentQuantity - $quantity;
                        $sqlUpdate = "UPDATE product_inventory SET quantity = :newQuantity WHERE itemID = :itemID";
                        $stmtUpdate = $pdo->prepare($sqlUpdate);
                        $stmtUpdate->bindValue(':newQuantity', $newQuantity, PDO::PARAM_INT);
                        $stmtUpdate->bindValue(':itemID', $itemID, PDO::PARAM_INT);
                        $stmtUpdate->execute();

                        $message = 'Sales record added successfully! Quantity updated';
                    } else {
                        $message = "Failed to add sales record.";
                    }
                } else {
                    $message = "Insufficient quantity for product: " . htmlspecialchars($product['product_name']);
                }
            } else {
                $message = "Product not found.";
            }
        }
    }
}

// Fetch all active products with suppliers for the sales form
$sql = "SELECT pi.itemID, pi.name AS product_name, pi.price, s.name AS supplier_name, pi.quantity 
        FROM product_inventory pi 
        JOIN suppliers s ON pi.FKSupplierID = s.supplierID
        WHERE pi.status = 1";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

checkLogin();
?>

<div class="container">
    <h1>Sales Dashboard</h1>

    <h3>Record Sales</h3>
    <?php if ($message): ?>
        <span class="success"><?php echo htmlspecialchars($message); ?></span>
    <?php endif; ?>
    <form name="recordSale" id="recordSale" method="post" action="<?php echo $currentFile; ?>" onsubmit="return confirm('Are you sure you would like to submit?');">
        <table>
            <thead>
            <tr>
                <th>Product</th>
                <th>Supplier</th>
                <th>Price</th>
                <th>Available Quantity</th>
                <th>Quantity Sold</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['supplier_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                    <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    <td>
                        <input type="number" name="quantity[<?php echo $product['itemID']; ?>]" min="0" max="<?php echo $product['quantity']; ?>" value="0" class="quantity-input">
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="submit-btn">Submit Sales</button>
    </form>

    <h3>Sales Records</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Product</th>
            <th>Quantity Sold</th>
            <th>Cost</th>
            <th>Supplier</th>
            <th>User</th>
        </tr>
        <?php
        $salesData = [];
        // Fetch sales records
        $sql = "SELECT sd.salesID, sd.date, sd.quantity_sold, sd.cost, 
               pi.name AS product_name, 
               s.name AS supplier_name, 
               u.username, sd.type
        FROM sales_data sd 
        JOIN product_inventory pi ON sd.FKItemID = pi.itemID 
        JOIN suppliers s ON pi.FKSupplierID = s.supplierID 
        JOIN users u ON sd.FKUserID = u.userID 
        WHERE sd.type = 'sale' 
        ORDER BY sd.date DESC";
        $stmt = $pdo->query($sql);
        $salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php foreach ($salesData as $sale):
            $formattedDate = date('Y-m-d H:i:s', strtotime($sale['date'])); // Format the date to your preferred format
            ?>
            <tr>
                <td><?php echo htmlspecialchars($formattedDate); ?></td> <!-- Use formatted date -->
                <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                <td><?php echo htmlspecialchars($sale['quantity_sold']); ?></td>
                <td><?php echo htmlspecialchars($sale['cost']); ?></td>
                <td><?php echo htmlspecialchars($sale['supplier_name']); ?></td>
                <td><?php echo htmlspecialchars($sale['username']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once 'footer.php'; ?>












