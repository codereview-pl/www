<?php
$page_title = 'Dokumentacja';
$page_desc  = 'Kompletna dokumentacja CodeReview.pl — instalacja, architektura, deployment.';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/">Start</a><span class="sep">/</span><span class="current">Dokumentacja</span></div>
    <h1>Dokumentacja<br><span class="gradient-text">CodeReview.pl</span></h1>
    <p>Quick Start, architektura, deployment produkcyjny.</p>
</div></section>
<section><div class="container">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:80px;" class="fade-in">
        <?php foreach([['🚀','Quick Start','#quickstart','5 minut'],['🏗️','Architektura','#arch','Jak działa'],['📦','Deployment','#deploy','Produkcja'],['🔧','Makefile','#make','Komendy']] as $d): ?>
        <a href="<?=$d[2]?>" style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;text-decoration:none;transition:all .3s;" onmouseover="this.style.borderColor='var(--border-accent)'" onmouseout="this.style.borderColor='var(--border)'">
            <div style="font-size:1.8rem;margin-bottom:10px;"><?=$d[0]?></div>
            <div style="font-weight:700;margin-bottom:4px;"><?=$d[1]?></div>
            <div style="color:var(--text-dim);font-size:.82rem;"><?=$d[3]?></div>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="prose">
        <h2 id="quickstart">🚀 Quick Start</h2>
        <h3>Opcja 1: Aplikacja desktopowa</h3>
        <p>Wejdź na <a href="<?=SITE_URL?>" style="color:var(--accent);">codereview.pl</a> i pobierz (Windows/macOS/Linux).</p>
        <h3>Opcja 2: Z kodu</h3>
<pre><code>git clone <?=SITE_GITHUB?>.git
cd codereview
make install    # Zainstaluj wszystko
make hub2       # Hub + 2 aplikacje (mentor + student)</code></pre>
        <h3>Opcja 3: W przeglądarce</h3>
        <p><a href="<?=SITE_WEBVM?>" style="color:var(--accent);">webvm.codereview.pl</a> — terminal Docker bez instalacji.</p>
        <h3>Wymagania</h3>
        <ul><li><strong>Node.js</strong> 18+</li><li><strong>Docker</strong> (opcjonalnie)</li><li><strong>Git</strong></li></ul>

        <h2 id="arch">🏗️ Architektura</h2>
<pre><code>┌──────────────┐                         ┌──────────────┐
│   MENTOR     │      WebSocket          │   STUDENT    │
│  (desktop)   │◄───────────────────────►│  (desktop)   │
│              │   ┌────────────────┐    │              │
│ - stream     │◄─►│      HUB       │◄──►│ - streaming  │
│ - terminale  │   │hub.codereview. │    │ - terminale  │
│ - chat+voice │   │    pl :3777    │    │ - chat       │
│ - docker GUI │   └────────────────┘    │ - docker GUI │
└──────────────┘                         └──────────────┘</code></pre>
        <h3>Struktura projektu</h3>
<pre><code>codereview/
├── src/                  # Electron app
│   ├── main/             # Main process
│   ├── renderer/         # UI
│   └── server/           # Signaling (dev)
├── hub/                  # Hub Server Node.js
├── hub.codereview.pl/    # Hub PHP (1 plik!)
├── www/                  # Download page
├── webvm/                # WebVM
├── docker/               # Dockerfiles
├── data/                 # SQLite (auto)
├── Makefile
└── docker-compose.yml</code></pre>
        <h3>Dane</h3>
        <p>SQLite dwuwarstwowo: <code>data/codereview.db</code> (wspólna) + <code>data/users/{user}/codereview.db</code> (per-user).</p>

        <h2 id="deploy">📦 Deployment</h2>
        <h3>Hub jako systemd</h3>
<pre><code>sudo cp deploy/codereview-hub.service /etc/systemd/system/
sudo systemctl enable --now codereview-hub</code></pre>
        <h3>Budowanie desktop</h3>
<pre><code>npm run package:linux   # AppImage + .deb
npm run package:win     # NSIS installer
npm run package:mac     # DMG
npm run package:all     # Wszystkie</code></pre>

        <h2 id="make">🔧 Makefile</h2>
<pre><code>make help          make install       make hub2
make dev           make stop          make hub
make webvm         make app           make test
make package-all   make www-release   make clean</code></pre>
    </div>
</div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
