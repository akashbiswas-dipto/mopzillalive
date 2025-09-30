<?php
include_once("navbar.php");

if (isset($_SESSION['usertype']) && ($_SESSION['usertype'] === '1' || $_SESSION['usertype'] == 2)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<?php
} else {
    header("Location: " . $base_url . "public/views/team_dashboard/team_dashboard.php");
}
?>