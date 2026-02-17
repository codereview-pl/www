<?php
/**
 * CodeReview.pl - Global Bootstrap
 * Initializes sessions, logging, and core utilities
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load logger
require_once __DIR__ . '/logger.php';

// Load language system
require_once __DIR__ . '/lang.php';
Language::init();

// Log application start
Logger::info('Application initialized', [
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
    'method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
]);

// Set timezone
date_default_timezone_set('Europe/Warsaw');

// Load configuration
require_once __DIR__ . '/config.php';

// Core utilities are already handled by config.php (logger)
// and can be required as needed (rate_limiter, form_handler, etc.)

// Log request info if in debug mode (optional)
if (defined('APP_DEBUG') && APP_DEBUG) {
    // Already handled in config.php for now, but could be expanded here
}
