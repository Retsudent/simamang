<?php
/**
 * Test Uploads Controller
 * This script will test the Uploads controller directly
 */

echo "=== TEST UPLOADS CONTROLLER ===\n\n";

// 1. Get current photo from database
echo "1. Getting current photo from database...\n";
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
        echo "❌ No photo found in database\n";
        exit;
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    exit;
}

// 2. Test the controller method directly
echo "\n2. Testing controller method directly...\n";

// Simulate the controller method
$filename = $currentPhoto;
$filepath = FCPATH . 'writable/uploads/profile/' . $filename;

echo "Testing filepath: $filepath\n";

if (file_exists($filepath)) {
    echo "✅ File exists\n";
    
    $mime = mime_content_type($filepath);
    echo "MIME type: $mime\n";
    
    $fileSize = filesize($filepath);
    echo "File size: $fileSize bytes\n";
    
    if ($fileSize > 0) {
        echo "✅ File is readable and has content\n";
    } else {
        echo "❌ File is empty\n";
    }
} else {
    echo "❌ File does not exist\n";
}

// 3. Test URL with index.php
echo "\n3. Testing URL with index.php...\n";
$testUrl = "http://localhost:8000/index.php/uploads/profile/$currentPhoto";
echo "Test URL: $testUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ URL with index.php is accessible (HTTP 200)\n";
} else {
    echo "❌ URL with index.php returned HTTP $httpCode\n";
}

// 4. Test URL without index.php
echo "\n4. Testing URL without index.php...\n";
$testUrl2 = "http://localhost:8000/uploads/profile/$currentPhoto";
echo "Test URL: $testUrl2\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ URL without index.php is accessible (HTTP 200)\n";
} else {
    echo "❌ URL without index.php returned HTTP $httpCode\n";
}

// 5. Check if .htaccess is working
echo "\n5. Checking .htaccess...\n";
$htaccessFile = 'public/.htaccess';
if (file_exists($htaccessFile)) {
    echo "✅ .htaccess file exists\n";
    $htaccessContent = file_get_contents($htaccessFile);
    if (strpos($htaccessContent, 'RewriteEngine On') !== false) {
        echo "✅ RewriteEngine is enabled\n";
    } else {
        echo "❌ RewriteEngine not found\n";
    }
} else {
    echo "❌ .htaccess file does not exist\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "\nIf URLs are still not working:\n";
echo "1. Check if mod_rewrite is enabled\n";
echo "2. Check if .htaccess is being read\n";
echo "3. Try using URLs with index.php\n";
echo "4. Check server configuration\n";
?>
