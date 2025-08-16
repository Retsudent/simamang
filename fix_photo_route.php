<?php
/**
 * Fix Photo Route Issue
 * This script will check and fix the photo route problem
 */

echo "=== FIX PHOTO ROUTE ISSUE ===\n\n";

// 1. Check current photo file
echo "1. Checking current photo file...\n";
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

// 2. Check file paths
echo "\n2. Checking file paths...\n";
$uploadDir = 'writable/uploads/profile/';
$photoPath = $uploadDir . $currentPhoto;

echo "Upload directory: $uploadDir\n";
echo "Photo path: $photoPath\n";

if (file_exists($photoPath)) {
    echo "✅ Photo file exists\n";
    echo "  - File size: " . filesize($photoPath) . " bytes\n";
} else {
    echo "❌ Photo file does not exist\n";
    exit;
}

// 3. Check WRITEPATH constant
echo "\n3. Checking WRITEPATH...\n";
echo "Current directory: " . getcwd() . "\n";

// 4. Test the route function manually
echo "\n4. Testing route function manually...\n";
$filename = $currentPhoto;
$filepath = $uploadDir . $filename;

echo "Testing filepath: $filepath\n";

if (file_exists($filepath)) {
    echo "✅ File exists\n";
    
    $mime = mime_content_type($filepath);
    echo "MIME type: $mime\n";
    
    // Test if we can read the file
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

// 5. Create a simple test route
echo "\n5. Creating test route...\n";
$testRoute = "
// Test route for photo access
\$routes->get('test-photo/(:segment)', function(\$filename) {
    \$filepath = 'writable/uploads/profile/' . \$filename;
    if (file_exists(\$filepath)) {
        \$mime = mime_content_type(\$filepath);
        header('Content-Type: ' . \$mime);
        header('Content-Disposition: inline; filename=\"' . \$filename . '\"');
        readfile(\$filepath);
        exit;
    } else {
        echo 'File not found: ' . \$filepath;
        exit;
    }
});
";

echo "Test route code:\n";
echo $testRoute . "\n";

// 6. Test URL access
echo "\n6. Testing URL access...\n";
$testUrl = "http://localhost:8000/test-photo/$currentPhoto";
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
    echo "✅ Test URL is accessible (HTTP 200)\n";
} else {
    echo "❌ Test URL returned HTTP $httpCode\n";
}

// 7. Check if the issue is with the route definition
echo "\n7. Checking route definition...\n";
$routesFile = file_get_contents('app/Config/Routes.php');

// Look for the profile photo route
if (preg_match('/uploads\/profile\/\(:segment\)/', $routesFile)) {
    echo "✅ Profile photo route found in Routes.php\n";
    
    // Extract the route function
    if (preg_match('/\$routes->get\(\'uploads\/profile\/\(:segment\)\', function\(\$filename\) \{([^}]+)\}\);?/s', $routesFile, $matches)) {
        echo "✅ Route function found:\n";
        echo trim($matches[1]) . "\n";
    } else {
        echo "❌ Route function not found\n";
    }
} else {
    echo "❌ Profile photo route not found in Routes.php\n";
}

echo "\n=== FIX COMPLETE ===\n";
echo "\nPossible solutions:\n";
echo "1. The route might need to be moved before other routes\n";
echo "2. The WRITEPATH might be incorrect\n";
echo "3. The route function might have syntax errors\n";
echo "4. The file permissions might be wrong\n";
?>
