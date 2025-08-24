<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/30/2024
    * Time: 11:19 AM
 */
session_start();
$ID = $_GET['q'];
$loggedinuser = $_SESSION['ID'];
$pageName = "Update Supplier Information";
require_once 'header.php';

// Variable names
$showForm = 1;
$errMsg = 0;
$duplicate = 0;
$errName = "";
$errContactInfo = "";
$repopdata = 1;
$supplierID = '';
$name = "";
$contactInfo = "";
$successMessage = "";
$errorMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $supplierID = $_SESSION['supplierID'];
    $repopdata = 0;
    $name = trim($_POST['name']);
    $contactInfo = trim($_POST['contactInfo']);

    // Error checking
    if (empty($name)) {
        $errMsg = 1;
        $errName = "Missing a name!";
        $repopdata = 0;
    }

    if (empty($contactInfo)) {
        $errMsg = 1;
        $errContactInfo = "Missing contact information!";
        $repopdata = 0;
    }

    if ($errMsg == 1) {
        $errorMessage = "<p style='color: red; text-align: center; font-size: 18px;'>There are errors. Please make changes and resubmit.</p>";
        $repopdata = 0;
    } else {
        // Check for duplicate supplier name
        $sql = "SELECT name FROM suppliers WHERE name = :name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row && ($name != $_SESSION['name'])) {
            $duplicate = 1;
            $errorMessage = "<p style='color: red; text-align: center;'>Supplier name already exists.</p>";
        } else {
            // Update supplier information in the database
            $sql = "UPDATE suppliers SET name = :name, contactInfo = :contactInfo WHERE supplierID = :ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':contactInfo', $contactInfo, PDO::PARAM_STR);
            $stmt->bindValue(':ID', $supplierID, PDO::PARAM_INT);
            $stmt->execute();

            $successMessage = "<p style='color: green; text-align: center; font-size: 28px;'>Supplier information updated successfully!</p>";
            $showForm = 0;
        }
    }
}

// Fetch supplier information if needed
if ($repopdata == 1) {
    $sql = "SELECT supplierID, name, contactInfo FROM suppliers WHERE supplierID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $ID, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        $supplierID = $row['supplierID'];
        $_SESSION['supplierID'] = $row['supplierID'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['contactInfo'] = $row['contactInfo'];
        $name = htmlspecialchars($_SESSION['name'], ENT_QUOTES); // Apply htmlspecialchars() here for display only
        $contactInfo = htmlspecialchars($_SESSION['contactInfo'], ENT_QUOTES); // Apply htmlspecialchars() here for display only
    }
}
checkLogin(true);
if ($showForm == 1) {
    ?>
    <div class="container" style="background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 50px; max-width: 400px; margin-left: auto; margin-right: auto; margin-bottom: 30px;">
        <h2 style="color: #343a40; text-align: center; margin-bottom: 20px;">Update Supplier</h2>

        <?php
        if (!empty($errorMessage)) {
            echo $errorMessage;
        }

        if (!empty($successMessage)) {
            echo $successMessage;
        }
        ?>

        <form name="Update Supplier" id="updateSupplier" method="post" action="<?php echo $currentFile; ?>">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="name" style="font-weight: bold;">Name:</label>
                <?php if ($duplicate == 1 && $name != $_SESSION['name']) { echo "<p class='error' style='color: red;'>Supplier name already exists.</p>"; } ?>
                <input type="text" id="name" name="name" placeholder="Enter supplier name" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%;" value="<?php echo $name; ?>">
                <?php if (!empty($errName)) echo "<span class='error' style='color: red;'>$errName</span>"; ?>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="contactInfo" style="font-weight: bold;">Contact Information:</label>
                <?php if (!empty($errContactInfo)) echo "<span class='error' style='color: red;'>$errContactInfo</span>"; ?>
                <input type="text" id="contactInfo" name="contactInfo" placeholder="Enter contact information" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%;" value="<?php echo $contactInfo; ?>">
            </div>

            <div style="text-align: center;">
                <button type="submit" style="background-color: #007bff; color: white; border: none; border-radius: 4px; padding: 10px 20px; cursor: pointer;">Update Supplier</button>
            </div>
        </form>
    </div>
    <?php
} else {
    echo $successMessage;
}

require_once 'footer.php';
?>











