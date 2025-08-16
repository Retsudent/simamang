<?php
/**
 * Test Profile Photo Upload with Login Session
 * This script will login first, then test the upload
 */

echo "=== TEST UPLOAD WITH LOGIN ===\n\n";

// Step 1: Get CSRF token from login page
echo "1. Getting CSRF token from login page...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'test_login_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_login_cookies.txt');

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
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_login_cookies.txt');
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
    echo "✅ Login redirect detected to: " . $matches[1] . "\n";
    if (strpos($matches[1], 'admin') !== false) {
        echo "✅ Successfully logged in as admin\n";
    }
}

// Step 3: Access profile page to get new CSRF token
echo "\n3. Getting CSRF token from profile page...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_login_cookies.txt');

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

// Step 4: Create a test image file
echo "\n4. Creating test image file...\n";
$testImagePath = 'test_upload_image.jpg';
$testImageData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A');
file_put_contents($testImagePath, $testImageData);
echo "✅ Test image created\n";

// Step 5: Perform upload
echo "\n5. Performing upload...\n";
$postData = [
    'csrf_test_name' => $profileCsrfToken,
    'foto_profil' => new CURLFile($testImagePath, 'image/jpeg', 'test_image.jpg')
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/profile/update-photo');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_login_cookies.txt');
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);
curl_close($ch);

echo "Upload HTTP Code: $httpCode\n";

// Check for redirect
if (preg_match('/Location: ([^\r\n]+)/', $headers, $matches)) {
    echo "✅ Upload redirect detected to: " . $matches[1] . "\n";
}

// Check response content
if (strpos($body, 'berhasil') !== false || strpos($body, 'success') !== false) {
    echo "✅ Upload appears successful\n";
} elseif (strpos($body, 'error') !== false || strpos($body, 'gagal') !== false) {
    echo "❌ Upload appears to have failed\n";
    echo "Response body: " . substr($body, 0, 500) . "...\n";
} else {
    echo "⚠️  Upload response unclear\n";
    echo "Response body: " . substr($body, 0, 500) . "...\n";
}

// Step 6: Cleanup
echo "\n6. Cleaning up...\n";
if (file_exists($testImagePath)) {
    unlink($testImagePath);
    echo "✅ Test image removed\n";
}

if (file_exists('test_login_cookies.txt')) {
    unlink('test_login_cookies.txt');
    echo "✅ Cookie file removed\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "\nIf upload was successful, the CSRF issue is resolved.\n";
echo "If upload failed, check the response for specific error messages.\n";
?>
