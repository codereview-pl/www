<?php
$page_title = 'Porównanie Narzędzi';
$page_desc  = 'Porównanie CodeReview.pl z innymi narzędziami do pair programmingu i mentoringu.';
require_once __DIR__ . '/../includes/header.php';

// Log page access
Logger::info('Comparison page viewed', [
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
]);
?>

<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/">Start</a><span class="sep">/</span><span class="current">Porównanie</span></div>
    <h1>Porównanie<br><span class="gradient-text">Narzędzi</span></h1>
    <p>CodeReview.pl vs inne platformy do pair programmingu i mentoringu programistycznego.</p>
</div></section>

<section><div class="container">
    <div class="section-header fade-in">
        <div class="section-label">// Porównanie</div>
        <h2 class="section-title">CodeReview.pl vs Konkurencja</h2>
        <p class="section-desc">Szczegółowe porównanie funkcji, możliwości i zastosowań.</p>
    </div>

    <!-- Comparison Table -->
    <div class="comparison-table fade-in">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Narzędzie</th>
                    <th>Typ</th>
                    <th>Współpraca Live</th>
                    <th>Terminal/Docker</th>
                    <th>Dostęp do Dysku</th>
                    <th>Instalacja</th>
                    <th>Cena</th>
                    <th>Zastosowanie</th>
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
                    <td>✅ Pełna (ekran + terminal)</td>
                    <td>✅ Docker + root</td>
                    <td>✅ Pełny dostęp</td>
                    <td>Desktop/WebVM</td>
                    <td><strong>29-149 zł/mies</strong></td>
                    <td>Edukacja, bootcampy, onboarding</td>
                </tr>

                <!-- PairCode -->
                <tr>
                    <td>
                        <div class="tool-info">
                            <strong><a href="https://paircode.live/" target="_blank">PairCode</a></strong><br>
                            <small>Przeglądarkowe</small>
                        </div>
                    </td>
                    <td><span class="badge">Web</span></td>
                    <td>✅ Ekran + video/audio</td>
                    <td>❌ Brak terminala</td>
                    <td>❌ Ograniczony</td>
                    <td>Brak</td>
                    <td>Free/Premium</td>
                    <td>Szybkie sesje debugowania</td>
                </tr>

                <!-- Replit Multiplayer -->
                <tr>
                    <td>
                        <div class="tool-info">
                            <strong><a href="https://replit.com" target="_blank">Replit Multiplayer</a></strong><br>
                            <small>Cloud IDE</small>
                        </div>
                    </td>
                    <td><span class="badge">Cloud</span></td>
                    <td>✅ Multi-cursor</td>
                    <td>✅ Terminal (nie root)</td>
                    <td>✅ W sandboxie</td>
                    <td>Brak</td>
                    <td>Free/$20/mies</td>
                    <td>Python/AI prototyping</td>
                </tr>

                <!-- CodeSandbox -->
                <tr>
                    <td>
                        <div class="tool-info">
                            <strong><a href="https://codesandbox.io" target="_blank">CodeSandbox</a></strong><br>
                            <small>Web IDE</small>
                        </div>
                    </td>
                    <td><span class="badge">Web</span></td>
                    <td>✅ Live collab</td>
                    <td>✅ Docker previews</td>
                    <td>✅ W projekcie</td>
                    <td>Brak</td>
                    <td>Free/$19/mies</td>
                    <td>Front-end, Node.js</td>
                </tr>

                <!-- StackBlitz -->
                <tr>
                    <td>
                        <div class="tool-info">
                            <strong><a href="https://stackblitz.com" target="_blank">StackBlitz</a></strong><br>
                            <small>WebContainers</small>
                        </div>
                    </td>
                    <td><span class="badge">Web</span></td>
                    <td>✅ Mysz/klawiatura sync</td>
                    <td>✅ WebContainers</td>
                    <td>✅ Pełny Node</td>
                    <td>Brak</td>
                    <td>Free/$20/mies</td>
                    <td>Rust/Go/Node</td>
                </tr>

                <!-- Gitpod -->
                <tr>
                    <td>
                        <div class="tool-info">
                            <strong><a href="https://gitpod.io" target="_blank">Gitpod</a></strong><br>
                            <small>VS Code w chmurze</small>
                        </div>
                    </td>
                    <td><span class="badge">Cloud</span></td>
                    <td>✅ VS Code share</td>
                    <td>✅ Docker/Git</td>
                    <td>✅ Pełny workspace</td>
                    <td>Brak</td>
                    <td>Free/$50/mies</td>
                    <td>Repo-based development</td>
                </tr>

                <!-- CodePen -->
                <tr>
                    <td>
                        <div class="tool-info">
                            <strong><a href="https://codepen.io" target="_blank">CodePen</a></strong><br>
                            <small>Front-end playground</small>
                        </div>
                    </td>
                    <td><span class="badge">Web</span></td>
                    <td>✅ Multi-user</td>
                    <td>❌ Brak</td>
                    <td>❌ Ograniczony</td>
                    <td>Brak</td>
                    <td>Free/$10/mies</td>
                    <td>HTML/CSS/JS prototypy</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Feature Comparison -->
    <div class="feature-comparison fade-in">
        <h3>Szczegółowe Porównanie Funkcji</h3>
        <div class="comparison-grid">
            <div class="comparison-card">
                <h4>🎯 Mentoring i Edukacja</h4>
                <div class="feature-list">
                    <div class="feature-item">
                        <strong>CodeReview.pl:</strong>
                        <span class="success">✅ Specjalistyczna platforma mentoringowa</span>
                    </div>
                    <div class="feature-item">
                        <strong>Inne:</strong>
                        <span class="warning">⚠️ Ogólne narzędzia do współpracy</span>
                    </div>
                </div>
            </div>

            <div class="comparison-card">
                <h4>🐳 Docker i Root Access</h4>
                <div class="feature-list">
                    <div class="feature-item">
                        <strong>CodeReview.pl:</strong>
                        <span class="success">✅ Pełny Docker + root</span>
                    </div>
                    <div class="feature-item">
                        <strong>Inne:</strong>
                        <span class="error">❌ Ograniczony lub brak dostępu</span>
                    </div>
                </div>
            </div>

            <div class="comparison-card">
                <h4>💰 Model Cenowy</h4>
                <div class="feature-list">
                    <div class="feature-item">
                        <strong>CodeReview.pl:</strong>
                        <span class="success">✅ Przystępne PLN, rabaty EDU</span>
                    </div>
                    <div class="feature-item">
                        <strong>Inne:</strong>
                        <span class="warning">⚠️ USD, często droższe</span>
                    </div>
                </div>
            </div>

            <div class="comparison-card">
                <h4>🌐 Język i Lokalizacja</h4>
                <div class="feature-list">
                    <div class="feature-item">
                        <strong>CodeReview.pl:</strong>
                        <span class="success">✅ Polska platforma, PL/EN</span>
                    </div>
                    <div class="feature-item">
                        <strong>Inne:</strong>
                        <span class="warning">⚠️ Angielskie głównie</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Use Cases -->
    <div class="use-cases fade-in">
        <h3>Dla Kogo Jest CodeReview.pl?</h3>
        <div class="use-cases-grid">
            <div class="use-case-card">
                <div class="use-case-icon">🎓</div>
                <h4>Bootcampy i Kursy</h4>
                <p>Monitorowanie kilkunastu studentów, przełączanie między terminalami, system zadań.</p>
            </div>
            <div class="use-case-card">
                <div class="use-case-icon">🏢</div>
                <h4>Onboarding Techniczny</h4>
                <p>Gotowe Docker workspace'y dla nowych pracowników, szybkie wdrożenie.</p>
            </div>
            <div class="use-case-card">
                <div class="use-case-icon">🎤</div>
                <h4>Rekrutacja Zdalna</h4>
                <p>Live coding, podgląd toku myślenia kandydata, real-time feedback.</p>
            </div>
            <div class="use-case-card">
                <div class="use-case-icon">💻</div>
                <h4>Pair Programming</h4>
                <p>Wspólne kodowanie bez udostępniania pulpitu, pełna kontrola.</p>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="cta-section fade-in">
        <div class="cta-box">
            <h2>Wybierz <span class="gradient-text">Najlepsze Narzędzie</span></h2>
            <p>CodeReview.pl to specjalistyczna platforma stworzona dla polskiego rynku edukacyjnego.</p>
            <div class="cta-actions">
                <a href="<?= SITE_URL ?>" class="btn btn-primary">Wypróbuj CodeReview.pl</a>
                <a href="/cennik" class="btn btn-ghost">Zobacz Cennik</a>
            </div>
        </div>
    </div>
</div></section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
