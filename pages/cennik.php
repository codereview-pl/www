<?php
$page_title = 'Cennik i licencjonowanie';
$page_desc  = 'Marketplace per-sesja + Premium Hub per-stanowisko z LLM. Rabat 50% EDU.';
require_once __DIR__ . '/../includes/header.php';
$seat = PRICE_HUB_SEAT;
$edu  = $seat * (1 - PRICE_EDU_DISCOUNT / 100);
?>
<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/">Start</a><span class="sep">/</span><span class="current">Cennik</span></div>
    <h1>Cennik i<br><span class="gradient-text">licencjonowanie</span></h1>
    <p>Marketplace per-sesja dla indywidualnych + Premium Hub per-stanowisko z AI dla zespołów i placówek edukacyjnych.</p>
</div></section>
<section><div class="container">
    <div class="section-header fade-in" style="text-align:center;"><div class="section-label">// Cennik</div><h2 class="section-title">Wybierz plan</h2></div>
    <div class="pricing-grid">
        <div class="price-card fade-in">
            <h3>Student</h3><div class="role">Korepetycje z juniorem</div>
            <div class="price-amount"><?=PRICE_STUDENT?> <span>zł/h</span></div>
            <div class="price-detail">Mentor: 85% (<?=round(PRICE_STUDENT*.85)?> zł)</div>
            <ul class="price-features"><li>Sesja live z terminalem</li><li>Docker workspace</li><li>Chat + voice + AI</li><li>Faktura VAT 23%</li><li>Escrow + reklamacja 24h</li></ul>
            <a href="/marketplace" class="btn btn-ghost" style="width:100%;justify-content:center;">Znajdź mentora</a>
        </div>
        <div class="price-card featured fade-in">
            <h3>Junior Mentor</h3><div class="role">Zarabiaj ucząc</div>
            <div class="price-amount"><?=PRICE_JUNIOR?> <span>zł/h</span></div>
            <div class="price-detail">Otrzymujesz 80% (<?=round(PRICE_JUNIOR*.80)?> zł)</div>
            <ul class="price-features"><li>Profil GitHub OAuth</li><li>Nieograniczone sesje</li><li>Rating i recenzje</li><li>Auto-payout Stripe</li><li>Dashboard + certyfikat</li></ul>
            <a href="#" class="btn btn-primary" style="width:100%;justify-content:center;">Zostań mentorem</a>
        </div>
        <div class="price-card fade-in">
            <h3>Premium Hub + AI</h3><div class="role">Firmy · Bootcampy · Edukacja</div>
            <div class="price-amount"><?=$seat?> <span>zł/mies</span></div>
            <div class="price-detail">za stanowisko studenta · mentor gratis</div>
            <div style="background:var(--accent-glow);border:1px solid var(--border-accent);border-radius:8px;padding:10px 14px;margin-bottom:20px;font-size:.8rem;text-align:left;">
                <span style="color:var(--accent);font-weight:700;">🎓 −<?=PRICE_EDU_DISCOUNT?>%</span>
                <span style="color:var(--text-dim);"> dla placówek edukacyjnych → </span>
                <span style="color:var(--accent);font-weight:700;"><?=number_format($edu,2,',','')?> zł/mies</span>
            </div>
            <ul class="price-features"><li>Wbudowany LLM (AI asystent)</li><li>1 stanowisko = 1 student</li><li>Mentor nie zajmuje licencji</li><li>Prywatny Hub + Docker</li><li>Analytics + API + white-label</li></ul>
            <a href="/kontakt" class="btn btn-ghost" style="width:100%;justify-content:center;">Zamów stanowiska</a>
        </div>
    </div>
