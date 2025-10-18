// Enhanced Dashboard Functionality
(function() {
    'use strict';

    // Calorie tracking variables
    let dailyCalorieGoal = 2000;
    let dailyBurnGoal = 500;
    let consumedCalories = 0;
    let burnedCalories = 0;

    // Initialize dashboard
    function initDashboard() {
        loadCalorieData();
        loadMealData();
        loadActivityData();
        loadGoalData();
        setupEventListeners();
        setupMealTabs();
    }

    // Add this function to handle schedule addition with animation
    function addScheduleWithAnimation(schedule) {
        const scheduleList = document.getElementById('dailyScheduleList');
        const scheduleItem = document.createElement('div');
        scheduleItem.className = 'schedule-card';
        scheduleItem.innerHTML = `
            <div class="schedule-time">${schedule.start_time.substring(0, 5)} - ${schedule.end_time.substring(0, 5)}</div>
            <div class="schedule-details">
                <h4>${schedule.schedule_name}</h4>
                <p>${schedule.description || 'No description'}</p>
            </div>
            <div class="schedule-status upcoming">
                <i class="fas fa-clock"></i>
            </div>
        `;
        scheduleList.appendChild(scheduleItem);
        scheduleItem.classList.add('fade-in'); // Add animation class
    }

    // Load calorie data
    function loadCalorieData() {
        fetch('get_calorie_intake_vs_burn.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    consumedCalories = data.consumed;
                    burnedCalories = data.burned;
                    updateCalorieDisplay();
                }
            })
            .catch(error => console.error('Error loading calorie data:', error));
    }

    // Update calorie display
    function updateCalorieDisplay() {
        const remainingCalories = dailyCalorieGoal - consumedCalories;
        const progressPercent = Math.min((consumedCalories / dailyCalorieGoal) * 100, 100);

        document.getElementById('consumedCalories').textContent = consumedCalories;
        document.getElementById('burnedCalories').textContent = burnedCalories;
        document.getElementById('remainingCalories').textContent = remainingCalories;
        document.getElementById('calorieProgressBar').style.width = progressPercent + '%';
        document.getElementById('progressText').textContent = `${consumedCalories} / ${dailyCalorieGoal} cal`;

        // Update goal progress
        updateGoalProgress('calorie', consumedCalories, dailyCalorieGoal);
    }

    // Load meal data
    function loadMealData() {
        fetch('get_consumed_foods.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayMeals(data.consumed_foods);
                }
            })
            .catch(error => console.error('Error loading meal data:', error));
    }

    // Display meals
    function displayMeals(meals) {
        const mealList = document.getElementById('mealList');
        mealList.innerHTML = '';

        if (meals && meals.length > 0) {
            meals.forEach(meal => {
                const mealItem = document.createElement('div');
                mealItem.className = 'meal-item';
                mealItem.innerHTML = `
                    <div class="meal-info">
                        <h5>${meal.nama_makanan}</h5>
                        <p>${meal.quantity} ${meal.porsi}</p>
                    </div>
                    <div class="meal-calories">${meal.kalori} cal</div>
                `;
                mealList.appendChild(mealItem);
            });
            document.getElementById('emptyMealState').style.display = 'none';
        } else {
            document.getElementById('emptyMealState').style.display = 'block';
        }
    }

    // Load activity data
    function loadActivityData() {
        fetch('get_daily_activities_updated.php')
            .then(response => response.json())
            .then(data => {
            if (data.success) {
                console.log("API Response Data:", data); // Debugging log
                updateActivityDisplay(data);
                // Update activity goal with actual data from API
                if (data.activity_goal_percentage !== undefined) {
                    const activityMinutes = (data.activity_goal_percentage / 100) * 300; // Calculate minutes from percentage
                    console.log("Activity Minutes Calculated:", activityMinutes); // Debugging log
                    updateGoalProgress('activity', activityMinutes, 300);
                }
                }
            })
            .catch(error => console.error('Error loading activity data:', error));
    }

    // Update activity display
    function updateActivityDisplay(data) {
        // Calculate steps based on activity duration (approximate conversion)
        const totalSteps = data.daily_activities.reduce((total, activity) => {
            // Approximate steps: 100 steps per minute of moderate activity
            return total + (activity.duration_minutes * 100);
        }, 0);
        
        // Calculate total active minutes
        const totalActiveMinutes = data.daily_activities.reduce((total, activity) => {
            return total + parseInt(activity.duration_minutes);
        }, 0);
        
        document.getElementById('dailySteps').textContent = totalSteps;
        document.getElementById('activeMinutes').textContent = totalActiveMinutes;
        document.getElementById('activityCalories').textContent = data.total_burned_calories || 0;
    }

    // Load goal data
    function loadGoalData() {
        // Load calorie goal progress
        updateGoalProgress('calorie', consumedCalories, dailyCalorieGoal);
        
        // Activity goal will be loaded from activity data API
        // We'll fetch activity data which includes goal percentage
        fetch('get_daily_activities_updated.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.activity_goal_percentage !== undefined) {
                    const activityMinutes = (data.activity_goal_percentage / 100) * 300; // Calculate minutes from percentage
                    updateGoalProgress('activity', activityMinutes, 300);
                }
            })
            .catch(error => console.error('Error loading activity goal data:', error));
    }

    // Update goal progress circles
    function updateGoalProgress(type, current, goal) {
        const percent = Math.min((current / goal) * 100, 100);
        const circle = document.getElementById(`${type}GoalCircle`);
        const text = document.getElementById(`${type}GoalPercent`);
        
        if (circle && text) {
            const circumference = 2 * Math.PI * 35;
            const offset = circumference - (percent / 100) * circumference;
            
            circle.style.strokeDashoffset = offset;
            text.textContent = Math.round(percent) + '%';
            
            // Update goal details text for activity goal
            if (type === 'activity') {
                const goalDetails = document.querySelector('.goal-card:nth-child(2) .goal-details p:first-child');
                if (goalDetails) {
                    goalDetails.textContent = `${Math.round(current)} / ${goal} min`;
                }
            }
        }
    }

    // Setup meal tabs functionality
    function setupMealTabs() {
        const mealTabs = document.querySelectorAll('.meal-tab');
        mealTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                mealTabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Load meals for selected time
                const mealType = this.getAttribute('data-meal');
                loadMealsByType(mealType);
            });
        });
    }

    // Load meals by type (breakfast, lunch, dinner, snacks)
    function loadMealsByType(mealType) {
        fetch(`get_consumed_foods.php?meal_type=${mealType}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayMeals(data.consumed_foods);
                } else {
                    document.getElementById('emptyMealState').style.display = 'block';
                    document.getElementById('mealList').innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Error loading meal data:', error);
                document.getElementById('emptyMealState').style.display = 'block';
                document.getElementById('mealList').innerHTML = '';
            });
    }

    // Setup event listeners
    function setupEventListeners() {
        // Refresh data every 30 seconds
        setInterval(() => {
            loadCalorieData();
            loadActivityData();
        }, 30000);

        // Handle window resize for charts
        window.addEventListener('resize', () => {
            // Chart.js handles resize automatically
        });

        // Error handling for failed API calls
        window.addEventListener('error', function(e) {
            console.error('JavaScript error:', e.error);
            showErrorNotification('An error occurred. Please refresh the page.');
        });

        // Handle offline/online status
        window.addEventListener('offline', function() {
            showErrorNotification('You are offline. Some features may not work.');
        });

        window.addEventListener('online', function() {
            showSuccessNotification('Connection restored.');
            // Refresh data when coming back online
            loadCalorieData();
            loadActivityData();
        });
    }

    // Show error notification
    function showErrorNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'notification-error';
        notification.innerHTML = `
            <i class="fas fa-exclamation-circle"></i>
            <span>${message}</span>
        `;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f44336;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Show success notification
    function showSuccessNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'notification-success';
        notification.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>${message}</span>
        `;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4caf50;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Initialize when DOM is loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashboard);
    } else {
        initDashboard();
    }

    // Export for global use
    window.DashboardEnhanced = {
        loadCalorieData: loadCalorieData,
        loadMealData: loadMealData,
        updateCalorieDisplay: updateCalorieDisplay
    };
})();


