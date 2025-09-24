<?php
    include_once("navbar.php");
    if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === '1'){

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>public/css/teamlist.css">
    <title>Team List- Mopzilla</title>
</head>
<body>

    <div class="box">        
            <div class="button_add">
                <a href="addteammember.php" class="addmemberbutton">Add Team Member</a>
            </div>
            <div class="list_team">
                <div class="row">
                    <?php if($userData && count($userData) > 0):
                    foreach($userData as $user):?>
                    <div  class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-4">
                        <div class="card" style="width: 18rem;">
                            <img class="card-img-top" src="<?php echo $base_url;?>public/<?php echo $user['idpic'];?>" alt="Card image cap">
                            <ul class="list-group list-group-flush">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $user['full_name']; ?></h5>
                                    <p class="card-text"><?php echo $user['user_id']; ?></p>
                                    <p class="card-text" style="font-weight: 600 !important;"><?php if($user['user_type']=='1'){
                                        echo "Admin";}
                                    else{
                                    echo "Team Member";} ?></p>
                                </div>
                                <li class="list-group-item">Date of Birth: <?php echo $user['dob']; ?></li>
                                <li class="list-group-item">Address: <?php echo $user['address']; ?></li>
                                <li class="list-group-item">Contact: <?php echo $user['contact']; ?></li>
                                <li class="list-group-item">Joining Date: <?php echo $user['signup_date']; ?></li>
                                <div class="card-body">
                                <a href="#" class="card-link">View Profile</a>                            
                            </ul>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <p>No user data available.</p>
                    <?php endif; ?>
                </div>
            </div>
    </div>
</body>
</html>
<?php }
    
else{
    header("Location: <?php echo $base_url;?>public/views/team_dashboard/team_dashboard.php");
} ?>
