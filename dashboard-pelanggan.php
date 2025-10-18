<?php
session_start();
require_once 'config.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitney Dashboard</title>
    <meta name="view-transition" content="same-origin">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/Dashboard-pelanggan.css">
    <link rel="stylesheet" href="css/dashboard-enhanced.css">
    <link rel="stylesheet" href="css/cross-fade-transition.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
</head>
<body>
    <?php include 'sidebar-pelanggan.php'; ?>

    <div class="main-content">
        <header class="top-header">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
            </div>
            <div class="user-profile">
                <div class="notification">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </div>
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile" class="profile-img">
                <div class="user-info">
<span class="username"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></span>
                </div>
            </div>
        </header>

        <div class="dashboard-grid">

            <!-- Row 2: Calorie Tracking -->
            <div class="dashboard-card calorie-tracker">
                <div class="card-header">
                    <h3>Daily Calorie Tracking</h3>
                    <button class="btn-add-meal" onclick="window.location.href='Food.php'">
                        <i class="fas fa-plus"></i> Add Meal
                    </button>
                </div>
<div class="calorie-summary">
    <div class="calorie-metric">
        <h4>Consumed</h4>
        <div class="value" id="consumedCalories">0</div>
        <div class="target">Target: 2000 cal</div>
    </div>
    <div class="calorie-metric">
        <h4>Burned</h4>
        <div class="value" id="burnedCalories">0</div>
        <div class="target">Goal: 500 cal</div>
    </div>
    <div class="calorie-metric">
        <h4>Remaining</h4>
        <div class="value" id="remainingCalories">2000</div>
        <div class="target">Daily Goal</div>
    </div>
</div>
<div class="calorie-progress">
    <div class="progress-bar-container">
        <div class="progress-bar" id="calorieProgressBar" style="width: 0%"></div>
    </div>
    <div class="progress-info">
        <span>0%</span>
        <span id="progressText">0 / 2000 cal</span>
        <span>100%</span>
    </div>
