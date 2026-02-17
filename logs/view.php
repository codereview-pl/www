#!/usr/bin/env php
<?php
/**
 * CodeReview.pl - Log Viewer CLI
 * Command line interface for viewing logs
 * Usage: php logs/view.php [type] [lines]
 */

define('CLI_MODE', true);

require_once __DIR__ . '/../includes/logger.php';

$type = $argv[1] ?? 'app';
$lines = (int)($argv[2] ?? 50);

// Validate type
if (!in_array($type, ['app', 'error', 'access'])) {
    echo "Invalid log type. Use: app, error, or access\n";
    exit(1);
}

echo "=== CodeReview.pl Logs ===\n";
echo "Type: $type | Lines: $lines | Time: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("=", 80) . "\n\n";

$logs = Logger::getLogs($type, $lines);

if (empty($logs)) {
    echo "No logs found.\n";
} else {
    foreach (array_reverse($logs) as $log) {
        echo $log . "\n";
    }
}

echo "\n" . str_repeat("-", 80) . "\n";
echo "Usage examples:\n";
echo "  php logs/view.php app 50      # Show 50 lines of app logs\n";
echo "  php logs/view.php error 100   # Show 100 lines of error logs\n";
echo "  php logs/view.php access 20    # Show 20 lines of access logs\n";
