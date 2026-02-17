#!/usr/bin/env php
<?php
/**
 * CodeReview.pl - Admin Password Generator
 * Usage: php admin/create_user.php [username] [password]
 */

if ($argc < 3) {
    echo "Usage: php admin/create_user.php [username] [password]\n";
    exit(1);
}

$user = $argv[1];
$pass = $argv[2];
$hash = password_hash($pass, PASSWORD_BCRYPT);

$htpasswd_line = "$user:$hash\n";
$file = __DIR__ . '/.htpasswd';

// Note: Apache's Basic Auth with BCRYPT requires special handling in some versions,
// but modern Apache supports it. Standard htpasswd usually uses crypt or md5.
// For PHP-based checks, password_hash is best.
// If using .htaccess for Basic Auth, we might need a specific format.

file_put_contents($file, $htpasswd_line, FILE_APPEND);
echo "✓ User $user added to .htpasswd\n";
