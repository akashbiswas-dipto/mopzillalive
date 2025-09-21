<?php
    include_once("navbar.php");
    if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === '1'){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/mopzilla/public/css/teamlist.css">
    <title>Add Task- Mopzilla</title>
</head>
<body>
    <div class="box">
            <form action="../../../controller/taskController.php" method="POST">
                <h1>Add Task</h1>
                <input type="text" name="customername" placeholder="Customer Name" required><br>
                <input type="text" name="customercontact" placeholder="Customer Contact Number" required><br>
                <input type="text" name="customeraddress" placeholder="Customer Address" required><br>
                <select id="wtype" name="worktype" required>
                    <option value="" disabled selected hidden>-- Choose Work Type--</option>
                    <option value="1">Weekly</option>
                    <option value="2">Fortnightly</option>
                </select><br>
                <input type="text" name="hour" placeholder="Working Hour" required><br>        
                <input type="text" name="hourrate" placeholder="Working Hour Rate" required><br>        
                <input type="textarea" name="customer_note" placeholder="Customer Note" required><br>  
                <input type="datetime-local" name="date" placeholder="Date" required><br>         
                <select id="status" name="status" required>
                    <option value="" disabled selected hidden>-- Choose Status--</option>
                    <option value="1">Pending</option>
                    <option value="2">Confirmed</option>
                </select><br>
                <button type="submit" name="add_task">Add Task</button>
    </div>
</body>
</html>
<?php }
else{
    header("Location: /mopzilla/public/views/team_dashboard.php");
} ?>
