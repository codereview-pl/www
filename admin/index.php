<?php
/**
 * CodeReview.pl - Admin Dashboard
 * Simple admin interface for system management
 */

require_once __DIR__ . '/../includes/logger.php';

$page_title = 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - CodeReview.pl</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 10px 20px; background: #007cba; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px; }
        .btn:hover { background: #005a87; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .log-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-card { background: #f8f9fa; padding: 15px; border-radius: 6px; text-align: center; }
        .stat-number { font-size: 2em; font-weight: bold; color: #007cba; }
        .stat-label { color: #666; margin-top: 5px; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= $page_title ?></h1>
            <p>System management and monitoring for CodeReview.pl</p>
            <p><small>User: <?= htmlspecialchars($_SERVER['PHP_AUTH_USER'] ?? 'unknown') ?> | Time: <?= date('Y-m-d H:i:s') ?></small></p>
        </div>

        <div class="card">
            <h2>Quick Actions</h2>
            <a href="view_logs.php" class="btn">View Logs</a>
            <a href="view_logs.php?type=error" class="btn">View Errors</a>
            <a href="view_logs.php?type=access" class="btn">View Access</a>
            <a href="../database/init_db.php" class="btn">Initialize Database</a>
        </div>

        <div class="card">
            <h2>Log Statistics</h2>
            <div class="log-stats">
                <?php
                $logTypes = ['app', 'error', 'access'];
                foreach ($logTypes as $type) {
                    $logs = Logger::getLogs($type, 1000);
                    $count = count($logs);
                    $lastLog = !empty($logs) ? end($logs) : 'No logs';
                    preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $lastLog, $matches);
                    $lastTime = $matches[1] ?? 'Unknown';
                ?>
                <div class="stat-card">
                    <div class="stat-number"><?= $count ?></div>
                    <div class="stat-label"><?= ucfirst($type) ?> logs</div>
                    <small>Last: <?= $lastTime ?></small>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="card">
            <h2>System Information</h2>
            <pre>
PHP Version: <?= PHP_VERSION ?>
Server: <?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') ?>
Document Root: <?= htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') ?>
HTTPS: <?= isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'Yes' : 'No' }
Remote Address: <?= htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'Unknown') ?>
User Agent: <?= htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') ?>

Log Directory: <?= __DIR__ . '/../logs' ?>
Log Files:
<?php
$logDir = __DIR__ . '/../logs';
foreach (['app.log', 'error.log', 'access.log'] as $file) {
    $path = $logDir . '/' . $file;
    if (file_exists($path)) {
        $size = filesize($path);
        $modified = date('Y-m-d H:i:s', filemtime($path));
        echo "  $file: " . number_format($size) . " bytes, modified $modified\n";
    } else {
        echo "  $file: Not found\n";
    }
}
?>
            </pre>
        </div>

        <div class="card">
            <h2>Danger Zone</h2>
            <p><strong>Warning:</strong> These actions are irreversible!</p>
            <a href="view_logs.php?clear=all" class="btn btn-danger" onclick="return confirm('Clear ALL logs? This cannot be undone.')">Clear All Logs</a>
            <a href="view_logs.php?clear=error" class="btn btn-danger" onclick="return confirm('Clear error logs? This cannot be undone.')">Clear Error Logs</a>
        </div>
    </div>
</body>
</html>
