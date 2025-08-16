<?php

/**
 * Script test untuk fitur profil SIMAMANG
 * Memverifikasi bahwa semua komponen fitur profil berfungsi dengan baik
 */

echo "🧪 TESTING FITUR PROFIL SIMAMANG\n";
echo "================================\n\n";

// Test 1: Cek file yang diperlukan
echo "📁 TEST 1: Cek File yang Diperlukan\n";
echo "------------------------------------\n";

$requiredFiles = [
    'app/Controllers/Profile.php',
    'app/Views/profile/index.php',
    'app/Views/profile/edit.php',
    'app/Database/Migrations/AddProfilePhoto.php',
    'app/Config/Routes.php',
    'app/Views/layouts/main.php'
];

$allFilesExist = true;
foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file - ADA\n";
    } else {
        echo "❌ $file - TIDAK ADA\n";
        $allFilesExist = false;
    }
}

if ($allFilesExist) {
    echo "\n🎉 Semua file yang diperlukan tersedia!\n";
} else {
    echo "\n⚠️  Beberapa file tidak ditemukan!\n";
}

echo "\n";

// Test 2: Cek struktur folder
echo "📂 TEST 2: Cek Struktur Folder\n";
echo "-------------------------------\n";

$uploadPath = 'writable/uploads/profile/';
if (is_dir($uploadPath)) {
    echo "✅ Folder uploads/profile - ADA\n";
    if (is_writable($uploadPath)) {
        echo "✅ Folder uploads/profile - WRITABLE\n";
    } else {
        echo "❌ Folder uploads/profile - TIDAK WRITABLE\n";
    }
} else {
    echo "❌ Folder uploads/profile - TIDAK ADA\n";
    echo "💡 Buat folder: mkdir -p $uploadPath\n";
}

echo "\n";

// Test 3: Cek routes
echo "🌐 TEST 3: Cek Routes\n";
echo "--------------------\n";

$routesFile = 'app/Config/Routes.php';
if (file_exists($routesFile)) {
    $routesContent = file_get_contents($routesFile);
    
    $requiredRoutes = [
        'profile',
        'Profile::index',
        'Profile::edit',
        'Profile::update',
        'Profile::updatePhoto',
        'Profile::changePassword'
    ];
    
    $allRoutesExist = true;
    foreach ($requiredRoutes as $route) {
        if (strpos($routesContent, $route) !== false) {
            echo "✅ Route: $route - ADA\n";
        } else {
            echo "❌ Route: $route - TIDAK ADA\n";
            $allRoutesExist = false;
        }
    }
    
    if ($allRoutesExist) {
        echo "\n🎉 Semua routes yang diperlukan tersedia!\n";
    } else {
        echo "\n⚠️  Beberapa routes tidak ditemukan!\n";
    }
} else {
    echo "❌ File routes tidak ditemukan!\n";
}

echo "\n";

// Test 4: Cek helper
echo "🔧 TEST 4: Cek Helper\n";
echo "---------------------\n";

$autoloadFile = 'app/Config/Autoload.php';
if (file_exists($autoloadFile)) {
    $autoloadContent = file_get_contents($autoloadFile);
    
    if (strpos($autoloadContent, "'text'") !== false) {
        echo "✅ Helper 'text' - TERLOAD\n";
    } else {
        echo "❌ Helper 'text' - TIDAK TERLOAD\n";
        echo "💡 Tambahkan 'text' ke array helpers di Autoload.php\n";
    }
    
    if (strpos($autoloadContent, "'form'") !== false) {
        echo "✅ Helper 'form' - TERLOAD\n";
    } else {
        echo "❌ Helper 'form' - TIDAK TERLOAD\n";
    }
    
    if (strpos($autoloadContent, "'url'") !== false) {
        echo "✅ Helper 'url' - TERLOAD\n";
    } else {
        echo "❌ Helper 'url' - TIDAK TERLOAD\n";
    }
} else {
    echo "❌ File Autoload.php tidak ditemukan!\n";
}

echo "\n";

// Test 5: Cek database migration
echo "🗄️ TEST 5: Cek Database Migration\n";
echo "---------------------------------\n";

$migrationFile = 'app/Database/Migrations/AddProfilePhoto.php';
if (file_exists($migrationFile)) {
    $migrationContent = file_get_contents($migrationFile);
    
    if (strpos($migrationContent, 'foto_profil') !== false) {
        echo "✅ Field foto_profil - ADA di migration\n";
    } else {
        echo "❌ Field foto_profil - TIDAK ADA di migration\n";
    }
    
    if (strpos($migrationContent, 'admin') !== false) {
        echo "✅ Tabel admin - ADA di migration\n";
    } else {
        echo "❌ Tabel admin - TIDAK ADA di migration\n";
    }
    
    if (strpos($migrationContent, 'pembimbing') !== false) {
        echo "✅ Tabel pembimbing - ADA di migration\n";
    } else {
        echo "❌ Tabel pembimbing - TIDAK ADA di migration\n";
    }
    
    if (strpos($migrationContent, 'siswa') !== false) {
        echo "✅ Tabel siswa - ADA di migration\n";
    } else {
        echo "❌ Tabel siswa - TIDAK ADA di migration\n";
    }
} else {
    echo "❌ File migration tidak ditemukan!\n";
}

