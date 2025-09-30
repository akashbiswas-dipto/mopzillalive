<!DOCTYPE html>
<?php session_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define BASE_PATH only if not already defined
if (!defined('BASE_PATH')) {
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        $base_url = "http://localhost/mopzilla/";
        define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']."/mopzilla/");
    } else {
        $base_url = "https://mop-zilla.com/";
        define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']."/");
    }
}

// Include controllers
include_once(BASE_PATH . "controller/authController.php");
include_once(BASE_PATH . "controller/taskController.php");
if(isset($_SESSION['usertype']) && ($_SESSION['usertype'] == 3)){
$userData=getuserData($conn);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/mopzilla/public/content/logo.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/mopzilla/public/css/navbar.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="customer_dashboard.php">
                <img src="/mopzilla/public/content/logo.png" alt="Mopzilla Logo" width="60px"> 
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/mopzilla/public/views/customer_dashboard/customer_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/mopzilla/public/views/team_dashboard/tasklist.php">Task List</a>
                    </li>
                </ul>
            </div>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                            <a class="nav-link" href="#">Profile</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="../../../controller/authController.php?WorkType=Logout">Logout</a>
                        </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

<?php }
else{
    header("Location: /mopzilla/public/views/login.php");
} ?>
