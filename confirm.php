<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/24/2024
    * Time: 10:52 AM
 */
$pageName = 'Confirmation Page';
session_start();
require_once 'header.php';

// Check the state passed via GET parameters
if (isset($_GET['state'])) {
    $state = $_GET['state'];

    if ($state == 1) {
        // Display logged out message
        echo "<p style='font-size: 36px; text-align: center; color: #28a745;'>You have successfully been logged out.</p>";

    } elseif ($state == 2 && isset($_SESSION['username'])) {
        // Welcome back message after login
        $userRole = ($_SESSION['adminstatus'] == 1) ? "Admin" : "User"; // Determine if Admin or User
        echo "<p style='font-size: 36px'>Welcome back, <b>" . htmlspecialchars(ucwords($_SESSION['username'])) . "</b>!</p>";
        echo "<p>You are logged in as <b>" . $userRole . "</b>.</p>";

        // Admin-specific message
        if ($_SESSION['adminstatus'] == 1) {
            echo "<p>As an Admin, you can update, add, or manage the inventory. Head to the <a href='admin_dashboard.php'>Admin Dashboard</a>.</p>";
        } else {
            echo "<p>You can view the inventory and check available items. Visit the <a href='user_dashboard.php'>User Dashboard</a>.</p>";
        }
    } else {
        echo "<p style='font-size: 36px'>Invalid state or session data. Please <a href='login.php'>log in</a>.</p>";
    }
} else {
    echo "<p style='font-size: 36px'>Invalid request. Please <a href='login.php'>log in</a>.</p>";
}

require_once 'footer.php';