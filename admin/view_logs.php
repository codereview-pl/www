<?php
/**
 * CodeReview.pl - Log Viewer (Admin Only)
 * Simple web interface to view application logs
 * Protected by .htaccess authentication
 */

require_once __DIR__ . '/../includes/logger.php';

header('Content-Type: text/plain; charset=utf-8');

$type = $_GET['type'] ?? 'app';
$lines = (int)($_GET['lines'] ?? 50);
$clear = $_GET['clear'] ?? '';

if ($clear && in_array($clear, ['all', 'app', 'error', 'access'])) {
    Logger::clearLogs($clear);
    echo "Logs cleared: $clear\n\n";
}

echo "=== CodeReview.pl Admin Logs ===\n";
echo "Type: $type | Lines: $lines | Time: " . date('Y-m-d H:i:s') . "\n";
echo "User: " . ($_SERVER['PHP_AUTH_USER'] ?? 'unknown') . "\n";
echo "URL: " . ($_SERVER['REQUEST_URI'] ?? '') . "\n";
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
echo "Quick links:\n";
echo "• App logs: " . $_SERVER['PHP_SELF'] . "?type=app\n";
echo "• Error logs: " . $_SERVER['PHP_SELF'] . "?type=error\n";
echo "• Access logs: " . $_SERVER['PHP_SELF'] . "?type=access\n";
echo "• Clear all: " . $_SERVER['PHP_SELF'] . "?clear=all\n";
echo "• Clear errors: " . $_SERVER['PHP_SELF'] . "?clear=error\n";
