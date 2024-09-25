<?php
$host = 'localhost';
$user = 'root'; // default user
$password = ''; // default password
$dbname = 'galerydb';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
