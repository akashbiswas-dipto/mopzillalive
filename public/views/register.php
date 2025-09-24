<?php include_once('navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>public/css/register.css">
    <title>Join the Mopzilla Family</title>
</head>
<body>
    <div class="register_box">
        <form action="<?php echo $base_url; ?>controller/authController.php" method="POST">
            <h1>Join the Mopzilla Family</h1>
            <input type="text" name="full_name" placeholder="Full Name" required><br>
            <input type="text" name="address" placeholder="Full Address" required><br>
            <input type="text" name="contact" placeholder="Contact Number, Ex. 04123456789" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>
</html>