// Add Activity Form Handler - FIXED VERSION
document.addEventListener('DOMContentLoaded', function() {
    
    
    const addActivityForm = document.getElementById('addActivityForm');
    const formMessage = document.getElementById('formMessage');

    // Set today's date as default
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('activityDate').value = today;

    

    

    

    // Form submission
    addActivityForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            activity_name: document.getElementById('activityName').value.trim(),
            duration_minutes: parseInt(document.getElementById('durationMinutes').value),
            calories_burned: parseFloat(document.getElementById('caloriesBurned').value),
            tanggal: document.getElementById('activityDate').value
        };

        // Validation
        if (!formData.activity_name) {
            showMessage('Please enter an activity name', 'error');
            return;
        }

        if (formData.duration_minutes <= 0) {
            showMessage('Please enter a valid duration', 'error');
            return;
        }

        if (formData.calories_burned <= 0) {
            showMessage('Please enter valid calories burned', 'error');
            return;
        }

        // Submit data
        fetch('save_daily_activity.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Activity added successfully!', 'success');
                                resetForm();
                refreshActivitiesList();
                // Trigger calorie summary update
                if (typeof updateCalorieSummary === 'function') {
                        updateCalorieSummary();
                    }
                // Dispatch custom event for other components
                document.dispatchEvent(new CustomEvent('activityAdded'));
            } else {
                showMessage(data.message || 'Error adding activity', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Network error. Please try again.', 'error');
        });
    });

    function showMessage(message, type) {
        formMessage.textContent = message;
        formMessage.style.color = type === 'success' ? '#28a745' : '#dc3545';
    }

    function resetForm() {
        addActivityForm.reset();
        document.getElementById('activityDate').value = today;
        formMessage.textContent = '';
    }

    function refreshActivitiesList() {
        // Refresh the activities list
        fetch('get_daily_activities.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.daily_activities.length > 0) {
                    const dailyTableBody = document.getElementById('dailyActivitiesDetailsTableBody');
                    dailyTableBody.innerHTML = '';
                    let totalBurnedCalories = 0;
                    
                    data.daily_activities.forEach(activity => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${activity.id}</td>
                            <td>${activity.activity_name}</td>
                            <td>${activity.duration_minutes}</td>
                            <td>${activity.calories_burned}</td>
                            <td>${activity.tanggal}</td>
                            <td>${activity.created_at}</td>
                        `;
                        dailyTableBody.appendChild(row);
                        totalBurnedCalories += parseFloat(activity.calories_burned) || 0;
                    });
                    
                    document.getElementById('currentBurnedCalories').innerText = totalBurnedCalories.toFixed(2);
                    
                    // Update progress bar
                    const maxCalories = 500;
                    const progressPercent = Math.min((totalBurnedCalories / maxCalories) * 100, 100);
                    document.getElementById('burnedCaloriesProgressFill').style.width = progressPercent + '%';
                }
            })
            .catch(error => console.error('Error refreshing activities:', error));
    }
});
