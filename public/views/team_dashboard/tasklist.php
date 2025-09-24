<?php
include_once("navbar.php");

if (isset($_SESSION['usertype']) && ($_SESSION['usertype'] === '1' || $_SESSION['usertype'] === '2')) {
    if(empty($_POST) || (isset($_POST['client_id']) && $_POST['client_id'] === 'all')){
        $taskData = getTaskData($conn, $_SESSION['usertype']);
        
    } elseif(isset($_POST['client_id']) && !empty($_POST['client_id'])){
        $clientid=$_POST['client_id'];
        $taskData = getTasksDataByClient($conn, $clientid);
        $_SESSION['clientid']=$_POST['client_id'];
        //print_r($taskData);
    }
    // Mapping for work type
    $workTypeMap = [
        1 => 'Weekly',
        2 => 'Fortnightly',
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>public/css/tasklist.css">
    <title>Task list - Mopzilla</title>
    <style>
        
        
    </style>
</head>
<body>
<div class="box">
    <?php if($_SESSION['usertype'] === '1') { ?>
    <div class="button_add">
        <a href="addtask.php" class="addmemberbutton">Add Task</a> 
    </div>
    <div class="filter_button">
        <form method="POST" action="">
            <label for="client_id" style="font-weight: 600;">Filter by Customer:</label>
            <select name="client_id" class="filter_select">
                <option value="all" <?php echo (!isset($_GET['client_id']) || $_GET['client_id'] == 'all') ? 'selected' : ''; ?>>All Customers</option>
                <?php
                $clients = mysqli_query($conn, "SELECT * FROM client ORDER BY name ASC");
                
                while ($client = mysqli_fetch_assoc($clients)) {
                    print_r($client);
                    echo "<option value='{$client['customer_id']}'>{$client['name']}</option>";
                }
                ?>
            </select>
            <button type="submit" name="search">Filter</button>
        </form>
    </div>
    <?php } ?>

    <div class="list_team">
        <div class="row">
            <?php if ($taskData && count($taskData) > 0): ?>
                <?php
                $weeklyTasks = [];

                // Group tasks by week starting Monday
                foreach ($taskData as $task) {
                    $date = new DateTime($task['work_date']);
                    $monday = clone $date;
                    $monday->modify('Monday this week');
                    $weekKey = $monday->format('Y-m-d'); // Monday date as key
                    $weeklyTasks[$weekKey][] = $task;
                }

                // Sort weeks chronologically
                ksort($weeklyTasks);
                ?>
                <div class="all-tables-container">
                    <?php foreach ($weeklyTasks as $week => $tasksInWeek):
                        $monday = new DateTime($week);
                        $sunday = clone $monday;
                        $sunday->modify('+6 days');
                        $mondayFormatted = $monday->format('d M Y');
                        $sundayFormatted = $sunday->format('d M Y');

                        // Calculate weekly total
                        $weeklyTotal = 0;
                        foreach ($tasksInWeek as $task) {
                            if($_SESSION['usertype'] === '1'){
                            $weeklyTotal += ($task['workinghour'] * $task['hourly_rate']) + $task['agency_fee'];}
                            elseif($_SESSION['usertype'] === '2'){
                                $weeklyTotal += ($task['workinghour'] * 22.5);
                            }
                            
                        }
                    ?>
                    <h4 class="text-primary" style="color: #234f1E !important;">
                        Week: <?php echo $mondayFormatted . ' – ' . $sundayFormatted; ?> | 
                        Total Earning: $<?php echo number_format($weeklyTotal, 2); ?>
                    </h4>

                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Work Type</th>
                                <th>Hours</th>
                                <?php if($_SESSION['usertype'] === '1') { ?> 
                                <th>Hourly Rate</th>
                                <th>Agency Fee</th>
                                <th>Total</th>
                                <?php } ?>
                                <th>Note</th>
                                <th>Work Day</th>
                                <th>Work Date</th>
                                <?php if($_SESSION['usertype'] === '1') { ?> 
                                <th>Status</th>
                                <th>Team Member 1</th>
                                <th>Team Member 2</th>
                                <th>Actions</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($tasksInWeek as $task):
                                $clientData = getclientData($conn, $task['client_id']);
                                $total = ($task['workinghour'] * $task['hourly_rate']) + $task['agency_fee'];
                                $date = new DateTime($task['work_date']);
                            ?>
                            <form action="<?php echo $base_url;?>controller/taskController.php" method="POST">
                            <tr>
                                <td><?php echo $clientData['name']; ?></td>
                                <td><?php echo $clientData['address']; ?></td>
                                <td><?php echo $clientData['contact']; ?></td>
                                <td><?php echo $workTypeMap[$task['work_type']] ?? 'Unknown'; ?></td>
                                <td><?php echo $task['workinghour']; ?></td>
                                <?php if($_SESSION['usertype'] === '1') { ?> 
                                <td>$<?php echo number_format($task['hourly_rate'], 2); ?></td>
                                <td>$<?php echo number_format($task['agency_fee'], 2); ?></td>
                                <td>$<?php echo number_format($total, 2); ?></td>
                                <?php } ?>
                                
                                <td><?php echo $task['notes']; ?></td>
                                <td><?php echo $date->format('l'); ?></td>
                               <?php if($_SESSION['usertype'] === '1'): ?>
                                    <td>
                                        <input 
                                            type="datetime-local" 
                                            name="work_date" 
                                            value="<?php echo $date->format('Y-m-d\TH:i'); ?>"
                                        >
                                    </td>
                                <?php else: ?>
                                    <td><?php echo $date->format('d-m-Y h:i A'); ?></td>
                                <?php endif; ?>
                                <?php if($_SESSION['usertype'] === '1') { ?> 
                                <td>
                                    <select name="status">
                                        <option value="1" <?php echo ($task['status'] == '1') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="2" <?php echo ($task['status'] == '2') ? 'selected' : ''; ?>>Confirmed</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="team_member1">
                                        <option value="">Select Team Member</option>
                                        <?php foreach ($userData as $user): ?>
                                            <option value="<?php echo $user['user_id']; ?>" 
                                                <?php echo ($task['team_member1'] == $user['user_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($user['full_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="team_member2">
                                        <option value="">Select Team Member</option>
                                        <?php foreach ($userData as $user): ?>
                                            <option value="<?php echo $user['user_id']; ?>" 
                                                <?php echo ($task['team_member2'] == $user['user_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($user['full_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" name="task_id" value="<?php echo $task['sl']; ?>">
                                    <input type="hidden" name="client_id" value="<?php echo $task['client_id']; ?>">
                                    <input type="hidden" name="work_type" value="<?php echo $task['work_type']; ?>">

                                    <div class="dropdown">
                                        <div class="dropbtn">Actions ▾</button>
                                        <div class="dropdown-content">
                                            <button type="submit" name="update_task" value="<?php echo $task['sl']; ?>">Update</button>
                                            <button type="submit" name="update_task_all" value="<?php echo $task['sl']; ?>">Update All Task</button>
                                            <button type="submit" name="delete_task" value="<?php echo $task['sl']; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</button>
                                            <button type="submit" name="delete_task_all" value="<?php echo $task['sl']; ?>" onclick="return confirm('Are you sure you want to delete all tasks of this client?');">Delete All Task</button>
                                        </div>
                                    </div>
                                </td>
                                <?php } ?>
                            </tr>
                            </form>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No task data available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
<?php
} else {
    header("Location:".$base_url."public/views/team_dashboard/team_dashboard.php");
    exit();
}
?>
