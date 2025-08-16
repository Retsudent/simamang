<?php
/**
 * Simple Photo Test
 * This script will test if the photo route is working
 */

echo "=== SIMPLE PHOTO TEST ===\n\n";

// 1. Get current photo
echo "1. Getting current photo...\n";
try {
    $host = 'localhost';
    $dbname = 'simamang';
    $username = 'postgres';
    $password = 'postgres';
    
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT foto_profil FROM admin WHERE username = 'admin'");
    $currentPhoto = $stmt->fetchColumn();
    
    if ($currentPhoto) {
        echo "✅ Current photo: $currentPhoto\n";
    } else {
        echo "❌ No photo found\n";
        exit;
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    exit;
}

// 2. Test different URL patterns
echo "\n2. Testing different URL patterns...\n";

$urls = [
    "http://localhost:8000/uploads/profile/$currentPhoto",
    "http://localhost:8000/index.php/uploads/profile/$currentPhoto",
    "http://localhost:8000/uploads/profile/$currentPhoto",
    "http://localhost:8000/index.php?/uploads/profile/$currentPhoto"
];

foreach ($urls as $url) {
    echo "Testing: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "  ❌ cURL Error: $error\n";
    } else {
        echo "  HTTP Code: $httpCode\n";
        if ($httpCode == 200) {
            echo "  ✅ SUCCESS!\n";
        } else {
            echo "  ❌ Failed\n";
        }
    }
    echo "\n";
}

// 3. Test if server is responding
echo "3. Testing server response...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Server is responding (HTTP 200)\n";
} else {
    echo "❌ Server returned HTTP $httpCode\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "\nIf all URLs return 404:\n";
echo "1. The route might not be registered correctly\n";
echo "2. The controller might not be loading\n";
echo "3. There might be a server configuration issue\n";
echo "4. Try restarting the PHP server\n";
?>
