<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/28/2024
    * Time: 11:59 AM
 */
session_start();
$pageName = "Manage Inventory";
require_once "header.php";

// Initialize variables
$searchTerm = '';

// Check if search term is set and sanitize input
if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
}

// Prepare SQL query with search criteria if applicable
$sql = "
    SELECT 
        pi.itemID AS ID, 
        pi.name AS product_name, 
        pi.description, 
        pi.quantity, 
        pi.price, 
        c.name AS category_name, 
        s.name AS supplier_name,
        pi.status
    FROM 
        product_inventory pi
    JOIN 
        categories c ON pi.FKcategoryID = c.categoryID
    JOIN 
        suppliers s ON pi.FKsupplierID = s.supplierID
";

// Add search criteria if a search term is provided
if ($searchTerm) {
    $sql .= " WHERE 
                pi.itemID LIKE :search OR 
                pi.name LIKE :search OR 
                pi.description LIKE :search OR 
                pi.quantity LIKE :search OR 
                pi.price LIKE :search OR 
                c.name LIKE :search OR 
                s.name LIKE :search";
}

// Add sorting by status (active items first)
$sql .= " ORDER BY pi.status DESC";

$stmt = $pdo->prepare($sql);

if ($searchTerm) {
    $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
}

// Execute query and fetch results
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
checkLogin(true);
?>

<div class="container">
    <h2 class="page-title"><?php echo htmlspecialchars($pageName); ?></h2>
    <p class="page-subtitle">Track and manage your inventory items, search by any detail, and update as needed.</p>

    <!-- Actions Section -->
    <section class="actions">
        <a href="add_inventory.php" class="button">Add Inventory</a>
        <a href="restock_inventory.php" class="button">Restock Inventory</a>
    </section>

    <!-- Search Container -->
    <div class="search-container">
        <h2>Search Inventory</h2>
        <form name="searchInventory" id="searchInventory" method="post" action="<?php echo $currentFile; ?>">
            <input type="text" name="search" id="search" placeholder="Enter any detail to search" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <?php if (isset($_SESSION['deleteMessage'])): ?>
            <p style="color: red; display: inline-block;"><?php echo htmlspecialchars($_SESSION['deleteMessage']); ?></p>
            <?php unset($_SESSION['deleteMessage']); // Clear the message after displaying ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($result)): ?>
        <!-- Inventory Table -->
        <table class="users-table">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Category</th>
                <th>Supplier Name</th>
                <th>Item Status</th>
                <th>Options</th>
            </tr>
            <?php foreach ($result as $row) { ?>
                <tr style="background-color: <?php echo ($row['quantity'] < 10) ? 'red' : 'white'; ?>;">
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                    <td>
                        <?php
                        if ($row['status'] == 1) {
                            echo "Active";
                        } else {
                            echo "Inactive";
                        }
                        ?>
                    </td>
                    <td>
                        <a href="update_inventory.php?q=<?php echo $row['ID']; ?>" class="btn btn-update">Update</a>
                        <a href="delete_inventory.php?q=<?php echo $row['ID']; ?>&l=<?php echo htmlspecialchars($row['product_name']); ?>" class="btn btn-delete">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php else: ?>
        <p><span class='error-message'>No results found for the given search term.</span></p>
    <?php endif; ?>
</div>

<?php
require_once "footer.php";
?>













