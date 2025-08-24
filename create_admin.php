<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/28/2024
    * Time: 2:00 PM
 */

require_once 'connect.php';

// User data for the admin account
$username = 'admin_test';
$password = 'admintestpassword';
$adminStatus = 1; // Admin status

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL statement
$sql = "INSERT INTO users (username, password, adminstatus) VALUES (:username, :password, :adminstatus)";
$stmt = $pdo->prepare($sql);

// Bind parameters and execute
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashedPassword);
$stmt->bindParam(':adminstatus', $adminStatus);

try {
    $stmt->execute();
    echo "Admin user created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

