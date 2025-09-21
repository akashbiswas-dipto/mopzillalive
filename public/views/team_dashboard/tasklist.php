<?php
include_once("navbar.php");
?><?php 
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === '1' || $_SESSION['usertype'] === '2') {
    $taskData = getTaskData($conn, $_SESSION['usertype']);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/mopzilla/public/css/tasklist.css">
        <title>Task list - Mopzilla</title>
    </head>
    <body>
        <div class="box">
            <?php 
                if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === '1') { ?>
            <div class="button_add">
                <a href="addtask.php" class="addmemberbutton">Add Task</a> 
            </div><?php 
            } ?>
            <div class="list_team">
                <div class="row">
                    <?php
                    if ($taskData && count($taskData) > 0):
                        $weeklyTasks = [];
                        foreach ($taskData as $task) {
                            $date = new DateTime($task['work_date']);
                            $weekKey = $date->format('o-\WW'); 
                            $weeklyTasks[$weekKey][] = $task;
                        }

                        foreach ($weeklyTasks as $week => $tasksInWeek):
                    ?>
                    <div class="col-12 mt-4">
                        <?php
                            list($year, $weekNum) = explode('-W', $week);
                            $monday = new DateTime();
                            $monday->setISODate($year, $weekNum);
                            $sunday = clone $monday;
                            $sunday->modify('+6 days');
                            $mondayFormatted = $monday->format('d M Y');
                            $sundayFormatted = $sunday->format('d M Y'); 
                            ?>

                            <h4 class="text-primary " style="color: #234f1E !important;">Week: <?php echo $mondayFormatted . ' – ' . $sundayFormatted; ?></h4>

                        <div class="table-responsive">
                            <table border-collapse="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                                <thead style="background-color: #f2f2f2;">
                                    <tr>
                                        <th style="width: 150px !important;">Name</th>
                                        <th style="width: 250px !important;">Address</th>
                                        <th style="width: 100px !important;">Contact</th>
                                        <th style="width: 100px !important;">Work Type</th>
                                        <th style="width: 60px !important;">Hours</th>
                                        <?php if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === '1') { ?> 
                                        <th style="width: 100px !important;">Hourly Rate</th>
                                        <th style="width: 100px !important;">Agency Fee</th>
                                        <?php } ?>
                                        <th style="width: 80px !important;">Total</th>
                                        <th style="width: 500px !important;">Note</th>
                                        <th style="width: 150px !important;">Work Day</th>
                                        <th style="width: 150px !important;">Work Date</th>
                                        <?php if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === '1') { ?> 
                                        <th style="width: 80px !important;">Status</th>
                                        <th style="width: 120px !important;">team Member 1</th>
                                        <th style="width: 120px !important;">team Member 2</th>
                                        <th style="width: 280px !important;"></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tasksInWeek as $task):
                                        $clientData = getclientData($conn, $task['client_id']);
                                        $total = (float)(($task['workinghour'] * $task['hourly_rate']) + $task['agency_fee']);
                                        $date = new DateTime($task['work_date']);
                                    ?>
                                    <form action="../../../controller/taskController.php" method="POST">
                                    <tr>
                                        <td><?php echo $clientData['name']; ?></td>
                                        <td><?php echo $clientData['address']; ?></td>
                                        <td><?php echo $clientData['contact']; ?></td>
                                        <td><?php echo ($task['work_type'] == 1 ? 'Weekly' : 'Fortnightly'); ?></td>
                                        <td><?php echo $task['workinghour']; ?></td>
                                        <?php if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === '1') { ?> 
                                        <td>$<?php echo number_format($task['hourly_rate'], 2); ?></td>
                                        <td>$<?php echo number_format($task['agency_fee'], 2); ?></td>
                                        <?php } ?>
                                        <td>$<?php echo number_format($total, 2); ?></td>
                                        <td><?php echo $task['notes']; ?></td>
                                        <td><?php
                                                    $dayOfWeek = $date->format('l');
                                                    echo $dayOfWeek;
                                        ?></td>
                                        <td><?php echo $date->format('d-m-Y h:i A'); ?></td>
                                        <?php if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === '1') { ?> 
                                        <td>
                                            <select id="status" name="status">
                                                <option value="1" <?php echo ($task['status'] == '1') ? 'selected' : ''; ?>>Pending</option>
                                                <option value="2" <?php echo ($task['status'] == '2') ? 'selected' : ''; ?>>Confirmed</option>
                                            </select>
                                        </td>
                                         <td>
                                            <select id="team_member" name="team_member1">
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
                                            <select id="team_member" name="team_member2">
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
                                            <button type="submit" class="task-buttons" name="update_task" value="<?php echo $task['sl']; ?>">Update</button>
                                            <button type="submit" class="task-buttons" name="delete_task" value="<?php echo $task['sl']; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</button>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    </form>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <p>No task data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}
else {
    header("Location: /mopzilla/public/views/team_dashboard/team_dashboard.php");
    exit();
}
?>
