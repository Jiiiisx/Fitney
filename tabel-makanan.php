<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel Makanan - Fitjourney</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .table-container {
            padding: 40px;
        }

        .food-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .food-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
        }

        .food-table td {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .food-table tr:hover {
            background-color: #f8f9ff;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .food-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
            transition: transform 0.3s ease;
        }

        .food-image:hover {
            transform: scale(1.1);
        }

        .food-name {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .food-category {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .category-breakfast {
            background-color: #ffecd2;
            color: #ff6b35;
        }

        .category-lunch {
            background-color: #e8f5e8;
            color: #4caf50;
        }

        .category-dinner {
            background-color: #e3f2fd;
            color: #2196f3;
        }

        .category-snack {
            background-color: #fce4ec;
            color: #e91e63;
        }

        .category-beverage {
            background-color: #fff3e0;
            color: #ff9800;
        }

        .calories-badge {
            display: inline-block;
            padding: 8px 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .search-container {
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            border-color: #667eea;
        }

        .filter-select {
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 1rem;
            outline: none;
            cursor: pointer;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .food-table {
                font-size: 0.9rem;
            }
            
            .food-table th,
            .food-table td {
                padding: 15px 10px;
            }
            
            .food-image {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-utensils"></i> Tabel Makanan</h1>
            <p>Daftar lengkap makanan dengan informasi kalori dan kategori</p>
        </div>

        <div class="table-container">
            <div class="search-container">
                <input type="text" class="search-input" id="searchInput" placeholder="Cari makanan...">
                <select class="filter-select" id="categoryFilter">
                    <option value="">Semua Kategori</option>
                    <option value="breakfast">Sarapan</option>
