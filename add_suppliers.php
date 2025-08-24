<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/30/2024
    * Time: 11:18 AM
 */
session_start();
require_once 'header.php';

$showForm = 1;
$errMsg = 0;
$errSupplierName = "";
$errSupplierContact = "";
$errcreateSupplier = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplierName = trim($_POST['supplierName'], ENT_QUOTES);
    $supplierContact = trim($_POST['supplierContact'], ENT_QUOTES);

    // Validate supplier name
    if (empty($supplierName)) {
        $errMsg = 1;
        $errSupplierName = "Missing supplier name";
    } else {
        $sql = "SELECT * FROM suppliers WHERE name = ?";
        $supplierExists = check_duplicates($pdo, $sql, $supplierName);
        if ($supplierExists) {
            $errMsg = 1;
            $errSupplierName = "Supplier name already exists";
        }
    }

    // Validate supplier contact
    if (empty($supplierContact)) {
        $errMsg = 1;
        $errSupplierContact = "Missing supplier contact information";
    }

    // If there are validation errors
    if ($errMsg == 1) {
        $errcreateSupplier = "<p class='error' style='font-size: 20px; text-align: center;'>There are errors. Please make changes and resubmit.</p>";
    } else {
        // Insert into the database
        $sql = "INSERT INTO suppliers (name, contactInfo) VALUES (:name, :contactInfo)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $supplierName);
        $stmt->bindValue(':contactInfo', $supplierContact);

        if ($stmt->execute()) {
            echo "<p class='success'>Supplier Successfully Added</p>";
            $showForm = 0;
        } else {
            echo "<p class='error'>There was an error adding the supplier.</p>";
        }
    }
}
checkLogin(true);
if ($showForm == 1) {
    ?>
    <div class="container" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 50px; max-width: 400px; margin-left: auto; margin-right: auto; margin-bottom: 30px;">
        <h2 style='font-size: 25px; text-align: center;'>Add Supplier</h2>
        <?php if (!empty($errcreateSupplier)) echo $errcreateSupplier; ?>
        <form name="Create Supplier" id="createsupplier" method="post" action="<?php echo $currentFile; ?>">
            <!-- Supplier Name Field -->
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="supplierName" style="font-weight: bold;">Supplier Name:</label>
                <?php if (!empty($errSupplierName)) echo "<span class='error' style='color: red; font-size: 0.9rem; margin-top: 5px;'>$errSupplierName</span>"; ?>
                <input type="text" id="supplierName" name="supplierName" placeholder="Enter supplier name" size="60" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%; box-sizing: border-box;" value="<?php if (isset($supplierName)) { echo htmlspecialchars($supplierName); } ?>">
            </div>

            <!-- Contact Information Field -->
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="supplierContact" style="font-weight: bold;">Contact Information:</label>
                <?php if (!empty($errSupplierContact)) echo "<span class='error' style='color: red; font-size: 0.9rem; margin-top: 5px;'>$errSupplierContact</span>"; ?>
                <input type="text" id="supplierContact" name="supplierContact" placeholder="Enter contact information" size="60" style="border: 1px solid #ced4da; border-radius: 4px; padding: 8px; width: 100%; box-sizing: border-box;" value="<?php if (isset($supplierContact)) { echo htmlspecialchars($supplierContact); } ?>">
            </div>

            <!-- Submit Button -->
            <button type="submit" name="submit" id="submit" style="background-color: #28a745; border: none; width: 100%; padding: 10px; font-size: 16px; border-radius: 4px; color: white;">Create Supplier</button>
        </form>
    </div>

    <?php
}
?>

<?php require_once 'footer.php'; ?>



