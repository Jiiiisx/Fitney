<nav class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-dumbbell"></i>
            <span>FITNEY</span>
        </div>
    </div>
    
    <ul class="sidebar-menu">
        <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard-admin.php' ? 'active' : ''; ?>">
            <a href="dashboard-admin.php">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'menu-makanan.php' ? 'active' : ''; ?>">
            <a href="menu-makanan.php">
                <i class="fas fa-utensils"></i>
                <span>Menu Makanan</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="Login.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>
