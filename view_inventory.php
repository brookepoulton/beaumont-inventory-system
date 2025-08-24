<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/28/2024
    * Time: 12:01 PM
 */
session_start();
$pageName = "View Inventory";
require_once "header.php";

// Initialize variables
$searchTerm = '';

// Check if search term is set and sanitize input
if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
}

$sql = "SELECT 
            pi.itemID, 
            pi.name, 
            pi.description, 
            pi.quantity, 
            pi.price,
            pi.status,
            c.name AS category,  
            s.name AS supplier_name,
            s.contactInfo
        FROM 
            product_inventory pi
        JOIN 
            suppliers s ON pi.FKsupplierID = s.supplierID
        JOIN 
            categories c ON pi.FKcategoryID = c.categoryID
        WHERE
            pi.status = 1";

// Add search conditions if search term is provided
if ($searchTerm) {
    $sql .= " AND ( 
                pi.itemID LIKE :search OR 
                pi.name LIKE :search OR 
                pi.description LIKE :search OR 
                pi.quantity LIKE :search OR 
                pi.price LIKE :search OR 
                c.name LIKE :search OR  
                s.name LIKE :search
            )";
}

$stmt = $pdo->prepare($sql);

if ($searchTerm) {
    $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
}

// Execute query and fetch items
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
checkLogin();
?>

<div class="container">
    <h2 class="page-title"><?php echo htmlspecialchars($pageName); ?></h2>
    <p class="page-subtitle">Here you can view the inventory items and search by any detail.</p>

    <!-- Actions Section -->
    <section class="actions">
        <a href="restock_inventory.php" class="button">Restock Inventory</a>
    </section>

    <!-- Search Container -->
    <div class="search-container">
        <h2>Search Inventory</h2>
        <form name="viewInventory" id="viewInventory" method="post" action="<?php echo $currentFile; ?>">
            <input type="text" name="search" id="search" placeholder="Enter any detail to search" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>

    <!-- Inventory Table -->
    <?php if (!empty($items)): ?>
        <table class="suppliers-table">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Category</th>
                <th>Supplier Name</th>
                <th>Contact Information</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr style="background-color: <?php echo ($item['quantity'] < 10) ? 'red' : 'white'; ?>;">
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($item['price']); ?></td>
                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                    <td><?php echo htmlspecialchars($item['supplier_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['contactInfo']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p><span class='error-message'>No results found for the given search term.</span></p>
    <?php endif; ?>
</div>

<?php require_once "footer.php"; ?>




