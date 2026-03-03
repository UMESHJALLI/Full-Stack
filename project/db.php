<?php
// Database configuration - UPDATED WITH CORRECT DATABASE NAME
$dbServer = "sql305.infinityfree.com"; // Your InfinityFree MySQL host
$dbName = "if0_41282970_exam_portal";  // Your database name from phpMyAdmin
$dbUser = "if0_41282970";              // Your database username
$dbPass = "Q4moolrsvG0";     // IMPORTANT: Enter the password you set

// Create connection
$conn = new mysqli($dbServer, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");

// Start session for user management
session_start();

// Optional: Uncomment to test connection
// echo "Database connected successfully!";
?>