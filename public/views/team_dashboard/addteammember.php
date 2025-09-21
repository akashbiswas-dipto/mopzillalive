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
    <title>Add member- Mopzilla</title>
</head>
<body>

    <div class="box">
            <form action="../../../controller/authController.php" method="POST"  enctype="multipart/form-data">
                <h1>Add Team Member</h1>
                <input type="text" name="team_username" placeholder="Username" required><br>
                <input type="password" name="team_password" placeholder="Password" required><br>
                <input type="text" name="team_email" placeholder="Email" required><br>        
                <input type="text" name="cnumber" placeholder="Contact Number" required><br>  
                <input type="text" name="address" placeholder="Address" required><br>         
                <label for="dob">Date of Birth </label><br><input type="date" name="dob" placeholder="Date of Birth" required><br>                
                <input type="file" name="idpic" required><br>
                <label for="utype">Select User Type</label><br>
                <select id="utype" name="usertype" required>
                    <option value="" disabled selected hidden>-- Choose User Type--</option>
                    <option value="1">Admin</option>
                    <option value="2">Team Member</option>
                </select><br>
                <button type="submit" name="add_team_member">Add Member</button>
    </div>
</body>
</html>
<?php }
else{
    header("Location: /mopzilla/public/views/team_dashboard");
} ?>
