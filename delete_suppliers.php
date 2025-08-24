<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/30/2024
    * Time: 11:18 AM
 */
session_start();
$pageName = "Delete Supplier";
require_once 'header.php';

$showform = 1;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $supplierID = $_POST['supplierID'];
        // Prepare and execute delete query
        $sql = "DELETE FROM suppliers WHERE supplierID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $supplierID, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmation message for successful deletion
        echo "<p style='text-align: center; font-size: 20px; color: green;'>Supplier with name <strong>" . htmlspecialchars($_POST['supplierName']) . "</strong> has been successfully deleted.</p>";
        $showform = 0;
}
checkLogin(true);
if ($showform == 1 && isset($_GET['q']) && !empty($_GET['q'])) {
    $supplierID = $_GET['q'];

    // Check for associated inventory items
    $sql = "SELECT COUNT(*) FROM product_inventory WHERE FKsupplierID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $supplierID, PDO::PARAM_INT);
    $stmt->execute();
    $itemCount = $stmt->fetchColumn();

    if ($itemCount > 0) {
        // Show error message if supplier has associated inventory items
        echo "<p style='text-align: center; color: red; font-size: 24px;'>Error: Cannot delete supplier with associated inventory items. Please delete or reassign these items first.</p>";

    } else {
        // Fetch the supplier name from the database
        $sql = "SELECT name FROM suppliers WHERE supplierID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':ID', $supplierID, PDO::PARAM_INT);
        $stmt->execute();
        $supplier = $stmt->fetch();

        // Check if supplier exists
        if (!$supplier) {
            echo "<p style='text-align: center; color: red;'>Error: Supplier not found.</p>";
            $showform = 0;
        } else {
            $supplierName = $supplier['name'];
            ?>

            <div style="text-align: center; font-size: 20px; margin: 30px 0; color: #dc3545;">
                <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($supplierName); ?></strong>?</p>
            </div>


            <div style="text-align: center; margin-bottom: 20px;">
                <form id="delete" method="post" action="<?php echo $currentFile; ?>" style="display: inline-block;">
                    <input type="hidden" name="supplierID" value="<?php echo htmlspecialchars($supplierID); ?>">
                    <input type="hidden" name="supplierName" value="<?php echo htmlspecialchars($supplierName); ?>">
                    <input type="submit" value="DELETE" style="background-color: #dc3545; color: white; padding: 10px 20px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer;">
                </form>
            </div>

            <?php
        }
    }
}
?>


<div style="text-align: center; margin-bottom: 20px;">
    <a href="manage_suppliers.php" style="display: inline-block; padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;">Back to Supplier Management</a>
</div>

<?php
require_once 'footer.php';
?>