</div>
            </div>

            <!-- Row 2: Activity Overview -->
            <div class="dashboard-card activity-overview">
                <h3>Activity Overview</h3>
                <div class="activity-stats">
                    <div class="activity-stat">
                        <h4>Steps Todays</h4>
                        <div class="value" id="dailySteps">0</div>
                    </div>
                    <div class="activity-stat">
                        <h4>Active Minutes</h4>
                        <div class="value" id="activeMinutes">0</div>
                    </div>
                    <div class="activity-stat">
                        <h4>Calories Burned</h4>
                        <div class="value" id="activityCalories">0</div>
                    </div>
                </div>
                <canvas id="activityChart"></canvas>
            </div>

            <!-- Row 3: Weekly Progress Charts -->
            <div class="dashboard-card weekly-progress">
                <h3>Weekly Progress</h3>
                <div class="weekly-charts">
                    <div class="chart-card">
                        <h4>Weekly Activity Trend</h4>
                        <canvas id="weeklyActivityChart"></canvas>
                    </div>
                    <div class="chart-card">
                        <h4>Calorie Intake vs Burn</h4>
                        <canvas id="calorieChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Row 3: Goal Setting -->
            <div class="dashboard-card goal-setting">
                <h3>Weekly Goals</h3>
                <div class="goal-cards">
                    <div class="goal-card">
                        <h4>Calorie Goal</h4>
                        <div class="goal-progress-circle">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="35" stroke="#e0e0e0" stroke-width="6" fill="none"/>
                                <circle cx="40" cy="40" r="35" stroke="#4caf50" stroke-width="6" fill="none" 
                                        stroke-dasharray="220" stroke-dashoffset="110" id="calorieGoalCircle"/>
                            </svg>
                            <div class="goal-progress-text" id="calorieGoalPercent">50%</div>
                        </div>
                        <div class="goal-details">
                            <p>1000 / 2000 cal</p>
                            <p>5 days remaining</p>
                        </div>
                    </div>
                    <div class="goal-card">
                        <h4>Activity Goal</h4>
                        <div class="goal-progress-circle">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="35" stroke="#e0e0e0" stroke-width="6" fill="none"/>
                                <circle cx="40" cy="40" r="35" stroke="#2196f3" stroke-width="6" fill="none" 
                                        stroke-dasharray="220" stroke-dashoffset="165" id="activityGoalCircle"/>
                            </svg>
                        <div class="goal-progress-text" id="activityGoalPercent">0%</div>
                        </div>
                        <div class="goal-details">
                            <p>75 / 300 min</p>
                            <p>5 days remaining</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 4: Meal Planning -->
            <div class="dashboard-card meal-planner">
                <h3>Today's Meal Plan</h3>
                <div class="meal-list" id="mealList">
                    <!-- Meals will be loaded dynamically -->
                </div>
                <div class="empty-state" id="emptyMealState" style="display: none;">
                    <i class="fas fa-utensils"></i>
                    <p>No meals planned for this time.</p>
                    <small>Add meals in the Calorie Tracker page!</small>
                </div>
            </div>

            <!-- Row 4: Recent Activities -->
            <div class="dashboard-card recent-activities">
                <h3>Recent Activities</h3>
                <div class="activities-list" id="recentActivitiesList">
                    <!-- Activities will be loaded dynamically -->
                </div>
                <div class="empty-state" id="emptyActivitiesState" style="display: none;">
                    <i class="fas fa-running"></i>
                    <p>No recent activities recorded.</p>
                    <small>Add activities to track your progress!</small>
                </div>
            </div>

            <!-- Row 4: Daily Schedule -->
            <div class="dashboard-card daily-schedule">
                <h3>Today's Schedule</h3>
                <div class="schedule-list" id="dailyScheduleListDashboard">
                    <!-- Schedule items will be loaded dynamically -->
                </div>
                <div class="empty-state" id="emptyScheduleStateDashboard" style="display: none;">
                    <i class="fas fa-calendar"></i>
                    <p>No schedule for today.</p>
                    <small>Add activities to your schedule!</small>
                </div>
            </div>
        <!-- New Row: All Food Items -->
            <div class="dashboard-card all-food-items-card grid-col-span-12">
                <div class="card-header">
                    <h3>All Food Items</h3>
                </div>
                <div class="food-list-container" id="allFoodList">
                    <!-- Food items will be loaded here -->
                </div>
                <div class="empty-state" id="emptyFoodState" style="display: none;">
                    <i class="fas fa-utensils"></i>
                    <p>No food items found.</p>
                </div>
            </div>
        </div>

    <script src="js/weekly_activity_chart.js"></script>
    <script src="js/dashboard-enhanced.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Calorie Chart
            const calorieCtx = document.getElementById('calorieChart').getContext('2d');
            let calorieChart = new Chart(calorieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Consumed', 'Burned'],
                    datasets: [{
                        data: [0, 0],
                        backgroundColor: ['#ff6b6b', '#4ecdc4'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // Fetch calorie data
            fetch('get_calorie_intake_vs_burn.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        calorieChart.data.datasets[0].data = [data.consumed, data.burned];
                        calorieChart.update();
                    }
                });

            // Fetch and display recent activities
            function fetchRecentActivities() {
                fetch('get_daily_activities.php')
                    .then(response => response.json())
                    .then(data => {
                        const activityList = document.getElementById('recentActivitiesList');
                        const emptyState = document.getElementById('emptyActivitiesState');
                        activityList.innerHTML = ''; // Clear existing activities

                        if (data.success && data.daily_activities.length > 0) {
                            emptyState.style.display = 'none';
                            data.daily_activities.forEach(activity => {
                                const activityItem = document.createElement('div');
                                activityItem.className = 'activity-item';
                                activityItem.innerHTML = `
                                    <div class="activity-icon">
                                        <i class="fas fa-running"></i>
                                    </div>
                                    <div class="activity-details">
                                        <h4>${activity.activity_name}</h4>
                                        <p>${activity.duration_minutes} min • ${activity.calories_burned} cal</p>
                                        <span class="time">Today</span>
                                    </div>
                                    <div class="activity-status completed">
                                        <i class="fas fa-check"></i>
                                    </div>
                                `;
                                activityList.appendChild(activityItem);
                            });
                        } else {
                            emptyState.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching recent activities:', error);
                        document.getElementById('emptyActivitiesState').style.display = 'block';
                    });
            }

            // Call the function to fetch activities on page load
            fetchRecentActivities();

            // Fetch and display daily schedule
            function fetchDailyScheduleDashboard() {
                fetch('get_daily_schedule.php')
                    .then(response => response.json())
                    .then(data => {
                        const scheduleList = document.getElementById('dailyScheduleListDashboard');
                        const emptyState = document.getElementById('emptyScheduleStateDashboard');
                        scheduleList.innerHTML = ''; // Clear existing schedules

                        if (data.success && data.schedules.length > 0) {
                            emptyState.style.display = 'none';
                            data.schedules.forEach(schedule => {
                                const scheduleItem = document.createElement('div');
                                scheduleItem.className = 'schedule-card';
                                scheduleItem.innerHTML = `
                                    <div class="schedule-time">${schedule.start_time.substring(0, 5)}</div>
                                    <div class="schedule-details">
                                        <h4>${schedule.schedule_name}</h4>
                                        <p>${schedule.description || 'No description'}</p>
                                    </div>
                                    <div class="schedule-status upcoming">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                `;
                                scheduleList.appendChild(scheduleItem);
                            });
                        } else {
                            emptyState.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching daily schedule:', error);
                        document.getElementById('emptyScheduleStateDashboard').style.display = 'block';
                    });
            }

            // Call the function to fetch daily schedule on page load
            fetchDailyScheduleDashboard();

            // Fetch and display all food items
            function fetchAllFoodItems() {
                fetch('get_all_foods.php')
                    .then(response => response.json())
                    .then(data => {
                        const foodListContainer = document.getElementById('allFoodList');
                        const emptyState = document.getElementById('emptyFoodState');
                        foodListContainer.innerHTML = ''; // Clear existing items

                        if (data.success && data.foods.length > 0) {
                            emptyState.style.display = 'none';
                            data.foods.forEach(food => {
                                const foodItem = document.createElement('div');
                                foodItem.className = 'food-item-card'; // A new class for styling
                                foodItem.innerHTML = `
                                    <h4>${food.nama_makanan}</h4>
                                    <p>Kategori: ${food.kategori || 'N/A'}</p>
                                    <p>Porsi: ${food.porsi}g</p>
                                    <span class="calories-badge">${food.kalori} kcal</span>
                                `;
                                foodListContainer.appendChild(foodItem);
                            });
                        } else {
                            emptyState.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching all food items:', error);
                        document.getElementById('emptyFoodState').style.display = 'block';
                    });
            }

            // Call the function to fetch all food items on page load
            fetchAllFoodItems();
        });
    </script>
</body>
</html>