<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smartsphere";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "<script>console.log('connection successfull');</script>";
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS smartsphere";
if ($conn->query($sql) === TRUE) {
    echo "<script>console.log('Database created successfully');</script>";
} else {
    echo "<script>console.log('Error creating database: " . addslashes($conn->error) . "');</script>";
}

// Select the newly created database
$conn->select_db($dbname);
