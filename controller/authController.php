<?php 

include_once("database.php"); 

if (isset($_POST['team_login'])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $formData=[
            'username'=>$_POST['username'],
            'password'=>$_POST['password']
        ];         
        
        loginUserTeam( $secret_key,$conn,$formData );
    } 

}

if(isset($_POST['customer_login'])){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $formData=[
        'email'=>$_POST['username'],
        'password'=>$_POST['password']
    ];
    loginCustomer( $secret_key, $conn, $formData );
    }
}

if (isset($_GET['WorkType'])=='Logout'){
    global $base_url;
    echo 'i am in logout';
    session_start();
    $_SESSION = [];
    session_destroy();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time() - 3600, '/');
    }
    }
    header("location:".$base_url."public/views/login_user.php");
}

if(isset($_POST['register']) && $_SERVER["REQUEST_METHOD"] == "POST"){
    global $base_url;
        $formData=[
            "name"=>$_POST["full_name"],
            "address"=>$_POST["address"],
            "email"=>$_POST["email"],
            "password"=>$_POST["password"],
            "contact"=>$_POST["contact"]           
        ];            
        $sql= "SELECT * FROM client WHERE email='".$formData['email']."' Limit 1";
        $result = mysqli_query($conn, $sql);
        if ($result && $result->num_rows > 0) {
            header("location:".$base_url."public/views/login.php?error=User already exists");
            exit();
        }
        else{
            CreateCustomer( $secret_key_customer,$conn,$formData );
        }
}


if(isset($_POST['add_team_member']) && $_SERVER["REQUEST_METHOD"] == "POST"){
    global $base_url;
        if (!isset($_FILES['idpic']) || $_FILES['idpic']['error'] !== UPLOAD_ERR_OK) {
        die("❌ No file uploaded or upload error.");
        }
        $targetDir = $base_url."public/content/teammember/";  // absolute server path
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName     = basename($_FILES["idpic"]["name"]);
        $relativePath = "content/teammember/" . $fileName;
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        $check = getimagesize($_FILES["idpic"]["tmp_name"]);
        if ($check === false) {
                die("❌ File is not an image.");
        }

            // Check file type
            if (!in_array($imageFileType, $allowedTypes)) {
                die("❌ Only JPG, JPEG, PNG & GIF allowed.");
            }

            // Move file to uploads folder
            if (move_uploaded_file($_FILES["idpic"]["tmp_name"], $targetFile)) {
                echo "✅ The file " . htmlspecialchars(basename($_FILES["idpic"]["name"])) . " has been uploaded.<br>";
                echo "<img src='$targetFile' width='200'>";
            } else {
                echo "❌ Sorry, there was an error uploading your file.";
            }

        $formData=[
            'team_username'=>$_POST['team_username'],
            'team_password'=>$_POST['team_password'],
            'team_email'=>$_POST['team_email'],
            'cnumber'=>$_POST['cnumber'],
            'address'=>$_POST['address'],
            'dob'=>$_POST['dob'],
            'idpic'=> $relativePath,
            'usertype'=>$_POST['usertype']
        ];         
        addTeamMember( $secret_key,$conn,$formData );
    
}

