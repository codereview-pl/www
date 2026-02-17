<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title = __('sessions_title');
$page_desc = __('sessions_desc');

$session_duration = 15;
$max_participants = 3;

$sessions_file = __DIR__ . '/data/sessions.json';
$messages_file = __DIR__ . '/data/messages.json';

if (!is_dir(__DIR__ . '/data')) {
    mkdir(__DIR__ . '/data', 0755, true);
}

function load_sessions($file) {
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if ($data && is_array($data)) {
            return array_filter($data, function($s) {
                return $s['expires'] > time();
            });
        }
    }
    return [];
}

function save_sessions($file, $sessions) {
    file_put_contents($file, json_encode(array_values($sessions), JSON_PRETTY_PRINT));
}

function load_messages($file) {
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true) ?: [];
    }
    return [];
}

function save_messages($file, $messages) {
    file_put_contents($file, json_encode($messages, JSON_PRETTY_PRINT));
}

$sessions = load_sessions($sessions_file);
$messages = load_messages($messages_file);

$action = $_GET['action'] ?? '';
$room_id = $_GET['room'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_session'])) {
    $topic = trim($_POST['topic'] ?? '');
    $host_name = trim($_POST['host_name'] ?? '');
    
    if ($topic && $host_name) {
        $new_session = [
            'id' => bin2hex(random_bytes(8)),
            'topic' => htmlspecialchars($topic),
            'host' => htmlspecialchars($host_name),
            'participants' => [htmlspecialchars($host_name)],
            'max_participants' => $max_participants,
            'duration' => $session_duration,
            'created' => time(),
            'expires' => time() + ($session_duration * 60),
            'status' => 'waiting'
        ];
        $sessions[$new_session['id']] = $new_session;
        save_sessions($sessions_file, $sessions);
        header('Location: /sesje?room=' . $new_session['id']);
        exit;
    }
}

if ($action === 'join' && $room_id && isset($sessions[$room_id])) {
    $name = trim($_POST['name'] ?? '');
    if ($name && count($sessions[$room_id]['participants']) < $max_participants) {
        if (!in_array($name, $sessions[$room_id]['participants'])) {
            $sessions[$room_id]['participants'][] = htmlspecialchars($name);
            $sessions[$room_id]['status'] = 'in_progress';
            save_sessions($sessions_file, $sessions);
        }
        header('Location: /sesje?room=' . $room_id);
        exit;
    }
}

if ($action === 'leave' && $room_id && isset($sessions[$room_id])) {
    $name = $_POST['name'] ?? '';
    $key = array_search($name, $sessions[$room_id]['participants']);
    if ($key !== false) {
        unset($sessions[$room_id]['participants'][$key]);
        $sessions[$room_id]['participants'] = array_values($sessions[$room_id]['participants']);
        
        if (count($sessions[$room_id]['participants']) === 0) {
            unset($sessions[$room_id]);
        } elseif ($sessions[$room_id]['host'] === $name) {
            $sessions[$room_id]['status'] = 'waiting';
        }
        save_sessions($sessions_file, $sessions);
    }
    header('Location: /sesje');
    exit;
}

if ($action === 'send' && $room_id && isset($sessions[$room_id])) {
    $msg = trim($_POST['message'] ?? '');
    $sender = $_POST['sender'] ?? 'Anon';
    if ($msg) {
        $messages[$room_id][] = [
            'sender' => htmlspecialchars($sender),
            'message' => htmlspecialchars($msg),
            'time' => time()
        ];
        save_messages($messages_file, $messages);
    }
    header('Location: /sesje?room=' . $room_id);
    exit;
}

