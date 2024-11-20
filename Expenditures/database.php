<?php
$servername = "localhost";
$username = "root";
$password = "";

// Database and table creation
try {
    // Connect to MySQL
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if it doesn't exist
    $dbName = "Expenditures_App";
    $conn->exec("CREATE DATABASE IF NOT EXISTS $dbName");
    $conn->exec("USE $dbName");

    // Create users table
    $sqlUsers = "CREATE TABLE IF NOT EXISTS users (
        Id INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(255) NOT NULL,
        Username VARCHAR(100) UNIQUE NOT NULL,
        Password VARCHAR(255) NOT NULL,
        Available_Amount FLOAT NOT NULL DEFAULT 0.0
    )";
    $conn->exec($sqlUsers);

    // Create Calculate table
    $sqlCalculate = "CREATE TABLE IF NOT EXISTS Calculate (
        Id INT NOT NULL,
        Food FLOAT NOT NULL DEFAULT 0.0,
        Extra FLOAT NOT NULL DEFAULT 0.0,
        Available_Amount FLOAT NOT NULL DEFAULT 0.0,
        Date DATE NOT NULL,
        FOREIGN KEY (Id) REFERENCES users(Id) ON DELETE CASCADE
    )";

    $conn->exec($sqlCalculate);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn = null;
?>
