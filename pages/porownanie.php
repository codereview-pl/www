<?php
$page_title = __('nav_comparison');
$page_desc  = __('comp_desc');
require_once __DIR__ . '/../includes/header.php';

// Log page access
Logger::info('Comparison page viewed', [
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
]);
?>

<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/"><?= __('nav_home') ?></a><span class="sep">/</span><span class="current"><?= __('nav_comparison') ?></span></div>
    <h1><?= __('comp_title') ?></h1>
    <p><?= __('comp_desc') ?></p>
</div></section>

<section><div class="container">
    <div class="section-header fade-in">
        <div class="section-label"><?= __('comp_label') ?></div>
        <h2 class="section-title"><?= __('comp_subtitle') ?></h2>
        <p class="section-desc"><?= __('comp_subdesc') ?></p>
    </div>

    <!-- Comparison Table -->
    <div class="comparison-table fade-in">
        <table class="data-table">
            <thead>
                <tr>
                    <th><?= __('comp_header_tool') ?></th>
                    <th><?= __('comp_header_type') ?></th>
                    <th><?= __('comp_header_live') ?></th>
                    <th><?= __('comp_header_docker') ?></th>
                    <th><?= __('comp_header_disk') ?></th>
                    <th><?= __('comp_header_install') ?></th>
                    <th><?= __('comp_header_price') ?></th>
                    <th><?= __('comp_header_use') ?></th>
                </tr>
            </thead>
            <tbody>
                <!-- CodeReview.pl -->
                <tr class="highlight-row">
                    <td>
                        <div class="tool-info">
                            <strong>CodeReview.pl</strong><br>
                            <small>Platforma mentoringowa</small>
                        </div>
                    </td>
                    <td><span class="badge badge-accent">Hybrydowa</span></td>
                    <td>✅ <?= Language::getCurrent() === 'pl' ? 'Pełna (ekran + terminal)' : 'Full (screen + terminal)' ?></td>
                    <td>✅ Docker + root</td>
                    <td>✅ <?= Language::getCurrent() === 'pl' ? 'Pełny dostęp' : 'Full access' ?></td>
                    <td>Desktop/WebVM</td>
                    <td><strong>29-149 zł/mies</strong></td>
                    <td>Edukacja, bootcampy, onboarding</td>
                </tr>
<?php
$tools = [
    [
        'name' => 'PairCode',
        'url' => 'https://paircode.live/',
        'type' => 'Web',
        'live' => '✅ Ekran + video/audio',
        'live_en' => '✅ Screen + video/audio',
        'docker' => '❌ Brak terminala',
        'docker_en' => '❌ No terminal',
        'disk' => '❌ Ograniczony',
        'disk_en' => '❌ Limited',
        'install' => 'Brak',
        'install_en' => 'None',
        'price' => 'Free/Premium',
        'use' => 'Szybkie sesje debugowania',
        'use_en' => 'Quick debugging sessions'
    ],
    [
        'name' => 'Replit Multiplayer',
        'url' => 'https://replit.com',
        'type' => 'Cloud',
        'live' => '✅ Multi-cursor',
        'live_en' => '✅ Multi-cursor',
        'docker' => '✅ Terminal (nie root)',
        'docker_en' => '✅ Terminal (non-root)',
        'disk' => '✅ W sandboxie',
        'disk_en' => '✅ In sandbox',
        'install' => 'Brak',
        'install_en' => 'None',
        'price' => 'Free/$20/mies',
        'use' => 'Python/AI prototyping',
        'use_en' => 'Python/AI prototyping'
    ],
    [
        'name' => 'CodeSandbox',
        'url' => 'https://codesandbox.io',
        'type' => 'Web',
        'live' => '✅ Live collab',
        'live_en' => '✅ Live collab',
        'docker' => '✅ Docker previews',
        'docker_en' => '✅ Docker previews',
        'disk' => '✅ W projekcie',
        'disk_en' => '✅ In project',
        'install' => 'Brak',
        'install_en' => 'None',
        'price' => 'Free/$19/mies',
        'use' => 'Front-end, Node.js',
        'use_en' => 'Front-end, Node.js'
    ],
    [
        'name' => 'StackBlitz',
        'url' => 'https://stackblitz.com',
        'type' => 'Web',
        'live' => '✅ Mysz/klawiatura sync',
        'live_en' => '✅ Mouse/keyboard sync',
        'docker' => '✅ WebContainers',
        'docker_en' => '✅ WebContainers',
        'disk' => '✅ Pełny Node',
        'disk_en' => '✅ Full Node',
        'install' => 'Brak',
        'install_en' => 'None',
        'price' => 'Free/$20/mies',
        'use' => 'Rust/Go/Node',
        'use_en' => 'Rust/Go/Node'
    ],
    [
        'name' => 'Gitpod',
        'url' => 'https://gitpod.io',
        'type' => 'Cloud',
        'live' => '✅ VS Code share',
        'live_en' => '✅ VS Code share',
        'docker' => '✅ Docker/Git',
        'docker_en' => '✅ Docker/Git',
        'disk' => '✅ Pełny workspace',
        'disk_en' => '✅ Full workspace',
        'install' => 'Brak',
        'install_en' => 'None',
        'price' => 'Free/$50/mies',
        'use' => 'Repo-based development',
        'use_en' => 'Repo-based development'
    ],
    [
        'name' => 'CodePen',
        'url' => 'https://codepen.io',
        'type' => 'Web',
        'live' => '✅ Multi-user',
        'live_en' => '✅ Multi-user',
        'docker' => '❌ Brak',
        'docker_en' => '❌ None',
        'disk' => '❌ Ograniczony',
        'disk_en' => '❌ Limited',
        'install' => 'Brak',
        'install_en' => 'None',
        'price' => 'Free/$10/mies',
        'use' => 'HTML/CSS/JS prototypy',
        'use_en' => 'HTML/CSS/JS prototypes'
    ]
];

