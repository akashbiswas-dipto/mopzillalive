<?php include_once("database.php"); 

if(isset($_POST["add_task"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $formData=[
            'customername'=>$_POST['customername'],
            'customercontact'=>$_POST['customercontact'],
            'customeraddress'=>$_POST['customeraddress'],
            'worktype'=>$_POST['worktype'],
            'hour'=>$_POST['hour'],
            'hourrate'=>$_POST['hourrate'],
            'agency_fee'=>"23.00",
            'customer_note'=>$_POST['customer_note'],
            'date'=>$_POST['date'],
            'status'=>$_POST['status']
        ];
        addTask( $secret_key,$conn,$formData );
    }
}

if(isset($_POST['update_task'])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        global $base_url;
        $task_id = $_POST['task_id'];
        $status = $_POST['status'];
        $team_member1 = $_POST['team_member1'];
        $team_member2 = $_POST['team_member2'];
        $date=$_POST['work_date'];
        $notes= $_POST['notes'];
        $sql = "UPDATE tasklist SET work_date='$date', status='$status', notes='$notes', team_member1='$team_member1', team_member2='$team_member2' WHERE sl='$task_id'";
        if (mysqli_query($conn, $sql)) {
            header("location:".$base_url."public/views/team_dashboard/tasklist.php?success=Task updated successfully");
            exit();
        } else {
            header("location:".$base_url."public/views/team_dashboard/tasklist.php?error=Error updating task: ");
            exit();
        }
        
    }
}

if (isset($_POST['update_task_all'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        global $base_url;
        $client_id   = $_POST['client_id'];
        $status      = $_POST['status'];
        $team_member1 = $_POST['team_member1'];
        $team_member2 = $_POST['team_member2'];
        $date        = $_POST['work_date'];
        $worktype    = $_POST['work_type']; // 1 = weekly, 2 = fortnightly
        $notes= $_POST['notes'];
        $startDate   = new DateTime($date);
        $intervalSpec = ($worktype == '1') ? 'P1W' : 'P2W';  // Weekly or Fortnightly
        $interval    = new DateInterval($intervalSpec);
        $endDate     = clone $startDate;
        $endDate->modify('+3 months');

        $success = true;
        $currentDate = clone $startDate;

        // Fetch all tasks for this client in the next 3 months
        $sql = "SELECT sl FROM tasklist 
                WHERE client_id='$client_id' 
                AND work_date BETWEEN '".$startDate->format('Y-m-d H:i:s')."' AND '".$endDate->format('Y-m-d H:i:s')."' 
                ORDER BY work_date ASC";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $sl = $row['sl']; // primary key of task

                $work_date = $currentDate->format('Y-m-d H:i:s');

                // Update by primary key instead of work_date
                $update = "UPDATE tasklist 
                           SET work_date='$work_date', 
                               status='$status', 
                               notes='$notes',
                               team_member1='$team_member1', 
                               team_member2='$team_member2' 
                           WHERE sl='$sl' AND client_id='$client_id'";

                if (!mysqli_query($conn, $update)) {
                    $success = false;
                    break;
                }

                // move to next week/fortnight
                $currentDate->add($interval);
            }
        }

        if ($success) {
            header("location:".$base_url."public/views/team_dashboard/tasklist.php?success=Tasks updated successfully");
            exit(); 
        } else {
            header("location:".$base_url."public/views/team_dashboard/tasklist.php?error=Error updating tasks");
            exit();
        }
    }
}


