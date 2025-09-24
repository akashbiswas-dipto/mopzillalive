<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    // Localhost settings
    $db_host = "localhost";         // usually localhost
    $db_user = "root";              // your local DB user
    $db_pass = "";                  // your local DB password (often empty in XAMPP)
    $db_name = "mopzilla";    // your local database name
} else {
    // Live server settings

    $db_host = "localhost";          // usually localhost for cPanel MySQL
    $db_user = "mopzilla_adminakash"; // your live DB username
    $db_pass = "Mopzilla@Admin"; // your live DB password
    $db_name = "mopzilla_live";       // your live database name
}
$secret_key='m0pz!lla_s3cr3t_k3y_2025';
$secret_key_customer='m0pz!lla_s3cr3t_k3y_C0sT0m5r_2025';

// ✅ Connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}
?>
