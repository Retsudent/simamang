<?php
/**
 * Script test detail untuk memverifikasi routes
 */

echo "🔍 Testing Routes Detail...\n\n";

$routesFile = 'app/Config/Routes.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    echo "📄 File Routes ditemukan\n";
    echo "📏 Ukuran file: " . strlen($content) . " bytes\n\n";
    
    // Cek dengan berbagai metode pencarian
    $searchTerms = [
        'update-photo',
        'change-password',
        'updatePhoto',
        'changePassword'
    ];
    
    foreach ($searchTerms as $term) {
        echo "🔍 Mencari: '$term'\n";
        
        // Method 1: strpos
        $pos1 = strpos($content, $term);
        echo "   strpos: " . ($pos1 !== false ? "DITEMUKAN di posisi $pos1" : "TIDAK DITEMUKAN") . "\n";
        
        // Method 2: stripos (case insensitive)
        $pos2 = stripos($content, $term);
        echo "   stripos: " . ($pos2 !== false ? "DITEMUKAN di posisi $pos2" : "TIDAK DITEMUKAN") . "\n";
        
        // Method 3: preg_match
        $pattern = '/' . preg_quote($term, '/') . '/';
        $matches = [];
        $found = preg_match($pattern, $content, $matches);
        echo "   preg_match: " . ($found ? "DITEMUKAN" : "TIDAK DITEMUKAN") . "\n";
        
        // Method 4: substr dengan context
        if ($pos1 !== false) {
            $context = substr($content, max(0, $pos1 - 20), 40);
            echo "   Context: ..." . $context . "...\n";
        }
        
        echo "\n";
    }
    
    // Cek encoding
    echo "🔍 Checking Encoding...\n";
    echo "   mb_detect_encoding: " . mb_detect_encoding($content) . "\n";
    echo "   mb_check_encoding: " . (mb_check_encoding($content, 'UTF-8') ? 'UTF-8 valid' : 'UTF-8 invalid') . "\n";
    
    // Cek karakter tersembunyi
    echo "\n🔍 Checking Hidden Characters...\n";
    $lines = explode("\n", $content);
    $profileRoutesStart = 0;
    
    for ($i = 0; $i < count($lines); $i++) {
        if (strpos($lines[$i], 'Profile routes') !== false) {
            $profileRoutesStart = $i;
            break;
        }
    }
    
    echo "   Profile routes mulai di baris: " . ($profileRoutesStart + 1) . "\n";
    
    // Tampilkan baris profile routes dengan detail
    for ($i = $profileRoutesStart; $i < min(count($lines), $profileRoutesStart + 10); $i++) {
        $line = $lines[$i];
        $lineNum = $i + 1;
        
        // Tampilkan karakter ASCII untuk debugging
        $ascii = '';
        for ($j = 0; $j < min(strlen($line), 20); $j++) {
            $ascii .= ord($line[$j]) . ' ';
        }
        
        echo sprintf("   %3d: %s\n", $lineNum, $line);
        echo sprintf("        ASCII: %s\n", $ascii);
        
        // Cek apakah ada karakter aneh
        if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $line)) {
            echo "        ⚠️  WARNING: Control characters detected!\n";
        }
    }
    
} else {
    echo "❌ File Routes tidak ditemukan\n";
}
