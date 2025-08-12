<?php
/**
 * CLI Production Error Handler
 */

$errorId = uniqid('cli_prod_', true);
$timestamp = date('Y-m-d H:i:s');

// CLI Colors helper (guard against multiple includes)
if (!function_exists('getColorsProd')) {
    function getColorsProd(): array
    {
        return [
            'red'    => "\033[31m",
            'green'  => "\033[32m",
            'yellow' => "\033[33m",
            'blue'   => "\033[34m",
            'cyan'   => "\033[36m",
            'white'  => "\033[37m",
            'reset'  => "\033[0m",
            'bg_red' => "\033[41m",
        ];
    }
}

if (!function_exists('printColorProd')) {
    function printColorProd($text, $color = 'white', $newline = true) {
        $colors = getColorsProd();
        $prefix = $colors[$color] ?? '';
        $suffix = $colors['reset'] ?? '';
        $output = $prefix . $text . $suffix;
        if ($newline) {
            $output .= "\n";
        }
        echo $output;
    }
}

if (!function_exists('printSeparatorProd')) {
    function printSeparatorProd($char = '=', $length = 80) {
        echo str_repeat($char, $length) . "\n";
    }
}

// Clear screen
if (function_exists('system')) {
    if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
        @system('cls');
    } else {
        @system('clear');
    }
}

// Print header
printSeparatorProd();
printColorProd(str_pad('SIMAMANG - CLI PRODUCTION ERROR', 80, ' ', STR_PAD_BOTH), 'bg_red');
printSeparatorProd();

// Print error details
printColorProd("\nSystem Error Detected", 'red');
printColorProd("A system error has occurred. Please contact the administrator.", 'white');
printColorProd("Error ID: " . $errorId, 'cyan');
printColorProd("Time: " . $timestamp, 'cyan');
printColorProd("Environment: Production", 'yellow');

// Print help
printColorProd("\nTroubleshooting Steps:", 'yellow');
printColorProd("1. Check system logs", 'white');
printColorProd("2. Verify database connection", 'white');
printColorProd("3. Check file permissions", 'white');
printColorProd("4. Contact system administrator", 'white');

// Print footer
printSeparatorProd();
printColorProd("Error logged with ID: " . $errorId, 'red');
printSeparatorProd();

exit(1);
?>
