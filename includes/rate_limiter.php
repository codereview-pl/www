<?php
/**
 * CodeReview.pl - Rate Limiter
 * Provides a reusable way to limit actions by IP or session
 */

class RateLimiter {
    private const DEFAULT_LIMIT = 60; // seconds
    
    /**
     * Check if an action is rate limited
     * 
     * @param string $action Key for the action (e.g., 'contact_form')
     * @param int $limit Minimum time between actions in seconds
     * @param bool $byIp If true, uses IP address as part of the key
     * @return bool True if rate limited, false otherwise
     */
    public static function isLimited(string $action, int $limit = self::DEFAULT_LIMIT, bool $byIp = true): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $key = 'rate_limit_' . $action;
        if ($byIp) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $key .= '_' . md5($ip);
        }

        $now = time();
        $lastTime = $_SESSION[$key] ?? 0;

        if ($now - $lastTime < $limit) {
            return true;
        }

        $_SESSION[$key] = $now;
        return false;
    }

    /**
     * Get remaining wait time in seconds
     */
    public static function getWaitTime(string $action, int $limit = self::DEFAULT_LIMIT, bool $byIp = true): int {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $key = 'rate_limit_' . $action;
        if ($byIp) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $key .= '_' . md5($ip);
        }

        $now = time();
        $lastTime = $_SESSION[$key] ?? 0;
        $remaining = $limit - ($now - $lastTime);

        return max(0, $remaining);
    }
}
