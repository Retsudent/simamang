<?php
/**
 * Debug Profile Photo Upload Issue
 * This script will help identify why the profile photo is not changing
 */

echo "=== DEBUG PROFILE PHOTO UPLOAD ===\n\n";

// 1. Check if upload directory exists and is writable
echo "1. Checking upload directory...\n";
$uploadDir = 'writable/uploads/profile/';
if (!is_dir($uploadDir)) {
    echo "❌ Upload directory does not exist: $uploadDir\n";
    if (mkdir($uploadDir, 0755, true)) {
        echo "✅ Created upload directory: $uploadDir\n";
    } else {
        echo "❌ Failed to create upload directory\n";
    }
} else {
    echo "✅ Upload directory exists: $uploadDir\n";
}

if (is_writable($uploadDir)) {
    echo "✅ Upload directory is writable\n";
} else {
    echo "❌ Upload directory is not writable\n";
}

// 2. Check database connection and user data
echo "\n2. Checking database and user data...\n";
try {
    $host = 'localhost';
    $dbname = 'simamang';
    $username = 'postgres';
    $password = 'postgres';
    
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to PostgreSQL database\n";
    
    // Check admin user data
    $stmt = $pdo->query("SELECT id, nama, username, foto_profil, updated_at FROM admin WHERE username = 'admin'");
    $adminData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($adminData) {
        echo "✅ Admin user found:\n";
        echo "  - ID: {$adminData['id']}\n";
        echo "  - Nama: {$adminData['nama']}\n";
        echo "  - Username: {$adminData['username']}\n";
        echo "  - Foto Profil: " . ($adminData['foto_profil'] ?: 'NULL') . "\n";
        echo "  - Updated At: {$adminData['updated_at']}\n";
        
        // Check if photo file exists
        if ($adminData['foto_profil']) {
            $photoPath = $uploadDir . $adminData['foto_profil'];
            if (file_exists($photoPath)) {
                echo "✅ Photo file exists: $photoPath\n";
                echo "  - File size: " . filesize($photoPath) . " bytes\n";
                echo "  - Last modified: " . date('Y-m-d H:i:s', filemtime($photoPath)) . "\n";
            } else {
                echo "❌ Photo file does not exist: $photoPath\n";
            }
        }
    } else {
        echo "❌ Admin user not found\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

// 3. Test actual upload process
echo "\n3. Testing upload process...\n";

// Get CSRF token from profile page
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'debug_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'debug_cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Profile page accessible\n";
    
    // Extract CSRF token
    if (preg_match('/name="csrf_test_name" value="([^"]+)"/', $response, $matches)) {
        $csrfToken = $matches[1];
        echo "✅ CSRF token extracted: " . substr($csrfToken, 0, 10) . "...\n";
        
        // Create test image
        $testImagePath = 'debug_test_image.jpg';
        $testImageData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A');
        file_put_contents($testImagePath, $testImageData);
        
        // Get current photo before upload
        $stmt = $pdo->query("SELECT foto_profil FROM admin WHERE username = 'admin'");
        $currentPhoto = $stmt->fetchColumn();
        echo "Current photo before upload: " . ($currentPhoto ?: 'NULL') . "\n";
        
        // Perform upload
        $postData = [
            'csrf_test_name' => $csrfToken,
            'foto_profil' => new CURLFile($testImagePath, 'image/jpeg', 'debug_test.jpg')
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/profile/update-photo');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'debug_cookies.txt');
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
                echo "✅ Upload appears successful\n";
                
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
            }
        }
        
        // Cleanup
        if (file_exists($testImagePath)) {
            unlink($testImagePath);
        }
        
    } else {
        echo "❌ CSRF token not found in profile page\n";
    }
} else {
    echo "❌ Profile page returned HTTP $httpCode\n";
}

// 4. Check session data
echo "\n4. Checking session data...\n";
if (file_exists('debug_cookies.txt')) {
    $cookieContent = file_get_contents('debug_cookies.txt');
    echo "Cookie file content:\n";
    echo $cookieContent . "\n";
}

// 5. Cleanup
if (file_exists('debug_cookies.txt')) {
    unlink('debug_cookies.txt');
}

echo "\n=== DEBUG COMPLETE ===\n";
echo "\nPossible issues:\n";
echo "1. Session not maintaining login state\n";
echo "2. Database update not working\n";
echo "3. File upload not working\n";
echo "4. Cache issues in browser\n";
?>
