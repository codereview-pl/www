<?php
$page_title = 'Regulamin platformy';
$page_desc  = 'Regulamin marketplace CodeReview.pl / coboarding.com. DSA/GPSR/Omnibus.';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/">Start</a><span class="sep">/</span><span class="current">Regulamin</span></div>
    <h1>Regulamin<br><span class="gradient-text">platformy</span></h1>
    <p>Zgodny z DSA, GPSR i Omnibus.</p>
</div></section>
<section><div class="container-narrow"><div class="prose">
    <p><strong>Ostatnia aktualizacja:</strong> <?=date('d.m.Y')?></p>
    <h2>§1 Definicje</h2>
    <p><strong>Platforma</strong> — serwis CodeReview.pl / coboarding.com prowadzony przez <?=SITE_AUTHOR?>.<br>
    <strong>Mentor</strong> — osoba oferująca usługi mentoringu.<br>
    <strong>Student</strong> — osoba korzystająca z mentoringu.<br>
    <strong>Sesja</strong> — jednorazowa usługa live.<br>
    <strong>Stanowisko</strong> — licencja na 1 równoczesnego studenta (Premium Hub).</p>
    <h2>§2 Postanowienia ogólne</h2>
    <p>Platforma pośredniczy w płatnościach i zapewnia infrastrukturę techniczną. Nie jest stroną umowy o usługi edukacyjne.</p>
    <h2>§3 Rejestracja i identyfikacja (DSA)</h2>
    <p>Rejestracja przez GitHub OAuth. Mentor podaje: imię, nazwisko, telefon, aktywne konto GitHub. Weryfikacja zgodnie z Digital Services Act.</p>
    <h2>§4 Płatności i escrow</h2>
    <p>Płatności via Stripe Connect. Escrow do zakończenia sesji i oceny (min. 24h). Prowizja platformy: 15-20%. Faktury VAT 23% automatycznie via Stripe Billing.</p>
    <h2>§5 Cennik</h2>
    <p>Sesja od <?=PRICE_STUDENT?> zł/h brutto. Premium Hub: <?=PRICE_HUB_SEAT?> zł netto/mies za stanowisko. Rabat EDU: <?=PRICE_EDU_DISCOUNT?>% dla placówek edukacyjnych.</p>
    <h2>§6 Prawo do odstąpienia</h2>
    <p>14 dni od zakupu dla konsumentów, chyba że sesja zrealizowana za wyraźną zgodą. Prawo wygasa z chwilą rozpoczęcia sesji live.</p>
    <h2>§7 Reklamacje</h2>
    <p>Zgłoszenie w 24h. Weryfikacja: logi SQLite, nagrania, metadane. Rozwiązanie: zwrot 100% lub mediacja 50/50. Ban po 2 uznanych reklamacjach.</p>
    <h2>§8 Mechanizm skarg (DSA)</h2>
    <p>Skargi rozpatrywane w 72h. Punkt kontaktowy: <a href="mailto:<?=SITE_EMAIL?>" style="color:var(--accent);"><?=SITE_EMAIL?></a>.</p>
    <h2>§9 Licencja</h2>
    <p>Open source: Apache 2.0. Premium Hub: licencja komercyjna per-stanowisko.</p>
    <h2>§10 Postanowienia końcowe</h2>
    <p>Regulamin wchodzi w życie z dniem publikacji. Zmiany z 14-dniowym wyprzedzeniem. Prawo polskie.</p>
    <blockquote><p>Kontakt: <a href="mailto:<?=SITE_EMAIL?>" style="color:var(--accent);"><?=SITE_EMAIL?></a> · <?=SITE_AUTHOR?></p></blockquote>
</div></div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
