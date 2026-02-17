<?php
$page_title = 'Polityka prywatności';
$page_desc  = 'RODO — przetwarzanie danych osobowych na CodeReview.pl / coboarding.com.';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/">Start</a><span class="sep">/</span><span class="current">Prywatność</span></div>
    <h1>Polityka<br><span class="gradient-text">prywatności</span></h1>
    <p>RODO (Rozporządzenie 2016/679).</p>
</div></section>
<section><div class="container-narrow"><div class="prose">
    <p><strong>Ostatnia aktualizacja:</strong> <?=date('d.m.Y')?></p>
    <h2>1. Administrator</h2>
    <p><?=SITE_AUTHOR?>, serwis CodeReview.pl / coboarding.com. Kontakt: <a href="mailto:<?=SITE_EMAIL?>" style="color:var(--accent);"><?=SITE_EMAIL?></a>.</p>
    <h2>2. Zakres danych</h2>
    <ul>
        <li><strong>Identyfikacyjne:</strong> imię, nazwisko, GitHub username, email, avatar</li>
        <li><strong>Sesji:</strong> logi mentoringu, czas trwania, Room ID</li>
        <li><strong>Płatności:</strong> via Stripe — platforma nie przechowuje kart</li>
        <li><strong>Techniczne:</strong> IP, przeglądarka, logi dostępu</li>
    </ul>
    <h2>3. Cele przetwarzania</h2>
    <ul>
        <li><strong>Realizacja usługi</strong> (art. 6.1.b) — umowa marketplace</li>
        <li><strong>Rozliczenia</strong> (art. 6.1.c) — obowiązek prawny</li>
        <li><strong>Bezpieczeństwo</strong> (art. 6.1.f) — uzasadniony interes</li>
        <li><strong>Marketing</strong> (art. 6.1.a) — tylko ze zgodą</li>
    </ul>
    <h2>4. Odbiorcy</h2>
    <ul>
        <li><strong>Stripe Inc.</strong> — płatności</li>
        <li><strong>GitHub Inc.</strong> — OAuth</li>
        <li><strong>Hosting EU</strong> — serwery</li>
    </ul>
    <h2>5. Okres przechowywania</h2>
    <ul><li>Konto: do usunięcia + 30 dni</li><li>Sesje: 12 mies.</li><li>Rozliczenia: 5 lat</li><li>Reklamacje: 3 lata</li></ul>
    <h2>6. Twoje prawa</h2>
    <p>Dostęp (art. 15), sprostowanie (16), usunięcie (17), ograniczenie (18), przenoszenie (20), sprzeciw (21), cofnięcie zgody (7.3). Kontakt: <a href="mailto:<?=SITE_EMAIL?>" style="color:var(--accent);"><?=SITE_EMAIL?></a>.</p>
    <h2>7. Cookies</h2>
    <p>Niezbędne (sesja, auth). Analityczne/marketingowe tylko po zgodzie.</p>
    <h2>8. Bezpieczeństwo</h2>
    <p>SQLite encrypted at-rest. SSL/TLS. Kopie EU. Ograniczony dostęp.</p>
    <h2>9. PUODO</h2>
    <p>Prawo skargi do Prezesa UODO, ul. Stawki 2, 00-193 Warszawa.</p>
    <blockquote><p>Kontakt: <a href="mailto:<?=SITE_EMAIL?>" style="color:var(--accent);"><?=SITE_EMAIL?></a></p></blockquote>
</div></div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
