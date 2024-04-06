<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todolist_php";
// Create connection
$conn = new mysqli($servername, $username, $password , $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection Status: offline " . $conn->connect_error);
}
// echo "Connection Status: online";
?>