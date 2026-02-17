<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$page_title = __('nav_contact');
$page_desc  = __('contact_desc');
require_once __DIR__ . '/../includes/header.php';

// Handle form submission
$form_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../includes/form_handler.php';
    $handler = new FormHandler();
    $form_result = $handler->handleContactForm();
    
    // Log access
    Logger::access('Contact form submission attempt', [
        'success' => $form_result['success'] ?? false,
        'subject' => $_POST['subject'] ?? 'unknown'
    ]);
}

// Generate CSRF token
$form_token = hash('sha256', session_id() ?? 'no-session' . date('Y-m-d'));
?>
<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/"><?= __('nav_home') ?></a><span class="sep">/</span><span class="current"><?= __('nav_contact') ?></span></div>
    <h1><?= __('contact_title') ?></h1>
    <p><?= __('contact_desc') ?></p>
</div></section>
<section><div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;">
        <div class="fade-in">
            <h2 style="font-size:1.3rem;font-weight:700;margin-bottom:28px;"><?= __('contact_form_title') ?></h2>
            
            <?php if ($form_result): ?>
                <div class="alert <?= $form_result['success'] ? 'alert-success' : 'alert-error' ?>">
                    <?= htmlspecialchars($form_result['success'] ? __('contact_success') : ($form_result['message'] ?? __('contact_error'))) ?>
                </div>
            <?php endif; ?>
            
            <form action="#" method="POST">
                <input type="hidden" name="form_token" value="<?= $form_token ?>">
                
                <div class="form-group">
                    <label for="name"><?= __('contact_name') ?></label>
                    <input type="text" id="name" name="name" class="form-input" required placeholder="Jan Kowalski" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    <?php if (isset($form_result['errors']['name'])): ?>
                        <div class="form-error"><?= htmlspecialchars($form_result['errors']['name']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email"><?= __('contact_email') ?></label>
                    <input type="email" id="email" name="email" class="form-input" required placeholder="jan@firma.pl" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <?php if (isset($form_result['errors']['email'])): ?>
                        <div class="form-error"><?= htmlspecialchars($form_result['errors']['email']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="company"><?= __('contact_company') ?></label>
                    <input type="text" id="company" name="company" class="form-input" placeholder="(opcjonalne)" value="<?= htmlspecialchars($_POST['company'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="subject"><?= __('contact_subject') ?></label>
                    <select id="subject" name="subject" class="form-select">
                        <?php
                        $subjects = Language::getCurrent() === 'pl' ? [
                            'hub' => 'Zamówienie stanowisk Premium Hub',
                            'edu' => 'Rabat EDU (−50%)',
                            'demo' => 'Umów demo',
                            'mentor' => 'Zostań mentorem',
                            'support' => 'Wsparcie techniczne',
                            'other' => 'Inne'
                        ] : [
                            'hub' => 'Order Premium Hub seats',
                            'edu' => 'EDU Discount (−50%)',
                            'demo' => 'Schedule a demo',
                            'mentor' => 'Become a mentor',
                            'support' => 'Technical support',
                            'other' => 'Other'
                        ];
                        foreach ($subjects as $val => $label): ?>
                            <option value="<?= $val ?>" <?= (($_POST['subject'] ?? '') === $val) ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($form_result['errors']['subject'])): ?>
                        <div class="form-error"><?= htmlspecialchars($form_result['errors']['subject']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="seats"><?= __('contact_seats') ?></label>
                    <input type="number" id="seats" name="seats" class="form-input" min="1" placeholder="np. 10" value="<?= htmlspecialchars($_POST['seats'] ?? '') ?>">
                    <?php if (isset($form_result['errors']['seats'])): ?>
                        <div class="form-error"><?= htmlspecialchars($form_result['errors']['seats']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="message"><?= __('contact_message') ?></label>
                    <textarea id="message" name="message" class="form-textarea" required placeholder="Opisz potrzeby..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    <?php if (isset($form_result['errors']['message'])): ?>
                        <div class="form-error"><?= htmlspecialchars($form_result['errors']['message']) ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg"><?= __('contact_send') ?></button>
            </form>
        </div>
        <div class="fade-in">
            <h2 style="font-size:1.3rem;font-weight:700;margin-bottom:28px;"><?= __('contact_details_title') ?></h2>
            <?php foreach([
                ['📧',Language::getCurrent() === 'pl' ? 'Email' : 'Email','<a href="mailto:'.SITE_EMAIL.'" style="color:var(--accent);text-decoration:none;font-family:var(--font-mono);font-size:.9rem;">'.SITE_EMAIL.'</a>'],
                ['💻',Language::getCurrent() === 'pl' ? 'GitHub' : 'GitHub','<a href="'.SITE_GITHUB.'" style="color:var(--accent);text-decoration:none;font-family:var(--font-mono);font-size:.9rem;">wronai/codereview</a>'],
                ['🌐',Language::getCurrent() === 'pl' ? 'Domeny' : 'Domains','<span style="font-family:var(--font-mono);font-size:.85rem;color:var(--text-dim);">codereview.pl · coboarding.com<br>hub.codereview.pl · webvm.codereview.pl</span>'],
                ['🏢',Language::getCurrent() === 'pl' ? 'Autor' : 'Author','<span style="color:var(--text-dim);font-size:.9rem;">'.SITE_AUTHOR.' · Polska</span>'],
            ] as $c): ?>
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;margin-bottom:16px;">
                <div style="font-size:1.4rem;margin-bottom:10px;"><?=$c[0]?></div>
                <div style="font-weight:600;margin-bottom:4px;"><?=$c[1]?></div>
                <?=$c[2]?>
            </div>
            <?php endforeach; ?>
            <div style="background:var(--accent-glow);border:1px solid var(--border-accent);border-radius:var(--radius);padding:24px;margin-top:16px;">
                <h3 style="font-size:.95rem;font-weight:700;margin-bottom:12px;color:var(--accent);"><?= __('contact_calc_title') ?></h3>
                <div style="font-family:var(--font-mono);font-size:.82rem;color:var(--text-dim);line-height:2;">
                    1 <?= Language::getCurrent() === 'pl' ? 'stanowisko' : 'seat' ?>: <strong style="color:var(--text);"><?=PRICE_HUB_SEAT?> zł/mies</strong><br>
                    5 <?= Language::getCurrent() === 'pl' ? 'stanowisk' : 'seats' ?>: <strong style="color:var(--text);"><?=PRICE_HUB_SEAT*5?> zł/mies</strong><br>
                    10 <?= Language::getCurrent() === 'pl' ? 'stanowisk' : 'seats' ?>: <strong style="color:var(--text);"><?=PRICE_HUB_SEAT*10?> zł/mies</strong><br>
                    <span style="color:var(--accent);"><?= Language::getCurrent() === 'pl' ? '🎓 EDU −50%' : '🎓 EDU −50%' ?>:</span> 10 st. = <strong style="color:var(--accent);"><?=PRICE_HUB_SEAT*5?> zł/mies</strong>
                </div>
            </div>
        </div>
    </div>
</div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
