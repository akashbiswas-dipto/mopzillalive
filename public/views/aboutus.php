<?php include_once('navbar.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>public/css/aboutus.css">
    <title>About Us- Mopzilla</title>

</head>
<body>
    <div class="aboutus">
        <h1>About Us</h1>
        <p><a href="">Home</a> > About Us</p>
        <div class="description">
            <h1>Why Should<span style="color:black;"> You Choose </span>Our Services?</h1>
            <p>We are committed to delivering exceptional cleaning services that leave your spaces 
                sparkling clean and hygienic. Our professional team uses eco-friendly products to ensure 
                a safe and healthy environment for you.</p>
            <div class="support">
                <div class="icon">
                    <img src="<?php echo $base_url;?>public/content/home.png" alt="Support Icon">
                    <h5>House Cleaning</h5>
                    <p>Apartment, condos, and houses - busy people who want their home to feel fresh again.</p>
                </div>
                <div class="icon">
                    <img src="<?php echo $base_url;?>public/content/broom.png" alt="Support Icon">
                    <h5>Office Cleaning</h5>
                    <p>Maintain a clean, healthy workspace that supports productivity and professionalism.</p>
                </div>
            </div>
        </div>

    </div>
    <?php include_once('footer.php') ?>
</body>
</html>