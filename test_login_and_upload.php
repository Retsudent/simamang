<?php
/**
 * Test Login and Upload with Proper Session
 * This script will login first, then test upload with maintained session
 */

echo "=== TEST LOGIN AND UPLOAD ===\n\n";

// Step 1: Get CSRF token from login page
echo "1. Getting CSRF token from login page...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'test_session_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_session_cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode != 200) {
    echo "❌ Failed to access login page. HTTP Code: $httpCode\n";
    exit;
}

echo "✅ Login page accessed successfully\n";

// Extract CSRF token from login page
if (!preg_match('/name="csrf_test_name" value="([^"]+)"/', $response, $matches)) {
    echo "❌ CSRF token not found in login page\n";
    exit;
}

$loginCsrfToken = $matches[1];
echo "✅ Login CSRF token extracted: " . substr($loginCsrfToken, 0, 10) . "...\n";

// Step 2: Login with admin credentials
echo "\n2. Logging in as admin...\n";
$loginData = [
    'csrf_test_name' => $loginCsrfToken,
    'username' => 'admin',
    'password' => 'admin123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_session_cookies.txt');
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);
curl_close($ch);

echo "Login HTTP Code: $httpCode\n";

// Check for redirect to dashboard
if (preg_match('/Location: ([^\r\n]+)/', $headers, $matches)) {
    $redirectUrl = $matches[1];
    echo "✅ Login redirect detected to: $redirectUrl\n";
    
    if (strpos($redirectUrl, 'admin') !== false) {
        echo "✅ Successfully logged in as admin\n";
    } else {
        echo "❌ Login failed - unexpected redirect\n";
        exit;
    }
} else {
    echo "❌ No redirect detected after login\n";
    exit;
}

// Step 3: Access profile page to get new CSRF token
echo "\n3. Getting CSRF token from profile page...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_session_cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode != 200) {
    echo "❌ Failed to access profile page. HTTP Code: $httpCode\n";
    echo "Response: " . substr($response, 0, 200) . "...\n";
    exit;
}

echo "✅ Profile page accessed successfully\n";

// Extract CSRF token from profile page
if (!preg_match('/name="csrf_test_name" value="([^"]+)"/', $response, $matches)) {
    echo "❌ CSRF token not found in profile page\n";
    exit;
}

$profileCsrfToken = $matches[1];
echo "✅ Profile CSRF token extracted: " . substr($profileCsrfToken, 0, 10) . "...\n";

// Step 4: Check current photo in database
echo "\n4. Checking current photo in database...\n";
try {
    $host = 'localhost';
    $dbname = 'simamang';
    $username = 'postgres';
    $password = 'postgres';
    
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT foto_profil FROM admin WHERE username = 'admin'");
    $currentPhoto = $stmt->fetchColumn();
    echo "Current photo before upload: " . ($currentPhoto ?: 'NULL') . "\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    exit;
}

// Step 5: Create and upload test image
echo "\n5. Creating and uploading test image...\n";
$testImagePath = 'test_session_image.jpg';
$testImageData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A');
file_put_contents($testImagePath, $testImageData);
echo "✅ Test image created\n";

$postData = [
    'csrf_test_name' => $profileCsrfToken,
    'foto_profil' => new CURLFile($testImagePath, 'image/jpeg', 'test_session.jpg')
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/profile/update-photo');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_session_cookies.txt');
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);
curl_close($ch);

echo "Upload HTTP Code: $httpCode\n";

if (preg_match('/Location: ([^\r\n]+)/', $headers, $matches)) {
    $redirectUrl = $matches[1];
    echo "✅ Upload redirect detected to: $redirectUrl\n";
    
    if (strpos($redirectUrl, 'profile') !== false) {
        echo "✅ Upload successful - redirected to profile page\n";
        
        // Check if photo was updated in database
        $stmt = $pdo->query("SELECT foto_profil, updated_at FROM admin WHERE username = 'admin'");
        $newPhotoData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($newPhotoData) {
            echo "After upload:\n";
            echo "  - Foto Profil: " . ($newPhotoData['foto_profil'] ?: 'NULL') . "\n";
            echo "  - Updated At: {$newPhotoData['updated_at']}\n";
            
            if ($newPhotoData['foto_profil'] && $newPhotoData['foto_profil'] !== $currentPhoto) {
                echo "✅ Photo was updated in database!\n";
                
                // Check if new photo file exists
                $uploadDir = 'writable/uploads/profile/';
                $newPhotoPath = $uploadDir . $newPhotoData['foto_profil'];
                if (file_exists($newPhotoPath)) {
                    echo "✅ New photo file exists: $newPhotoPath\n";
                    echo "  - File size: " . filesize($newPhotoPath) . " bytes\n";
                } else {
                    echo "❌ New photo file does not exist: $newPhotoPath\n";
                }
            } else {
                echo "❌ Photo was not updated in database\n";
            }
        }
    } elseif (strpos($redirectUrl, 'login') !== false) {
        echo "❌ Upload failed - redirected to login page (session lost)\n";
    } else {
        echo "⚠️  Upload redirect unclear\n";
    }
} else {
    echo "❌ No redirect detected in upload response\n";
}

// Step 6: Cleanup
echo "\n6. Cleaning up...\n";
if (file_exists($testImagePath)) {
    unlink($testImagePath);
    echo "✅ Test image removed\n";
}

if (file_exists('test_session_cookies.txt')) {
    unlink('test_session_cookies.txt');
    echo "✅ Cookie file removed\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "\nIf photo was updated in database but not showing in browser:\n";
echo "1. Try refreshing the page (Ctrl+F5)\n";
echo "2. Clear browser cache\n";
echo "3. Check if the photo URL is correct\n";
?>
