<?php
// Include database connection
require_once 'config.php';

// Query untuk mengambil data makanan
$sql = "SELECT * FROM makanan ORDER BY id DESC LIMIT 6";
$result = $pdo->query($sql);

// Query untuk menghitung total menu
$total_menu_sql = "SELECT COUNT(*) as total FROM makanan";
$total_menu_result = $pdo->query($total_menu_sql);
$total_menu = $total_menu_result->fetch(PDO::FETCH_ASSOC)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Fitney</title>
    <meta name="view-transition" content="same-origin">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/Dashboard-admin.css">
    <link rel="stylesheet" href="css/cross-fade-transition.css">
</head>
<body>
    <?php include 'sidebar-admin.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Dashboard Admin</h1>
            </div>
            
            <div class="header-right">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari menu atau pelanggan...">
                </div>
                
                <div class="notification-bell">
                    <i class="fas fa-bell"></i>
                    <span class="notification-count">3</span>
                </div>
                
                <div class="user-profile">
                    <div class="user-info">
                        <span class="user-name">Admin Fitney</span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Stats -->
        <section class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Menu</h3>
                    <p class="stat-number"><?php echo $total_menu; ?></p>
                    <span class="stat-change positive">Data aktual</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Kalori</h3>
                    <p class="stat-number">0 kcal</p>
                    <span class="stat-change positive"></span>
                </div>
            </div>
        </section>

        <!-- Content Grid -->
        <section class="content-grid">
            <!-- Recent Menu -->
            <div class="content-card">
                <div class="card-header">
                    <h2>Menu Terbaru</h2>
                </div>
                
                <div class="menu-grid">
                    <?php
                    if ($result->rowCount() > 0) {
                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $gambar = !empty($row['foto_makanan']) ? $row['foto_makanan'] : 'https://via.placeholder.com/150x150?text=No+Image';
                            ?>
                            <div class="menu-item">
                                <div class="menu-info">
                                    <h4><?php echo htmlspecialchars($row['nama_makanan']); ?></h4>
                                    <p>Porsi: <?php echo htmlspecialchars($row['porsi']); ?>g</p>
                                    <span class="calories"><?php echo htmlspecialchars($row['kalori']); ?> kcal</span>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p style="text-align: center; color: #666;">Belum ada data makanan.</p>';
                    }
                    ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card quick-actions">
                <div class="card-header">
                    <h2>Aksi Cepat</h2>
                </div>
                
                <div class="action-buttons">
                    <a href="Tambah-makanan.php" class="action-btn primary">
                        <i class="fas fa-plus"></i>
                        Tambah Menu Baru
                    </a>
                    <button class="action-btn secondary">
                        <i class="fas fa-cog"></i>
                        Pengaturan
                    </button>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Toggle sidebar
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });
    </script>
</body>
</html>
