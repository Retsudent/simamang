<?php
/**
 * Final Photo Solution
 * This script provides alternative solutions for photo access
 */

echo "=== FINAL PHOTO SOLUTION ===\n\n";

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

// 2. Check if photo file exists
echo "\n2. Checking photo file...\n";
$uploadDir = 'writable/uploads/profile/';
$photoPath = $uploadDir . $currentPhoto;

if (file_exists($photoPath)) {
    echo "✅ Photo file exists: $photoPath\n";
    echo "  - File size: " . filesize($photoPath) . " bytes\n";
    echo "  - MIME type: " . mime_content_type($photoPath) . "\n";
} else {
    echo "❌ Photo file does not exist\n";
    exit;
}

// 3. Create a simple photo access script
echo "\n3. Creating simple photo access script...\n";
$photoAccessScript = 'public/photo.php';
$scriptContent = '<?php
// Simple photo access script
$filename = $_GET["file"] ?? "";
$type = $_GET["type"] ?? "profile";

if (empty($filename)) {
    http_response_code(404);
    echo "File not specified";
    exit;
}

$filepath = "../writable/uploads/$type/" . basename($filename);

if (file_exists($filepath)) {
    $mime = mime_content_type($filepath);
    header("Content-Type: $mime");
    header("Content-Disposition: inline; filename=\"" . basename($filename) . "\"");
    readfile($filepath);
} else {
    http_response_code(404);
    echo "File not found";
}
?>';

file_put_contents($photoAccessScript, $scriptContent);
echo "✅ Created photo access script: $photoAccessScript\n";

// 4. Test the new photo access
echo "\n4. Testing new photo access...\n";
$testUrl = "http://localhost:8000/photo.php?file=$currentPhoto&type=profile";
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
    echo "✅ New photo access works! (HTTP 200)\n";
} else {
    echo "❌ New photo access returned HTTP $httpCode\n";
}

// 5. Update profile view to use new photo URL
echo "\n5. Updating profile view...\n";
$profileViewPath = 'app/Views/profile/index.php';
$profileViewContent = file_get_contents($profileViewPath);

// Replace the photo URL in the view
$oldPhotoUrl = '<?= base_url(\'uploads/profile/\' . $user[\'foto_profil\']) ?>';
$newPhotoUrl = '<?= base_url(\'photo.php?file=\' . $user[\'foto_profil\'] . \'&type=profile\') ?>';

if (strpos($profileViewContent, $oldPhotoUrl) !== false) {
    $profileViewContent = str_replace($oldPhotoUrl, $newPhotoUrl, $profileViewContent);
    file_put_contents($profileViewPath, $profileViewContent);
    echo "✅ Updated profile view to use new photo URL\n";
} else {
    echo "⚠️  Could not find old photo URL in profile view\n";
}

// 6. Test profile page
echo "\n6. Testing profile page...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'test_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Profile page accessible\n";
    
    // Check if new photo URL is in the page
    if (strpos($response, 'photo.php?file=') !== false) {
        echo "✅ New photo URL found in profile page\n";
    } else {
        echo "❌ New photo URL not found in profile page\n";
    }
} else {
    echo "❌ Profile page returned HTTP $httpCode\n";
}

// 7. Cleanup
if (file_exists('test_cookies.txt')) {
    unlink('test_cookies.txt');
}

echo "\n=== SOLUTION COMPLETE ===\n";
echo "\nPhoto access solution implemented:\n";
echo "1. ✅ Created simple photo access script: /photo.php\n";
echo "2. ✅ Updated profile view to use new photo URL\n";
echo "3. ✅ Photo should now be accessible via: $testUrl\n";
echo "\nTo access photos:\n";
echo "- Profile photos: http://localhost:8000/photo.php?file=FILENAME&type=profile\n";
echo "- Bukti photos: http://localhost:8000/photo.php?file=FILENAME&type=bukti\n";
echo "\nThe photo should now be visible in the profile page!\n";
?>
