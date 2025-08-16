<?php
/**
 * Debug CSRF Token Issues for Profile Photo Upload
 * This script will help identify and fix CSRF token problems
 */

echo "=== DEBUG CSRF TOKEN UPLOAD ===\n\n";

// 1. Check CSRF configuration
echo "1. Checking CSRF Configuration...\n";
$csrfConfig = file_get_contents('app/Config/Security.php');
if ($csrfConfig === false) {
    echo "❌ Cannot read Security.php\n";
} else {
    echo "✅ Security.php readable\n";
    
    // Check CSRF settings
    if (strpos($csrfConfig, "'csrf' => true") !== false) {
        echo "✅ CSRF protection is enabled\n";
    } else {
        echo "❌ CSRF protection might be disabled\n";
    }
    
    // Check CSRF token name
    if (strpos($csrfConfig, "'tokenName' => 'csrf_token_name'") !== false) {
        echo "✅ CSRF token name is configured\n";
    } else {
        echo "❌ CSRF token name might not be configured\n";
    }
}

echo "\n2. Checking Profile Controller CSRF...\n";
$profileController = file_get_contents('app/Controllers/Profile.php');
if ($profileController === false) {
    echo "❌ Cannot read Profile.php\n";
} else {
    echo "✅ Profile.php readable\n";
    
    // Check if CSRF is properly handled
    if (strpos($profileController, 'csrf') !== false) {
        echo "✅ CSRF is mentioned in Profile controller\n";
    } else {
        echo "❌ CSRF not found in Profile controller\n";
    }
    
    // Check for proper form validation
    if (strpos($profileController, 'validate') !== false) {
        echo "✅ Form validation found\n";
    } else {
        echo "❌ Form validation might be missing\n";
    }
}

echo "\n3. Checking Profile View CSRF...\n";
$profileView = file_get_contents('app/Views/profile/index.php');
if ($profileView === false) {
    echo "❌ Cannot read profile/index.php\n";
} else {
    echo "✅ profile/index.php readable\n";
    
    // Check for CSRF token in form
    if (strpos($profileView, 'csrf_field') !== false) {
        echo "✅ CSRF token found in view\n";
    } else {
        echo "❌ CSRF token missing in view\n";
    }
    
    // Check for proper form method
    if (strpos($profileView, 'method="post"') !== false) {
        echo "✅ Form method is POST\n";
    } else {
        echo "❌ Form method might not be POST\n";
    }
    
    // Check for multipart/form-data
    if (strpos($profileView, 'multipart/form-data') !== false) {
        echo "✅ Form has multipart encoding\n";
    } else {
        echo "❌ Form missing multipart encoding\n";
    }
}

echo "\n4. Checking Routes for CSRF...\n";
$routes = file_get_contents('app/Config/Routes.php');
if ($routes === false) {
    echo "❌ Cannot read Routes.php\n";
} else {
    echo "✅ Routes.php readable\n";
    
    // Check for update-photo route
    if (strpos($routes, 'update-photo') !== false) {
        echo "✅ update-photo route found\n";
    } else {
        echo "❌ update-photo route missing\n";
    }
    
    // Check if route is in profile group
    if (strpos($routes, "'profile' => ['filter' => 'auth']") !== false) {
        echo "✅ Profile routes are protected by auth filter\n";
    } else {
        echo "❌ Profile routes might not be protected\n";
    }
}

echo "\n5. Testing CSRF Token Generation...\n";
// Test if we can access the profile page to get CSRF token
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
    
    // Extract CSRF token from response
    if (preg_match('/name="csrf_test_name" value="([^"]+)"/', $response, $matches)) {
        echo "✅ CSRF token found: " . substr($matches[1], 0, 10) . "...\n";
    } else {
        echo "❌ CSRF token not found in response\n";
    }
} else {
    echo "❌ Profile page returned HTTP $httpCode\n";
    echo "Response: " . substr($response, 0, 200) . "...\n";
}

echo "\n6. Recommendations:\n";
echo "- If CSRF token is missing, check if Security.php has proper CSRF configuration\n";
echo "- If form is missing CSRF token, add <?= csrf_hash() ?> to the form\n";
echo "- If upload fails, ensure form has enctype='multipart/form-data'\n";
echo "- Check if session is working properly for CSRF token storage\n";

echo "\n=== END DEBUG ===\n";
?>
