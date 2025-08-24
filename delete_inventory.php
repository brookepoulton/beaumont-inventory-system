<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/28/2024
    * Time: 12:00 PM
 */
session_start();
$pageName = "Delete Inventory Item";
require_once 'header.php';

$showform = 1;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $inventoryID = $_POST['inventoryID'];

    // Check if there is a sales record for the item
    $sql = "SELECT COUNT(*) FROM sales_data WHERE FKitemID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $inventoryID, PDO::PARAM_INT);
    $stmt->execute();
    $salesRecordExists = $stmt->fetchColumn() > 0;

    if ($salesRecordExists) {
        // Display error message if sales record exists
        echo "<div style='text-align: center; margin: 30px 0; color: red; font-size: 18px;'>
                <p>Error: You cannot delete an item with an existing sales record. 
                Please mark the item as inactive through the <a href='update_inventory.php?q=" . htmlspecialchars($inventoryID) . "'>Update</a> link.</p>
              </div>";
    } else {
        // If no sales record exists, proceed with deletion
        $sql = "DELETE FROM product_inventory WHERE itemID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $inventoryID, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmation message for successful deletion
        echo "<div style='text-align: center; margin: 30px 0;'><p style='font-size: 20px; color: green;'>Inventory item with name <strong>" . htmlspecialchars($_POST['itemName']) . "</strong> has been successfully deleted.</p></div>";
        $showform = 0;
    }
}

checkLogin(true);

if ($showform == 1 && isset($_GET['q']) && !empty($_GET['q'])) {
    $inventoryID = $_GET['q'];

    // Fetch the item name from the database
    $sql = "SELECT name FROM product_inventory WHERE itemID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $inventoryID, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch();

    // Check if item exists
    if (!$item) {
        echo "<div style='text-align: center;'><p style='color: red;'>Error: Inventory item not found.</p></div>";
        $showform = 0;
    } else {
        $itemName = $item['name'];
        ?>

        <div style="text-align: center; font-size: 20px; margin: 30px 0; color: #dc3545;">
            <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($itemName); ?></strong>?</p>
        </div>

        <form id="delete" method="post" action="<?php echo $currentFile; ?>" style="text-align: center; margin-bottom: 20px;">
            <input type="hidden" name="inventoryID" value="<?php echo htmlspecialchars($inventoryID); ?>">
            <input type="hidden" name="itemName" value="<?php echo htmlspecialchars($itemName); ?>">
            <input type="submit" value="DELETE" style="background-color: #dc3545; color: white; padding: 10px 20px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer;">
        </form>

        <?php
    }
}
?>

<!-- Back to Inventory Management link -->
<div style="text-align: center; margin-top: 20px;">
    <a href="manage_inventory.php" style="display: inline-block; padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;">Back to Inventory Management</a>
</div>

<?php
require_once 'footer.php';
?>





