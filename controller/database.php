<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    $base_url  = "http://localhost/mopzilla/";
    if (!defined('BASE_PATH')) {
        define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']."/mopzilla/");
    }

    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "mopzilla";
} else {
    $base_url  = "https://mop-zilla.com/";
    if (!defined('BASE_PATH')) {
        define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']."/");
    }

    $db_host = "localhost";
    $db_user = "mopzilla_adminakash";
    $db_pass = "Mopzilla@Admin";
    $db_name = "mopzilla_live";
}
$secret_key='m0pz!lla_s3cr3t_k3y_2025';
$secret_key_customer='m0pz!lla_s3cr3t_k3y_C0sT0m5r_2025';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}
?>