function CreateCustomer( $secret_key_customer, $conn,$formData ) {
    global $base_url;
    $newpass=$secret_key_customer.$formData['password'];
    $hash_password = md5($newpass);
    $sql="select * from client";
    $result = mysqli_query($conn, $sql);
    $rowcount=mysqli_num_rows($result);
    if($rowcount<=9){
            $user_id="MopzillaClient"."00".($rowcount+1);
        }
    elseif($rowcount<= 99){
            $user_id="MopzillaClient"."0".($rowcount+1);
        }
    else{
            $user_id="MopzillaClient".($rowcount+1);
        }
    $sql = "INSERT INTO client (name, email, password, contact, address,customer_id,customer_type,promo_available) 
            VALUES ('".$formData['name']."', '".$formData['email']."', '$hash_password', '".$formData['contact']."', '".$formData['address']."','$user_id','1','1')";    
    if (mysqli_query($conn, $sql)) {
        header("location:".$base_url."public/views/login.php?success=Registration successful. Please log in.");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

function loginCustomer($secret_key, $conn, $formData){
    global $base_url;
    $email=$formData["email"];
    $password=$formData["password"];
    $hash_password = md5("Tanmay@123");
    $sql = "SELECT * FROM client WHERE email='$email' Limit 1";
    $result = mysqli_query($conn, $sql);
    if($result && $result->num_rows == 0){
        header("location:".$base_url."public/views/login.php?error=Invalid Username or Password");
    }
    else{
        $user=$result->fetch_assoc();
        print_r($user);
        if($hash_password== $user['password']){
            session_start();
            $_SESSION['customer_id'] = $user['customer_id'];
            $_SESSION['customer_name'] = $user['name'];
            $_SESSION['customer_email'] = $user['email'];
            header("location:".$base_url."public/views/customer_dashboard/customer_dashboard.php");
            exit();
        } else {
            header("location:".$base_url."public/views/login.php?error=Invalid Password");
        }
    }
}

function addTeamMember($secret_key,$conn, $formData) {
    global $base_url;
    $username =$formData['team_username'];
    $password = $formData['team_password'];
    $email = $formData['team_email'];
    $cnumber = $formData['cnumber'];
    $address = $formData['address'];
    $dob = $formData['dob'];
    $idpic = $formData['idpic'];
    $usertype = $formData['usertype'];

    $sql = "SELECT * FROM user_team WHERE user_id='$username' Limit 1";
    $result = mysqli_query($conn, $sql);

    if ($result && $result->num_rows > 0) {
        header("location:".$base_url."public/views/team_dashboard/addteammember.php?error=Username already exists");
    }
    else{
        $sql= "SELECT * FROM user_team";
        $result = mysqli_query($conn, $sql);
        $rowcount=mysqli_num_rows($result);
        if($rowcount<=9){
            $user_id="Mopzilla"."00".($rowcount+1);
        }
        elseif($rowcount<= 99){
            $user_id="Mopzilla"."0".($rowcount+1);
        }
        else{
            $user_id="Mopzilla".($rowcount+1);
        }
    
        $newpass=$secret_key.$password;
        $hashed_password = md5($newpass);
        $sql = "INSERT INTO user_team (full_name, password, email, contact, address, dob, idpic, user_type,user_id) 
                VALUES ('$username', '$hashed_password', '$email', '$cnumber', '$address', '$dob', '$idpic', '$usertype','$user_id')";
        if (mysqli_query($conn, $sql)) {
            header("location:".$base_url."public/views/team_dashboard/teamlist.php?success=New team member added successfully");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

function  loginUserTeam($secret_key,$conn, $formData) {
    global $base_url;
    echo 'i am in login user method';
    $username =$formData['username'];
    $password = $formData['password'];
    $sql = "SELECT * FROM user_team WHERE user_id='$username' Limit 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && $result->num_rows == 0) {
        header("location:".$base_url."public/views/login_user.php?error=Invalid Username or Password");
    }
    else{
        $newpass=$secret_key.$password;
        $hashed_password = md5($newpass);
        $user=$result->fetch_assoc();
        if($hashed_password== $user['password']){
            session_start();
            $_SESSION['team_id'] = $user['user_id'];
            $_SESSION['team_username'] = $user['full_name'];
            $_SESSION['usertype'] = $user['user_type'];
            $_SESSION['team_email'] = $user['email'];
            header("location:".$base_url."public/views/team_dashboard/team_dashboard.php");
            exit();
        } else {
            echo $base_url;
            /*header("location:".$base_url."public/views/login_user.php?error=InvalidPassword"); */
        }
    } 
}

function getuserData($conn){
    $sql = "SELECT * FROM user_team";
    $result = mysqli_query($conn, $sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        // fetch_assoc returns an associative array
        return $users;
    } else {
        return null; // no user found
    }
}

function getuserDataByID( $conn, $user_id ) {
    $sql = "SELECT * FROM user_team where user_id='$user_id'";
    $result = mysqli_query($conn, $sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    else{
        return null;
    }
}

function getclientData($conn, $user_id){
    $sql = "SELECT * FROM client where customer_id='$user_id' Limit 1";
    $result = mysqli_query($conn, $sql);

    if ($result && $result->num_rows > 0) {
        // fetch_assoc returns an associative array
        return $result->fetch_assoc();
    } else {
        return null; // no user found
    }
}

function getallclientData($conn) {
    $sql = "SELECT * FROM client Order By customer_id ASC";
    $result = mysqli_query($conn, $sql);

    $clients = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
        return $clients;
    } else {
        return []; 
    }
}

?> 
