<?php
/**
 * Script test sederhana untuk memverifikasi routes
 */

echo "🔍 Testing Routes...\n\n";

$routesFile = 'app/Config/Routes.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    echo "📄 File Routes ditemukan\n";
    echo "📏 Ukuran file: " . strlen($content) . " bytes\n\n";
    
    // Cek routes yang seharusnya ada
    $routesToCheck = [
        'profile/update-photo' => 'Route update-photo',
        'profile/change-password' => 'Route change-password',
        'Profile::updatePhoto' => 'Method updatePhoto',
        'Profile::changePassword' => 'Method changePassword'
    ];
    
    foreach ($routesToCheck as $search => $description) {
        if (strpos($content, $search) !== false) {
            echo "✅ $description: DITEMUKAN\n";
        } else {
            echo "❌ $description: TIDAK DITEMUKAN\n";
        }
    }
    
    echo "\n🔍 Mencari pattern yang mirip...\n";
    
    // Cek apakah ada pattern yang mirip
    if (preg_match('/profile.*update.*photo/i', $content)) {
        echo "✅ Pattern 'profile update photo' ditemukan\n";
    }
    
    if (preg_match('/profile.*change.*password/i', $content)) {
        echo "✅ Pattern 'profile change password' ditemukan\n";
    }
    
    // Tampilkan beberapa baris di sekitar routes profile
    echo "\n📋 Isi file routes (sekitar profile routes):\n";
    $lines = explode("\n", $content);
    $startLine = 0;
    
    for ($i = 0; $i < count($lines); $i++) {
        if (strpos($lines[$i], 'Profile routes') !== false) {
            $startLine = $i;
            break;
        }
    }
    
    for ($i = max(0, $startLine - 2); $i < min(count($lines), $startLine + 10); $i++) {
        echo sprintf("%3d: %s\n", $i + 1, $lines[$i]);
    }
    
} else {
    echo "❌ File Routes tidak ditemukan\n";
}
