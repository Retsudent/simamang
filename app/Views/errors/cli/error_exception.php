<?php
/**
 * CLI Error Exception Handler
 * 
 * This file handles error exceptions when running CodeIgniter from the command line.
 * It provides detailed error information for debugging purposes.
 */

// Get error details
$errorId = uniqid('cli_error_', true);
$timestamp = date('Y-m-d H:i:s');
$memoryUsage = memory_get_usage(true);
$peakMemory = memory_get_peak_usage(true);

// Format memory usage
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

// CLI Colors helper (guard against multiple includes)
if (!function_exists('getColorsEx')) {
    function getColorsEx(): array
    {
        return [
            'red'      => "\033[31m",
            'green'    => "\033[32m",
            'yellow'   => "\033[33m",
            'blue'     => "\033[34m",
            'magenta'  => "\033[35m",
            'cyan'     => "\033[36m",
            'white'    => "\033[37m",
            'bold'     => "\033[1m",
            'reset'    => "\033[0m",
            'bg_red'   => "\033[41m",
            'bg_green' => "\033[42m",
            'bg_yellow'=> "\033[43m",
            'bg_blue'  => "\033[44m",
        ];
    }
}

// Function to print colored text
if (!function_exists('printColor')) {
    function printColor($text, $color = 'white', $newline = true) {
        $colors = getColorsEx();
        $prefix = $colors[$color] ?? '';
        $suffix = $colors['reset'] ?? '';
        $output = $prefix . $text . $suffix;
        if ($newline) {
            $output .= "\n";
        }
        echo $output;
    }
}

// Function to print separator
if (!function_exists('printSeparator')) {
    function printSeparator($char = '=', $length = 80) {
        echo str_repeat($char, $length) . "\n";
    }
}

// Function to print header
if (!function_exists('printHeader')) {
    function printHeader($title, $color = 'blue') {
        printSeparator();
        printColor(str_pad($title, 80, ' ', STR_PAD_BOTH), $color);
        printSeparator();
    }
}

// Function to print section
if (!function_exists('printSection')) {
    function printSection($title, $color = 'yellow') {
        printColor("\n" . $title, $color);
        printSeparator('-', strlen($title));
    }
}

// Function to print key-value pair
if (!function_exists('printKeyValue')) {
    function printKeyValue($key, $value, $keyColor = 'cyan', $valueColor = 'white') {
        $colors = getColorsEx();
        echo ($colors[$keyColor] ?? '') . str_pad($key, 20) . ($colors['reset'] ?? '') . ": ";
        printColor($value, $valueColor, false);
    }
}

// Function to print table row
if (!function_exists('printTableRow')) {
    function printTableRow($key, $value, $keyColor = 'cyan', $valueColor = 'white') {
        printKeyValue($key, $value, $keyColor, $valueColor);
    }
}

// Clear screen (if supported)
if (function_exists('system')) {
    if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
        @system('cls');
    } else {
        @system('clear');
    }
}

// Print main header
printHeader('SIMAMANG - CLI ERROR EXCEPTION', 'bg_red');

// Print error summary
printSection('ERROR SUMMARY', 'red');
printTableRow('Error ID', $errorId, 'cyan', 'white');
printTableRow('Timestamp', $timestamp, 'cyan', 'white');
printTableRow('Environment', defined('ENVIRONMENT') ? ENVIRONMENT : 'Unknown', 'cyan', 'white');
printTableRow('PHP Version', PHP_VERSION, 'cyan', 'white');

// Try to get CodeIgniter version safely
$ciVersion = 'Unknown';
if (class_exists('CodeIgniter\CodeIgniter')) {
    $ciVersion = \CodeIgniter\CodeIgniter::CI_VERSION;
} elseif (defined('CodeIgniter\CodeIgniter::CI_VERSION')) {
    $ciVersion = \CodeIgniter\CodeIgniter::CI_VERSION;
}
printTableRow('CodeIgniter', $ciVersion, 'cyan', 'white');

// Print error details
if (isset($exception)) {
    printSection('EXCEPTION DETAILS', 'red');
    printTableRow('Exception Class', get_class($exception), 'cyan', 'white');
    printTableRow('Error Code', $exception->getCode(), 'cyan', 'white');
    printTableRow('Error Message', $exception->getMessage(), 'cyan', 'white');
    printTableRow('File', $exception->getFile(), 'cyan', 'white');
    printTableRow('Line', $exception->getLine(), 'cyan', 'white');
}

