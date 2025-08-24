<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/24/2024
    * Time: 10:32 AM
 */
// starts session
session_start();

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Include Files
require_once "connect.php";
require_once "functions.php";

// Check if the user is logged in and an admin
$currentFile = basename($_SERVER['SCRIPT_FILENAME']);
$rightNow = time();

$isLoggedIn = isset($_SESSION['userID']);
$isAdmin = isset($_SESSION['adminstatus']) && $_SESSION['adminstatus'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beaumont Jewelry Boutique - <?php echo $pageName; ?></title>
    <link rel="stylesheet" href="styles.css">
    <script src="validation.js"></script>
</head>
<body style="padding: 0px; margin: 0px;">
<header>
    <h1>Beaumont Jewelry Boutique Inventory Management</h1>
    <nav class="navbar">
        <div class="navbar-left">
            <?php if (!$isLoggedIn) { echo "<a href='login.php'>Login</a>"; } ?>
        </div>

        <!-- Center Section: Main Navigation Links -->
        <div class="navbar-center">
            <?php
            if ($isLoggedIn) {
                if ($isAdmin) {
                    echo "<a href='admin_dashboard.php'>Home</a>";
                    echo "<a href='manage_inventory.php'>Manage Inventory</a>";
                    echo "<a href='manage_suppliers.php'>Manage Suppliers</a>";
                    echo "<a href='manage_users.php'>Manage Users</a>";
                    echo "<a href='manage_category.php'>Manage Categories</a>";
                    echo "<a href='sales_dashboard.php'>Sales Dashboard</a>";
                } else {
                    echo "<a href='user_dashboard.php'>Home</a>";
                    echo "<a href='view_inventory.php'>View Inventory</a>";
                    echo "<a href='view_suppliers.php'>View Suppliers</a>";
                    echo "<a href='sales_dashboard.php'>Sales Dashboard</a>";
                }
            }
            ?>
        </div>

        <div class="navbar-right">
            <?php
            if ($isLoggedIn) {
                echo "<a href='updatepassword.php'>Change Password</a>";
                echo "<a href='logout.php'>Logout</a>";
            }
            ?>
        </div>
    </nav>


</header>
</body>
</html>