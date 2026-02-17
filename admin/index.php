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
        :root {
            --primary: #007cba;
            --primary-hover: #005a87;
            --danger: #dc3545;
            --danger-hover: #c82333;
            --bg: #f0f2f5;
            --card-bg: #ffffff;
            --text-main: #1a1a1a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        body { font-family: 'Inter', -apple-system, sans-serif; margin: 0; padding: 20px; background: var(--bg); color: var(--text-main); line-height: 1.5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: var(--card-bg); padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid var(--border); }
        .header h1 { margin: 0 0 8px 0; font-size: 24px; font-weight: 700; }
        .header p { margin: 0; color: var(--text-muted); }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; }
        .card { background: var(--card-bg); padding: 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid var(--border); }
        .card h2 { margin: 0 0 20px 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .btn { display: inline-flex; align-items: center; padding: 10px 20px; background: var(--primary); color: white; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 14px; transition: all 0.2s; border: none; cursor: pointer; }
        .btn:hover { background: var(--primary-hover); transform: translateY(-1px); }
        .btn-danger { background: var(--danger); }
        .btn-danger:hover { background: var(--danger-hover); }
        .actions { display: flex; flex-wrap: wrap; gap: 12px; }
        .log-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .stat-card { background: #f8fafc; padding: 16px; border-radius: 8px; text-align: center; border: 1px solid var(--border); }
        .stat-number { font-size: 24px; font-weight: 700; color: var(--primary); }
        .stat-label { font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; }
        pre { background: #1e293b; color: #f8fafc; padding: 20px; border-radius: 8px; overflow-x: auto; font-family: 'JetBrains Mono', monospace; font-size: 13px; line-height: 1.6; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-info { background: #e0f2fe; color: #075985; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 8px 0; border-bottom: 1px solid var(--border); font-size: 14px; }
        .info-table tr:last-child td { border-bottom: none; }
        .info-table td:first-child { color: var(--text-muted); width: 40%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h1><?= $page_title ?></h1>
                    <p>Zarządzanie i monitoring systemu CodeReview.pl</p>
                </div>
                <div style="text-align: right;">
                    <span class="badge badge-info">v1.0.5</span>
                    <p style="font-size: 12px; margin-top: 4px; color: var(--text-muted);">
                        Zalogowany: <strong><?= htmlspecialchars($_SERVER['PHP_AUTH_USER'] ?? 'unknown') ?></strong><br>
                        <?= date('Y-m-d H:i:s') ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <h2>⚡ Szybkie akcje</h2>
                <div class="actions">
                    <a href="view_logs.php" class="btn">Logi aplikacji</a>
                    <a href="view_logs.php?type=error" class="btn">Błędy</a>
                    <a href="view_logs.php?type=access" class="btn">Logi dostępu</a>
                    <a href="../database/init_db.php" class="btn" onclick="return confirm('Inicjalizować bazę danych? Dane mogą zostać nadpisane.')">Inicjalizacja DB</a>
                    <a href="/" class="btn" style="background: #64748b;">Powrót do strony</a>
                </div>
            </div>

            <div class="card">
                <h2>📊 Statystyki logów</h2>
                <div class="log-stats">
                    <?php
                    $logTypes = ['app', 'error', 'access'];
                    foreach ($logTypes as $type) {
                        $logs = Logger::getLogs($type, 1000);
                        $count = count($logs);
                    ?>
                    <div class="stat-card">
                        <div class="stat-number"><?= $count ?></div>
                        <div class="stat-label"><?= $type ?></div>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="card">
                <h2>🖥️ Informacje o systemie</h2>
                <table class="info-table">
                    <tr><td>PHP Version</td><td><strong><?= PHP_VERSION ?></strong></td></tr>
                    <tr><td>Server</td><td><?= htmlspecialchars(explode(' ', $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown')[0]) ?></td></tr>
                    <tr><td>OS</td><td><?= PHP_OS ?></td></tr>
                    <tr><td>Memory Limit</td><td><?= ini_get('memory_limit') ?></td></tr>
                    <tr><td>Upload Max</td><td><?= ini_get('upload_max_filesize') ?></td></tr>
                    <tr><td>Display Errors</td><td><span class="badge <?= ini_get('display_errors') ? 'badge-danger' : 'badge-success' ?>"><?= ini_get('display_errors') ? 'ON' : 'OFF' ?></span></td></tr>
                </table>
            </div>

            <div class="card">
                <h2>📁 Pliki logów</h2>
                <table class="info-table">
                    <?php
                    $logDir = __DIR__ . '/../logs';
                    foreach (['app.log', 'error.log', 'access.log'] as $file) {
                        $path = $logDir . '/' . $file;
                        $exists = file_exists($path);
                        $size = $exists ? number_format(filesize($path) / 1024, 2) . ' KB' : 'n/a';
                        $writable = $exists ? is_writable($path) : is_writable($logDir);
                    ?>
                    <tr>
                        <td><?= $file ?></td>
                        <td>
                            <?= $size ?> 
                            <?= $exists ? '<span class="badge badge-success">OK</span>' : '<span class="badge badge-danger">Missing</span>' ?>
                            <?= $writable ? '' : '<span class="badge badge-danger">Locked</span>' ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="card">
                <h2>🩺 Health Check</h2>
                <table class="info-table">
                    <tr>
                        <td>Baza danych (PDO)</td>
                        <td>
                            <?php 
                            $db = get_db();
                            echo $db ? '<span class="badge badge-success">Połączono</span>' : '<span class="badge badge-danger">Błąd</span>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Katalog logów</td>
                        <td>
                            <?= is_writable(__DIR__ . '/../logs') ? '<span class="badge badge-success">Zapisywalny</span>' : '<span class="badge badge-danger">Brak uprawnień</span>' ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Sesja PHP</td>
                        <td>
                            <?= session_id() ? '<span class="badge badge-success">Aktywna</span>' : '<span class="badge badge-danger">Nieaktywna</span>' ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card" style="margin-top: 24px;">
            <h2>🛠️ Szczegóły środowiska (RAW)</h2>
            <pre>
BASE_PATH: <?= defined('BASE_PATH') ? BASE_PATH : 'Not defined' ?>
DOCUMENT_ROOT: <?= $_SERVER['DOCUMENT_ROOT'] ?>
HTTP_HOST: <?= $_SERVER['HTTP_HOST'] ?>
SERVER_ADDR: <?= $_SERVER['SERVER_ADDR'] ?? 'n/a' ?>
REMOTE_ADDR: <?= $_SERVER['REMOTE_ADDR'] ?>

Loaded Extensions: <?= implode(', ', array_slice(get_loaded_extensions(), 0, 15)) ?>...
            </pre>
        </div>

        <div class="card" style="margin-top: 24px; border-color: #fee2e2;">
            <h2 style="color: var(--danger);">🚨 Strefa niebezpieczna</h2>
            <p style="font-size: 14px; margin-bottom: 20px;">Te akcje są nieodwracalne. Używaj z rozwagą.</p>
            <div class="actions">
                <a href="view_logs.php?clear=all" class="btn btn-danger" onclick="return confirm('Wyczyścić WSZYSTKIE logi? Te dane przepadną.')">Wyczyść wszystkie logi</a>
                <a href="view_logs.php?clear=error" class="btn btn-danger" onclick="return confirm('Wyczyścić logi błędów?')">Wyczyść błędy</a>
            </div>
        </div>
    </div>
</body>
</html>