// Print file information
if (isset($file) && isset($line)) {
    printSection('FILE INFORMATION', 'yellow');
    printTableRow('File Path', $file, 'cyan', 'white');
    printTableRow('Line Number', $line, 'cyan', 'white');
    
    // Check if file exists and is readable
    if (is_file($file) && is_readable($file)) {
        printColor("\nFile exists and is readable.", 'green');
        
        // Show context around the error line
        $context = 3; // Lines before and after
        $start = max(1, $line - $context);
        $end = $line + $context;
        
        printColor("\nContext around line $line:", 'yellow');
        printSeparator('-');
        
        $fileHandle = fopen($file, 'r');
        if ($fileHandle) {
            $currentLine = 1;
            while (($buffer = fgets($fileHandle)) !== false) {
                if ($currentLine >= $start && $currentLine <= $end) {
                    $marker = ($currentLine == $line) ? '>>> ' : '    ';
                    $lineColor = ($currentLine == $line) ? 'red' : 'white';
                    printColor(sprintf('%s%4d: %s', $marker, $currentLine, rtrim($buffer)), $lineColor, false);
                }
                $currentLine++;
            }
            fclose($fileHandle);
        }
    } else {
        printColor("\nFile does not exist or is not readable.", 'red');
    }
}

// Print stack trace
if (isset($trace) && is_array($trace)) {
    printSection('STACK TRACE', 'yellow');
    
    foreach ($trace as $index => $row) {
        printColor("\n#" . $index, 'cyan');
        
        if (isset($row['file'])) {
            printTableRow('  File', $row['file'], 'cyan', 'white');
        }
        
        if (isset($row['line'])) {
            printTableRow('  Line', $row['line'], 'cyan', 'white');
        }
        
        if (isset($row['function'])) {
            printTableRow('  Function', $row['function'], 'cyan', 'white');
        }
        
        if (isset($row['class'])) {
            printTableRow('  Class', $row['class'], 'cyan', 'white');
        }
        
        if (isset($row['type'])) {
            printTableRow('  Type', $row['type'], 'cyan', 'white');
        }
    }
}

// Print system information
printSection('SYSTEM INFORMATION', 'blue');
printTableRow('Memory Usage', formatBytes($memoryUsage), 'cyan', 'white');
printTableRow('Peak Memory', formatBytes($peakMemory), 'cyan', 'white');
printTableRow('Memory Limit', ini_get('memory_limit'), 'cyan', 'white');
printTableRow('Max Execution Time', ini_get('max_execution_time') . 's', 'cyan', 'white');
printTableRow('Upload Max Filesize', ini_get('upload_max_filesize'), 'cyan', 'white');
printTableRow('Post Max Size', ini_get('post_max_size'), 'cyan', 'white');

// Print server information
printSection('SERVER INFORMATION', 'blue');
printTableRow('Server Software', $_SERVER['SERVER_SOFTWARE'] ?? 'CLI', 'cyan', 'white');
printTableRow('Server Protocol', $_SERVER['SERVER_PROTOCOL'] ?? 'CLI', 'cyan', 'white');
printTableRow('Request Method', $_SERVER['REQUEST_METHOD'] ?? 'CLI', 'cyan', 'white');
printTableRow('Request URI', $_SERVER['REQUEST_URI'] ?? 'CLI', 'cyan', 'white');
printTableRow('Remote Address', $_SERVER['REMOTE_ADDR'] ?? 'CLI', 'cyan', 'white');
printTableRow('User Agent', $_SERVER['HTTP_USER_AGENT'] ?? 'CLI', 'cyan', 'white');

// Print debugging information
printSection('DEBUGGING INFORMATION', 'green');
printColor("To debug this error:", 'white');
printColor("1. Check the file and line number mentioned above", 'white');
printColor("2. Review the stack trace for the call sequence", 'white');
printColor("3. Check if all required files exist", 'white');
printColor("4. Verify file permissions", 'white');
printColor("5. Check for syntax errors in the code", 'white');

// Print help information
printSection('HELP & SUPPORT', 'green');
printColor("If you need help:", 'white');
printColor("1. Check the CodeIgniter documentation", 'white');
printColor("2. Review the error logs in writable/logs/", 'white');
printColor("3. Contact the development team", 'white');
printColor("4. Provide this error ID: " . $errorId, 'white');

// Print footer
printSeparator();
printColor("Error logged with ID: " . $errorId, 'red');
printColor("Generated at: " . $timestamp, 'white');
printSeparator();

// Exit with error code
exit(1);
?>
