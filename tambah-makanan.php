<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Menu</title>
  <link rel="stylesheet" href="css/Tambah-makanan.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
  <main class="add-menu-container">
    <form class="menu-form" action="proses-makanan.php" method="POST" enctype="multipart/form-data">
      
      
      <input type="text" name="nama_makanan" placeholder="Name the food" class="input-name" required />
      
      <div class="input-row">
        <input type="number" name="porsi" placeholder="..." class="input-gram" required />
        <span class="unit-label">Penyajian (gram)</span>
      </div>
      
      <div class="input-row">
      <input type="number" name="kalori" placeholder="..." class="input-calories" required />
      <span class="unit-label">Kalori</span>
    </div>
    <div class="input-row">
      <select name="kategori" class="input-gram" required>
        <option value="" disabled selected>Pilih Kategori</option>
        <option value="Makan Malam">Makanan</option>
        <option value="Cemilan">Cemilan</option>
        <option value="Minuman">Minuman</option>
        <option value="Dessert">Dessert</option>
        <option value="Protein">Protein</option>
        <option value="Karbohidrat">Karbohidrat</option>
        <option value="Sayuran">Sayuran</option>
        <option value="Buah">Buah</option>
      </select>
    </div>
    
    <div class="btn">
      <a href="Dashboard-admin.php" class="add-button">Keluar</a>
      <button type="submit" class="add-button">Add to menu</button>
    </div>
  </form>
</main>
</body>
</html>
