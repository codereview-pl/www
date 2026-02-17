<?php
$page_title = __('nav_api');
$page_desc  = __('api_desc');
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/rate_limiter.php';

// Rate limiting for API page
if (RateLimiter::isLimited('api_page', 30)) {
    Logger::warning('API page rate limit exceeded', ['ip' => $_SERVER['REMOTE_ADDR']]);
}

// Log API documentation access
Logger::info('API documentation viewed', [
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
]);
?>
<section class="page-hero"><div class="hero-glow"></div><div class="container">
    <div class="breadcrumbs"><a href="/"><?= __('nav_home') ?></a><span class="sep">/</span><span class="current"><?= __('nav_api') ?></span></div>
    <h1><?= __('api_title') ?></h1>
    <p><?= __('api_desc') ?></p>
</div></section>
<section><div class="container">
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:64px;" class="fade-in">
        <?php foreach([['💳','Stripe Connect','Escrow, Express Accounts, faktury VAT.','#stripe'],['🔐','GitHub OAuth','Auto-fill profilu z repos/stars.','#github'],['🔌','Hub WebSocket','Socket.IO signaling, room matching.','#ws']] as $a): ?>
        <a href="<?=$a[3]?>" class="feature-card" style="text-decoration:none;">
            <div class="feature-icon"><?=$a[0]?></div><h3><?=$a[1]?></h3><p><?=$a[2]?></p>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="prose">
        <h2><?= __('api_tech_stack') ?></h2>
<pre><code>Backend:    PHP 8.3 + Laravel 11
Database:   MySQL 8 / PostgreSQL 16
Frontend:   HTMX + Alpine.js + Tailwind
Payments:   Stripe Connect PHP SDK v14
Auth:       Laravel Sanctum / GitHub OAuth
Deployment: Laravel Forge / Ploi (50€/mies.)</code></pre>
        <h2 id="stripe">💳 Stripe Connect</h2>
<pre><code>&lt;?php
use Stripe\Stripe;
Stripe::setApiKey('sk_live_...');

// Express Account dla mentora
$account = \Stripe\Account::create([
    'type' => 'express', 'country' => 'PL',
    'email' => $mentor_email,
    'capabilities' => ['card_payments' => ['requested' => true]]
]);

// PaymentIntent z escrow
$pi = \Stripe\PaymentIntent::create([
    'amount' => 6000, // 60 PLN
    'currency' => 'pln',
    'transfer_data' => ['destination' => $account->id],
    'metadata' => ['session_id' => $room_id]
]);</code></pre>

        <h2 id="github">🔐 GitHub OAuth</h2>
<pre><code>&lt;?php
$provider = new \League\OAuth2\Client\Provider\GitHub([
    'clientId'     => env('GITHUB_ID'),
    'clientSecret' => env('GITHUB_SECRET'),
    'redirectUri'  => 'https://coboarding.com/auth/github/callback',
]);

$token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
$user = $provider->getResourceOwner($token);

Mentor::updateOrCreate(['github_id' => $user->getId()], [
    'name' => $user->getName(),
    'avatar' => $user->toArray()['avatar_url'],
]);</code></pre>

        <h2 id="ws">🔌 Hub WebSocket</h2>
<pre><code>import { io } from 'socket.io-client';

const socket = io('wss://hub.codereview.pl:3777', {
    query: { username: 'mentor-jan', role: 'mentor', room: 'sesja-01' }
});

socket.on('student-joined', (data) => { /* ... */ });
socket.on('terminal-data', (data) => { /* ... */ });
socket.emit('terminal-input', { target: 'student-anna', data: 'npm test\n' });</code></pre>
        <h3><?= __('api_events') ?></h3>
    </div>
    <table class="data-table fade-in" style="margin-top:24px;">
        <thead><tr><th>Event</th><th><?= Language::getCurrent() === 'pl' ? 'Kierunek' : 'Direction' ?></th><th><?= Language::getCurrent() === 'pl' ? 'Opis' : 'Description' ?></th></tr></thead>
        <tbody>
            <tr><td style="font-family:var(--font-mono);color:var(--accent);">join-room</td><td>client → hub</td><td style="color:var(--text-dim);"><?= Language::getCurrent() === 'pl' ? 'Dołącz do sesji' : 'Join session' ?></td></tr>
            <tr><td style="font-family:var(--font-mono);color:var(--accent);">student-joined</td><td>hub → mentor</td><td style="color:var(--text-dim);"><?= Language::getCurrent() === 'pl' ? 'Student dołączył' : 'Student joined' ?></td></tr>
            <tr><td style="font-family:var(--font-mono);color:var(--accent);">terminal-data</td><td>student → mentor</td><td style="color:var(--text-dim);"><?= Language::getCurrent() === 'pl' ? 'Output terminala' : 'Terminal output' ?></td></tr>
            <tr><td style="font-family:var(--font-mono);color:var(--accent);">terminal-input</td><td>mentor → student</td><td style="color:var(--text-dim);"><?= Language::getCurrent() === 'pl' ? 'Komenda na terminal' : 'Terminal command' ?></td></tr>
            <tr><td style="font-family:var(--font-mono);color:var(--accent);">screen-frame</td><td>student → mentor</td><td style="color:var(--text-dim);"><?= Language::getCurrent() === 'pl' ? 'Screenshot (base64)' : 'Screenshot (base64)' ?></td></tr>
            <tr><td style="font-family:var(--font-mono);color:var(--accent);">chat-message</td><td>bidirectional</td><td style="color:var(--text-dim);"><?= Language::getCurrent() === 'pl' ? 'Wiadomość' : 'Message' ?></td></tr>
            <tr><td style="font-family:var(--font-mono);color:var(--accent);">slide-sync</td><td>mentor → all</td><td style="color:var(--text-dim);"><?= Language::getCurrent() === 'pl' ? 'Sync slajdów' : 'Slides sync' ?></td></tr>
        </tbody>
    </table>
</div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
