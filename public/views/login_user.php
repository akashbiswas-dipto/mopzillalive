<?php include_once('navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>public/css/login.css">
    <title>Login- Mopzilla</title>
</head>
<body>
    <div class="login">        
        <img src='<?php echo $base_url;?>public/content/logo.png' class="login_logo">
        <h1>Cleaning superheroes assemble – your city needs sparkle!</h1>
        <form action="<?php echo $base_url;?>controller/authController.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="team_login">Login</button>
        </form>
    </div>
    
</body>
</html>