<?php
/**
 * CodeReview.pl - Logging System
 * Centralized logging with rotation and different log levels
 */

// Ensure logs directory exists
if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0755, true);
}

class Logger {
    private const LOG_DIR = __DIR__ . '/../logs';
    private const LOG_FILE = 'app.log';
    private const ERROR_LOG = 'error.log';
    private const ACCESS_LOG = 'access.log';
    private const MAX_SIZE = 10 * 1024 * 1024; // 10MB
    private const MAX_FILES = 5;
    
    public static function log(string $level, string $message, array $context = []): void {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = empty($context) ? '' : ' | ' . json_encode($context);
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        
        // Main log file
        self::writeToFile(self::LOG_FILE, $logEntry);
        
        // Specific log files based on level
        if ($level === 'ERROR' || $level === 'CRITICAL') {
            self::writeToFile(self::ERROR_LOG, $logEntry);
        }
        
        // Also write to PHP error log for critical errors
        if ($level === 'CRITICAL') {
            error_log($message);
        }
    }
    
    public static function info(string $message, array $context = []): void {
        self::log('INFO', $message, $context);
    }
    
    public static function warning(string $message, array $context = []): void {
        self::log('WARNING', $message, $context);
    }
    
    public static function error(string $message, array $context = []): void {
        self::log('ERROR', $message, $context);
    }
    
    public static function critical(string $message, array $context = []): void {
        self::log('CRITICAL', $message, $context);
    }
    
    public static function access(string $message, array $context = []): void {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
        $uri = $_SERVER['REQUEST_URI'] ?? 'unknown';
        
        $contextStr = empty($context) ? '' : ' | ' . json_encode($context);
        $logEntry = "[{$timestamp}] {$ip} {$method} {$uri} | {$message}{$contextStr} | {$userAgent}" . PHP_EOL;
        
        self::writeToFile(self::ACCESS_LOG, $logEntry);
    }
    
    public static function db(string $message, array $context = []): void {
        self::log('DB', $message, $context);
    }
    
    public static function api(string $message, array $context = []): void {
        self::log('API', $message, $context);
    }
    
    private static function writeToFile(string $filename, string $content): void {
        $filepath = self::LOG_DIR . '/' . $filename;
        
        // Rotate if file is too large
        if (file_exists($filepath) && filesize($filepath) > self::MAX_SIZE) {
            self::rotateLog($filename);
        }
        
        file_put_contents($filepath, $content, FILE_APPEND | LOCK_EX);
    }
    
    private static function rotateLog(string $filename): void {
        $filepath = self::LOG_DIR . '/' . $filename;
        
        // Remove oldest log if we have too many
        for ($i = self::MAX_FILES; $i > 1; $i--) {
            $oldFile = self::LOG_DIR . '/' . $filename . '.' . $i;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        
        // Shift existing logs
        for ($i = self::MAX_FILES - 1; $i > 0; $i--) {
            $oldFile = $filepath . '.' . $i;
            $newFile = $filepath . '.' . ($i + 1);
            if (file_exists($oldFile)) {
                rename($oldFile, $newFile);
            }
        }
        
        // Move current log to .1
        if (file_exists($filepath)) {
            rename($filepath, $filepath . '.1');
        }
    }
    
    public static function getLogs(string $type = 'app', int $lines = 100): array {
        $filename = match($type) {
            'error' => self::ERROR_LOG,
            'access' => self::ACCESS_LOG,
            default => self::LOG_FILE
        };
        
        $filepath = self::LOG_DIR . '/' . $filename;
        
        if (!file_exists($filepath)) {
            return [];
        }
        
        $content = file_get_contents($filepath);
        $allLines = explode(PHP_EOL, trim($content));
        
        // Return last N lines
        return array_slice($allLines, -$lines);
    }
    
    public static function clearLogs(string $type = 'all'): void {
        $files = $type === 'all' 
            ? [self::LOG_FILE, self::ERROR_LOG, self::ACCESS_LOG]
            : [match($type) {
                'error' => self::ERROR_LOG,
                'access' => self::ACCESS_LOG,
                default => self::LOG_FILE
            }];
        
        foreach ($files as $filename) {
            $filepath = self::LOG_DIR . '/' . $filename;
            if (file_exists($filepath)) {
                file_put_contents($filepath, '');
            }
            
            // Also remove rotated logs
            for ($i = 1; $i <= self::MAX_FILES; $i++) {
                $rotatedFile = $filepath . '.' . $i;
                if (file_exists($rotatedFile)) {
                    unlink($rotatedFile);
                }
            }
        }
    }
}

// Helper function for quick logging
function log_info(string $message, array $context = []): void {
    Logger::info($message, $context);
}

function log_error(string $message, array $context = []): void {
    Logger::error($message, $context);
}

function log_warning(string $message, array $context = []): void {
    Logger::warning($message, $context);
}

function log_access(string $message, array $context = []): void {
    Logger::access($message, $context);
}
