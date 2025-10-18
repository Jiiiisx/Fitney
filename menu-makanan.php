<?php
// Include database connection
require_once 'config.php';

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    try {
        $pdo->beginTransaction();

        // Get image filename to delete from server
        $get_image_sql = "SELECT foto_makanan FROM makanan WHERE id = :id";
        $stmt = $pdo->prepare($get_image_sql);
        $stmt->execute(['id' => $delete_id]);
        $image_row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($image_row && !empty($image_row['foto_makanan'])) {
            $image_path = $image_row['foto_makanan'];
            // Delete image file if exists
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // First, delete dependencies from consumed_foods
        $delete_consumed_sql = "DELETE FROM consumed_foods WHERE food_id = :food_id";
        $stmt = $pdo->prepare($delete_consumed_sql);
        $stmt->execute(['food_id' => $delete_id]);
        
        // Then, delete from the main makanan table
        $delete_makanan_sql = "DELETE FROM makanan WHERE id = :id";
        $stmt = $pdo->prepare($delete_makanan_sql);
        $stmt->execute(['id' => $delete_id]);

        $pdo->commit();
        
        header("Location: menu-makanan.php?success=1");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_param = '%' . $search . '%';

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

try {
    // Count total records
    $count_sql = "SELECT COUNT(*) as total FROM makanan";
    $params = [];
    
    if ($search) {
        $count_sql .= " WHERE nama_makanan LIKE :search";
        $params['search'] = $search_param;
    }
    
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_records / $limit);

    // Get menu data
    $sql = "SELECT * FROM makanan";
    $params = [];
    
    if ($search) {
        $sql .= " WHERE nama_makanan LIKE :search";
        $params['search'] = $search_param;
    }
    
    $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
    $params['limit'] = $limit;
    $params['offset'] = $offset;
    
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        if ($key === 'limit' || $key === 'offset') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }
    $stmt->execute();
    $result = $stmt;
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $result = null;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Makanan - Fitney</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/menu-makanan.css">
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
                <h1>Menu Makanan</h1>
            </div>
            
            <div class="header-right">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari menu makanan..." 
                           value="<?php echo htmlspecialchars($search); ?>">
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

        <!-- Stats Cards -->
        <section class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Menu</h3>
                    <p class="stat-number"><?php echo $total_records; ?></p>
                    <span class="stat-change positive">Data aktual</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>Menu Baru Bulan Ini</h3>
                    <p class="stat-number">
                        <?php
                        $current_month = date('Y-m');
$new_menu_sql = "SELECT COUNT(*) as total FROM makanan WHERE DATE_FORMAT(created_at, '%Y-%m') = '$current_month'";
$new_menu_result = $pdo->query($new_menu_sql);
echo $new_menu_result->fetch(PDO::FETCH_ASSOC)['total'];
                        ?>
                    </p>
                    <span class="stat-change positive">Bulan ini</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-info">
                    <h3>Kategori</h3>
                    <p class="stat-number">
                        <?php
$categories_sql = "SELECT COUNT(DISTINCT kategori) as total FROM makanan";
$categories_result = $pdo->query($categories_sql);
echo $categories_result->fetch(PDO::FETCH_ASSOC)['total'];
                        ?>
                    </p>
                    <span class="stat-change positive">Kategori unik</span>
                </div>
            </div>
        </section>

        <!-- Menu Table -->
        <section class="content-card">
            <div class="card-header">
                <h2>Daftar Menu Makanan</h2>
                <a href="Tambah-makanan.php" class="btn-add">
                    <i class="fas fa-plus"></i>
                    Tambah Menu Baru
                </a>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Menu berhasil dihapus!
                </div>
            <?php endif; ?>
            
            <div class="table-container">
                <table class="menu-table">
                    <thead>
                        <tr>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Porsi</th>
                    <th>Kalori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->rowCount() > 0) {
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $gambar = !empty($row['foto_makanan']) ? $row['foto_makanan'] : 'https://via.placeholder.com/60x60?text=No+Image';
                        ?>
                        <tr>
                            <td>
                                <div class="menu-name">
                                    <strong><?php echo htmlspecialchars($row['nama_makanan']); ?></strong>
                                </div>
                            </td>
<td>
    <?php 
        if (!empty($row['kategori'])) {
            echo htmlspecialchars($row['kategori']);
        } else {
            echo '<span style="color: #999;">(Kategori tidak tersedia)</span>';
        }
    ?>
</td>
                            <td><?php echo htmlspecialchars($row['porsi']); ?>g</td>
                            <td><span class="calories-badge"><?php echo htmlspecialchars($row['kalori']); ?> kcal</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="Tambah-makanan.php?edit_id=<?php echo $row['id']; ?>" 
                                       class="btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
<a href="javascript:void(0);" onclick="if(confirm('Apakah Anda yakin ingin menghapus menu ini?')) { window.location.href='menu-makanan.php?delete_id=<?php echo $row['id']; ?>'; }" 
   class="btn-delete" title="Hapus">
    <i class="fas fa-trash"></i>
</a>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="5" style="text-align: center; padding: 40px; color: #666;">
                            <i class="fas fa-utensils" style="font-size: 3rem; margin-bottom: 10px; opacity: 0.3;"></i><br>
                            Belum ada data menu makanan
                          </td></tr>';
                }
                ?>
            </tbody>
        </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" 
                           class="pagination-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
                           class="pagination-btn <?php echo $i == $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" 
                           class="pagination-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <script>
        // Toggle sidebar
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchValue = this.value.trim();
                const url = new URL(window.location);
                if (searchValue) {
                    url.searchParams.set('search', searchValue);
                } else {
                    url.searchParams.delete('search');
                }
                url.searchParams.delete('page');
                window.location = url;
            }
        });

        // Confirm delete
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus menu ini?')) {
                window.location.href = `menu-makanan.php?delete_id=${id}`;
            }
        }
    </script>
</body>
</html>
