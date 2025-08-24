<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/28/2024
    * Time: 12:10 PM
 */
session_start();
require_once 'header.php';

$showForm = 1;
$errMsg = 0;
$errusername = "";
$errpwd = "";
$adminstatus = 0;
$errcreateuser = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(htmlspecialchars($_POST['username'], ENT_QUOTES));
    $pwd = $_POST['password'];
    $adminstatus = isset($_POST['admin']) ? 1 : 0;

    // Validate username
    if (empty($username)) {
        $errMsg = 1;
        $errusername = "Missing username";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $usernameexists = check_duplicates($pdo, $sql, $username);
        if ($usernameexists) {
            $errMsg = 1;
            $errusername = "Username already exists";
        }
    }

    // Validate password
    if (empty($pwd)) {
        $errMsg = 1;
        $errpwd = "Missing a password";
    } elseif (strlen($pwd) < 8) {
        $errMsg = 1;
        $errpwd = "Password must be at least 8 characters!";
    }

    // If there are validation errors
    if ($errMsg == 1) {
        $errcreateuser = "<p class='error' style='font-size: 20px; text-align: center;'>There are errors. Please make changes and resubmit.</p>";
    } else {
        // Hash the password and insert into the database
        $hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, adminstatus) VALUES (:username, :password, :adminstatus)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $hashedpwd);
        $stmt->bindValue(':adminstatus', $adminstatus);

        if ($stmt->execute()) {
            echo "<p class='success'>User Successfully Added</p>";
            $showForm = 0; // Hide form on success
        } else {
            echo "<p class='error'>There was an error adding the user.</p>";
        }
    }
}
checkLogin(true);
if ($showForm == 1) {
    ?>
    <div class="container" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 50px; max-width: 400px; margin-left: auto; margin-right: auto; margin-bottom: 30px;">
        <h2 style='font-size: 25px; text-align: center;'>Add User</h2> <!-- Updated title -->
        <?php if (!empty($errcreateuser)) echo $errcreateuser; ?>
        <form name="Create User" id="createuser" method="post" action="<?php echo $currentFile; ?>">

            <!-- Username Field -->
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="username" style="font-weight: bold;">Username:</label>
                <?php if (!empty($errusername)) echo "<span class='error' style='color: red; font-size: 0.9rem; margin-top: 5px;'>$errusername</span>"; ?>
                <input type="text" id="username" name="username" placeholder="Enter username" size="60" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%; box-sizing: border-box;" value="<?php if (isset($username)) { echo htmlspecialchars($username); } ?>">
            </div>

            <!-- Password Field -->
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="password" style="font-weight: bold;">Password:</label>
                <?php if (!empty($errpwd)) echo "<span class='error' style='color: red; font-size: 0.9rem; margin-top: 5px;'>$errpwd</span>"; ?>
                <input type="password" id="password" name="password" placeholder="Enter password" size="60" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%; box-sizing: border-box;">
            </div>

            <!-- Admin Checkbox -->
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="admin" style="font-weight: bold;">Admin:</label>
                <input type="checkbox" id="admin" name="admin" value="1" style="margin-left: 10px;" <?php if ($adminstatus) echo "checked"; ?>>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="submit" id="submit" style="background-color: #28a745; border: none; width: 100%; padding: 10px; font-size: 16px; border-radius: 4px; color: white;">Create User</button>
        </form>
    </div>

    <?php
}
?>

<?php require_once 'footer.php'; ?>





