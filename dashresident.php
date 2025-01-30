<?php
session_start();
require 'db_config.php';

// Ensure user is logged in and is a resident
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'resident') {
    header("Location: login.php");
    exit;
}

// Fetch user's name
$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT name FROM users WHERE user_id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

// Fetch bin data
$bin_query = "SELECT location, capacity, rain_status, status FROM smart_bins";
$bin_result = $conn->query($bin_query);
$bins = [];

while ($bin = $bin_result->fetch_assoc()) {
    $bins[] = $bin;
}

// Fetch notifications
$notifications = [];
foreach ($bins as $bin) {
    if ($bin['capacity'] >= 90) {
        $notifications[] = "Bin at {$bin['location']} is nearly full ({$bin['capacity']}%).";
    }
}
?>

<?php include 'header.php'; ?>

<div class="dashboard-container">
    <div class="welcome-section">
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
        <p>Your Smart Waste Management Dashboard</p>
    </div>

    <!-- Notifications Section -->
    <div class="notifications-section">
        <h2>Notifications</h2>
        <ul>
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notif): ?>
                    <li class="notification-item">&#9888; <?php echo htmlspecialchars($notif); ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="notification-item">No alerts at the moment. You're good to go!</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Bin Status Section -->
    <div class="bin-status-section">
        <h2>Bin Overview</h2>
        <div class="bin-cards">
            <?php foreach ($bins as $bin): ?>
                <div class="bin-card">
                    <h3><?php echo htmlspecialchars($bin['location']); ?></h3>
                    <p>Capacity: <?php echo $bin['capacity']; ?>%</p>
                    <p>Status: <?php echo htmlspecialchars($bin['status']); ?></p>
                    <p>Rain: <?php echo htmlspecialchars($bin['rain_status']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Map Section -->
    <div class="map-section">
        <h2>Nearby Bins</h2>
        <div id="map"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<script>
    // Initialize the map
    const map = L.map('map').setView([3.139, 101.6869], 12); // Replace with default coordinates
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add bin markers
    const bins = <?php echo json_encode($bins); ?>;
    bins.forEach(bin => {
        L.marker([bin.latitude, bin.longitude]) // Replace with actual coordinates
            .addTo(map)
            .bindPopup(`<strong>${bin.location}</strong><br>Capacity: ${bin.capacity}%<br>Status: ${bin.status}<br>Rain: ${bin.rain_status}`);
    });
</script>

<?php include 'footer.php'; ?>


<style>
    /* Dashboard Styling */
.dashboard-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
    font-family: 'Arial', sans-serif;
}

.welcome-section {
    text-align: center;
    background: #4CAF50;
    color: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.notifications-section {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.notifications-section ul {
    list-style: none;
    padding: 0;
}

.notification-item {
    margin-bottom: 10px;
    color: #d9534f;
    font-weight: bold;
}

.bin-status-section {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.bin-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.bin-card {
    background: #f4f4f4;
    padding: 20px;
    border-radius: 10px;
    width: 250px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    text-align: center;
}

.map-section {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

#map {
    height: 400px;
    border-radius: 10px;
    margin-top: 10px;
}

</style>