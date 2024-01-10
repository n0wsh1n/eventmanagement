<?php
// Database connection
$servername = "Googlr Cloud SQL";
$username = "root";
$password = "hello";
$dbname = "event_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
