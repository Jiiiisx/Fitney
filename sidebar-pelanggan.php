<div class="sidebar">
    <div class="logo">
        <i class="fas fa-dumbbell"></i>
        <span>Fitney</span>
    </div>
    <nav class="nav-menu">
        <a href="dashboard-pelanggan.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard-pelanggan.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="Food.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'Food.php' ? 'active' : ''; ?>">
            <i class="fas fa-utensils"></i>
            <span>Calorie Tracker</span>
        </a>
        <a href="schedule.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'schedule.php' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt"></i>
            <span>Schedule</span>
        </a>
        <a href="logout.php" class="nav-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Log out</span>
        </a>
    </nav>
</div>
