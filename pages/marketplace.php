<?php
$page_title = 'Marketplace korepetycji programistycznych';
$page_desc  = 'Peer-to-peer marketplace mentoringu z escrow, fakturami VAT i Stripe Connect.';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/mentor.php';

$search = $_GET['search'] ?? '';
$tech = $_GET['tech'] ?? '';
$mentors = Mentor::getAll();

// Simple filtering logic (in production this would be in the model/DB)
if (!empty($search) || !empty($tech)) {
    $mentors = array_filter($mentors, function($m) use ($search, $tech) {
        $matchSearch = empty($search) || 
                       stripos($m['name'], $search) !== false || 
                       stripos($m['bio'], $search) !== false;
        
        $specs = is_string($m['specialties'] ?? '') ? explode(',', $m['specialties']) : ($m['specialties'] ?? []);
        $matchTech = empty($tech) || in_array($tech, array_map('trim', $specs));
        
        return $matchSearch && $matchTech;
    });
}

// Get unique tech tags for filter
$all_techs = [];
foreach (Mentor::getAll() as $m) {
    $specs = is_string($m['specialties'] ?? '') ? explode(',', $m['specialties']) : ($m['specialties'] ?? []);
    foreach ($specs as $s) $all_techs[] = trim($s);
}
$unique_techs = array_unique($all_techs);
sort($unique_techs);
?>
<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/"><?= __('nav_home') ?></a><span class="sep">/</span><span class="current"><?= __('nav_marketplace') ?></span></div>
    <h1><?= __('market_title') ?></h1>
    <p><?= __('market_desc') ?></p>
</div></section>

<section><div class="container">
    <!-- Filters -->
    <div class="filter-bar fade-in" style="background:var(--bg-card);padding:24px;border-radius:var(--radius);border:1px solid var(--border);margin-bottom:48px;">
        <form action="/marketplace" method="GET" style="display:grid;grid-template-columns:1fr auto auto;gap:16px;align-items:end;">
            <div class="form-group" style="margin-bottom:0;">
                <label for="search" style="font-size:.8rem;margin-bottom:8px;"><?= __('market_search_placeholder') ?></label>
                <input type="text" id="search" name="search" class="form-input" placeholder="<?= __('market_search_placeholder') ?>" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label for="tech" style="font-size:.8rem;margin-bottom:8px;"><?= __('nav_api') ?></label>
                <select id="tech" name="tech" class="form-select" style="min-width:180px;">
                    <option value=""><?= __('market_tech_all') ?></option>
                    <?php foreach ($unique_techs as $t): ?>
                    <option value="<?= htmlspecialchars($t) ?>" <?= $tech === $t ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height:46px;"><?= __('market_filter_btn') ?></button>
        </form>
    </div>

    <div class="section-header fade-in">
        <div class="section-label"><?= __('market_mentor_label') ?></div>
        <h2 class="section-title"><?= __('market_mentor_title') ?></h2>
        <p class="section-desc"><?= __('market_mentor_desc') ?></p>
    </div>

    <div class="mentors-grid fade-in" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-bottom:64px;">
        <?php if (empty($mentors)): ?>
            <div style="grid-column:1/-1;text-align:center;padding:48px;background:var(--bg-card);border-radius:var(--radius);border:1px dashed var(--border);">
                <p style="color:var(--text-dim);"><?= __('market_no_mentors') ?></p>
                <a href="/marketplace" class="btn btn-ghost" style="margin-top:16px;"><?= __('market_clear_filters') ?></a>
            </div>
        <?php else: ?>
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
                    <a href="#" class="btn btn-primary btn-sm"><?= __('market_reserve_btn') ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="section-header fade-in"><div class="section-label"><?= __('market_flow_label') ?></div><h2 class="section-title"><?= __('market_flow_title') ?></h2></div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:64px;" class="fade-in">
        <?php foreach(__('market_flow_steps') as $s): ?>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:28px;position:relative;">
            <div style="position:absolute;top:-12px;left:20px;width:28px;height:28px;background:var(--accent);color:var(--bg-deep);font-weight:800;font-size:.85rem;border-radius:8px;display:flex;align-items:center;justify-content:center;"><?=$s[0]?></div>
            <h3 style="font-size:.95rem;font-weight:700;margin:8px 0 10px;"><?=$s[1]?></h3>
            <p style="color:var(--text-dim);font-size:.85rem;line-height:1.6;"><?=$s[2]?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="how-diagram fade-in" style="padding:36px;">
        <h3 style="font-family:var(--font-sans);font-size:1.1rem;font-weight:700;margin-bottom:20px;text-align:center;"><?= __('market_why_title') ?></h3>
        <table class="data-table"><thead><tr><th><?= __('market_aspect') ?></th><th>OLX</th><th>CodeReview.pl</th></tr></thead><tbody>
            <tr><td style="font-weight:600;">Płatności</td><td style="color:var(--warn);">Brak</td><td style="color:var(--accent);">Stripe Connect + escrow</td></tr>
            <tr><td style="font-weight:600;">Faktury</td><td style="color:var(--warn);">Brak</td><td style="color:var(--accent);">VAT 23% auto</td></tr>
            <tr><td style="font-weight:600;">Ochrona kupującego</td><td style="color:var(--warn);">Brak</td><td style="color:var(--accent);">Escrow + reklamacja 24h</td></tr>
            <tr><td style="font-weight:600;">Weryfikacja</td><td style="color:var(--warn);">Telefon</td><td style="color:var(--accent);">GitHub + repos + rating</td></tr>
            <tr><td style="font-weight:600;">Środowisko pracy</td><td style="color:var(--warn);">Brak</td><td style="color:var(--accent);">Terminal + Docker + VS Code</td></tr>
        </tbody></table>
    </div>
</div></section>
<section><div class="container">
    <div class="section-header fade-in"><div class="section-label"><?= __('market_complaints_label') ?></div><h2 class="section-title"><?= __('market_complaints_title') ?></h2></div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;" class="fade-in">
        <?php foreach(__('market_complaints_steps') as $r): ?>
        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
            <div style="font-size:1.8rem;margin-bottom:12px;"><?=$r[0]?></div>
            <h3 style="font-size:.95rem;font-weight:700;margin-bottom:8px;"><?=$r[1]?></h3>
            <p style="color:var(--text-dim);font-size:.85rem;line-height:1.6;"><?=$r[2]?></p>
        </div>
        <?php endforeach; ?>
    </div>
</div></section>
<section class="cta-section"><div class="container"><div class="cta-box fade-in">
    <h2><?= __('market_be_mentor_title') ?></h2>
    <p><?= __('market_be_mentor_desc') ?></p>
    <div class="cta-actions"><a href="#" class="btn btn-primary"><?= __('market_be_mentor_btn') ?></a><a href="#" class="btn btn-ghost"><?= __('market_find_mentor_btn') ?></a></div>
</div></div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