if(isset($_POST["delete_task"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        global $base_url;
        $task_id = $_POST['delete_task'];
        $sql = "DELETE FROM tasklist WHERE sl='$task_id'";
        if (mysqli_query($conn, $sql)) {
            header("location:".$base_url."public/views/team_dashboard/tasklist.php?success=Task deleted successfully");
            exit();
        } else {
            header("location:".$base_url."public/views/team_dashboard/tasklist.php?error=Error deleting task: ");
            exit();
        }
    }
}

if(isset($_POST["delete_task_all"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        global $base_url;
        $client_id = $_POST['client_id'];
        $sql = "DELETE FROM tasklist WHERE client_id='$client_id'";
        if (mysqli_query($conn, $sql)) {
            header("location:".$base_url."public/views/team_dashboard/tasklist.php?success=Tasks deleted successfully");
            exit();
        } else {
            header("location:".$base_url."public/views/team_dashboard/tasklist.php?error=Error deleting task: ");
            exit();
        }
    }
}

function addTask($secret_key, $conn, $formData) {
    global $base_url;
    $customername = $formData['customername'];
    $customercontact = $formData['customercontact'];
    $worktype = $formData['worktype']; // 1 = weekly, 2 = fortnightly
    $hour = $formData['hour'];
    $hourrate = $formData['hourrate'];
    $agency_fee = $formData['agency_fee'];
    $customer_note = $formData['customer_note'];
    $date = $formData['date']; // string date format
    $status = $formData['status'];
    // Check if client exists
    $sql = "SELECT * FROM client WHERE contact='$customercontact' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
        $user_id = $user_data['customer_id'];
    } else {
        // Create new client id
        $sql = "SELECT * FROM client";
        $result = mysqli_query($conn, $sql);
        $rowcount = mysqli_num_rows($result);

        if ($rowcount <= 9) {
            $user_id = "MopzillaClient" . "00" . ($rowcount + 1);
        } elseif ($rowcount <= 99) {
            $user_id = "MopzillaClient" . "0" . ($rowcount + 1);
        } else {
            $user_id = "MopzillaClient" . ($rowcount + 1);
        }
       // Fetch all existing customer IDs
        $existing_ids = [];
        $sql = "SELECT customer_id FROM client";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $existing_ids[] = $row['customer_id'];
            }
        }

        // Generate new unique ID
        $rowcount = count($existing_ids);
        do {
            $rowcount++;
            if ($rowcount <= 9) {
                $user_id = "MopzillaClient00" . $rowcount;
            } elseif ($rowcount <= 99) {
                $user_id = "MopzillaClient0" . $rowcount;
            } else {
                $user_id = "MopzillaClient" . $rowcount;
            }
        } while (in_array($user_id, $existing_ids));

        $sql = "INSERT INTO client (name, contact, address, customer_id, customer_type, promo_available) 
                VALUES ('" . $customername . "','" . $customercontact . "', '" . $formData['customeraddress'] . "','$user_id','1','1')";

        if (!mysqli_query($conn, $sql)) {
            header("location:".$base_url."public/views/team_dashboard/addtask.php?error=Client creation failed.");
            exit();
        }
    }

    // Prepare dates for creating multiple tasks
    $startDate = new DateTime($date);
    $intervalSpec = ($worktype == '1') ? 'P1W' : 'P2W';  // P1W = 1 week, P2W = 2 weeks (fortnightly)
    $interval = new DateInterval($intervalSpec);
    $endDate = clone $startDate;
    $endDate->modify('+3 months');

    // Insert tasks for the whole year
    $success = true;
    $currentDate = clone $startDate;

    while ($currentDate <= $endDate) {
        $work_date = $currentDate->format('Y-m-d H:i:s');

        $sql = "INSERT INTO tasklist (client_id, work_type, workinghour, hourly_rate, agency_fee, notes, work_date, status) 
                VALUES ('$user_id', '$worktype', '$hour', '$hourrate', '$agency_fee', '$customer_note', '$work_date', '$status')";

        if (!mysqli_query($conn, $sql)) {
            $success = false;
            break;
        }

        $currentDate->add($interval);
    }
    if ($success) {
        header("location:".$base_url."public/views/team_dashboard/tasklist.php?success=Tasks added successfully");
        exit();
    } else {
        header("location:".$base_url."public/views/team_dashboard/addtask.php?error=Error adding tasks");
        exit();
    }
}

