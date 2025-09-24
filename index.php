<?php 
    include_once('public/views/navbar.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>public/css/index.css">
    <title>Mopzilla- From Chaos to Clean- The Mopzilla Way. </title>
</head>
<body>
    <div class="tagline">
        <h1>Where <span style="color:#234f1E; ">Clean</span><br> Meets<br><span style="color:#234f1E; "> Mighty</span>.</h1>
    </div>
    <div class="whychoseus">
        <h1><img src="<?php echo $base_url;?>public/content/logo.png" alt="mopzilla logo" width="100px">WHY <span style="color:#234f1E; ">CHOSE US?</span></h1>
        <div class="content">
            <div class="reason">   
                <img src="<?php echo $base_url;?>public/content/broom.png" alt="cleaning image">
                <h5>High_Voltage Shine</h5>
                <p>We roll in with beast-level gear that blasts away dust, stains, and allergens—leaving your space sparkling like it just leveled up.</p>
            </div>
            <div class="reason">  
                <img src="<?php echo $base_url;?>public/content/recycle.png" alt="recycle image">
                <h5>Go Green or Go Home</h5>
                <p>Our eco-friendly potions (aka cleaning products) are safe, sustainable, and biodegradable. Good for you, your crew, and the planet too.</p>
             </div>
            <div class="reason">
                <img src="<?php echo $base_url;?>public/content/clock.png" alt="clock image">
                <h5>Trust the Mop Monsters</h5>
                <p>Our squad shows up on time, respects your space, and treats every job like it’s their own lair. Consistency is our superpower.</p>
            </div>
            <div class="reason">
                <img src="<?php echo $base_url;?>public/content/dollar-sign.png" alt="dollar-sign image">
                <h5>Wallet-Friendly Wipeouts</h5>
                <p>We customize cleaning plans to fit your schedule and budget—because epic cleaning doesn’t have to cost kaiju-sized bucks.</p>
            </div>
            <div class="reason">
                <img src="<?php echo $base_url;?>public/content/green-love.png" alt="love image">
                <h5>Happy Vibes Guaranteed</h5>
                <p>Your joy is our victory dance. If you’re not grinning at the results, Mopzilla stomps back in to make it right.</p>
            </div>
        </div>
    </div>
    <div class="believe">
        <div class="message">
            <h1>Smart Cleaning, Anytime. Mopzilla Never Sleeps</h1>
            <p>Day or night, rain or shine, Mopzilla’s always on duty. We bring smart cleaning solutions powered by tech, eco-friendly products, and monster-sized energy—keeping your space spotless 24/7 without missing a beat.</p>
            <a href="">Learn More?</a>
        </div>
    </div>
    <?php include_once(BASE_PATH.'public/views/footer.php');?>
</body>
</html>