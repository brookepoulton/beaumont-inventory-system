<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/30/2024
    * Time: 3:16 PM
 */
session_start();
$pageName = "Manage Categories";
require_once 'header.php';
require_once 'connect.php';

$showForm = 1;
$errMsg = 0;
$duplicate = 0;
$errCategory = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $category = trim(htmlspecialchars($_POST['category'], ENT_QUOTES));

    // Error checking
    if (empty($category)) {
        $errMsg = 1;
        $errCategory = "Category name is required!";
    } else {
        // Check if the category already exists in the database
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE name = :category");
        $stmt->execute([':category' => $category]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            $duplicate = 1;
            echo "<div class='error-message'>Category already exists!</div>";
        } else {
            // Insert new category into the database
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:category)");
            $stmt->execute([':category' => $category]);
            echo "<p class='success'>Category has been added!</p>";
        }
    }
}

// Check for deletion
if (isset($_GET['delete'])) {
    $deleteCategoryID = htmlspecialchars($_GET['delete'], ENT_QUOTES);

    try {
        // Attempt to delete the category by ID
        $stmt = $pdo->prepare("DELETE FROM categories WHERE categoryID = :categoryID");
        $stmt->execute([':categoryID' => $deleteCategoryID]);
        echo "<p class='success'>Category has been deleted!</p>";
    } catch (PDOException $e) {
        // Handle the foreign key constraint error
        if ($e->getCode() == 23000) {
            echo "<div class='error-message'>You cannot delete a category that is currently associated with a product.</div>";
        } else {
            echo "<div class='error-message'>An error occurred. Please try again later.</div>";
        }
    }
}

// Retrieve the updated list of categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
checkLogin(true);
if ($showForm == 1) {
    ?>
    <div class="search-container">
        <h2>Add Category</h2>
        <form name="addCategory" id="addCategory" method="post" action="<?php echo $currentFile; ?>">
            <label for="category" class="category-label">Category Name: </label>
            <input type="text" id="category" name="category" placeholder="Enter category name">
            <br>
            <button type="submit" class="styled-submit-btn">Add Category</button>
        </form>
        <?php
        // Display error message if category name is empty
        if ($errMsg == 1) {
            echo "<div class='error-message'>{$errCategory}</div>";
        }
        ?>
    </div>

    <div class="category-list">
        <h3 style="font-size: 28px;">Current Categories:</h3>
        <ul>
            <?php
            // Display existing categories
            foreach ($categories as $category) {
                echo "<li style='font-size: 20px;'>
            " . htmlspecialchars($category['name']) . "
            <a href='?delete=" . htmlspecialchars($category['categoryID']) . "' class='btn-delete' onclick='return confirm(\"Are you sure you want to delete this category?\");'>Delete</a>
          </li>";
            }
            ?>


        </ul>
    </div>
    <?php
}
require_once 'footer.php';
?>








