<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="/" class="logo">
                    <div class="logo-icon">CR</div>
                    Code<span>Review</span>.pl
                </a>
                <p><?= SITE_DESC ?>. Sesje live, Docker workspace'y, marketplace korepetycji. Open Source — Apache 2.0.</p>
            </div>
            <div class="footer-col">
                <h4>Produkt</h4>
                <a href="<?= SITE_URL ?>">Pobierz aplikację</a>
                <a href="<?= SITE_HUB ?>">Hub Server</a>
                <a href="<?= SITE_WEBVM ?>">WebVM</a>
                <a href="/marketplace">Marketplace</a>
            </div>
            <div class="footer-col">
                <h4>Dla deweloperów</h4>
                <a href="<?= SITE_GITHUB ?>">GitHub</a>
                <a href="/dokumentacja">Dokumentacja</a>
                <a href="/api">API</a>
                <a href="/cennik">Cennik</a>
            </div>
            <div class="footer-col">
                <h4>Kontakt</h4>
                <a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a>
                <a href="/regulamin">Regulamin</a>
                <a href="/prywatnosc">Polityka prywatności</a>
                <a href="/kontakt">Kontakt</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© <?= date('Y') ?> <?= SITE_NAME ?> / <?= SITE_BRAND ?> — <?= SITE_AUTHOR ?>. Apache License 2.0</span>
            <span style="font-family: var(--font-mono); font-size: 0.75rem;">v1.0 · PHP <?= PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION ?> · made in Poland 🇵🇱</span>
        </div>
    </div>
</footer>

<script src="/assets/js/main.js"></script>
<?php if (!empty($page_js)): ?>
<script><?= $page_js ?></script>
<?php endif; ?>
</body>
</html>
