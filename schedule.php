<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitney Schedule</title>
    <meta name="view-transition" content="same-origin">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/Dashboard-pelanggan.css"> <!-- Reusing some styles -->
    <link rel="stylesheet" href="css/Schedule.css">
    <link rel="stylesheet" href="css/cross-fade-transition.css">
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
    <script src="js/staggered-list-animator.js"></script>
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
                    <span class="username"><?php echo $_SESSION['username']; ?></span>
                    <span class="role"><?php echo ucfirst($_SESSION['role']); ?></span>
                </div>
            </div>
        </header>

        <div class="dashboard-grid animated-container">
            <div class="schedule-section animated-item" style="padding: 20px;"> 
                <h3>Today's Schedule</h3>
                <div id="calendar-container"></div>
                <div class="schedule-grid" id="dailyScheduleGrid">
                    <!-- Schedule items will be loaded here dynamically -->
                </div>
                <div class="empty-state" id="emptyScheduleState" style="display: none;">
                    <i class="fas fa-calendar-check"></i>
                    <p>No schedule entries for today.</p>
                    <small>Add your daily plans here!</small>
                </div>
            </div>

            <div class="add-schedule-section animated-item">
                <h3>Add New Schedule</h3>
                <form id="addScheduleForm" class="schedule-form">
                    <div class="form-group">
                        <label for="scheduleDate">Date</label>
                        <input type="date" id="scheduleDate" required>
                    </div>
                    <div class="form-group">
                        <label for="scheduleName">Schedule Name</label>
                        <input type="text" id="scheduleName" placeholder="e.g., Morning Workout" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="startTime">Start Time</label>
                            <input type="time" id="startTime" required>
                        </div>
                        <div class="form-group">
                            <label for="endTime">End Time</label>
                            <input type="time" id="endTime" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea id="description" placeholder="e.g., Focus on cardio and abs"></textarea>
                    </div>
                    <button type="submit" class="btn-add-schedule">
                        <i class="fas fa-plus"></i> Add Schedule
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarContainer = document.getElementById('calendar-container');
            const scheduleDateInput = document.getElementById('scheduleDate');
            let selectedDate = new Date();

            function renderCalendar(date) {
                calendarContainer.innerHTML = '';
                const month = date.getMonth();
                const year = date.getFullYear();

                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);

                const calendarHeader = document.createElement('div');
                calendarHeader.className = 'calendar-header';
                calendarHeader.innerHTML = `
                    <button id="prev-month"><i class="fas fa-chevron-left"></i></button>
                    <h2>${date.toLocaleString('default', { month: 'long' })} ${year}</h2>
                    <button id="next-month"><i class="fas fa-chevron-right"></i></button>
                `;
                calendarContainer.appendChild(calendarHeader);

                const calendarGrid = document.createElement('div');
                calendarGrid.className = 'calendar-grid';

                const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                daysOfWeek.forEach(day => {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day-name';
                    dayElement.textContent = day;
                    calendarGrid.appendChild(dayElement);
                });

                for (let i = 0; i < firstDay.getDay(); i++) {
                    const emptyDay = document.createElement('div');
                    calendarGrid.appendChild(emptyDay);
                }

                for (let i = 1; i <= lastDay.getDate(); i++) {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day';
                    dayElement.textContent = i;
                    dayElement.dataset.date = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

                    if (i === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) {
                        dayElement.classList.add('today');
                    }
                    if (i === selectedDate.getDate() && month === selectedDate.getMonth() && year === selectedDate.getFullYear()) {
                        dayElement.classList.add('selected');
                    }

                    calendarGrid.appendChild(dayElement);
                }

                calendarContainer.appendChild(calendarGrid);

                document.getElementById('prev-month').addEventListener('click', () => {
                    selectedDate.setMonth(selectedDate.getMonth() - 1);
                    renderCalendar(selectedDate);
                });

                document.getElementById('next-month').addEventListener('click', () => {
                    selectedDate.setMonth(selectedDate.getMonth() + 1);
                    renderCalendar(selectedDate);
                });

                calendarGrid.addEventListener('click', (e) => {
                    if (e.target.classList.contains('calendar-day')) {
                        selectedDate = new Date(e.target.dataset.date + 'T00:00:00');
                        scheduleDateInput.value = e.target.dataset.date;
                        renderCalendar(selectedDate);
                        fetchDailySchedule(e.target.dataset.date);
                    }
                });
            }

            function fetchDailySchedule(date) {
                fetch(`get_daily_schedule.php?date=${date}`)
                    .then(response => response.json())
                    .then(data => {
                        const scheduleGrid = document.getElementById('dailyScheduleGrid');
                        const emptyState = document.getElementById('emptyScheduleState');
                        scheduleGrid.innerHTML = ''; // Clear existing schedule items

                        if (data.success && data.schedules.length > 0) {
                            emptyState.style.display = 'none';
                            scheduleGrid.style.display = 'grid';
                            data.schedules.forEach(schedule => {
                                const scheduleItem = document.createElement('div');
                                scheduleItem.className = 'schedule-card';
                                scheduleItem.innerHTML = `
                                    <div class="schedule-time">${schedule.start_time.substring(0, 5)} - ${schedule.end_time.substring(0, 5)}</div>
                                    <h4 class="schedule-title">${schedule.schedule_name}</h4>
                                    <p class="schedule-description">${schedule.description || 'No description'}</p>
                                `;
                                scheduleGrid.appendChild(scheduleItem);
                            });
                        } else {
                            emptyState.style.display = 'block';
                            scheduleGrid.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching daily schedule:', error);
                        document.getElementById('emptyScheduleState').style.display = 'block';
                        document.getElementById('dailyScheduleGrid').style.display = 'none';
                    });
            }

            document.getElementById('addScheduleForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const scheduleName = document.getElementById('scheduleName').value.trim();
                const startTime = document.getElementById('startTime').value;
                const endTime = document.getElementById('endTime').value;
                const description = document.getElementById('description').value.trim();
                const scheduleDate = document.getElementById('scheduleDate').value;

                if (!scheduleName || !startTime || !endTime || !scheduleDate) {
                    alert('Please fill in all required fields.');
                    return;
                }

                fetch('save_daily_schedule.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        schedule_name: scheduleName,
                        start_time: startTime,
                        end_time: endTime,
                        description: description,
                        schedule_date: scheduleDate
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Jadwal berhasil disimpan!');
                        this.reset();
                        fetchDailySchedule(scheduleDate); // Reload schedules after saving
                    } else {
                        alert('Gagal menyimpan jadwal: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan jadwal.');
                });
            });

            const today = new Date().toISOString().slice(0, 10);
            scheduleDateInput.value = today;
            renderCalendar(selectedDate);
            fetchDailySchedule(today); // Initial fetch on page load
        });
    </script>
</body>
</html>