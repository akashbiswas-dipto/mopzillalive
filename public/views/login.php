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
        <h1>Your Clean Home, One Click Away.</h1>
        <form action="<?php echo $base_url;?>controller/authController.php" method="POST">
            <input type="email" name="username" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="customer_login">Login</button>
        </form>
        <?php if(!isset($_GET['success']) || isset($_GET['error'])){?> 
        <div class="register">
            <?php if(isset($_GET['error'])=="Invalid%20Password") {
                echo "<p style='color: red !important; font-weight: 600;'> Invalid User email or Password. <a href=''>Forgot your password?</a></p>";
                }
                else if(isset($_GET['error'])=="User already exists") {
                    echo "<p style='color: red !important; font-weight: 600;'> You already Exist in our profile.</p> <a>Forgot your password?</a>";
                }
                ?>
            <p>Don't have an account? <a href="<?php echo $base_url;?>public/views/register.php">Register here</a></p>
        </div>
        <?php }
        elseif(isset($_GET['error'])){?>
            <p class="success"><?php echo $_GET['success']; ?></p>
         <?php }
         ?>
    </div>
    
</body>
</html>