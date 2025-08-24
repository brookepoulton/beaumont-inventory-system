<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 11/1/2024
    * Time: 3:54 PM
 */
session_start();
$pageName = "Manage Suppliers";
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
        s.supplierID AS ID, 
        s.name AS supplier_name, 
        s.contactInfo 
    FROM 
        suppliers s
";

// Add search criteria if a search term is provided
if ($searchTerm) {
    $sql .= " WHERE 
                s.supplierID LIKE :search OR 
                s.name LIKE :search OR 
                s.contactInfo LIKE :search";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare($sql);
}

// Execute query and fetch results
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
checkLogin(true);
?>

<div class="container">
    <h2 class="page-title"><?php echo htmlspecialchars($pageName); ?></h2>
    <p class="page-subtitle">View, search, and manage suppliers for your inventory needs.</p>


    <!-- Actions Section -->
    <section class="actions">
        <a href="add_suppliers.php" class="button">Add Supplier</a>
    </section>

    <!-- Search Container -->
    <div class="search-container">
        <h2>Search Suppliers</h2>
        <form name="searchSuppliers" id="searchSuppliers" method="post" action="<?php echo $currentFile; ?>">
            <input type="text" name="search" id="search" placeholder="Enter any detail to search" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>

    <?php if (!empty($result)): ?>
        <!-- Suppliers Table -->
        <table class="suppliers-table">
            <tr>
                <th>Supplier Name</th>
                <th>Contact Information</th>
                <th>Options</th>
            </tr>
            <?php foreach ($result as $row) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['contactInfo']); ?></td>
                    <td>
                        <a href="update_suppliers.php?q=<?php echo $row['ID']; ?>" class="btn btn-update">Update</a>
                        <a href="delete_suppliers.php?q=<?php echo $row['ID']; ?>&l=<?php echo htmlspecialchars($row['supplier_name']); ?>" class="btn btn-delete">Delete</a>
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



