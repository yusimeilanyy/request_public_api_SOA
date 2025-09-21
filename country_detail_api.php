<?php
// Daftar negara-negara Asia Tenggara
$southeast_asian_countries = [
    'indonesia' => 'Indonesia',
    'malaysia' => 'Malaysia',
    'thailand' => 'Thailand',
    'singapore' => 'Singapore',
    'philippines' => 'Philippines',
    'vietnam' => 'Vietnam',
    'cambodia' => 'Cambodia',
    'laos' => 'Laos',
    'myanmar' => 'Myanmar',
    'brunei' => 'Brunei'
];

function getCountryData($countryName) {
    $api_url = "https://restcountries.com/v3.1/name/" . urlencode($countryName);
    
    // Konfigurasi context untuk request
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 30,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json'
            ]
        ]
    ]);
    
    // Lakukan request
    $response = @file_get_contents($api_url, false, $context);
    
    if ($response === false) {
        return ['error' => 'Failed to fetch data from API for ' . $countryName];
    }
    
    // Decode JSON
    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'JSON Decode Error: ' . json_last_error_msg()];
    }
    
    return $data;
}

// Handle form submission
$selected_country = '';
$country_data = null;
$error = null;

if (isset($_POST['country']) && !empty($_POST['country'])) {
    $selected_country = $_POST['country'];
    $result = getCountryData($selected_country);
    
    if (isset($result['error'])) {
        $error = $result['error'];
    } else {
        $country_data = $result[0];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Southeast Asian Countries Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            background-color: white;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .back-link {
            margin: 20px 0;
            text-align: center;
        }
        .back-link a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .country-info {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .country-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .flag {
            font-size: 48px;
            margin-right: 20px;
        }
        .country-name {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .info-item {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
            font-size: 14px;
        }
        .error {
            color: red;
            padding: 15px;
            background-color: #ffebee;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #f44336;
        }
        .languages, .currencies {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .tag {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌏 Southeast Asian Countries Information</h1>
        
        <div class="back-link">
            <a href="index.php">← Kembali ke Menu Utama</a>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="country">Pilih Negara Asia Tenggara:</label>
                <select name="country" id="country" required>
                    <option value="">-- Pilih Negara --</option>
                    <?php foreach ($southeast_asian_countries as $key => $name): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($selected_country === $key) ? 'selected' : ''; ?>>
                            <?php echo $name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="submit-btn">🔍 Cari Informasi Negara</button>
            <?php if ($country_data): ?>
                <button type="button" class="submit-btn" onclick="location.href='country_detail_api.php'" style="background-color: #28a745;">🔄 Reset</button>
            <?php endif; ?>
        </form>
        
        <?php if ($error): ?>
            <div class="error">
                <h3>Error:</h3>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($country_data): ?>
            <div class="country-info">
                <div class="country-header">
                    <div class="flag"><?php echo $country_data['flag']; ?></div>
                    <div>
                        <div class="country-name"><?php echo htmlspecialchars($country_data['name']['common']); ?></div>
                        <div style="font-size: 18px; color: #666;">
                            <?php echo htmlspecialchars($country_data['name']['official']); ?>
                        </div>
                    </div>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">🏛️ Ibu Kota</div>
                        <div class="info-value">
                            <?php echo isset($country_data['capital']) ? htmlspecialchars(implode(', ', $country_data['capital'])) : 'N/A'; ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">🌍 Region</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($country_data['region'] . ' - ' . $country_data['subregion']); ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">👥 Populasi</div>
                        <div class="info-value">
                            <?php echo number_format($country_data['population']); ?> jiwa
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">📏 Luas Area</div>
                        <div class="info-value">
                            <?php echo number_format($country_data['area']); ?> km²
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">🗣️ Bahasa</div>
                        <div class="info-value">
                            <div class="languages">
                                <?php if (isset($country_data['languages'])): ?>
                                    <?php foreach ($country_data['languages'] as $code => $language): ?>
                                        <span class="tag"><?php echo htmlspecialchars($language); ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">💰 Mata Uang</div>
                        <div class="info-value">
                            <div class="currencies">
                                <?php if (isset($country_data['currencies'])): ?>
                                    <?php foreach ($country_data['currencies'] as $code => $currency): ?>
                                        <span class="tag">
                                            <?php echo htmlspecialchars($currency['name'] . ' (' . $currency['symbol'] . ')'); ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">🌐 Domain Internet</div>
                        <div class="info-value">
                            <?php echo isset($country_data['tld']) ? htmlspecialchars(implode(', ', $country_data['tld'])) : 'N/A'; ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">📞 Kode Telepon</div>
                        <div class="info-value">
                            <?php 
                            if (isset($country_data['idd']['root']) && isset($country_data['idd']['suffixes'])) {
                                echo htmlspecialchars($country_data['idd']['root'] . implode(', ', $country_data['idd']['suffixes']));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">🚗 Arah Mengemudi</div>
                        <div class="info-value">
                            <?php echo ucfirst(htmlspecialchars($country_data['car']['side'])); ?> side
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">🕐 Zona Waktu</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars(implode(', ', $country_data['timezones'])); ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">🗺️ Koordinat</div>
                        <div class="info-value">
                            <?php 
                            $lat = $country_data['latlng'][0];
                            $lng = $country_data['latlng'][1];
                            echo $lat . '°, ' . $lng . '°';
                            ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">🌍 Google Maps</div>
                        <div class="info-value">
                            <a href="<?php echo htmlspecialchars($country_data['maps']['googleMaps']); ?>" target="_blank" style="color: #007bff; text-decoration: none;">
                                Buka di Google Maps →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #888;">
            <p>Data provided by <a href="https://restcountries.com" target="_blank">REST Countries API</a></p>
        </div>
    </div>
</body>
</html>