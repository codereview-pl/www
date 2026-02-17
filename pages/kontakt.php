<?php
$page_title = 'Kontakt';
$page_desc  = 'Skontaktuj się — zamów stanowiska Premium Hub, zgłoś problem, umów demo.';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/">Start</a><span class="sep">/</span><span class="current">Kontakt</span></div>
    <h1>Porozmawiajmy<br><span class="gradient-text">o Twoich potrzebach</span></h1>
    <p>Zamów stanowiska Premium Hub, zgłoś problem lub umów demo.</p>
</div></section>
<section><div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;">
        <div class="fade-in">
            <h2 style="font-size:1.3rem;font-weight:700;margin-bottom:28px;">Wyślij wiadomość</h2>
            <form action="#" method="POST">
                <div class="form-group"><label for="name">Imię i nazwisko</label><input type="text" id="name" name="name" class="form-input" required placeholder="Jan Kowalski"></div>
                <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" class="form-input" required placeholder="jan@firma.pl"></div>
                <div class="form-group"><label for="company">Firma / Organizacja</label><input type="text" id="company" name="company" class="form-input" placeholder="(opcjonalne)"></div>
                <div class="form-group"><label for="subject">Temat</label>
                    <select id="subject" name="subject" class="form-select">
                        <option value="hub">Zamówienie stanowisk Premium Hub</option>
                        <option value="edu">Rabat EDU (−50%)</option>
                        <option value="demo">Umów demo</option>
                        <option value="mentor">Zostań mentorem</option>
                        <option value="support">Wsparcie techniczne</option>
                        <option value="other">Inne</option>
                    </select>
                </div>
                <div class="form-group"><label for="seats">Liczba stanowisk</label><input type="number" id="seats" name="seats" class="form-input" min="1" placeholder="np. 10"></div>
                <div class="form-group"><label for="message">Wiadomość</label><textarea id="message" name="message" class="form-textarea" required placeholder="Opisz potrzeby..."></textarea></div>
                <button type="submit" class="btn btn-primary btn-lg">Wyślij wiadomość</button>
            </form>
        </div>
        <div class="fade-in">
            <h2 style="font-size:1.3rem;font-weight:700;margin-bottom:28px;">Dane kontaktowe</h2>
            <?php foreach([
                ['📧','Email','<a href="mailto:'.SITE_EMAIL.'" style="color:var(--accent);text-decoration:none;font-family:var(--font-mono);font-size:.9rem;">'.SITE_EMAIL.'</a>'],
                ['💻','GitHub','<a href="'.SITE_GITHUB.'" style="color:var(--accent);text-decoration:none;font-family:var(--font-mono);font-size:.9rem;">wronai/codereview</a>'],
                ['🌐','Domeny','<span style="font-family:var(--font-mono);font-size:.85rem;color:var(--text-dim);">codereview.pl · coboarding.com<br>hub.codereview.pl · webvm.codereview.pl</span>'],
                ['🏢','Autor','<span style="color:var(--text-dim);font-size:.9rem;">'.SITE_AUTHOR.' · Polska</span>'],
            ] as $c): ?>
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;margin-bottom:16px;">
                <div style="font-size:1.4rem;margin-bottom:10px;"><?=$c[0]?></div>
                <div style="font-weight:600;margin-bottom:4px;"><?=$c[1]?></div>
                <?=$c[2]?>
            </div>
            <?php endforeach; ?>
            <div style="background:var(--accent-glow);border:1px solid var(--border-accent);border-radius:var(--radius);padding:24px;margin-top:16px;">
                <h3 style="font-size:.95rem;font-weight:700;margin-bottom:12px;color:var(--accent);">💡 Kalkulacja Premium Hub</h3>
                <div style="font-family:var(--font-mono);font-size:.82rem;color:var(--text-dim);line-height:2;">
                    1 stanowisko: <strong style="color:var(--text);"><?=PRICE_HUB_SEAT?> zł/mies</strong><br>
                    5 stanowisk: <strong style="color:var(--text);"><?=PRICE_HUB_SEAT*5?> zł/mies</strong><br>
                    10 stanowisk: <strong style="color:var(--text);"><?=PRICE_HUB_SEAT*10?> zł/mies</strong><br>
                    <span style="color:var(--accent);">🎓 EDU −50%:</span> 10 st. = <strong style="color:var(--accent);"><?=PRICE_HUB_SEAT*5?> zł/mies</strong>
                </div>
            </div>
        </div>
    </div>
</div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