// Function to handle adding a new schedule
function handleAddSchedule(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    fetch('save_daily_schedule.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add the new schedule to the list with animation
            addScheduleWithAnimation(data.schedule);
            
            // Close the modal
            const modal = document.getElementById('add-schedule-modal');
            modal.style.display = 'none';
            
            // Reset the form
            form.reset();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the schedule.');
    });
}

// Attach event listener to the form
document.addEventListener('DOMContentLoaded', function() {
    const addScheduleForm = document.getElementById('add-schedule-form');
    if (addScheduleForm) {
        addScheduleForm.addEventListener('submit', handleAddSchedule);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Check if anime.js is available
    if (typeof anime === 'undefined') {
        console.warn('anime.js is not loaded. Skipping animations.');
        // Fallback to make cards visible if anime.js is missing
        document.querySelectorAll('.dashboard-card').forEach(card => {
            card.style.opacity = 1;
        });
        return;
    }

    const cards = document.querySelectorAll('.dashboard-card');

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Use anime.js to animate the card
                anime({
                    targets: entry.target,
                    translateY: [50, 0],
                    opacity: [0, 1],
                    scale: [0.95, 1],
                    duration: 700,
                    easing: 'easeOutCubic',
                    // Use a delay based on the card's position in the NodeList
                    // This creates the staggered effect.
                    delay: Array.from(cards).indexOf(entry.target) * 100
                });
                // Stop observing the card once it has been animated
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    // Observe each card
    cards.forEach(card => {
        observer.observe(card);
    });
});