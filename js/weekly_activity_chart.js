// Weekly Activity Chart Implementation
document.addEventListener('DOMContentLoaded', function() {
    // Function to get weekly activity data
    function fetchWeeklyActivityData() {
        fetch('get_weekly_activities.php')
            .then(response => response.json())
            .then(data => {
                renderWeeklyActivityChart(data);
            })
            .catch(error => {
                console.error('Error fetching weekly activity data:', error);
                // Fallback to dummy data if fetch fails
                const dummyData = {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    minutes: [30, 45, 60, 40, 75, 50, 80]
                };
                renderWeeklyActivityChart(dummyData);
            });
    }

    // Function to render the weekly activity chart
    function renderWeeklyActivityChart(data) {
        const ctx = document.getElementById('weeklyActivityChart');
        if (!ctx) return;

        const chart = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Daily Activity (minutes)',
                    data: data.minutes,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' minutes';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + ' min';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    // Initialize the chart
    fetchWeeklyActivityData();
});
