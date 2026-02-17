<?php
/**
 * CodeReview.pl - Database Initialization
 * Creates necessary tables and sets up the database
 */

// Prevent direct access from web
if (!defined('INIT_DB')) {
    http_response_code(403);
    exit('Access denied');
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/form_handler.php';

echo "=== CodeReview.pl Database Initialization ===\n\n";

try {
    // Test database connection
    $db = get_db();
    if (!$db) {
        throw new Exception('Cannot connect to database');
    }
    
    echo "✓ Database connection successful\n";
    
    // Create contact submissions table
    FormHandler::createContactTable();
    
    // Create additional tables if needed
    createUsersTable();
    createSessionsTable();
    createLogsTable();
    
    echo "\n=== Database initialization complete ===\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    Logger::critical('Database initialization failed', ['error' => $e->getMessage()]);
    exit(1);
}

function createUsersTable(): void {
    global $db;
    
    $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            github_id VARCHAR(50) UNIQUE,
            email VARCHAR(255) UNIQUE NOT NULL,
            name VARCHAR(255) NOT NULL,
            avatar_url TEXT,
            role ENUM('student', 'mentor', 'admin') DEFAULT 'student',
            stripe_account_id VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_github (github_id),
            INDEX idx_email (email),
            INDEX idx_role (role)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $db->exec($sql);
    echo "✓ Users table created/verified\n";
    Logger::db('Users table created/verified');
}

function createSessionsTable(): void {
    global $db;
    
    $sql = "
        CREATE TABLE IF NOT EXISTS mentoring_sessions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            mentor_id INT NOT NULL,
            student_id INT NOT NULL,
            room_id VARCHAR(100) UNIQUE NOT NULL,
            title VARCHAR(255),
            description TEXT,
            status ENUM('scheduled', 'active', 'completed', 'cancelled') DEFAULT 'scheduled',
            scheduled_at TIMESTAMP NULL,
            started_at TIMESTAMP NULL,
            ended_at TIMESTAMP NULL,
            price_cents INT DEFAULT 0,
            currency VARCHAR(3) DEFAULT 'PLN',
            payment_intent_id VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (mentor_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_mentor (mentor_id),
            INDEX idx_student (student_id),
            INDEX idx_room (room_id),
            INDEX idx_status (status),
            INDEX idx_scheduled (scheduled_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $db->exec($sql);
    echo "✓ Mentoring sessions table created/verified\n";
    Logger::db('Mentoring sessions table created/verified');
}

function createLogsTable(): void {
    global $db;
    
    $sql = "
        CREATE TABLE IF NOT EXISTS app_logs (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            level ENUM('DEBUG', 'INFO', 'WARNING', 'ERROR', 'CRITICAL') NOT NULL,
            message TEXT NOT NULL,
            context JSON,
            ip VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_level (level),
            INDEX idx_created (created_at),
            INDEX idx_ip (ip)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $db->exec($sql);
    echo "✓ Application logs table created/verified\n";
    Logger::db('Application logs table created/verified');
}
