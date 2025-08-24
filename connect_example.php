<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 8/24/2025
    * Time: 5:22 PM
 */
// Database connection settings
$dsn = "mysql:host=localhost;dbname=495f4bpoulton;";
$user = "your_username";
$pass = "your_password";

try {
    // Creates a new PDO instance
    $pdo = new PDO($dsn, $user, $pass);

    // Sets the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handling connection error
    echo "Connection failed: " . $e->getMessage();
}
 