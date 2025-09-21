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
</head>
<body>
    <div class="box">
        <img class="card-img-top" src="../../<?php echo $userData['idpic'];?>" alt="Card image cap">
        
    </div>
</body>
</html>