foreach ($tools as $t): 
    $isEn = Language::getCurrent() === 'en';
?>
                <tr>
                    <td>
                        <div class="tool-info">
                            <strong><a href="<?= $t['url'] ?>" target="_blank"><?= $t['name'] ?></a></strong>
                        </div>
                    </td>
                    <td><span class="badge"><?= $t['type'] ?></span></td>
                    <td><?= $isEn ? $t['live_en'] : $t['live'] ?></td>
                    <td><?= $isEn ? $t['docker_en'] : $t['docker'] ?></td>
                    <td><?= $isEn ? $t['disk_en'] : $t['disk'] ?></td>
                    <td><?= $isEn ? $t['install_en'] : $t['install'] ?></td>
                    <td><?= $t['price'] ?></td>
                    <td><?= $isEn ? $t['use_en'] : $t['use'] ?></td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Feature Comparison -->
    <div class="feature-comparison fade-in">
        <h3><?= __('comp_detail_title') ?></h3>
        <div class="comparison-grid">
            <div class="comparison-card">
                <h4>🎯 <?= Language::getCurrent() === 'pl' ? 'Mentoring i Edukacja' : 'Mentoring & Education' ?></h4>
                <div class="feature-list">
                    <div class="feature-item">
                        <strong>CodeReview.pl:</strong>
                        <span class="success">✅ <?= Language::getCurrent() === 'pl' ? 'Specjalistyczna platforma mentoringowa' : 'Specialized mentoring platform' ?></span>
                    </div>
                    <div class="feature-item">
                        <strong>Inne:</strong>
                        <span class="warning">⚠️ <?= Language::getCurrent() === 'pl' ? 'Ogólne narzędzia do współpracy' : 'General collaboration tools' ?></span>
                    </div>
                </div>
            </div>

            <div class="comparison-card">
                <h4>🐳 <?= Language::getCurrent() === 'pl' ? 'Docker i Dostęp Root' : 'Docker & Root Access' ?></h4>
                <div class="feature-list">
                    <div class="feature-item">
                        <strong>CodeReview.pl:</strong>
                        <span class="success">✅ <?= Language::getCurrent() === 'pl' ? 'Pełny Docker + root' : 'Full Docker + root' ?></span>
                    </div>
                    <div class="feature-item">
                        <strong>Inne:</strong>
                        <span class="error">❌ <?= Language::getCurrent() === 'pl' ? 'Ograniczony lub brak dostępu' : 'Limited or no access' ?></span>
                    </div>
                </div>
            </div>

            <div class="comparison-card">
                <h4>💰 <?= Language::getCurrent() === 'pl' ? 'Model Cenowy' : 'Pricing Model' ?></h4>
                <div class="feature-list">
                    <div class="feature-item">
                        <strong>CodeReview.pl:</strong>
                        <span class="success">✅ <?= Language::getCurrent() === 'pl' ? 'Przystępne PLN, rabaty EDU' : 'Affordable PLN, EDU discounts' ?></span>
                    </div>
                    <div class="feature-item">
                        <strong>Inne:</strong>
                        <span class="warning">⚠️ <?= Language::getCurrent() === 'pl' ? 'USD, często droższe' : 'USD, often more expensive' ?></span>
                    </div>
                </div>
            </div>

            <div class="comparison-card">
                <h4>🌐 <?= Language::getCurrent() === 'pl' ? 'Język i Lokalizacja' : 'Language & Localization' ?></h4>
                <div class="feature-list">
                    <div class="feature-item">
                        <strong>CodeReview.pl:</strong>
                        <span class="success">✅ <?= Language::getCurrent() === 'pl' ? 'Polska platforma, PL/EN' : 'Polish platform, PL/EN' ?></span>
                    </div>
                    <div class="feature-item">
                        <strong>Inne:</strong>
                        <span class="warning">⚠️ <?= Language::getCurrent() === 'pl' ? 'Głównie po angielsku' : 'Mainly English' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Use Cases -->
    <div class="use-cases fade-in">
        <h3><?= __('comp_for_whom') ?></h3>
        <div class="use-cases-grid">
            <?php foreach (array_slice(__('usecases'), 0, 4) as $u): ?>
            <div class="use-case-card">
                <div class="use-case-icon"><?= $u[0] ?></div>
                <h4><?= $u[1] ?></h4>
                <p><?= $u[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- CTA -->
    <div class="cta-section fade-in">
        <div class="cta-box">
            <h2><?= __('comp_choose_best') ?></h2>
            <p><?= __('comp_cta_desc') ?></p>
            <div class="cta-actions">
                <a href="<?= SITE_URL ?>" class="btn btn-primary"><?= __('btn_download') ?></a>
                <a href="/cennik" class="btn btn-ghost"><?= __('nav_pricing') ?></a>
            </div>
        </div>
    </div>
</div></section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
