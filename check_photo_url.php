<?php
/**
 * Check Photo URL and Accessibility
 * This script will verify if the uploaded photo is accessible via URL
 */

echo "=== CHECK PHOTO URL AND ACCESSIBILITY ===\n\n";

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

// 2. Check if photo file exists
echo "\n2. Checking photo file...\n";
$uploadDir = 'writable/uploads/profile/';
$photoPath = $uploadDir . $currentPhoto;

if (file_exists($photoPath)) {
    echo "✅ Photo file exists: $photoPath\n";
    echo "  - File size: " . filesize($photoPath) . " bytes\n";
    echo "  - Last modified: " . date('Y-m-d H:i:s', filemtime($photoPath)) . "\n";
    
    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $photoPath);
    finfo_close($finfo);
    echo "  - MIME type: $mimeType\n";
} else {
    echo "❌ Photo file does not exist: $photoPath\n";
    exit;
}

// 3. Check photo URL accessibility
echo "\n3. Checking photo URL accessibility...\n";
$photoUrl = "http://localhost:8000/uploads/profile/$currentPhoto";
echo "Photo URL: $photoUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $photoUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$contentLength = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Photo URL is accessible (HTTP 200)\n";
    echo "  - Content-Type: $contentType\n";
    echo "  - Content-Length: $contentLength bytes\n";
} else {
    echo "❌ Photo URL returned HTTP $httpCode\n";
    echo "Response headers:\n";
    echo $response . "\n";
}

// 4. Check if route exists for photo access
echo "\n4. Checking photo route...\n";
$routesFile = file_get_contents('app/Config/Routes.php');
if (strpos($routesFile, 'uploads/profile') !== false) {
    echo "✅ Photo route found in Routes.php\n";
} else {
    echo "❌ Photo route not found in Routes.php\n";
}

// 5. Test direct file access
echo "\n5. Testing direct file access...\n";
$directUrl = "http://localhost:8000/index.php/uploads/profile/$currentPhoto";
echo "Direct URL: $directUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $directUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Direct URL is accessible (HTTP 200)\n";
} else {
    echo "❌ Direct URL returned HTTP $httpCode\n";
}

// 6. Check profile page photo display
echo "\n6. Checking profile page photo display...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'check_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'check_cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Profile page accessible\n";
    
    // Check if photo URL is in the page
    if (strpos($response, $currentPhoto) !== false) {
        echo "✅ Photo filename found in profile page\n";
    } else {
        echo "❌ Photo filename not found in profile page\n";
    }
    
    // Check if img tag with photo exists
    if (preg_match('/<img[^>]*src="[^"]*' . preg_quote($currentPhoto, '/') . '[^"]*"[^>]*>/', $response)) {
        echo "✅ Photo img tag found in profile page\n";
    } else {
        echo "❌ Photo img tag not found in profile page\n";
    }
    
} else {
    echo "❌ Profile page returned HTTP $httpCode\n";
}

// 7. Cleanup
if (file_exists('check_cookies.txt')) {
    unlink('check_cookies.txt');
}

echo "\n=== CHECK COMPLETE ===\n";
echo "\nIf photo URL is accessible but not showing in browser:\n";
echo "1. Clear browser cache (Ctrl+Shift+Delete)\n";
echo "2. Hard refresh the page (Ctrl+F5)\n";
echo "3. Try opening photo URL directly in new tab\n";
echo "4. Check browser developer tools for network errors\n";
?>
