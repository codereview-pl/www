<?php
require_once __DIR__ . '/bootstrap.php';

// Log page access
Logger::access('Page view', [
    'page_title' => $page_title ?? 'home',
    'referrer' => $_SERVER['HTTP_REFERER'] ?? 'direct',
    'query_params' => $_GET ?? []
]);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? SITE_NAME) ?> — <?= SITE_DESC ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_desc ?? SITE_DESC) ?>">
    <meta name="author" content="<?= SITE_AUTHOR ?>">
    <link rel="canonical" href="<?= SITE_URL . ($_SERVER['REQUEST_URI'] ?? '/') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($page_title ?? SITE_NAME) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_desc ?? SITE_DESC) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= SITE_URL . ($_SERVER['REQUEST_URI'] ?? '/') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <?php if (!empty($page_css)): ?>
    <style><?= $page_css ?></style>
    <?php endif; ?>
</head>
<body>
<div class="grid-bg"></div>

<nav>
    <div class="container">
        <a href="/" class="logo">
            <div class="logo-icon">CR</div>
            Code<span>Review</span>.pl
        </a>
        <ul class="nav-links" id="navLinks">
            <?php foreach (get_nav_items() as $item): ?>
            <li><a href="<?= $item['url'] ?>"<?= is_current_page($item['url']) ? ' class="active"' : '' ?>><?= $item['label'] ?></a></li>
            <?php endforeach; ?>
        </ul>
        <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu" style="display:none;background:none;border:none;cursor:pointer;padding:8px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <div class="nav-cta">
            <div class="lang-switcher" style="display:flex;gap:8px;margin-right:12px;font-size:.8rem;font-weight:600;font-family:var(--font-mono);">
                <?php foreach (Language::getLangs() as $l): ?>
                <a href="?lang=<?= $l ?>" style="text-decoration:none;color:<?= Language::getCurrent() === $l ? 'var(--accent)' : 'var(--text-muted)' ?>;padding:4px;"><?= strtoupper($l) ?></a>
                <?php endforeach; ?>
            </div>
            <a href="<?= SITE_WEBVM ?>" class="btn btn-ghost btn-sm">WebVM</a>
            <a href="<?= SITE_URL ?>" class="btn btn-primary btn-sm">Pobierz ↓</a>
        </div>
    </div>
</nav>
<script>
(function(){
    var btn=document.getElementById('mobileMenuBtn');
    var nav=document.getElementById('navLinks');
    if(btn&&nav){btn.addEventListener('click',function(){nav.classList.toggle('active');btn.classList.toggle('active');});
    var mq=window.matchMedia('(max-width:900px)');
    function check(m){btn.style.display=m.matches?'flex':'none';if(!m.matches)nav.classList.remove('active');}
    mq.addListener(check);check(mq);
    nav.querySelectorAll('a').forEach(function(a){a.addEventListener('click',function(){if(mq.matches)nav.classList.remove('active');});});
}})();
</script>
