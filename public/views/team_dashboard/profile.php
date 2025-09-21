<?php
    include_once("navbar.php");
    $userData=getuserDataByID($conn,$_SESSION['team_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Hello- ".$_SESSION['team_username'];?></title>
    <link rel="stylesheet" type="text/css" href="/mopzilla/public/css/profile.css">
</head>
<body>
    <div class="box">      
        <img class="card-img-top" src="../../<?php echo $userData['idpic'];?>" alt="Card image cap">
        <div class="details">
            <h5>Name: <?php echo $userData['full_name'];?></h5>
            <h5>User ID: <?php echo $userData['user_id'];?></h5>
            <h5>Address: <?php echo $userData['address'];?></h5>
            <h5>Contact Number: <?php echo $userData['contact'];?></h5>
            <h5>Birthday: <?php echo $userData['dob'];?></h5>
        </div>
    </div>
</body>
</html>