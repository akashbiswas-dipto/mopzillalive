<!DOCTYPE html>
<?php session_start(); 
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
    $base_url = "http://localhost/mopzilla/"; 
    define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']."/mopzilla/");
    } else {
        $base_url = "https://mop-zilla.com/"; 
        define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']."/");
    }
    ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo $base_url;?>public/content/logo.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>public/css/navbar.css">
    <title>Mopzilla- From Chaos to Clean- The Mopzilla Way. </title>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="<?php echo $base_url;?>index.php">
                <img src="<?php echo $base_url;?>public/content/logo.png" alt="Mopzilla Logo" width="60px"> 
            </a>

            <!-- Toggler/collapse button for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list" style="font-size: 1.5rem;"></i>
            </button>


            <!-- Navbar links -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php echo $base_url;?>index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_url;?>public/views/aboutus.php">About Us</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>

                </ul>
            </div>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link-menu" aria-current="page" href="#">Get a Quote</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_url;?>public/views/login.php">Login/Sign Up</a>
                        </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>