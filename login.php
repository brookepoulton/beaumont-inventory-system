<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/20/2024
    * Time: 2:22 PM
 */
$pageName = "Login Page";
require_once "header.php";

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set initial variables
$showForm = 1;
$errMsg = 0;
$errUsername = "";
$errPwd = "";
$errLogin = "";
$errForm = "";

// Checks if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize input
    $username = strtolower(trim($_POST['username']));
    $pwd = $_POST['password'];

    // Validation for username and password
    if (empty($username)) {
        $errMsg = 1;
        $errUsername = "Please enter your username";
    }
    if (empty($pwd)) {
        $errMsg = 1;
        $errPwd = "Please enter your password";
    } else {
        if (strlen($pwd) > 72) {
            $errMsg = 1;
            $errPwd = "Password cannot exceed 72 characters!";
        }
    }

    if (!$errMsg) {
        // SQL query to check if the user exists by username
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) {
            // Checks if the account is active
            if ($row['isActive'] == 0) {
                $errLogin = "Your account is inactive. Please contact an administrator.";
            } else {
                // Verify password
                if (password_verify($pwd, $row['password'])) {
                    // Set session variables
                    $_SESSION['userID'] = $row['userID'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['adminstatus'] = $row['adminstatus'];

                    // Redirect based on admin status (1 for Admin, 0 for Regular User)
                    if ($row['adminstatus'] == 1) {
                        header("Location: admin_dashboard.php");
                    } else {
                        header("Location: user_dashboard.php");
                    }

                    $showForm = 0; // Successfully logged in, stop showing the form
                } else {
                    $errLogin = "Username or password is incorrect!";
                }
            }
        } else {
            $errLogin = "Username or password is incorrect!";
        }
    } else {
        // If there are validation errors, set a general error message
        $errForm = "<p class='error' style='text-align: center; font-size: 18px; color: red;'>There are errors. Please make changes and resubmit.</p>";
    }
}

if ($showForm == 1) {
    ?>
    <div style="display: flex; justify-content: center; align-items: center; padding: 30px;">
        <div style="max-width: 500px; width: 100%; padding: 20px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #fff;">
            <h2 style="text-align: center;">Login</h2>
            <?php
            if (!empty($errForm)) echo $errForm;
            if (!empty($errLogin)) echo "<span class='error' style='text-align: center; font-size: 18px; color: red;'>$errLogin</span>";
            ?>
            <form name="Login Form" id="login" method="post" action="<?php echo $currentFile; ?>">
                <label for="username" style="display: block; text-align: left; margin-top: 10px;">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" size="40"
                       style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                <?php if (!empty($errUsername)) echo "<span class='error' style='color: red;'>$errUsername</span>"; ?>

                <label for="password" style="display: block; text-align: left; margin-top: 10px;">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" size="40"
                       style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                <?php if (!empty($errPwd)) echo "<span class='error' style='color: red;'>$errPwd</span>"; ?>

                <input type="submit" name="submit" id="submit-button" value="Submit"
                       style="padding: 10px 20px; margin-top: 15px; background-color: #333; color: #fff; border: none; border-radius: 4px; cursor: pointer; display: block; width: 100%;">
            </form>
        </div>
    </div>
    <?php
}
require_once 'footer.php';
?>



