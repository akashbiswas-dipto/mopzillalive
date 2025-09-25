<?php
include_once("navbar.php");
if (isset($_SESSION['usertype']) && ($_SESSION['usertype'] === '1' || $_SESSION['usertype'] === '2')) { 
    $filter = $_GET['filter'] ?? 'week'; // default to week
    $taskData = getTaskData($conn, $_SESSION['usertype']);
    $periods = [];

    if ($taskData && count($taskData) > 0) {
        foreach ($taskData as $task) {
            $date = new DateTime($task['work_date']);

            if ($filter === 'month') {
                $periodKey = $date->format('Y-m'); // monthly grouping
            } else {
                $weekStart = clone $date;
                $weekStart->modify('monday this week');
                $weekEnd = clone $weekStart;
                $weekEnd->modify('sunday this week');
                $periodKey = $weekStart->format('Y-m-d') . ' to ' . $weekEnd->format('Y-m-d'); // weekly grouping
            }

            if (!isset($periods[$periodKey])) {
                $periods[$periodKey] = [
                    'total_hours' => 0,
                    'total_earnings' => 0,
                    'usertype2_earnings' => 0
                ];
            }

            // Add hours
            $periods[$periodKey]['total_hours'] += $task['workinghour'];

            // Earnings calculation
            if ($_SESSION['usertype'] === '1') {
                $earning = ($task['workinghour'] * $task['hourly_rate']) + $task['agency_fee'];
                $periods[$periodKey]['total_earnings'] += $earning;
                if(!empty($task['team_member2'])){
                    $earning_team = (($task['workinghour'] / 2) * 22.5);
                    $periods[$periodKey]['usertype2_earnings'] += $earning_team;
                }
                $earning_team = (($task['workinghour'] / 2) * 22.5);
                $periods[$periodKey]['total_earnings'] += $earning_team;
                $periods[$periodKey]['usertype2_earnings'] += $earning_team;
            }
            elseif ($_SESSION['usertype'] === '2') {
                $earning = (($task['workinghour'] / 2) * 22.5);
                $periods[$periodKey]['total_earnings'] += $earning;
                $periods[$periodKey]['usertype2_earnings'] += $earning;
            }
        }

        // Calculate profit
        foreach ($periods as $key => &$data) {
            $data['profit'] = $data['total_earnings'] - $data['usertype2_earnings'];
        }
        unset($data);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['team_username'];?> - Earning Statement</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>public/css/es.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    </style>
</head>
<body>
    <div class="box">
        <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Earning Statement</h2>

        <!-- Filter -->
        <div class="filter">
            <form method="get" action="">
                <label for="filter">View by: </label>
                <select name="filter" id="filter" onchange="this.form.submit()">
                    <option value="week" <?= $filter === 'week' ? 'selected' : '' ?>>Weekly</option>
                    <option value="month" <?= $filter === 'month' ? 'selected' : '' ?>>Monthly</option>
                </select>
            </form>
        </div>

        <!-- Graph -->
        <div class="chart">
            <h3 style="text-align: center; margin-bottom: 20px;">
                <?= ucfirst($filter) ?> Earnings (Graph)
            </h3>
            <canvas id="earningsChart"></canvas>
        </div>
    </div>

    <script>
        // PHP → JS data
        const labels   = <?= json_encode(array_keys($periods)) ?>;
        const earnings = <?= json_encode(array_column($periods, 'total_earnings')) ?>;
        const hours    = <?= json_encode(array_column($periods, 'total_hours')) ?>;
        <?php if($_SESSION['usertype'] === '1'){?>
        const profit   = <?= json_encode(array_column($periods, 'profit')) ?>;
        <?php } ?>
        // Chart.js
        const ctx = document.getElementById('earningsChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Earnings ($)',
                        data: earnings,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Profit ($)',
                        data: profit,
                        backgroundColor: 'rgba(75, 192, 75, 0.6)',
                        borderColor: 'rgba(75, 192, 75, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Hours Worked',
                        data: hours,
                        borderColor: 'rgba(255, 159, 64, 1)',
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        borderWidth: 2,
                        type: 'line',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Earnings ($)' }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        title: { display: true, text: 'Hours' }
                    }
                }
            }
        });
    </script>
</body>
</html>
<?php
} else {
    header("location:".$base_url."public/views/login_user.php?error=Unauthorized access");
    exit();
}
?>
