<?php
$servername = "localhost";
$username = "root";
$password = ""; // XAMPP default has no password
$dbname = "knews"; // Same as your remote DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
