<?php
/**
 * CodeReview.pl / coboarding.com
 * Global Configuration
 *
 * Paths: index.php in root (httpdocs/)
 * Plesk: /var/www/vhosts/codereview.pl/httpdocs/
 */

define('SITE_NAME', 'CodeReview.pl');
define('SITE_BRAND', 'coboarding.com');
define('SITE_DESC', 'Platforma do zdalnego mentoringu programistów');
define('SITE_URL', 'https://codereview.pl');
define('SITE_HUB', 'https://hub.codereview.pl');
define('SITE_WEBVM', 'https://webvm.codereview.pl');
define('SITE_MARKETPLACE', 'https://coboarding.com');
define('SITE_GITHUB', 'https://github.com/wronai/codereview');
define('SITE_EMAIL', 'tom@sapletta.com');
define('SITE_AUTHOR', 'Tom Sapletta');

// Pricing
define('PRICE_STUDENT', 60);
define('PRICE_JUNIOR', 100);
define('PRICE_HUB_SEAT', 29);
define('PRICE_EDU_DISCOUNT', 50); // percent

// DB (from env or defaults)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_DATABASE') ?: 'codereview');
define('DB_USER', getenv('DB_USERNAME') ?: 'codereview');
define('DB_PASS', getenv('DB_PASSWORD') ?: '');

// Base path detection (works in both Docker and Plesk)
define('BASE_PATH', rtrim(dirname(__DIR__), '/'));

// Navigation
function get_nav_items(): array {
    return [
        ['url' => '/',            'label' => 'Strona główna'],
        ['url' => '/marketplace', 'label' => 'Marketplace'],
        ['url' => '/cennik',      'label' => 'Cennik'],
        ['url' => '/dokumentacja','label' => 'Docs'],
        ['url' => '/api',         'label' => 'API'],
        ['url' => '/kontakt',     'label' => 'Kontakt'],
    ];
}

function is_current_page(string $url): bool {
    $current = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $current = rtrim($current, '/') ?: '/';
    $url = rtrim($url, '/') ?: '/';
    return $current === $url;
}

/**
 * Simple DB connection (PDO)
 * Returns null if DB is not available (graceful fallback)
 */
function get_db(): ?PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    try {
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log('DB connection failed: ' . $e->getMessage());
        return null;
    }
}
