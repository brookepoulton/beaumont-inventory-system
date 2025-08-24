<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/28/2024
    * Time: 12:10 PM
 */
session_start();
$pageName = "Update Password";
$showForm = 1;
$errMsg = 0;
$errPwd1 = "";
$errPwd2 = "";

require_once 'header.php';
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize and validate passwords
    $pwd1 = trim($_POST['pwd1']);
    $pwd2 = trim($_POST['pwd2']);
    $hashedPwd = password_hash($pwd2, PASSWORD_DEFAULT);

    // Validation for password fields
    if (empty($pwd1)) {
        $errMsg = 1;
        $errPwd1 = "Please enter a password";
    } elseif (strlen($pwd1) < 8) {
        $errMsg = 1;
        $errPwd1 = "Password must be at least 8 characters!";
    } elseif (strlen($pwd1) > 72) {
        $errMsg = 1;
        $errPwd1 = "Password cannot exceed 72 characters!";
    }

    if (empty($pwd2)) {
        $errMsg = 1;
        $errPwd2 = "Please confirm your password";
    } elseif ($pwd1 !== $pwd2) {
        $errMsg = 1;
        $errPwd2 = "Passwords do not match";
    }

    // Ensure the user ID is set and valid
    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];

        // If no errors, proceed with updating the password
        if ($errMsg === 0) {
            $sql = "UPDATE users SET password = :hashedPwd WHERE userID = :userID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':hashedPwd', $hashedPwd);
            $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();

            // Logout the user after password update
            session_unset();
            header("Location: confirm.php?state=1");
            exit;
        }
    } else {
        $errMsg = 1;
        echo "<p class='error'>User ID not found in session. Please log in again.</p>";
    }
}
checkLogin();
if ($showForm == 1) {
?>
    <div style="max-width: 400px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; background-color: #ffffff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #333;">Change Your Password</h2>
        <p style="text-align: center; color: #666;">Once you change your password, you will be logged out and need to log back in.</p>

        <form name="Password change" id="pwdchange" method="post" action="<?php echo $currentFile; ?>">
            <!-- Password Field -->
            <div style="margin-bottom: 15px;">
                <label for="pwd1" style="display: block; font-weight: bold; color: #333;">New Password</label>
                <input type="password" id="pwd1" name="pwd1" placeholder="Enter your new password" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                <?php if (!empty($errPwd1)) echo "<span class='error' style='color: red;'>$errPwd1</span>"; ?>
            </div>

            <!-- Confirm Password Field -->
            <div style="margin-bottom: 15px;">
                <label for="pwd2" style="display: block; font-weight: bold; color: #333;">Confirm Password</label>
                <input type="password" id="pwd2" name="pwd2" placeholder="Confirm your new password" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                <?php if (!empty($errPwd2)) echo "<span class='error' style='color: red;'>$errPwd2</span>"; ?>
            </div>

            <!-- Submit Button -->
            <div style="text-align: center;">
                <button type="submit" style="width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">Update Password</button>
            </div>
        </form>
    </div>

<?php
require_once 'footer.php';
}
?>





