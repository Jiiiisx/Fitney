<?php
// Include database configuration
require_once 'config.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$message = '';
$alert_type = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize and validate input
        $nama_makanan = trim($_POST['nama_makanan'] ?? '');
        $porsi = floatval($_POST['porsi'] ?? 0);
        $kalori = floatval($_POST['kalori'] ?? 0);
        $kategori = trim($_POST['kategori'] ?? '');
        
        // Validate required fields
        if (empty($nama_makanan)) {
            throw new Exception("Nama makanan tidak boleh kosong!");
        }
        
        if ($porsi <= 0) {
            throw new Exception("Porsi harus lebih dari 0!");
        }
        
        if ($kalori < 0) {
            throw new Exception("Kalori tidak boleh negatif!");
        }
        
        if (empty($kategori)) {
            throw new Exception("Kategori tidak boleh kosong!");
        }
        
        // Handle file upload
        $foto_makanan = '';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/makanan/';
            
            // Create directory if not exists
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                throw new Exception("Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau GIF.");
            }
            
            // Generate unique filename
            $new_filename = uniqid('makanan_') . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                $foto_makanan = $upload_path;
            } else {
                throw new Exception("Gagal mengupload file!");
            }
        }
        
        // Prepare SQL statement
        $sql = "INSERT INTO makanan (nama_makanan, foto_makanan, porsi, kalori, kategori) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        // Execute query
        $stmt->execute([
            $nama_makanan,
            $foto_makanan,
            $porsi,
            $kalori,
            $kategori
        ]);
        
        // Success message
        $message = "Makanan berhasil ditambahkan ke database!";
        $alert_type = "success";
        
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $alert_type = "error";
    }
} else {
    // Redirect if not POST request
    header("Location: tambah-makanan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Makanan - Fitjourney</title>
    <link rel="stylesheet" href="css/Tambah-makanan.css">
    <style>
        .alert {
            padding: 15px;
            margin: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn-container {
            text-align: center;
            margin: 20px;
        }
        .btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="add-menu-container">
        <h2>Hasil Proses Makanan</h2>
        
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $alert_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="btn-container">
            <a href="tambah-makanan.php" class="btn btn-primary">Tambah Makanan Lagi</a>
            <a href="Dashboard-admin.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            <a href="menu-makanan.php" class="btn btn-primary">Lihat Menu Makanan</a>
        </div>
    </div>

    <script>
        // Auto redirect after success
        <?php if ($alert_type === 'success'): ?>
            setTimeout(function() {
                window.location.href = 'tambah-makanan.php';
            }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>
