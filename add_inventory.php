<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/28/2024
    * Time: 12:00 PM
 */
session_start();
$pageName = "Add Inventory";
$showForm = 1;
$errMsg = 0;
$errName = "";
$errDescription = "";
$errSupplier = "";
$errQuantity = "";
$errPrice = "";
$errCategory = "";

require_once 'header.php';

// Initialize variables
$name = "";
$description = "";
$quantity = "";
$price = "";
$category = "";
$supplier = "";

// Retrieve categories for dropdown from the categories table
$categoryOptions = [];
$categoryStmt = $pdo->prepare("SELECT categoryID, name FROM categories");
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll();
foreach ($categories as $category) {
    $categoryOptions[$category['categoryID']] = $category['name'];
}

// Retrieve suppliers for dropdown
$supplierOptions = [];
$supplierStmt = $pdo->prepare("SELECT supplierID, name FROM suppliers");
$supplierStmt->execute();
$suppliers = $supplierStmt->fetchAll();
foreach ($suppliers as $supplier) {
    $supplierOptions[$supplier['supplierID']] = $supplier['name'];
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize and validate inputs
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $quantity = trim($_POST['quantity']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);
    $supplier = trim($_POST['supplier']);

    // Validation for all fields
    if (empty($name)) {
        $errMsg = 1;
        $errName = "Please enter a name.";
    }

    if (empty($description)) {
        $errMsg = 1;
        $errDescription = "Please enter a description.";
    }

    if (empty($quantity) || !is_numeric($quantity) || $quantity <= 0) {
        $errMsg = 1;
        $errQuantity = "Please enter a valid quantity (greater than 0).";
    } elseif ($quantity > 1000) {
        $errMsg = 1;
        $errQuantity = "Quantity cannot exceed 1000.";
    }

    if (empty($price) || !is_numeric($price) || $price <= 0) {
        $errMsg = 1;
        $errPrice = "Please enter a valid price.";
    } elseif ($price > 5000) {
        $errMsg = 1;
        $errPrice = "Price cannot exceed 5000.";
    }

    if (empty($category) || $category == "select") {
        $errMsg = 1;
        $errCategory = "Please select a category.";
    }

    if (empty($supplier) || $supplier == "select") {
        $errMsg = 1;
        $errSupplier = "Please select a supplier.";
    }

    // Insert into database if no errors
    if ($errMsg === 0) {
        $sql = "INSERT INTO product_inventory (name, description, quantity, price, FKcategoryID, FKsupplierID) VALUES (:name, :description, :quantity, :price, :category, :supplier)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':quantity', $quantity);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':supplier', $supplier);
        $stmt->execute();

        echo "<p style='color: green; text-align: center; font-size: 28px;'>Item added successfully.</p>";

        // Reset variables after success
        $name = "";
        $description = "";
        $quantity = "";
        $price = "";
        $category = "";
        $supplier = "";
    }
}

checkLogin(true);
if ($showForm == 1) {
    ?>
    <div style="max-width: 500px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; background-color: #ffffff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #333;">Add Inventory Item</h2>

        <form name="addInventory" id="addInventory" method="post" action="<?php echo $currentFile; ?>">
            <!-- Name Field -->
            <div style="margin-bottom: 15px;">
                <label for="name" style="display: block; font-weight: bold; color: #333;">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Enter item name" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                <?php if (!empty($errName)) echo "<span class='error' style='color: red;'>$errName</span>"; ?>
            </div>

            <!-- Description Field -->
            <div style="margin-bottom: 15px;">
                <label for="description" style="display: block; font-weight: bold; color: #333;">Description</label>
                <textarea id="description" name="description" placeholder="Enter item description"><?php echo htmlspecialchars($description); ?></textarea>
                <?php if (!empty($errDescription)) echo "<span class='error'>$errDescription</span>"; ?>
            </div>

            <!-- Quantity Field -->
            <div style="margin-bottom: 15px;">
                <label for="quantity" style="display: block; font-weight: bold; color: #333;">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" placeholder="Enter quantity" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                <?php if (!empty($errQuantity)) echo "<span class='error' style='color: red;'>$errQuantity</span>"; ?>
            </div>

            <!-- Price Field -->
            <div style="margin-bottom: 15px;">
                <label for="price" style="display: block; font-weight: bold; color: #333;">Price</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" placeholder="Enter price" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                <?php if (!empty($errPrice)) echo "<span class='error' style='color: red;'>$errPrice</span>"; ?>
            </div>

            <!-- Category Dropdown -->
            <div style="margin-bottom: 15px;">
                <label for="category" style="display: block; font-weight: bold; color: #333;">Category</label>
                <select id="category" name="category" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                    <option value="select" selected>Select a Category</option>
                    <?php foreach ($categoryOptions as $id => $name): ?>
                        <option value="<?php echo $id; ?>" <?php if ($category == $id) echo 'selected'; ?>><?php echo htmlspecialchars($name); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errCategory)) echo "<span class='error' style='color: red;'>$errCategory</span>"; ?>
            </div>

            <!-- Supplier Dropdown -->
            <div style="margin-bottom: 15px;">
                <label for="supplier" style="display: block; font-weight: bold; color: #333;">Supplier</label>
                <select id="supplier" name="supplier" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                    <option value="select" selected>Select a Supplier</option>
                    <?php foreach ($supplierOptions as $id => $name): ?>
                        <option value="<?php echo $id; ?>" <?php if ($supplier == $id) echo 'selected'; ?>><?php echo htmlspecialchars($name); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errSupplier)) echo "<span class='error' style='color: red;'>$errSupplier</span>"; ?>
            </div>

            <!-- Submit Button -->
            <div style="text-align: center;">
                <button type="submit" name="submit" value="addInventory" style="background-color: #007bff; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Add Item</button>
            </div>
        </form>
    </div>

    <?php
}
require_once 'footer.php';
?>














