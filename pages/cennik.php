<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$page_title = __('nav_pricing');
$page_desc  = __('price_desc');
require_once __DIR__ . '/../includes/header.php';
$seat = PRICE_HUB_SEAT;
$edu  = $seat * (1 - PRICE_EDU_DISCOUNT / 100);
?>
<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/"><?= __('nav_home') ?></a><span class="sep">/</span><span class="current"><?= __('nav_pricing') ?></span></div>
    <h1><?= __('price_title') ?></h1>
    <p><?= __('price_desc') ?></p>
</div></section>
<section><div class="container">
    <div class="section-header fade-in" style="text-align:center;"><div class="section-label">// <?= __('nav_pricing') ?></div><h2 class="section-title"><?= Language::getCurrent() === 'pl' ? 'Wybierz plan' : 'Choose your plan' ?></h2></div>
    <div class="pricing-grid">
        <div class="price-card fade-in">
            <h3><?= __('price_for_students') ?></h3><div class="role"><?= Language::getCurrent() === 'pl' ? 'Korepetycje z juniorem' : 'Tutoring with a junior' ?></div>
            <div class="price-amount"><?=PRICE_STUDENT?> <span>zł/h</span></div>
            <div class="price-detail"><?= Language::getCurrent() === 'pl' ? 'Mentor: 85%' : 'Mentor: 85%' ?> (<?=round(PRICE_STUDENT*.85)?> zł)</div>
            <ul class="price-features">
                <li><?= Language::getCurrent() === 'pl' ? 'Sesja live z terminalem' : 'Live session with terminal' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Docker workspace' : 'Docker workspace' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Chat + voice + AI' : 'Chat + voice + AI' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Faktura VAT 23%' : 'VAT 23% invoice' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Escrow + reklamacja 24h' : 'Escrow + 24h complaint' ?></li>
            </ul>
            <a href="/marketplace" class="btn btn-ghost" style="width:100%;justify-content:center;"><?= __('market_find_mentor_btn') ?></a>
        </div>
        <div class="price-card featured fade-in">
            <h3><?= __('price_for_mentors') ?></h3><div class="role"><?= Language::getCurrent() === 'pl' ? 'Zarabiaj ucząc' : 'Earn by teaching' ?></div>
            <div class="price-amount"><?=PRICE_JUNIOR?> <span>zł/h</span></div>
            <div class="price-detail"><?= Language::getCurrent() === 'pl' ? 'Otrzymujesz 80%' : 'You receive 80%' ?> (<?=round(PRICE_JUNIOR*.80)?> zł)</div>
            <ul class="price-features">
                <li><?= Language::getCurrent() === 'pl' ? 'Profil GitHub OAuth' : 'GitHub OAuth profile' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Nieograniczone sesje' : 'Unlimited sessions' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Rating i recenzje' : 'Rating & reviews' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Auto-payout Stripe' : 'Stripe auto-payout' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Dashboard + certyfikat' : 'Dashboard + certificate' ?></li>
            </ul>
            <a href="#" class="btn btn-primary" style="width:100%;justify-content:center;"><?= __('market_be_mentor_btn') ?></a>
        </div>
        <div class="price-card fade-in">
            <h3><?= __('price_for_companies') ?></h3><div class="role"><?= Language::getCurrent() === 'pl' ? 'Firmy · Bootcampy · Edukacja' : 'Companies · Bootcamps · Education' ?></div>
            <div class="price-amount"><?=$seat?> <span>zł/mies</span></div>
            <div class="price-detail"><?= Language::getCurrent() === 'pl' ? 'za stanowisko studenta · mentor gratis' : 'per student seat · mentor free' ?></div>
            <div style="background:var(--accent-glow);border:1px solid var(--border-accent);border-radius:8px;padding:10px 14px;margin-bottom:20px;font-size:.8rem;text-align:left;">
                <span style="color:var(--accent);font-weight:700;">🎓 −<?=PRICE_EDU_DISCOUNT?>%</span>
                <span style="color:var(--text-dim);"><?= Language::getCurrent() === 'pl' ? ' dla placówek edukacyjnych → ' : ' for educational institutions → ' ?></span>
                <span style="color:var(--accent);font-weight:700;"><?=number_format($edu,2,',','')?> zł/mies</span>
            </div>
            <ul class="price-features">
                <li><?= Language::getCurrent() === 'pl' ? 'Wbudowany LLM (AI asystent)' : 'Built-in LLM (AI assistant)' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? '1 stanowisko = 1 student' : '1 seat = 1 student' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Mentor nie zajmuje licencji' : 'Mentor doesn\'t take a license' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Prywatny Hub + Docker' : 'Private Hub + Docker' ?></li>
                <li><?= Language::getCurrent() === 'pl' ? 'Analytics + API + white-label' : 'Analytics + API + white-label' ?></li>
            </ul>
            <a href="/kontakt" class="btn btn-ghost" style="width:100%;justify-content:center;"><?= Language::getCurrent() === 'pl' ? 'Zamów stanowiska' : 'Order seats' ?></a>
        </div>
    </div>
