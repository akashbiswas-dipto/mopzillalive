<?php
    include_once("navbar.php");
    $taskData = getTaskData($conn, $_SESSION['usertype']);
    $now = new DateTime();
    $currentWeekKey = $now->format('o-\WW');
    $currentWeekTaskCount = 0;
    foreach ($taskData as $task) {
    $taskDate = new DateTime($task['work_date']);
    $taskWeekKey = $taskDate->format('o-\WW');
    if ($taskWeekKey === $currentWeekKey) {
        $currentWeekTaskCount++;
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/mopzilla/public/css/team_dashboard.css">
    <title>Hello - <?php echo $_SESSION['team_username'];?></title>
</head>
<body>
    <div class="box">
        <div class="row">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="/mopzilla/public/content/briefcase.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $currentWeekTaskCount; ?></h5>
                    <p class="card-text">Tasks - This Week</p>
                </div>
                <div class="card-body">
                    <a href="tasklist" class="card-link">View Task List</a>
                </div>
            </div>
            <?php 
            if(isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1 ){
                ?>
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="/mopzilla/public/content/client.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?php echo count($clientallData); ?></h5>
                    <p class="card-text">Clients</p>
                </div>
                <div class="card-body">
                    <a href="clientlist" class="card-link">View Client List</a>
                </div>
            </div>
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="/mopzilla/public/content/group.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?php echo count($userData);?></h5>
                    <p class="card-text">Team Members</p>
                </div>
                <div class="card-body">
                    <a href="teamlist" class="card-link">View Team Members</a>
                </div>
            </div>
            
             <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="/mopzilla/public/content/birthday.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?php echo count($userData);?></h5>
                    <p class="card-text">Birthdays</p>
                </div>
                <div class="card-body">
                    <a href="#" class="card-link">View Birthdays</a>
                </div>
            </div>
            <?php }
            ?>
        </div>
    </div>
</body>
</html>