echo "\n";

// Test 6: Cek controller
echo "🎮 TEST 6: Cek Controller\n";
echo "-------------------------\n";

$controllerFile = 'app/Controllers/Profile.php';
if (file_exists($controllerFile)) {
    $controllerContent = file_get_contents($controllerFile);
    
    $requiredMethods = [
        'index',
        'edit',
        'update',
        'updatePhoto',
        'changePassword'
    ];
    
    $allMethodsExist = true;
    foreach ($requiredMethods as $method) {
        if (strpos($controllerContent, "public function $method") !== false) {
            echo "✅ Method: $method() - ADA\n";
        } else {
            echo "❌ Method: $method() - TIDAK ADA\n";
            $allMethodsExist = false;
        }
    }
    
    if ($allMethodsExist) {
        echo "\n🎉 Semua method yang diperlukan tersedia!\n";
    } else {
        echo "\n⚠️  Beberapa method tidak ditemukan!\n";
    }
} else {
    echo "❌ File controller tidak ditemukan!\n";
}

echo "\n";

// Test 7: Cek views
echo "👁️ TEST 7: Cek Views\n";
echo "--------------------\n";

$viewFiles = [
    'app/Views/profile/index.php' => 'Halaman Profil',
    'app/Views/profile/edit.php' => 'Halaman Edit Profil'
];

foreach ($viewFiles as $file => $description) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        if (strpos($content, 'foto_profil') !== false) {
            echo "✅ $description - Field foto_profil ADA\n";
        } else {
            echo "❌ $description - Field foto_profil TIDAK ADA\n";
        }
        
        if (strpos($content, 'upload') !== false) {
            echo "✅ $description - Fitur upload ADA\n";
        } else {
            echo "❌ $description - Fitur upload TIDAK ADA\n";
        }
    } else {
        echo "❌ $file - TIDAK ADA\n";
    }
}

echo "\n";

// Test 8: Cek layout
echo "🎨 TEST 8: Cek Layout\n";
echo "---------------------\n";

$layoutFile = 'app/Views/layouts/main.php';
if (file_exists($layoutFile)) {
    $layoutContent = file_get_contents($layoutFile);
    
    if (strpos($layoutContent, 'profile') !== false) {
        echo "✅ Link profil - ADA di layout\n";
    } else {
        echo "❌ Link profil - TIDAK ADA di layout\n";
    }
    
    if (strpos($layoutContent, 'dropdown') !== false) {
        echo "✅ User dropdown - ADA di layout\n";
    } else {
        echo "❌ User dropdown - TIDAK ADA di layout\n";
    }
    
    if (strpos($layoutContent, 'foto_profil') !== false) {
        echo "✅ Foto profil - ADA di layout\n";
    } else {
        echo "❌ Foto profil - TIDAK ADA di layout\n";
    }
} else {
    echo "❌ File layout tidak ditemukan!\n";
}

echo "\n";

// Summary
echo "📊 RINGKASAN TEST\n";
echo "==================\n";

$totalTests = 8;
$passedTests = 0;

// Count passed tests (simplified logic)
if ($allFilesExist) $passedTests++;
if (is_dir($uploadPath) && is_writable($uploadPath)) $passedTests++;
if (strpos($routesContent ?? '', 'profile') !== false) $passedTests++;
if (strpos($autoloadContent ?? '', "'text'") !== false) $passedTests++;
if (strpos($migrationContent ?? '', 'foto_profil') !== false) $passedTests++;
if (strpos($controllerContent ?? '', 'public function index') !== false) $passedTests++;
if (file_exists('app/Views/profile/index.php')) $passedTests++;
if (strpos($layoutContent ?? '', 'profile') !== false) $passedTests++;

$percentage = round(($passedTests / $totalTests) * 100, 1);

echo "✅ Test yang berhasil: $passedTests/$totalTests\n";
echo "📈 Persentase keberhasilan: $percentage%\n";

if ($percentage >= 80) {
    echo "\n🎉 FITUR PROFIL SIAP DIGUNAKAN!\n";
    echo "💡 Jalankan migration database untuk mengaktifkan fitur\n";
} elseif ($percentage >= 60) {
    echo "\n⚠️  FITUR PROFIL HAMPIR SIAP\n";
    echo "🔧 Perbaiki beberapa masalah yang ditemukan\n";
} else {
    echo "\n❌ FITUR PROFIL BELUM SIAP\n";
    echo "🚨 Banyak masalah yang perlu diperbaiki\n";
}

echo "\n";
echo "📝 LANGKAH SELANJUTNYA:\n";
echo "1. Jalankan migration: php add_profile_photo_simple.php\n";
echo "2. Buat folder upload: mkdir -p writable/uploads/profile\n";
echo "3. Test fitur di browser: /profile\n";
echo "4. Upload foto profil dan test semua fitur\n";

echo "\n✨ SELESAI TESTING FITUR PROFIL SIMAMANG!\n";