</div></section>
<section style="background:linear-gradient(180deg,transparent,rgba(108,99,255,.02),transparent);">
    <div class="container">
        <div class="section-header fade-in"><div class="section-label">// Model stanowisk</div><h2 class="section-title">Jak działa licencjonowanie?</h2>
        <p class="section-desc">Płacisz za równoczesne stanowiska studentów. Mentor nigdy nie zajmuje licencji.</p></div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:48px;" class="fade-in license-scenarios">
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:28px;text-align:center;">
                <div style="font-size:2.4rem;margin-bottom:12px;">1️⃣</div>
                <div style="font-family:var(--font-mono);font-size:.85rem;color:var(--accent);font-weight:700;margin-bottom:8px;">1 stanowisko</div>
                <div style="font-size:2rem;font-weight:900;"><?=$seat?> <span style="font-size:.9rem;color:var(--text-muted);">zł/mies</span></div>
                <p style="color:var(--text-dim);font-size:.82rem;margin-top:12px;">Stała rekrutacja / mentoring 1:1.</p>
            </div>
            <div style="background:var(--bg-card);border:1px solid var(--border-accent);border-radius:var(--radius);padding:28px;text-align:center;box-shadow:0 0 24px var(--accent-glow);">
                <div style="font-size:2.4rem;margin-bottom:12px;">5️⃣</div>
                <div style="font-family:var(--font-mono);font-size:.85rem;color:var(--accent);font-weight:700;margin-bottom:8px;">5 stanowisk</div>
                <div style="font-size:2rem;font-weight:900;"><?=$seat*5?> <span style="font-size:.9rem;color:var(--text-muted);">zł/mies</span></div>
                <p style="color:var(--text-dim);font-size:.82rem;margin-top:12px;">Bootcamp lub zespół rekrutacyjny.</p>
            </div>
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:28px;text-align:center;">
                <div style="font-size:2.4rem;margin-bottom:12px;">🎓</div>
                <div style="font-family:var(--font-mono);font-size:.85rem;color:var(--accent);font-weight:700;margin-bottom:8px;">5 stanowisk EDU</div>
                <div style="font-size:2rem;font-weight:900;"><?=number_format($edu*5,2,',','')?> <span style="font-size:.9rem;color:var(--text-muted);">zł/mies</span></div>
                <p style="color:var(--text-dim);font-size:.82rem;margin-top:12px;">Szkoły, uczelnie, NGO (−50%).</p>
            </div>
        </div>
        <div class="fade-in" style="overflow-x:auto;">
            <table class="data-table"><thead><tr><th>Scenariusz</th><th>Stanowiska</th><th>Koszt</th><th>Uwagi</th></tr></thead><tbody>
            <?php foreach([
                ['Stała rekrutacja 1:1',1,false,'Mentor + 1 kandydat'],
                ['Rekrutacja równoległa',3,false,'3 sesje jednocześnie'],
                ['Bootcamp (10 osób)',10,false,'Mentor widzi 10 terminali'],
                ['Szkoła programowania 🎓',20,true,'Rabat EDU −50%'],
                ['Uczelnia (40 studentów) 🎓',40,true,'EDU −50% + ded. support'],
            ] as $s):
                $full=$s[1]*$seat; $final=$s[2]?$full*.5:$full;
            ?>
            <tr>
                <td style="font-weight:600;"><?=$s[0]?></td>
                <td style="font-family:var(--font-mono);color:var(--accent);"><?=$s[1]?></td>
                <td><?php if($s[2]):?><span style="text-decoration:line-through;color:var(--text-muted);"><?=$full?> zł</span> <span style="color:var(--accent);font-weight:700;"><?=(int)$final?> zł/mies</span><?php else:?><?=(int)$final?> zł/mies<?php endif;?></td>
                <td style="color:var(--text-dim);"><?=$s[3]?></td>
            </tr>
            <?php endforeach; ?></tbody></table>
        </div>
    </div>
</section>
<section><div class="container">
    <div class="section-header fade-in"><div class="section-label">// FAQ</div><h2 class="section-title">Najczęstsze pytania</h2></div>
    <div style="max-width:760px;">
    <?php foreach([
        ['Czym jest "stanowisko"?','Slot dla 1 studenta — terminal, Docker, AI. Mentor nie zajmuje licencji.'],
        ['Czy mentor płaci?','Nie. Płacisz tylko za stanowiska studentów.'],
        ['Co zawiera LLM?','AI asystent kodowania — podpowiedzi, code review, wyjaśnienia błędów.'],
        ['Jak uzyskać rabat EDU −50%?','Prześlij dokument (NIP szkoły, KRS). Weryfikacja do 24h.'],
        ['Zmiana stanowisk w trakcie miesiąca?','Tak — pro-rata. Dodaj/usuń w dowolnym momencie.'],
        ['Różnica vs open source?','Open source = pełna app. Premium dodaje: LLM, hosting, white-label, analytics.'],
    ] as $f): ?>
    <details class="faq-item fade-in"><summary><?=$f[0]?></summary><div class="faq-answer"><?=$f[1]?></div></details>
    <?php endforeach; ?>
    </div>
</div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