</div></section>
<section style="background:linear-gradient(180deg,transparent,rgba(108,99,255,.02),transparent);">
    <div class="container">
        <div class="section-header fade-in"><div class="section-label">// <?= Language::getCurrent() === 'pl' ? 'Model stanowisk' : 'Seat model' ?></div><h2 class="section-title"><?= Language::getCurrent() === 'pl' ? 'Jak działa licencjonowanie?' : 'How does licensing work?' ?></h2>
        <p class="section-desc"><?= Language::getCurrent() === 'pl' ? 'Płacisz za równoczesne stanowiska studentów. Mentor nigdy nie zajmuje licencji.' : 'You pay for concurrent student seats. Mentor never occupies a license.' ?></p></div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:48px;" class="fade-in license-scenarios">
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:28px;text-align:center;">
                <div style="font-size:2.4rem;margin-bottom:12px;">1️⃣</div>
                <div style="font-family:var(--font-mono);font-size:.85rem;color:var(--accent);font-weight:700;margin-bottom:8px;">1 <?= Language::getCurrent() === 'pl' ? 'stanowisko' : 'seat' ?></div>
                <div style="font-size:2rem;font-weight:900;"><?=$seat?> <span style="font-size:.9rem;color:var(--text-muted);">zł/mies</span></div>
                <p style="color:var(--text-dim);font-size:.82rem;margin-top:12px;"><?= Language::getCurrent() === 'pl' ? 'Stała rekrutacja / mentoring 1:1.' : 'Permanent recruitment / 1:1 mentoring.' ?></p>
            </div>
            <div style="background:var(--bg-card);border:1px solid var(--border-accent);border-radius:var(--radius);padding:28px;text-align:center;box-shadow:0 0 24px var(--accent-glow);">
                <div style="font-size:2.4rem;margin-bottom:12px;">5️⃣</div>
                <div style="font-family:var(--font-mono);font-size:.85rem;color:var(--accent);font-weight:700;margin-bottom:8px;">5 <?= Language::getCurrent() === 'pl' ? 'stanowisk' : 'seats' ?></div>
                <div style="font-size:2rem;font-weight:900;"><?=$seat*5?> <span style="font-size:.9rem;color:var(--text-muted);">zł/mies</span></div>
                <p style="color:var(--text-dim);font-size:.82rem;margin-top:12px;"><?= Language::getCurrent() === 'pl' ? 'Bootcamp lub zespół rekrutacyjny.' : 'Bootcamp or recruitment team.' ?></p>
            </div>
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:28px;text-align:center;">
                <div style="font-size:2.4rem;margin-bottom:12px;">🎓</div>
                <div style="font-family:var(--font-mono);font-size:.85rem;color:var(--accent);font-weight:700;margin-bottom:8px;">5 <?= Language::getCurrent() === 'pl' ? 'stanowisk EDU' : 'EDU seats' ?></div>
                <div style="font-size:2rem;font-weight:900;"><?=number_format($edu*5,2,',','')?> <span style="font-size:.9rem;color:var(--text-muted);">zł/mies</span></div>
                <p style="color:var(--text-dim);font-size:.82rem;margin-top:12px;"><?= Language::getCurrent() === 'pl' ? 'Szkoły, uczelnie, NGO (−50%).' : 'Schools, universities, NGO (−50%).' ?></p>
            </div>
        </div>
        <div class="fade-in" style="overflow-x:auto;">
            <table class="data-table"><thead><tr><th><?= Language::getCurrent() === 'pl' ? 'Scenariusz' : 'Scenario' ?></th><th><?= Language::getCurrent() === 'pl' ? 'Stanowiska' : 'Seats' ?></th><th><?= Language::getCurrent() === 'pl' ? 'Koszt' : 'Cost' ?></th><th><?= Language::getCurrent() === 'pl' ? 'Uwagi' : 'Notes' ?></th></tr></thead><tbody>
            <?php 
            $scenarios = Language::getCurrent() === 'pl' ? [
                ['Stała rekrutacja 1:1',1,false,'Mentor + 1 kandydat'],
                ['Rekrutacja równoległa',3,false,'3 sesje jednocześnie'],
                ['Bootcamp (10 osób)',10,false,'Mentor widzi 10 terminali'],
                ['Szkoła programowania 🎓',20,true,'Rabat EDU −50%'],
                ['Uczelnia (40 studentów) 🎓',40,true,'EDU −50% + ded. support'],
            ] : [
                ['Constant 1:1 recruitment',1,false,'Mentor + 1 candidate'],
                ['Parallel recruitment',3,false,'3 simultaneous sessions'],
                ['Bootcamp (10 people)',10,false,'Mentor sees 10 terminals'],
                ['Coding school 🎓',20,true,'EDU discount −50%'],
                ['University (40 students) 🎓',40,true,'EDU −50% + ded. support'],
            ];
            foreach($scenarios as $s):
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
    <div class="section-header fade-in"><div class="section-label">// FAQ</div><h2 class="section-title"><?= Language::getCurrent() === 'pl' ? 'Najczęstsze pytania' : 'Frequently Asked Questions' ?></h2></div>
    <div style="max-width:760px;">
    <?php 
    $faqs = Language::getCurrent() === 'pl' ? [
        ['Czym jest "stanowisko"?','Slot dla 1 studenta — terminal, Docker, AI. Mentor nie zajmuje licencji.'],
        ['Czy mentor płaci?','Nie. Płacisz tylko za stanowiska studentów.'],
        ['Co zawiera LLM?','AI asystent kodowania — podpowiedzi, code review, wyjaśnienia błędów.'],
        ['Jak uzyskać rabat EDU −50%?','Prześlij dokument (NIP szkoły, KRS). Weryfikacja do 24h.'],
        ['Zmiana stanowisk w trakcie miesiąca?','Tak — pro-rata. Dodaj/usuń w dowolnym momencie.'],
        ['Różnica vs open source?','Open source = pełna app. Premium dodaje: LLM, hosting, white-label, analytics.'],
    ] : [
        ['What is a "seat"?','A slot for 1 student — terminal, Docker, AI. Mentor does not occupy a license.'],
        ['Does the mentor pay?','No. You only pay for student seats.'],
        ['What does LLM include?','AI coding assistant — hints, code review, error explanations.'],
        ['How to get 50% EDU discount?','Submit a document (school ID, registration). Verification within 24h.'],
        ['Seat changes during the month?','Yes — pro-rata. Add/remove at any time.'],
        ['Difference vs open source?','Open source = full app. Premium adds: LLM, hosting, white-label, analytics.'],
    ];
    foreach($faqs as $f): ?>
    <details class="faq-item fade-in"><summary><?=$f[0]?></summary><div class="faq-answer"><?=$f[1]?></div></details>
    <?php endforeach; ?>
    </div>
</div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
