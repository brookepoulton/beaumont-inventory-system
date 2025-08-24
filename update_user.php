<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 11/2/2024
    * Time: 3:40 PM
 */
session_start();
$ID = $_GET['user_id'];
$loggedinuser = $_SESSION['ID'];
$pageName = "Update User Information";
require_once 'header.php';

// Variable initialization
$showForm = 1;
$errMsg = 0;
$duplicate = 0;
$errUsername = "";
$repopdata = 1;
$userID = '';
$username = "";
$adminStatus = 0;
$isActive = 1; // Default is active
$successMessage = "";
$errorMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $userID = $_GET['user_id'];
    $repopdata = 0;
    $username = trim($_POST['username']);
    $adminStatus = isset($_POST['adminStatus']) ? 1 : 0;
    $isActive = isset($_POST['isActive']) ? 1 : 0; // Get the checkbox value
    $newPassword = trim($_POST['password']);

    // Error checking
    if (empty($username)) {
        $errMsg = 1;
        $errUsername = "Missing a username!";
    }

    // Duplicate check
    if ($errMsg == 0) {
        $sql = "SELECT username FROM users WHERE username = :username AND userID != :userID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) {
            $duplicate = 1;
            $errorMessage = "<p style='color: red; text-align: center;'>Username already exists.</p>";
        }
    }

    // Update user information if no errors
    if ($duplicate == 0 && $errMsg == 0) {
        // Prepare to update user information
        $sql = "UPDATE users SET username = :username, adminStatus = :adminStatus, isActive = :isActive";
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
        }
        $sql .= " WHERE userID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':adminStatus', $adminStatus, PDO::PARAM_INT);
        $stmt->bindValue(':isActive', $isActive, PDO::PARAM_INT); // Bind isActive value
        if (!empty($newPassword)) {
            $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        }
        $stmt->bindValue(':ID', $userID, PDO::PARAM_INT);
        $stmt->execute();

        $successMessage = "<p style='color: green; text-align: center; font-size: 28px;'>User information updated successfully!</p>";
        $showForm = 0;
    } else {
        $errorMessage = "<p style='color: red; text-align: center; font-size: 18px;'>There are errors. Please make changes and resubmit.</p>";
    }
}

// Fetch user information if needed
if ($repopdata == 1) {
    $sql = "SELECT userID, username, adminStatus, isActive FROM users WHERE userID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $ID, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        $userID = $row['userID'];
        $username = htmlspecialchars($row['username'], ENT_QUOTES); // Use local $username
        $adminStatus = $row['adminStatus'];
        $isActive = $row['isActive']; // Fetch the current active status
    }
}

checkLogin(true);

if ($showForm == 1) {
?>
    <div class="container" style="background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 50px; max-width: 400px; margin-left: auto; margin-right: auto; margin-bottom: 30px;">
        <h2 style="color: #343a40; text-align: center; margin-bottom: 20px;">Update User</h2>

        <?php
        if (!empty($errorMessage)) {
            echo $errorMessage;
        }

        if (!empty($successMessage)) {
            echo $successMessage;
        }
        ?>

        <form name="Update User" id="updateUser" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?user_id=' . $ID); ?>">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="username" style="font-weight: bold;">Username:</label>
                <?php if ($duplicate == 1) { echo "<p class='error' style='color: red;'>Username already exists.</p>"; } ?>
                <input type="text" id="username" name="username" placeholder="Enter username" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%;" value="<?php echo $username; ?>">
                <?php if (!empty($errUsername)) echo "<span class='error' style='color: red;'>$errUsername</span>"; ?>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="password" style="font-weight: bold;">Password (optional):</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="adminStatus" style="font-weight: bold;">Admin Status:</label>
                <input type="checkbox" id="adminStatus" name="adminStatus" value="1" <?php if ($adminStatus == 1) echo "checked"; ?>>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="isActive" style="font-weight: bold;">Active Status:</label>
                <input type="checkbox" id="isActive" name="isActive" value="1" <?php if ($isActive == 1) echo "checked"; ?>>
            </div>

            <div style="text-align: center;">
                <button type="submit" style="background-color: #007bff; color: white; border: none; border-radius: 4px; padding: 10px 20px; cursor: pointer;">Update User</button>
            </div>
        </form>
    </div>
    <?php
} else {
    echo $successMessage;
}

require_once 'footer.php';
?>
















