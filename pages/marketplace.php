<?php
$page_title = 'Marketplace korepetycji programistycznych';
$page_desc  = 'Peer-to-peer marketplace mentoringu z escrow, fakturami VAT i Stripe Connect.';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/mentor.php';

$mentors = Mentor::getAll();
?>
<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/">Start</a><span class="sep">/</span><span class="current">Marketplace</span></div>
    <h1>Marketplace<br><span class="gradient-text">korepetycji</span></h1>
    <p>Peer-to-peer mentoring z escrow, fakturami VAT i automatycznymi reklamacjami. Pełny compliance DSA/GPSR/Omnibus.</p>
</div></section>

<section><div class="container">
    <div class="section-header fade-in">
        <div class="section-label">// Mentorzy</div>
        <h2 class="section-title">Znajdź swojego mentora</h2>
        <p class="section-desc">Najlepsi specjaliści gotowi do pomocy na żywo.</p>
    </div>

    <div class="mentors-grid fade-in" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-bottom:64px;">
        <?php foreach ($mentors as $m): ?>
        <div class="mentor-card" style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;transition:all .3s ease;">
            <div style="display:flex;gap:16px;margin-bottom:16px;">
                <img src="<?= $m['avatar_url'] ?>" alt="<?= $m['name'] ?>" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid var(--accent);">
                <div>
                    <h3 style="font-size:1.1rem;font-weight:700;margin:0;"><?= $m['name'] ?></h3>
                    <div style="display:flex;align-items:center;gap:4px;margin-top:4px;">
                        <span style="color:#fbbf24;">★</span>
                        <span style="font-size:.85rem;color:var(--text-dim);"><?= $m['rating'] ?></span>
                    </div>
                </div>
            </div>
            <p style="font-size:.9rem;color:var(--text-dim);margin-bottom:16px;line-height:1.5;min-height:3em;"><?= $m['bio'] ?></p>
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;">
                <?php 
                $specs = is_string($m['specialties'] ?? '') ? explode(',', $m['specialties']) : ($m['specialties'] ?? []);
                foreach ($specs as $s): ?>
                <span class="badge" style="background:var(--bg-elevated);color:var(--accent);padding:4px 10px;border-radius:6px;font-size:.75rem;font-weight:600;"><?= trim($s) ?></span>
                <?php endforeach; ?>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;border-top:1px solid var(--border);">
                <div>
                    <span style="font-size:1.2rem;font-weight:800;color:var(--text);"><?= $m['price_pln'] ?> zł</span>
                    <span style="font-size:.8rem;color:var(--text-dim);">/ h</span>
                </div>
                <a href="#" class="btn btn-primary btn-sm">Rezerwuj</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="section-header fade-in"><div class="section-label">// Flow transakcji</div><h2 class="section-title">Jak działa marketplace?</h2></div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:64px;" class="fade-in">
        <?php foreach([['1','🔍 Szukaj mentora','Filtruj po technologii, stawce, języku. Profile z GitHub OAuth.'],['2','💳 Zarezerwuj sesję','Stripe escrow — pieniądze nie trafiają od razu do mentora.'],['3','💻 Sesja live','Terminal, Docker, VS Code, chat + voice.'],['4','⭐ Oceń i zapłać','Ocena → auto-release po 24h. Faktura VAT.']] as $s): ?>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:28px;position:relative;">
            <div style="position:absolute;top:-12px;left:20px;width:28px;height:28px;background:var(--accent);color:var(--bg-deep);font-weight:800;font-size:.85rem;border-radius:8px;display:flex;align-items:center;justify-content:center;"><?=$s[0]?></div>
            <h3 style="font-size:.95rem;font-weight:700;margin:8px 0 10px;"><?=$s[1]?></h3>
            <p style="color:var(--text-dim);font-size:.85rem;line-height:1.6;"><?=$s[2]?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="how-diagram fade-in" style="padding:36px;">
        <h3 style="font-family:var(--font-sans);font-size:1.1rem;font-weight:700;margin-bottom:20px;text-align:center;">Dlaczego nie prosty model OLX?</h3>
        <table class="data-table"><thead><tr><th>Aspekt</th><th>OLX</th><th>CodeReview.pl</th></tr></thead><tbody>
            <tr><td style="font-weight:600;">Płatności</td><td style="color:var(--warn);">Brak</td><td style="color:var(--accent);">Stripe Connect + escrow</td></tr>
            <tr><td style="font-weight:600;">Faktury</td><td style="color:var(--warn);">Brak</td><td style="color:var(--accent);">VAT 23% auto</td></tr>
            <tr><td style="font-weight:600;">Ochrona kupującego</td><td style="color:var(--warn);">Brak</td><td style="color:var(--accent);">Escrow + reklamacja 24h</td></tr>
            <tr><td style="font-weight:600;">Weryfikacja</td><td style="color:var(--warn);">Telefon</td><td style="color:var(--accent);">GitHub + repos + rating</td></tr>
            <tr><td style="font-weight:600;">Środowisko pracy</td><td style="color:var(--warn);">Brak</td><td style="color:var(--accent);">Terminal + Docker + VS Code</td></tr>
        </tbody></table>
    </div>
</div></section>
<section><div class="container">
    <div class="section-header fade-in"><div class="section-label">// Reklamacje</div><h2 class="section-title">System reklamacji</h2></div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;" class="fade-in">
        <?php foreach([['📩','Zgłoszenie','Uczeń zgłasza w ciągu 24h od sesji.'],['🔍','Weryfikacja','Automatyczna analiza logów i nagrań.'],['💸','Rozwiązanie','Zwrot 100% lub mediacja 50/50 w 24h.'],['🚫','Konsekwencje','Ban mentora po 2 uznanych reklamacjach.']] as $r): ?>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
            <div style="font-size:1.8rem;margin-bottom:12px;"><?=$r[0]?></div>
            <h3 style="font-size:.95rem;font-weight:700;margin-bottom:8px;"><?=$r[1]?></h3>
            <p style="color:var(--text-dim);font-size:.85rem;line-height:1.6;"><?=$r[2]?></p>
        </div>
        <?php endforeach; ?>
    </div>
</div></section>
<section class="cta-section"><div class="container"><div class="cta-box fade-in">
    <h2>Zostań mentorem i <span class="gradient-text">zarabiaj ucząc</span></h2>
    <p>GitHub OAuth. Profil gotowy w 2 minuty.</p>
    <div class="cta-actions"><a href="#" class="btn btn-primary">Zostań mentorem</a><a href="#" class="btn btn-ghost">Znajdź mentora →</a></div>
</div></div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
