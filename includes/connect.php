<?php
// Start session for user authentication
session_start();

// Database connection parameters for production server
$host = "172.31.22.43";
$db = "Luke200601722";
$user = "Luke200601722";
$password = "wXoj6vTz6s";

// Establish PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>