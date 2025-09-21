<?php
include_once("navbar.php");

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === '1') {
    // Get all clients
    $clientallData = getallclientData($conn);

    // Sort by task count DESC
    usort($clientallData, function ($a, $b) use ($conn) {
        $tasksA = getTasksDataByClient($conn, $a['customer_id']);
        $tasksB = getTasksDataByClient($conn, $b['customer_id']);
        return count($tasksB) - count($tasksA);
    });
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client List - Mopzilla</title>
    <link rel="stylesheet" type="text/css" href="/mopzilla/public/css/client.css">
    <style>
        
    </style>
</head>
<body>

<div class="box">
    <div class="list_team">
        <?php if ($clientallData && count($clientallData) > 0): ?>
            <table class="table" border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <th>Name</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Customer ID</th>
                    <th>Customer Type</th>
                    <th>Promo Available</th>
                    <th>Profile Created</th>
                    <th>Task Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($clientallData as $user): 
                    $tasks = getTasksDataByClient($conn, $user['customer_id']);
                    $taskCount = count($tasks);
                    $dropdownId = 'dropdown_' . $user['customer_id'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['address']); ?></td>
                        <td><?php echo htmlspecialchars($user['contact']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['customer_id']); ?></td>
                        <td><?php echo ($user['customer_type'] == '1') ? 'Normal' : 'Other'; ?></td>
                        <td><?php echo ($user['promo_available'] == '1') ? 'Yes' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($user['profile_created']); ?></td>
                        <td class="relative-wrapper">
                            <?php if ($taskCount > 0): ?>
                                <button class="task-status-btn" onclick="toggleDropdown('<?php echo $dropdownId; ?>')">
                                    Active (<?php echo $taskCount; ?>)
                                </button>
                                <div id="<?php echo $dropdownId; ?>" class="task-dropdown">
                                    <ul>
                                        <?php 
                                            $sl=1;
                                            foreach ($tasks as $task): ?>
                                            <li>
                                                <?php 
                                                    $date = new DateTime($task['work_date']);
                                                    echo htmlspecialchars($sl. " — " .$task['notes']) . " — " . $date->format("d M Y");
                                                    $sl++;
                                                ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <span class="inactive-task">No Active Task</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No client data available.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    const isVisible = dropdown.style.display === 'block';
    
    // Hide all other dropdowns
    document.querySelectorAll('.task-dropdown').forEach(el => {
        el.style.display = 'none';
    });

    // Toggle current
    dropdown.style.display = isVisible ? 'none' : 'block';
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative-wrapper')) {
        document.querySelectorAll('.task-dropdown').forEach(el => {
            el.style.display = 'none';
        });
    }
});
</script>

</body>
</html>
<?php
} else {
    header("Location: /mopzilla/public/views/team_dashboard/team_dashboard.php");
    exit();
}
?>