$current_session = $room_id && isset($sessions[$room_id]) ? $sessions[$room_id] : null;
$room_messages = $current_session ? ($messages[$room_id] ?? []) : [];
?>
<!DOCTYPE html>
<html lang="<?= Language::getCurrent() ?>">
<head>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <style>
        .sessions-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-top: 40px; }
        .session-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; transition: all .3s; }
        .session-card:hover { border-color: var(--border-accent); transform: translateY(-2px); }
        .session-card.full { opacity: 0.7; }
        .session-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px; }
        .session-topic { font-size: 1.1rem; font-weight: 700; margin-bottom: 8px; }
        .session-host { color: var(--text-muted); font-size: .85rem; }
        .session-meta { display: flex; gap: 16px; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border); }
        .session-meta span { font-size: .8rem; color: var(--text-muted); }
        .session-status { font-size: .75rem; font-weight: 600; padding: 4px 10px; border-radius: 100px; }
        .session-status.waiting { background: var(--accent-glow); color: var(--accent); }
        .session-status.in_progress { background: rgba(108,99,255,.15); color: var(--secondary); }
        .session-status.full { background: rgba(255,107,107,.15); color: var(--warn); }
        .btn-join { width: 100%; margin-top: 16px; }
        
        .room-view { display: grid; grid-template-columns: 1fr 320px; gap: 24px; margin-top: 40px; }
        .room-main { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; }
        .room-sidebar { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; height: fit-content; }
        .room-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
        .participants-list { list-style: none; }
        .participants-list li { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--border); }
        .participants-list li:last-child { border-bottom: none; }
        .participant-avatar { width: 32px; height: 32px; background: var(--accent); color: var(--bg-deep); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; }
        .participant-host { font-size: .75rem; color: var(--accent); margin-left: auto; }
        
        .chat-messages { height: 300px; overflow-y: auto; margin-bottom: 16px; padding: 16px; background: var(--bg-elevated); border-radius: var(--radius-sm); }
        .chat-msg { margin-bottom: 12px; }
        .chat-msg .sender { font-size: .8rem; font-weight: 600; color: var(--accent); }
        .chat-msg .text { color: var(--text-dim); font-size: .9rem; margin-top: 4px; }
        .chat-msg .time { font-size: .7rem; color: var(--text-muted); }
        
        .llm-panel { margin-top: 24px; padding: 20px; background: linear-gradient(135deg, rgba(108,99,255,.1), rgba(0,229,155,.1)); border: 1px solid var(--border-secondary); border-radius: var(--radius-sm); }
        .llm-header { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
        .llm-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }
        .llm-dot.offline { background: var(--warn); }
        .llm-status { font-size: .85rem; color: var(--text-muted); }
        
        .create-form { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); padding: 32px; margin-top: 40px; }
        .create-form h3 { margin-bottom: 24px; }
        
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; margin-bottom: 24px; }
        .back-link:hover { color: var(--accent); }
        
        @media(max-width: 900px) { .room-view { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <main>
        <section class="page-hero">
            <div class="hero-glow"></div>
            <div class="container">
                <div class="hero-content">
                    <div class="section-label"><?= __('sessions_label') ?></div>
                    <h1><?= __('sessions_title') ?></h1>
                    <p><?= __('sessions_desc') ?></p>
                </div>
            </div>
        </section>
        
        <section style="padding-top: 0;">
            <div class="container">
                <?php if ($current_session): ?>
                    <a href="/sesje" class="back-link">← <?= __('nav_sessions') ?></a>
                    
                    <div class="room-view">
                        <div class="room-main">
                            <div class="room-header">
                                <div>
                                    <h2 style="margin-bottom: 4px;"><?= htmlspecialchars($current_session['topic']) ?></h2>
                                    <span class="session-status <?= $current_session['status'] ?>">
                                        <?= $current_session['status'] === 'waiting' ? __('sessions_waiting') : __('sessions_in_progress') ?>
                                    </span>
                                </div>
                                <?php $time_left = max(0, $current_session['expires'] - time()); ?>
                                <div style="text-align: right;">
                                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent);"><?= ceil($time_left / 60) ?></div>
                                    <div style="font-size: .8rem; color: var(--text-muted);"><?= __('sessions_min_left') ?></div>
                                </div>
                            </div>
                            
                            <div class="llm-panel">
                                <div class="llm-header">
                                    <div class="llm-dot"></div>
                                    <span class="llm-status"><?= __('sessions_llm_connected') ?></span>
                                </div>
                                <p style="font-size: .9rem; color: var(--text-dim);">
                                    <?= __('sessions_ai_assistant') ?>: Gotowy do pomocy w debugowaniu, code review i wyjaśnianiu koncepcji programistycznych.
                                </p>
                            </div>
                            
                            <div style="margin-top: 24px;">
                                <h4 style="margin-bottom: 16px;"><?= __('sessions_code') ?></h4>
                                <div style="background: var(--bg-elevated); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 20px; font-family: var(--font-mono); font-size: .85rem; color: var(--text-dim);">
                                    // Kod sesji będzie dostępny po dołączeniu do pokoju
                                </div>
                            </div>
                        </div>
                        
                        <div class="room-sidebar">
                            <h4 style="margin-bottom: 16px;">👥 <?= count($current_session['participants']) ?>/<?= $current_session['max_participants'] ?> <?= __('sessions_participants') ?></h4>
                            <ul class="participants-list">
                                <?php foreach ($current_session['participants'] as $p): ?>
                                <li>
                                    <div class="participant-avatar"><?= strtoupper(substr($p, 0, 1)) ?></div>
                                    <span><?= htmlspecialchars($p) ?></span>
                                    <?php if ($p === $current_session['host']): ?>
                                    <span class="participant-host">Host</span>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <h4 style="margin: 24px 0 16px;">💬 <?= __('sessions_chat') ?></h4>
                            <div class="chat-messages">
                                <?php foreach ($room_messages as $m): ?>
                                <div class="chat-msg">
                                    <div class="sender"><?= htmlspecialchars($m['sender']) ?></div>
                                    <div class="text"><?= htmlspecialchars($m['message']) ?></div>
                                    <div class="time"><?= date('H:i', $m['time']) ?></div>
                                </div>
                                <?php endforeach; ?>
                                <?php if (empty($room_messages)): ?>
                                <div style="color: var(--text-muted); font-size: .85rem; text-align: center;">Brak wiadomości</div>
                                <?php endif; ?>
                            </div>
                            
                            <form method="post" action="/sesje?action=send&room=<?= $room_id ?>">
                                <input type="hidden" name="sender" value="<?= htmlspecialchars($_COOKIE['session_name'] ?? 'Gość') ?>">
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" name="message" class="form-input" placeholder="<?= __('sessions_send_message') ?>" style="flex: 1;">
                                    <button type="submit" class="btn btn-primary btn-sm">→</button>
                                </div>
                            </form>
                            
                            <form method="post" action="/sesje?action=leave&room=<?= $room_id ?>" style="margin-top: 24px;">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($_COOKIE['session_name'] ?? '') ?>">
                                <button type="submit" class="btn btn-ghost" style="width: 100%;"><?= __('sessions_leave') ?></button>
                            </form>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <?php if (!empty($sessions)): ?>
                    <div class="sessions-grid">
                        <?php foreach ($sessions as $s): ?>
                        <div class="session-card <?= count($s['participants']) >= $s['max_participants'] ? 'full' : '' ?>">
                            <div class="session-header">
                                <div>
                                    <div class="session-topic"><?= htmlspecialchars($s['topic']) ?></div>
                                    <div class="session-host">👤 <?= htmlspecialchars($s['host']) ?></div>
                                </div>
                                <span class="session-status <?= count($s['participants']) >= $s['max_participants'] ? 'full' : $s['status'] ?>">
                                    <?= count($s['participants']) >= $s['max_participants'] ? __('sessions_full') : ($s['status'] === 'waiting' ? __('sessions_waiting') : __('sessions_in_progress')) ?>
                                </span>
                            </div>
                            <div class="session-meta">
                                <span>⏱️ <?= $s['duration'] ?> min</span>
                                <span>👥 <?= count($s['participants']) ?>/<?= $s['max_participants'] ?> <?= __('sessions_participants') ?></span>
                            </div>
                            <?php if (count($s['participants']) < $s['max_participants']): ?>
                            <form method="post" action="/sesje?action=join&room=<?= $s['id'] ?>">
                                <input type="text" name="name" class="form-input" placeholder="<?= __('sessions_your_name') ?>" required style="margin-top: 16px;">
                                <button type="submit" class="btn btn-primary btn-join"><?= __('sessions_join') }}</button>
                            </form>
                            <?php else: ?>
                            <button class="btn btn-ghost btn-join" disabled><?= __('sessions_full') ?></button>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div style="text-align: center; padding: 60px 20px; color: var(--text-muted);">
                        <p style="font-size: 1.1rem; margin-bottom: 8px;"><?= __('sessions_no_rooms') ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="create-form">
                        <h3>🎯 <?= __('sessions_create') ?></h3>
                        <form method="post">
                            <div class="form-group">
                                <label class="form-input"><?= __('sessions_your_name') ?></label>
                                <input type="text" name="host_name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label><?= __('sessions_topic') ?></label>
                                <input type="text" name="topic" class="form-input" placeholder="<?= __('sessions_placeholder_topic') ?>" required>
                            </div>
                            <div style="display: flex; gap: 24px; margin-bottom: 24px; color: var(--text-muted); font-size: .9rem;">
                                <span>⏱️ <?= __('sessions_duration') ?>: <?= $session_duration ?> min</span>
                                <span>👥 <?= __('sessions_max_participants') ?>: <?= $max_participants ?></span>
                            </div>
                            <button type="submit" name="create_session" class="btn btn-primary"><?= __('sessions_start') }}</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
