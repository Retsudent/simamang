<?php
/**
 * Test Navbar Photo Update
 * This script will test if the navbar photo updates correctly after upload
 */

echo "=== TEST NAVBAR PHOTO UPDATE ===\n\n";

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
        echo "❌ No photo found\n";
        exit;
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    exit;
}

// 2. Test navbar photo URL
echo "\n2. Testing navbar photo URL...\n";
$navbarPhotoUrl = "http://localhost:8000/photo.php?file=$currentPhoto&type=profile";
echo "Navbar photo URL: $navbarPhotoUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $navbarPhotoUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Navbar photo URL is accessible (HTTP 200)\n";
} else {
    echo "❌ Navbar photo URL returned HTTP $httpCode\n";
}

// 3. Test admin dashboard page (which has navbar)
echo "\n3. Testing admin dashboard page...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/admin/dashboard');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'navbar_test_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'navbar_test_cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Admin dashboard accessible\n";
    
    // Check if navbar photo URL is in the page
    if (strpos($response, 'photo.php?file=') !== false) {
        echo "✅ Navbar photo URL found in dashboard page\n";
    } else {
        echo "❌ Navbar photo URL not found in dashboard page\n";
    }
    
    // Check if old URL is still there
    if (strpos($response, 'uploads/profile/') !== false) {
        echo "❌ Old photo URL still found in dashboard page\n";
    } else {
        echo "✅ Old photo URL not found in dashboard page\n";
    }
    
} else {
    echo "❌ Admin dashboard returned HTTP $httpCode\n";
}

// 4. Test login and then check navbar
echo "\n4. Testing login and navbar photo...\n";

// Get CSRF token from login page
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'navbar_test_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'navbar_test_cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Login page accessible\n";
    
    // Extract CSRF token
    if (preg_match('/name="csrf_test_name" value="([^"]+)"/', $response, $matches)) {
        $csrfToken = $matches[1];
        echo "✅ CSRF token extracted: " . substr($csrfToken, 0, 10) . "...\n";
        
        // Login
        $loginData = [
            'csrf_test_name' => $csrfToken,
            'username' => 'admin',
            'password' => 'admin123'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'navbar_test_cookies.txt');
        curl_setopt($ch, CURLOPT_HEADER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            echo "✅ Login successful\n";
            
            // Now check dashboard with session
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/admin/dashboard');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_COOKIEFILE, 'navbar_test_cookies.txt');
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200) {
                echo "✅ Dashboard accessible with session\n";
                
                // Check if navbar photo URL is correct
                if (strpos($response, 'photo.php?file=' . $currentPhoto . '&type=profile') !== false) {
                    echo "✅ Correct navbar photo URL found in dashboard\n";
                } else {
                    echo "❌ Correct navbar photo URL not found in dashboard\n";
                }
                
            } else {
                echo "❌ Dashboard returned HTTP $httpCode\n";
            }
        } else {
            echo "❌ Login failed\n";
        }
    } else {
        echo "❌ CSRF token not found\n";
    }
} else {
    echo "❌ Login page returned HTTP $httpCode\n";
}

// 5. Cleanup
if (file_exists('navbar_test_cookies.txt')) {
    unlink('navbar_test_cookies.txt');
}

echo "\n=== TEST COMPLETE ===\n";
echo "\nIf navbar photo is not updating:\n";
echo "1. Check if session is being updated after upload\n";
echo "2. Check if navbar is using the correct photo URL\n";
echo "3. Try refreshing the page (Ctrl+F5)\n";
echo "4. Clear browser cache\n";
?>
