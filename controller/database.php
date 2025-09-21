<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mopzilla";
$secret_key='m0pz!lla_s3cr3t_k3y_2025';
$secret_key_customer='m0pz!lla_s3cr3t_k3y_C0sT0m5r_2025';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  
}



?>