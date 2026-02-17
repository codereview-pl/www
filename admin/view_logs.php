<?php
/**
 * CodeReview.pl - Log Viewer (Admin Only)
 * Simple web interface to view application logs
 * Protected by .htaccess authentication
 */

require_once __DIR__ . '/../includes/bootstrap.php';

$type = $_GET['type'] ?? 'app';
$lines = (int)($_GET['lines'] ?? 100);
$clear = $_GET['clear'] ?? '';

if ($clear && in_array($clear, ['all', 'app', 'error', 'access'])) {
    Logger::clearLogs($clear);
    header('Location: view_logs.php?type=' . ($clear === 'all' ? 'app' : $clear) . '&cleared=1');
    exit;
}

$logs = Logger::getLogs($type, $lines);
$page_title = 'Przeglądarka logów';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Admin - CodeReview.pl</title>
    <style>
        :root {
            --primary: #007cba;
            --primary-hover: #005a87;
            --danger: #dc3545;
            --bg: #f0f2f5;
            --card-bg: #ffffff;
            --text-main: #1a1a1a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        body { font-family: 'Inter', -apple-system, sans-serif; margin: 0; padding: 20px; background: var(--bg); color: var(--text-main); line-height: 1.5; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { background: var(--card-bg); padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .btn { display: inline-flex; align-items: center; padding: 8px 16px; background: var(--primary); color: white; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 13px; transition: all 0.2s; border: none; cursor: pointer; }
        .btn:hover { background: var(--primary-hover); }
        .btn-ghost { background: #64748b; }
        .btn-danger { background: var(--danger); }
        .controls { background: var(--card-bg); padding: 16px 24px; border-radius: 12px; margin-bottom: 24px; border: 1px solid var(--border); display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }
        .log-container { background: #1e293b; color: #f8fafc; padding: 0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .log-header { background: #0f172a; padding: 12px 24px; font-family: monospace; font-size: 12px; color: #94a3b8; border-bottom: 1px solid #1e293b; display: flex; justify-content: space-between; }
        .log-content { padding: 20px; font-family: 'JetBrains Mono', 'Fira Code', monospace; font-size: 13px; line-height: 1.6; overflow-x: auto; max-height: 70vh; overflow-y: auto; }
        .log-line { border-bottom: 1px solid #334155; padding: 4px 0; white-space: pre-wrap; }
        .log-line:last-child { border-bottom: none; }
        .level-INFO { color: #38bdf8; }
        .level-ERROR, .level-CRITICAL { color: #f87171; font-weight: bold; }
        .level-WARNING { color: #fbbf24; }
        .level-DB { color: #c084fc; }
        .level-API { color: #4ade80; }
        .timestamp { color: #64748b; font-size: 11px; margin-right: 8px; }
        select, input { padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); font-size: 13px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .badge-success { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= $page_title ?></h1>
            <div style="display: flex; gap: 12px;">
                <a href="index.php" class="btn btn-ghost">Dashboard</a>
                <a href="/" class="btn btn-ghost">Strona WWW</a>
            </div>
        </div>

        <?php if (isset($_GET['cleared'])): ?>
            <div style="background: #dcfce7; color: #166534; padding: 12px 24px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; border: 1px solid #bbf7d0;">
                Logi zostały wyczyszczone.
            </div>
        <?php endif; ?>

        <div class="controls">
            <form action="" method="GET" style="display: flex; gap: 16px; align-items: center; flex-grow: 1;">
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Typ logów</label>
                    <select name="type" onchange="this.form.submit()">
                        <option value="app" <?= $type === 'app' ? 'selected' : '' ?>>Aplikacja (app.log)</option>
                        <option value="error" <?= $type === 'error' ? 'selected' : '' ?>>Błędy (error.log)</option>
                        <option value="access" <?= $type === 'access' ? 'selected' : '' ?>>Dostęp (access.log)</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 4px;">Liczba linii</label>
                    <input type="number" name="lines" value="<?= $lines ?>" style="width: 80px;" onchange="this.form.submit()">
                </div>
                <div style="margin-left: auto; display: flex; gap: 12px; align-items: flex-end;">
                    <a href="?type=<?= $type ?>&clear=<?= $type ?>" class="btn btn-danger" onclick="return confirm('Wyczyścić te logi?')">Wyczyść <?= $type ?></a>
                    <a href="?type=<?= $type ?>&clear=all" class="btn btn-danger" onclick="return confirm('Wyczyścić WSZYSTKIE logi?')">Wyczyść wszystko</a>
                </div>
            </form>
        </div>

        <div class="log-container">
            <div class="log-header">
                <span>PLIK: logs/<?= $type ?>.log</span>
                <span>LINII: <?= count($logs) ?> / <?= $lines ?></span>
            </div>
            <div class="log-content">
                <?php if (empty($logs)): ?>
                    <div style="color: #64748b; text-align: center; padding: 40px;">Brak wpisów w logach.</div>
                <?php else: ?>
                    <?php 
                    // Show newest first for better UX in web view
                    foreach (array_reverse($logs) as $line): 
                        if (empty(trim($line))) continue;
                        
                        // Try to parse level for coloring
                        $level = 'INFO';
                        if (preg_match('/\] ([A-Z]+):/', $line, $matches)) {
                            $level = $matches[1];
                        } elseif (strpos($type, 'access') !== false) {
                            $level = 'API';
                        }
                    ?>
                        <div class="log-line">
                            <?php
                            // Highlight parts of the log
                            $displayLine = htmlspecialchars($line);
                            $displayLine = preg_replace('/^(\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\])/', '<span class="timestamp">$1</span>', $displayLine);
                            $displayLine = preg_replace('/ ([A-Z]+):/', ' <span class="level-$1">$1</span>:', $displayLine);
                            echo $displayLine;
                            ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        // Auto-refresh every 30 seconds
        setTimeout(() => {
            if (!window.location.search.includes('cleared')) {
                // window.location.reload();
            }
        }, 30000);
    </script>
</body>
</html>
