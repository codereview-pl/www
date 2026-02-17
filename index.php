<?php
/**
 * CodeReview.pl — Landing Page
 * Location: httpdocs/index.php (Plesk document root)
 */
$page_title = 'CodeReview.pl — Mentoring Programistów na Żywo';
$page_desc  = 'Platforma do zdalnego mentoringu programistów — sesje live z terminalem, chatem, Docker workspace\'ami. Marketplace korepetycji.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section style="min-height:100vh;display:flex;align-items:center;padding:140px 0 100px;">
    <div style="position:absolute;width:800px;height:800px;border-radius:50%;filter:blur(160px);opacity:.12;pointer-events:none;top:-200px;right:-200px;background:var(--accent);"></div>
    <div style="position:absolute;width:800px;height:800px;border-radius:50%;filter:blur(160px);opacity:.12;pointer-events:none;bottom:-300px;left:-300px;background:var(--secondary);"></div>
    <div class="container" style="position:relative;">
        <div class="hero-wrapper">
            <div class="hero-content">
                <div class="badge badge-accent" style="margin-bottom:28px;animation:fadeInUp .6s ease-out;">
                    <span style="width:8px;height:8px;background:var(--accent);border-radius:50%;animation:pulse 2s infinite;display:inline-block;"></span>
                    Open Source · Apache 2.0
                </div>
            <h1 style="font-size:clamp(2.8rem,6vw,4.5rem);font-weight:900;line-height:1.05;letter-spacing:-.03em;margin-bottom:24px;animation:fadeInUp .6s ease-out .1s both;">
                Mentoring<br>programistów<br>
                <span class="gradient-text">na żywo</span>
            </h1>
            <p style="font-size:1.2rem;color:var(--text-dim);line-height:1.7;max-width:580px;margin-bottom:40px;animation:fadeInUp .6s ease-out .2s both;">
                Sesje live z terminalem, chatem głosowym, Docker workspace'ami i VS Code w przeglądarce. Platforma + marketplace korepetycji programistycznych.
            </p>
            <div style="display:flex;gap:14px;flex-wrap:wrap;margin-bottom:56px;animation:fadeInUp .6s ease-out .3s both;">
                <a href="<?= SITE_URL ?>" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Pobierz aplikację
                </a>
                <a href="<?= SITE_WEBVM ?>" class="btn btn-ghost">Wypróbuj w przeglądarce →</a>
            </div>
            <div style="display:flex;gap:32px;animation:fadeInUp .6s ease-out .4s both;font-family:var(--font-mono);font-size:.85rem;">
                <a href="<?= SITE_GITHUB ?>" style="display:flex;align-items:center;gap:8px;color:var(--text-muted);text-decoration:none;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    GitHub
                </a>
                <a href="<?= SITE_HUB ?>" style="display:flex;align-items:center;gap:8px;color:var(--text-muted);text-decoration:none;">⚡ Hub</a>
                <a href="/marketplace" style="display:flex;align-items:center;gap:8px;color:var(--secondary);text-decoration:none;">🔗 coboarding.com</a>
            </div>
        </div>

        <!-- Terminal preview -->
        <div class="hero-terminal" style="animation:fadeInUp .8s ease-out .5s both;">
            <div class="terminal-window">
                <div class="terminal-bar">
                    <span class="terminal-dot r"></span><span class="terminal-dot y"></span><span class="terminal-dot g"></span>
                    <span class="terminal-title">mentor@codereview ~ session</span>
                </div>
                <div class="terminal-body" style="min-height:280px;">
                    <div><span class="t-prompt">$</span> <span class="t-cmd">git clone <?= SITE_GITHUB ?>.git</span></div>
                    <div><span class="t-output">Cloning into 'codereview'...</span></div>
                    <div><span class="t-prompt">$</span> <span class="t-cmd">cd codereview && make install</span></div>
                    <div><span class="t-success">✓</span> <span class="t-output">Dependencies installed</span></div>
                    <div><span class="t-prompt">$</span> <span class="t-cmd">make hub2</span></div>
                    <div><span class="t-success">✓</span> <span class="t-output">Hub running on</span> <span class="t-link">:3777</span></div>
                    <div><span class="t-success">✓</span> <span class="t-output">Mentor app →</span> <span class="t-link">:3000</span></div>
                    <div><span class="t-success">✓</span> <span class="t-output">Student app →</span> <span class="t-link">:3001</span></div>
                    <div style="margin-top:12px;"><span class="t-comment"># Live terminal streaming at 10 FPS ▌</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section id="features">
    <div class="container">
        <div class="section-header fade-in">
            <div class="section-label">// Funkcje</div>
            <h2 class="section-title">Wszystko do mentoringu</h2>
            <p class="section-desc">Terminal, chat, Docker, VS Code, slajdy — w jednym narzędziu.</p>
        </div>
        <div class="features-grid">
            <?php
            $features = [
                ['📺','Live Streaming','Podgląd okna studenta w real-time z konfigurowalnym FPS (1–10 kl/s).'],
                ['⌨️','Remote Terminal','Wiele zakładek — mentor pisze komendy na terminalu studenta.'],
                ['💬','Chat + Voice + AI','Voice messages, Speech-to-Text i AI sugestie kodowania.'],
                ['🐳','Docker Workspace','Pełny Linux z GUI Ubuntu (noVNC). Izolowane środowiska dev.'],
                ['📝','VS Code w przeglądarce','Instancje edytora VS Code w Docker — pełne IDE.'],
                ['📊','Slajdy','Synchronizacja slajdów (PDF/URL) z uczniami.'],
                ['🎯','System zadań','YAML import, śledzenie postępów, gamifikacja.'],
                ['👥','Multi-student','Lista studentów z miniaturami, multi-select messaging.'],
                ['🌍','Wielojęzyczny','PL, EN, DE, FR, ES, UA. Motywy Dark/Light/Warm.'],
            ];
            foreach ($features as $f): ?>
            <div class="feature-card fade-in">
                <div class="feature-icon"><?= $f[0] ?></div>
                <h3><?= $f[1] ?></h3>
                <p><?= $f[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- USE CASES -->
<section id="usecases" style="background:linear-gradient(180deg,transparent,rgba(108,99,255,.02),transparent);">
    <div class="container">
        <div class="section-header fade-in">
            <div class="section-label">// Zastosowania</div>
            <h2 class="section-title">Dla kogo jest CodeReview?</h2>
        </div>
        <div class="usecases-grid">
            <?php
            $usecases = [
                ['🎓','Bootcampy i kursy','Monitoruj kilkunastu studentów, przełączaj terminale.'],
                ['🏢','Onboarding techniczny','Gotowe Docker workspace\'y dla nowych pracowników.'],
                ['🎤','Rekrutacja zdalna','Live coding — podgląd toku myślenia kandydata.'],
                ['💻','Pair Programming','Wspólne kodowanie bez udostępniania pulpitu.'],
                ['🔄','Reverse Mentoring','Juniorzy uczą seniorów Docker/K8s live.'],
                ['🚀','Startup Pitch','Slajdy + live coding prototypu z feedbackiem.'],
                ['🏆','Hackathon','Multi-room Hub, shared VS Code sync.'],
                ['🎨','Non-Tech','GUI tools + voice chat — UX/data analysis.'],
            ];
            foreach ($usecases as $u): ?>
            <div class="usecase-card fade-in">
                <div class="usecase-emoji"><?= $u[0] ?></div>
                <div><h3><?= $u[1] ?></h3><p><?= $u[2] ?></p></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section id="how-it-works">
    <div class="container">
        <div class="section-header fade-in">
            <div class="section-label">// Jak to działa</div>
            <h2 class="section-title">3 kroki do rozpoczęcia</h2>
            <p class="section-desc">Proste wdrożenie mentoringu na żywo w kilka minut.</p>
        </div>
        
        <div class="how-steps fade-in">
            <!-- Step 1 -->
            <div class="how-step">
                <div class="how-step-content">
                    <div class="how-step-number">1</div>
                    <h3>Wybierz typ sesji</h3>
                    <p>Mentor → Student, Pair Programming lub grupowy bootcamp. Skonfiguruj role i uprawnienia.</p>
                </div>
                <div class="how-step-image">
                    <img src="/assets/img/1-select-type.png" alt="Wybór typu sesji mentoringowej" loading="lazy">
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="how-step">
                <div class="how-step-content">
                    <div class="how-step-number">2</div>
                    <h3>Nawiąż połączenie</h3>
                    <p>Pobierz aplikację lub użyj WebVM. Udostępnij link studentowi — połączenie szyfrowane.</p>
                </div>
                <div class="how-step-image">
                    <img src="/assets/img/2-setup-connection.png" alt="Konfiguracja połączenia mentoringowego" loading="lazy">
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="how-step">
                <div class="how-step-content">
                    <div class="how-step-number">3</div>
                    <h3>Zacznij mentoring</h3>
                    <p>Terminal, VS Code, Docker, chat głosowy. Wszystko w jednym oknie z podglądem na żywo.</p>
                </div>
                <div class="how-step-image">
                    <img src="/assets/img/3-docker-terminal.png" alt="Terminal i Docker workspace w akcji" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box fade-in">
            <h2>Gotowy na <span class="gradient-text">lepszy mentoring?</span></h2>
            <p>Pobierz aplikację, wypróbuj w przeglądarce lub uruchom z kodu.</p>
            <div class="cta-actions">
                <a href="<?= SITE_URL ?>" class="btn btn-primary">Pobierz CodeReview.pl</a>
                <a href="<?= SITE_WEBVM ?>" class="btn btn-ghost">WebVM w przeglądarce</a>
                <a href="/cennik" class="btn btn-ghost">Cennik →</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
