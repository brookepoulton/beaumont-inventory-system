<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/30/2024
    * Time: 11:18 AM
 */
session_start();
$pageName = "View Suppliers";
require_once "header.php";

// Initialize variables
$searchTerm = '';

// Check if search term is set and sanitize input
if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
}

// Prepare SQL query with search criteria if applicable
$sql = "SELECT 
            s.supplierID, 
            s.name AS supplier_name, 
            s.contactInfo 
        FROM 
            suppliers s";

if ($searchTerm) {
    $sql .= " WHERE 
                s.name LIKE :search OR 
                s.contactInfo LIKE :search";
}

$stmt = $pdo->prepare($sql);

if ($searchTerm) {
    $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
}

// Execute query and fetch suppliers
$stmt->execute();
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
checkLogin();
?>

<!-- View Suppliers Page -->

<div class="container">
    <h2 class="page-title"><?php echo htmlspecialchars($pageName); ?></h2>
    <p class="page-subtitle">View and search suppliers for your inventory needs.</p>

    <!-- Search Container -->
    <div class="search-container">
        <h2>Search Suppliers</h2>
        <form name="viewSuppliers" id="viewSuppliers" method="post" action="<?php echo htmlspecialchars($currentFile); ?>">
            <input type="text" name="search" id="search" placeholder="Enter supplier name or contact info" value="<?php echo htmlspecialchars($searchTerm); ?>" required>
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>

    <!-- Suppliers Table -->
    <?php if (!empty($suppliers)): ?>
        <table class="suppliers-table">
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Contact Information</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($supplier['supplier_name']); ?></td>
                        <td><?php echo htmlspecialchars($supplier['contactInfo']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; font-size: 28px; color: red;"><span class='error-message'>No results found for the given search term.</span></p>
    <?php endif; ?>
</div>

<?php require_once "footer.php"; ?>


