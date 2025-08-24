<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/30/2024
    * Time: 1:09 PM
 */
function check_duplicates($pdo, $sql, $field) {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $field);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}
//This function checks to see if someone is logged in
function checkLogin($requireAdmin = false)
{
    // Check if user is logged in
    if (!isset($_SESSION['userID'])) {
        echo "<p class='error' style='text-align: center; font-size: 24px; color: red; margin-top: 20px;'>You must be logged in to view this page.</p>";
        require_once "footer.php";
        exit();
    }

    // If admin authentication is required, check for admin status
    if ($requireAdmin && (!isset($_SESSION['adminstatus']) || $_SESSION['adminstatus'] != 1)) {
        echo "<p class='error' style='text-align: center; font-size: 24px; color: red; margin-top: 20px;'>This page requires admin authentication</p>";
        require_once "footer.php";
        exit();
    }
}



