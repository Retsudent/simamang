<?php
/**
 * CLI 404 Error Handler
 */

$errorId = uniqid('cli_404_', true);
$timestamp = date('Y-m-d H:i:s');

// CLI Colors
$colors = [
    'red' => "\033[31m",
    'green' => "\033[32m",
    'yellow' => "\033[33m",
    'blue' => "\033[34m",
    'cyan' => "\033[36m",
    'white' => "\033[37m",
    'reset' => "\033[0m",
    'bg_red' => "\033[41m",
];

function printColor($text, $color = 'white', $newline = true) {
    global $colors;
    $output = $colors[$color] . $text . $colors['reset'];
    if ($newline) {
        $output .= "\n";
    }
    echo $output;
}

function printSeparator($char = '=', $length = 80) {
    echo str_repeat($char, $length) . "\n";
}

// Clear screen
if (function_exists('system')) {
    system('clear');
}

// Print header
printSeparator();
printColor(str_pad('SIMAMANG - CLI 404 ERROR', 80, ' ', STR_PAD_BOTH), 'bg_red');
printSeparator();

// Print error details
printColor("\n404 - Page Not Found", 'red');
printColor("The requested page could not be found.", 'white');
printColor("Error ID: " . $errorId, 'cyan');
printColor("Time: " . $timestamp, 'cyan');

// Print help
printColor("\nAvailable Commands:", 'yellow');
printColor("1. php spark serve - Start development server", 'white');
printColor("2. php spark migrate - Run database migrations", 'white');
printColor("3. php spark db:seed - Seed database", 'white');
printColor("4. php spark make:controller - Create controller", 'white');
printColor("5. php spark make:model - Create model", 'white');

// Print footer
printSeparator();
printColor("For help, run: php spark help", 'green');
printSeparator();

exit(1);
?>
