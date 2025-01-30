// Popup functionality
document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('notification-popup');
    if (popup) {
            popup.style.display = 'block'; // Ensure popup is shown if notifications exist
            const closePopup = document.getElementById('close-popup');
            closePopup.addEventListener('click', () => {
                popup.style.display = 'none';
        });
    }

    // Chart Data (fetched from PHP variables)
    const locations = JSON.parse(document.getElementById('chart-data-locations').textContent);
    const capacities = JSON.parse(document.getElementById('chart-data-capacities').textContent);
    const statuses = JSON.parse(document.getElementById('chart-data-statuses').textContent);
    const rainStatuses = JSON.parse(document.getElementById('chart-data-rainStatuses').textContent);

    // Bin Capacity Chart
    const ctx1 = document.getElementById('binCapacityChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: locations,
            datasets: [{
                label: 'Bin Capacity (%)',
                data: capacities,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Bin Status Chart
    const statusCounts = statuses.reduce((acc, status) => {
        acc[status] = (acc[status] || 0) + 1;
        return acc;
    }, {});

    const ctx2 = document.getElementById('binStatusChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: Object.keys(statusCounts),
            datasets: [{
                data: Object.values(statusCounts),
                backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(255, 99, 132, 0.6)', 'rgba(75, 192, 192, 0.6)']
            }]
        },
        options: {
            responsive: true
        }
    });

    // Rain Status Chart
    const rainCounts = rainStatuses.reduce((acc, status) => {
        acc[status] = (acc[status] || 0) + 1;
        return acc;
    }, {});

    const ctx3 = document.getElementById('binRainChart').getContext('2d');
    new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: Object.keys(rainCounts),
            datasets: [{
                data: Object.values(rainCounts),
                backgroundColor: ['rgba(153, 102, 255, 0.6)', 'rgba(255, 159, 64, 0.6)', 'rgba(201, 203, 207, 0.6)']
            }]
        },
        options: {
            responsive: true
        }
    });
});
