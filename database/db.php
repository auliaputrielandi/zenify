<?php
$servername = "localhost:3308";
$username = "root"; 
$password = ""; 
$dbname = "zenify";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . " (" . $conn->connect_error . ")");
}

// echo "Connected successfully";
?>
