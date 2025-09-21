<?php
// Function untuk mendapatkan fakta random menggunakan file_get_contents
function getRandomUselessFact() {
    $api_url = "https://uselessfacts.jsph.pl/api/v2/facts/random";
    
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
        return ['error' => 'Failed to fetch data from Useless Facts API'];
    }
    
    // Decode JSON
    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'JSON Decode Error: ' . json_last_error_msg()];
    }
    
    return $data;
}

// Langsung fetch data saat halaman dimuat
$result = getRandomUselessFact();

if (isset($result['error'])) {
    $error = $result['error'];
    $fact_data = null;
} else {
    $fact_data = $result;
    $error = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Useless Facts</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        .back-link {
            margin: 20px 0;
            text-align: center;
        }
        .back-link a {
            text-decoration: none;
            color: #667eea;
            font-weight: bold;
            font-size: 16px;
        }
        .generate-btn {
            display: block;
            margin: 20px auto;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
        }
        .generate-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        .fact-container {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 15px;
            color: white;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fact-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .fact-text {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 20px;
            background-color: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        .fact-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .meta-item {
            background-color: rgba(255,255,255,0.2);
            padding: 15px;
            border-radius: 8px;
            backdrop-filter: blur(5px);
        }
        .meta-label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            opacity: 0.9;
        }
        .meta-value {
            font-size: 14px;
            word-break: break-all;
        }
        .error {
            color: #dc3545;
            padding: 20px;
            background-color: #f8d7da;
            border-radius: 10px;
            margin-top: 20px;
            border-left: 4px solid #dc3545;
            text-align: center;
        }
        .api-info {
            background-color: #e8f4fd;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
            text-align: center;
        }
        .api-info h3 {
            margin: 0 0 10px 0;
            color: #1976d2;
        }
        .api-info p {
            margin: 0;
            color: #555;
            font-size: 14px;
        }
        .loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🤓 Random Useless Facts</h1>
        
        <div class="back-link">
            <a href="index.php">← Kembali ke Menu Utama</a>
        </div>
        
        <div class="api-info">
            <h3>🎲 Random Useless Facts Generator</h3>
            <p>Klik tombol di bawah untuk mendapatkan fakta random yang tidak berguna tapi menarik!</p>
            <p><strong>API:</strong> https://uselessfacts.jsph.pl/api/v2/facts/random</p>
        </div>
        
        <a href="useless_fact_api.php" class="generate-btn">
            🎯 Generate Random Fact
        </a>
        
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Generating random fact...</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error">
                <h3>❌ Error Occurred:</h3>
                <p><?php echo htmlspecialchars($error); ?></p>
                <a href="useless_fact_api.php" class="generate-btn" style="margin-top: 15px; display: inline-block;">
                    🔄 Try Again
                </a>
            </div>
        <?php endif; ?>
        
        <?php if ($fact_data): ?>
            <div class="fact-container">
                <div class="fact-header">
                    🧠 Random Useless Fact
                </div>
                
                <div class="fact-text">
                    "<?php echo htmlspecialchars($fact_data['text']); ?>"
                </div>
                
                <div class="fact-meta">
                    <div class="meta-item">
                        <div class="meta-label">🆔 Fact ID</div>
                        <div class="meta-value"><?php echo htmlspecialchars($fact_data['id']); ?></div>
                    </div>
                    
                    <div class="meta-item">
                        <div class="meta-label">🔗 Permalink</div>
                        <div class="meta-value">
                            <a href="<?php echo htmlspecialchars($fact_data['permalink']); ?>" 
                               target="_blank" 
                               style="color: white; text-decoration: underline;">
                               View Original
                            </a>
                        </div>
                    </div>
                    
                    <?php if (isset($fact_data['source'])): ?>
                    <div class="meta-item">
                        <div class="meta-label">📚 Source</div>
                        <div class="meta-value"><?php echo htmlspecialchars($fact_data['source']); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($fact_data['source_url'])): ?>
                    <div class="meta-item">
                        <div class="meta-label">🌐 Source URL</div>
                        <div class="meta-value">
                            <a href="<?php echo htmlspecialchars($fact_data['source_url']); ?>" 
                               target="_blank" 
                               style="color: white; text-decoration: underline;">
                               Visit Source
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($fact_data['language'])): ?>
                    <div class="meta-item">
                        <div class="meta-label">🌍 Language</div>
                        <div class="meta-value"><?php echo strtoupper(htmlspecialchars($fact_data['language'])); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 40px; text-align: center; font-size: 12px; color: #888;">
            <p>Data provided by <a href="https://uselessfacts.jsph.pl/" target="_blank" style="color: #667eea;">Useless Facts API</a></p>
            <p>Each refresh gives you a completely random fact! 🎲</p>
        </div>
    </div>

    <script>
        // Optional: Add loading effect when clicking the generate button
        document.querySelector('.generate-btn').addEventListener('click', function() {
            document.getElementById('loading').style.display = 'block';
        });
    </script>
</body>
</html>