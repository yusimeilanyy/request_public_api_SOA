<?php

// URL API The Cat API
$api_url = "https://api.thecatapi.com/v1/images/search";
function getRandomCatWithFileGetContents($url) {
    // Konfigurasi context untuk request
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 30,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]
    ]);
    
    // Lakukan request
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        return ['error' => 'Failed to fetch data from API'];
    }
    
    // Decode JSON
    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'JSON Decode Error: ' . json_last_error_msg()];
    }
    
    return $data;
}

// Pilih metode yang akan digunakan (cURL lebih direkomendasikan)
$cat_data = getRandomCatWithFileGetContents($api_url);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Cat Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .cat-container {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .cat-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .error {
            color: red;
            padding: 10px;
            background-color: #ffebee;
            border-radius: 4px;
        }
        .back-link {
            margin: 20px 0;
        }
        .back-link a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .refresh-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
        }
        .refresh-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>🐱 Random Cat Image</h1>
    
    <div class="back-link">
        <a href="index.php">← Kembali ke Menu Utama</a>
    </div>
    
    <button class="refresh-btn" onclick="location.reload()">🔄 Get New Cat</button>
    
    <div class="cat-container">
        <?php if (isset($cat_data['error'])): ?>
            <div class="error">
                <h3>Error:</h3>
                <p><?php echo htmlspecialchars($cat_data['error']); ?></p>
            </div>
        <?php elseif (!empty($cat_data) && is_array($cat_data)): ?>
            <?php $cat = $cat_data[0]; ?>
            <h3>Here's your random cat! 🐾</h3>
            <img src="<?php echo htmlspecialchars($cat['url']); ?>" 
                 alt="Random Cat" 
                 class="cat-image">
            
            <div style="margin-top: 15px; font-size: 14px; color: #666;">
                <p><strong>Image ID:</strong> <?php echo htmlspecialchars($cat['id']); ?></p>
                <p><strong>Dimensions:</strong> <?php echo $cat['width']; ?> x <?php echo $cat['height']; ?> pixels</p>
                <?php if (isset($cat['breeds']) && !empty($cat['breeds'])): ?>
                    <p><strong>Breed:</strong> <?php echo htmlspecialchars($cat['breeds'][0]['name']); ?></p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="error">
                <p>No cat data received from API</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div style="margin-top: 30px; font-size: 12px; color: #888;">
        <p>Data provided by <a href="https://thecatapi.com" target="_blank">The Cat API</a></p>
    </div>
</body>
</html>