function getTaskData($conn, $usertype){
    
    if($usertype == 1){
        $sql="select * from tasklist Order By work_date ASC";
        $result=mysqli_query($conn,$sql);
        $taskData=array();
        if($result && mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                $taskData[]=$row;
            }
        }
        return $taskData;
    } elseif ($usertype == 2) {
        $user_id = $_SESSION['team_id'];
        $sql="select * from tasklist where team_member1='$user_id' or team_member2='$user_id'";
        $result=mysqli_query($conn,$sql);
        $taskData=array();
        if($result && mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                $taskData[]=$row;
            }
        }
        return $taskData;
    } else {
        return [];
    }
}

function getTasksDataByClient($conn, $clientid){
    $sql="select * from tasklist where client_id='$clientid' Order By work_date ASC";
    $result=mysqli_query($conn,$sql);
    $taskData=array();
    if($result && mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_assoc($result)){
            $taskData[]=$row;
        }
    }
    return $taskData;
}

if(isset($_POST['submit_invoice'])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        global $base_url;
        $team_id = $_POST['team_id'];
        $monday=$_POST['monday'];
        $tuesday=$_POST['tuesday'];
        $wednesday=$_POST['wednesday'];
        $thursday=$_POST['thursday'];
        $friday=$_POST['friday'];
        $total = $monday + $tuesday + $wednesday + $thursday + $friday;
        $total = $_POST['total'];
        $sql="INSERT INTO invoice_team (team_id, monday, tuesday, wednesday, thursday, friday, total, status) 
                VALUES ('$team_id', '$monday', '$tuesday', '$wednesday', '$thursday', '$friday', '$total', 1)";
        if (mysqli_query($conn, $sql)) {
            header("location:".$base_url."public/views/team_dashboard/invoice.php?success=Invoice added successfully");
            exit();
        } else {
            header("location:".$base_url."public/views/team_dashboard/invoice.php?error=Error adding invoice: ");
            exit();
        }
    }
}

if(isset($_POST['approve_invoice']) || isset($_POST['reject_invoice']) || isset($_POST['paid_invoice'])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        global $base_url;
        $invoice_id = $_POST['invoice_id'];
        if(isset($_POST['approve_invoice'])){
            $txid = $_POST['txid'];
            $sql = "UPDATE invoice_team SET status='2' WHERE sl='$invoice_id'";
            if (mysqli_query($conn, $sql)) {
                header("location:".$base_url."public/views/team_dashboard/invoice.php?success=Invoice marked as approved successfully");
                exit();
            } else {
                header("location:".$base_url."public/views/team_dashboard/invoice.php?error=Error marking invoice as approved: ");
                exit();
            }
        } elseif(isset($_POST['reject_invoice'])){
            $sql = "UPDATE invoice_team SET status='4' WHERE sl='$invoice_id'";
            if (mysqli_query($conn, $sql)) {
                header("location:".$base_url."public/views/team_dashboard/invoice.php?success=Invoice rejected successfully");
                exit();
            } else {
                header("location:".$base_url."public/views/team_dashboard/invoice.php?error=Error rejecting invoice: ");
                exit();
            }
        }
        elseif(isset($_POST['paid_invoice'])){
            $txid = $_POST['txid'];
            if(empty($txid)){
                header("location:".$base_url."public/views/team_dashboard/invoice.php?error=TxID cannot be empty");
                exit();
            }
            $sql = "UPDATE invoice_team SET status='3', txid='$txid' WHERE sl='$invoice_id'";
            if (mysqli_query($conn, $sql)) {
                header("location:".$base_url."public/views/team_dashboard/invoice.php?success=Invoice marked as paid successfully");
                exit();
            } else {
                header("location:".$base_url."public/views/team_dashboard/invoice.php?error=Error marking invoice as paid: ");
                exit();
            }
        }
    }

}
function getallinvoiceteam($conn){
    $sql="select * from invoice_team Order By submitdate DESC";
    $result=mysqli_query($conn,$sql);
    $invoiceData=array();
    if($result && mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_assoc($result)){
            $invoiceData[]=$row;
        }
    }
    return $invoiceData;
}

?>