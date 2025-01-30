<?php
session_start();
require 'db_config.php'; // Assume this file contains database connection logic

// Ensure user is logged in and is a collector
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'collector') {
    header("Location: login.php");
    exit;
}

// Fetch data for charts
$query = "SELECT location, capacity, rain_status, status FROM smart_bins";
$result = $conn->query($query);

$locations = [];
$capacities = [];
$statuses = [];
$rainStatuses = [];

while ($row = $result->fetch_assoc()) {
    $locations[] = $row['location'];
    $capacities[] = $row['capacity'];
    $statuses[] = $row['status'];
    $rainStatuses[] = $row['rain_status'];
}

// Fetch notifications
$notifications = []; // Initialize as an empty array
$notif_query = "SELECT location, capacity FROM smart_bins WHERE capacity >= 90";
$notif_result = $conn->query($notif_query);

while ($notif = $notif_result->fetch_assoc()) {
    $notifications[] = "Bin at " . $notif['location'] . " is nearly full (" . $notif['capacity'] . "%).";
}

// Count summary for info cards
$total_bins = count($locations);
$nearly_full_bins = count($notifications);
$rain_affected_bins = count(array_filter($rainStatuses, fn($status) => $status === 'rain'));
?>

<style>
    .dashboard-container {
    display: flex;
    min-height: 100vh;
}

.main-content {
    flex: 1;
    padding: 20px;
}

.alert {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    margin-bottom: 20px;
}

.info-cards {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.card {
    flex: 1;
    background: #fff;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    text-align: center;
}

.chart-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.chart-container {
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

</style>

<?php include 'header.php'; ?>

<div class="dashboard-container">

    <main class="main-content">
        <!-- Notifications -->
            <?php if (count($notifications) > 0): ?>
                <div class="alert" id="notification-alert">
                    <button style="float: right; background: none; border: none; color: #721c24; font-size: 16px; font-weight: bold; cursor: pointer;" onclick="closeNotification()">X</button>
                    <?php foreach ($notifications as $notif): ?>
                            <p>&#9888; <?php echo $notif; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <!-- Info Cards -->
        <div class="info-cards">
            <div class="card">
                <h3>Total Bins</h3>
                <p><?php echo $total_bins; ?></p>
            </div>
            <div class="card">
                <h3>Nearly Full Bins</h3>
                <p><?php echo $nearly_full_bins; ?></p>
            </div>
            <div class="card">
                <h3>Rain Affected Bins</h3>
                <p><?php echo $rain_affected_bins; ?></p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-section">
            <h2>Bin Data Overview</h2>
            <div class="chart-grid">
                <div class="chart-container">
                    <h3>Bin Capacity Overview</h3>
                    <canvas id="binCapacityChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Bin Status Distribution</h3>
                    <canvas id="binStatusChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Rain Status Overview</h3>
                    <canvas id="binRainChart"></canvas>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="script.js"></script>

<?php include 'footer.php'; ?>

<script id="chart-data-locations" type="application/json">
    <?php echo json_encode($locations); ?>
</script>
<script id="chart-data-capacities" type="application/json">
    <?php echo json_encode($capacities); ?>
</script>
<script id="chart-data-statuses" type="application/json">
    <?php echo json_encode($statuses); ?>
</script>
<script id="chart-data-rainStatuses" type="application/json">
    <?php echo json_encode($rainStatuses); ?>
</script>
<script>
    function closeNotification() {
        const notificationAlert = document.getElementById('notification-alert');
        if (notificationAlert) {
            notificationAlert.style.display = 'none';
        }
    }
</script>

