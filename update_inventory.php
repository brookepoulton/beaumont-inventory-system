<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/28/2024
    * Time: 12:00 PM
 */
session_start();
$ID = $_GET['q'];
$loggedinuser = $_SESSION['ID'];
$pageName = "Update Inventory Item";
require_once 'header.php';

// Variable names
$showForm = 1;
$errMsg = 0;
$duplicate = 0;
$errName = "";
$errQuantity = "";
$errDescription = "";
$errPrice = "";
$repopdata = 1;
$inventoryID = '';
$name = "";
$description = "";
$quantity = "";
$price = "";
$categoryID = "";
$supplierID = "";
$status = "";
$successMessage = "";
$errorMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $inventoryID = $_SESSION['inventoryID'];
    $repopdata = 0;
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $quantity = trim($_POST['quantity']);
    $price = trim($_POST['price']);
    $categoryID = trim($_POST['FKCategoryID']);
    $supplierID = trim($_POST['FKSupplierID']);
    $status = isset($_POST['status']) ? 1 : 0;

    // Error checking
    if (empty($name)) {
        $errMsg = 1;
        $errName = "Missing item name!";
    }
    if (empty($description)) {
        $errMsg = 1;
        $errDescription = "Missing item description!";
    }
    if (empty($quantity)) {
        $errMsg = 1;
        $errQuantity = "Missing quantity!";
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $errMsg = 1;
        $errQuantity = "Quantity must be a non-negative number!";
    } elseif ($quantity > 1000) {
        $errMsg = 1;
        $errQuantity = "Quantity cannot exceed 1000!";
    }
    if (empty($price)) {
        $errMsg = 1;
        $errPrice = "Missing price!";
    } elseif (!is_numeric($price) || $price < 0) {
        $errMsg = 1;
        $errPrice = "Price must be a non-negative number!";
    } elseif ($price > 5000) {
        $errMsg = 1;
        $errPrice = "Price cannot exceed 5000!";
    }

    if ($errMsg == 0) {
        // Check for duplicate name
        $sql = "SELECT name FROM product_inventory WHERE name = :name AND itemID != :currentID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':currentID', $inventoryID, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) {
            $duplicate = 1;
            $errorMessage = "<p style='color: red; text-align: center;'>Item name already exists.</p>";
        } else {
            // Update inventory information
            $sql = "UPDATE product_inventory SET 
                    name = :name, 
                    description = :description, 
                    quantity = :quantity, 
                    price = :price, 
                    FKCategoryID = :FKCategoryID, 
                    FKSupplierID = :FKSupplierID,
                    status = :status
                WHERE itemID = :ID";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindValue(':price', $price, PDO::PARAM_STR);
            $stmt->bindValue(':FKCategoryID', $categoryID, PDO::PARAM_INT);
            $stmt->bindValue(':FKSupplierID', $supplierID, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
            $stmt->bindValue(':ID', $inventoryID, PDO::PARAM_INT);
            $stmt->execute();

            // Success message
            $successMessage = "<p style='color: green; text-align: center; font-size: 28px;'>Inventory item updated successfully!</p>";
            $showForm = 0;
        }
    }
}

// Fetch inventory item information if needed
if ($repopdata == 1) {
    $sql = "SELECT itemID, name, description, quantity, price, FKCategoryID, FKSupplierID, status FROM product_inventory WHERE itemID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $ID);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        $inventoryID = $row['itemID'];
        $_SESSION['inventoryID'] = $row['itemID'];
        $name = $row['name'];
        $description = $row['description'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $categoryID = $row['FKCategoryID'];
        $supplierID = $row['FKSupplierID'];
        $status = $row['status'];
    }
}

// Fetch categories and suppliers for dropdowns
$sqlCategories = "SELECT categoryID, name FROM categories";
$stmtCategories = $pdo->prepare($sqlCategories);
$stmtCategories->execute();
$categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

$sqlSuppliers = "SELECT supplierID, name FROM suppliers";
$stmtSuppliers = $pdo->prepare($sqlSuppliers);
$stmtSuppliers->execute();
$suppliers = $stmtSuppliers->fetchAll(PDO::FETCH_ASSOC);

checkLogin(true);
if ($showForm == 1) {
    ?>
    <div class="container" style="background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 50px; margin-bottom: 50px; max-width: 500px; margin-left: auto; margin-right: auto;">
        <h2 style="color: #343a40; text-align: center; margin-bottom: 20px;">Update Inventory Item</h2>
        <?php
        if (!empty($errorMessage)) {
            echo $errorMessage;
        }
        if (!empty($successMessage)) {
            echo $successMessage; // Success message display
        }
        ?>
        <form name="Update Inventory" id="updateInventory" method="post" action="<?php echo $currentFile; ?>">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="name" style="font-weight: bold;">Item Name:</label>
                <?php if ($duplicate == 1 && $name != $_SESSION['name']) { echo "<p class='error'>Item name already exists.</p>"; } ?>
                <input type="text" id="name" name="name" placeholder="Enter item name" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%;" value="<?php echo htmlspecialchars($name); ?>">
                <?php if (!empty($errName)) echo "<span class='error'>$errName</span>"; ?>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="description" style="font-weight: bold;">Description:</label>
                <textarea id="description" name="description" placeholder="Enter item description" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%;"><?php echo htmlspecialchars($description); ?></textarea>
                <?php if (!empty($errDescription)) echo "<span class='error'>$errDescription</span>"; ?>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="quantity" style="font-weight: bold;">Quantity:</label>
                <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%;" value="<?php echo htmlspecialchars($quantity); ?>">
                <?php if (!empty($errQuantity)) echo "<span class='error'>$errQuantity</span>"; ?>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="price" style="font-weight: bold;">Price:</label>
                <input type="text" id="price" name="price" placeholder="Enter price" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%;" value="<?php echo htmlspecialchars($price); ?>">
                <?php if (!empty($errPrice)) echo "<span class='error'>$errPrice</span>"; ?>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="FKCategoryID" style="font-weight: bold;">Category:</label>
                <select id="FKCategoryID" name="FKCategoryID" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%;">
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['categoryID']; ?>" <?php if ($category['categoryID'] == $categoryID) echo 'selected'; ?>><?php echo $category['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="FKSupplierID" style="font-weight: bold;">Supplier:</label>
                <select id="FKSupplierID" name="FKSupplierID" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%;">
                    <?php foreach ($suppliers as $supplier) { ?>
                        <option value="<?php echo $supplier['supplierID']; ?>" <?php if ($supplier['supplierID'] == $supplierID) echo 'selected'; ?>><?php echo $supplier['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="status" style="font-weight: bold;">Active:</label>
                <input type="checkbox" id="status" name="status" <?php if ($status == 1) echo 'checked'; ?>>
            </div>

            <div class="form-group" style="text-align: center;">
                <input type="submit" value="Update Item" class="btn btn-primary" style="border: none; background-color: #007bff; color: white; padding: 10px 20px; border-radius: 4px;">
            </div>
        </form>
    </div>
    <?php
} else {
    echo $successMessage;
}
?>

<?php require_once 'footer.php'; ?>












