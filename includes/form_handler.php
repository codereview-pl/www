<?php
/**
 * CodeReview.pl - Form Handler
 * Handles contact form submissions with validation and logging
 */

require_once __DIR__ . '/logger.php';

class FormHandler {
    private array $errors = [];
    private array $data = [];
    
    public function handleContactForm(): array {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request method'];
        }
        
        // CSRF protection (simple implementation)
        if (!isset($_POST['form_token']) || $_POST['form_token'] !== $this->generateToken()) {
            Logger::warning('CSRF token mismatch', [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
            return ['success' => false, 'message' => 'Security token invalid'];
        }
        
        // Sanitize and validate input
        $this->data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'company' => trim($_POST['company'] ?? ''),
            'subject' => $_POST['subject'] ?? '',
            'seats' => (int)($_POST['seats'] ?? 0),
            'message' => trim($_POST['message'] ?? ''),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Validate fields
        $this->validateName();
        $this->validateEmail();
        $this->validateSubject();
        $this->validateSeats();
        $this->validateMessage();
        
        if (!empty($this->errors)) {
            Logger::warning('Form validation failed', [
                'errors' => $this->errors,
                'data' => $this->data
            ]);
            return ['success' => false, 'message' => 'Validation failed', 'errors' => $this->errors];
        }
        
        // Process the form
        return $this->processContactForm();
    }
    
    private function validateName(): void {
        if (empty($this->data['name'])) {
            $this->errors['name'] = 'Imię i nazwisko jest wymagane';
        } elseif (strlen($this->data['name']) < 2) {
            $this->errors['name'] = 'Imię jest zbyt krótkie';
        } elseif (strlen($this->data['name']) > 100) {
            $this->errors['name'] = 'Imię jest zbyt długie';
        }
    }
    
    private function validateEmail(): void {
        if (empty($this->data['email'])) {
            $this->errors['email'] = 'Email jest wymagany';
        } elseif (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Nieprawidłowy format email';
        }
    }
    
    private function validateSubject(): void {
        $validSubjects = ['hub', 'edu', 'demo', 'mentor', 'support', 'other'];
        if (!in_array($this->data['subject'], $validSubjects)) {
            $this->errors['subject'] = 'Nieprawidłowy temat';
        }
    }
    
    private function validateSeats(): void {
        if ($this->data['seats'] < 0 || $this->data['seats'] > 1000) {
            $this->errors['seats'] = 'Nieprawidłowa liczba stanowisk';
        }
    }
    
    private function validateMessage(): void {
        if (empty($this->data['message'])) {
            $this->errors['message'] = 'Wiadomość jest wymagana';
        } elseif (strlen($this->data['message']) < 10) {
            $this->errors['message'] = 'Wiadomość jest zbyt krótka';
        } elseif (strlen($this->data['message']) > 5000) {
            $this->errors['message'] = 'Wiadomość jest zbyt długa';
        }
    }
    
    private function processContactForm(): array {
        try {
            // Log the submission
            Logger::info('Contact form submitted', [
                'subject' => $this->data['subject'],
                'email' => $this->data['email'],
                'company' => $this->data['company'],
                'seats' => $this->data['seats'],
                'ip' => $this->data['ip']
            ]);
            
            // Save to database if available
            $db = get_db();
            if ($db) {
                $stmt = $db->prepare("
                    INSERT INTO contact_submissions 
                    (name, email, company, subject, seats, message, ip, user_agent, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $stmt->execute([
                    $this->data['name'],
                    $this->data['email'],
                    $this->data['company'],
                    $this->data['subject'],
                    $this->data['seats'],
                    $this->data['message'],
                    $this->data['ip'],
                    $this->data['user_agent'],
                    $this->data['timestamp']
                ]);
                
                Logger::db('Contact form saved to database', ['id' => $db->lastInsertId()]);
            } else {
                Logger::warning('Database not available, form not saved');
            }
            
            // Send email notification (simplified - in production use proper email service)
            $this->sendEmailNotification();
            
            return ['success' => true, 'message' => 'Wiadomość została wysłana pomyślnie'];
            
        } catch (Exception $e) {
            Logger::error('Contact form processing failed', [
                'error' => $e->getMessage(),
                'data' => $this->data
            ]);
            
            return ['success' => false, 'message' => 'Wystąpił błąd podczas przetwarzania formularza'];
        }
    }
    
    private function sendEmailNotification(): void {
        $to = SITE_EMAIL;
        $subject = '[CodeReview.pl] ' . $this->getSubjectLabel();
        
        $message = "Nowa wiadomość z formularza kontaktowego:\n\n";
        $message .= "Imię i nazwisko: " . $this->data['name'] . "\n";
        $message .= "Email: " . $this->data['email'] . "\n";
        $message .= "Firma: " . ($this->data['company'] ?: 'Brak') . "\n";
        $message .= "Temat: " . $this->getSubjectLabel() . "\n";
        if ($this->data['seats'] > 0) {
            $message .= "Liczba stanowisk: " . $this->data['seats'] . "\n";
        }
        $message .= "Wiadomość:\n" . $this->data['message'] . "\n\n";
        $message .= "IP: " . $this->data['ip'] . "\n";
        $message .= "Data: " . $this->data['timestamp'] . "\n";
        
        $headers = [
            'From: ' . SITE_EMAIL,
            'Reply-To: ' . $this->data['email'],
            'X-Mailer: CodeReview.pl Form Handler'
        ];
        
        // In production, use proper email service like SendGrid, Mailgun, etc.
        $result = mail($to, $subject, $message, implode("\r\n", $headers));
        
        if ($result) {
            Logger::info('Email notification sent', ['to' => $to, 'subject' => $subject]);
        } else {
            Logger::error('Failed to send email notification', ['to' => $to]);
        }
    }
    
    private function getSubjectLabel(): string {
        $labels = [
            'hub' => 'Zamówienie stanowisk Premium Hub',
            'edu' => 'Rabat EDU (−50%)',
            'demo' => 'Umów demo',
            'mentor' => 'Zostań mentorem',
            'support' => 'Wsparcie techniczne',
            'other' => 'Inne'
        ];
        
        return $labels[$this->data['subject']] ?? $this->data['subject'];
    }
    
    private function generateToken(): string {
        // Simple token generation - in production use more secure method
        return hash('sha256', session_id() . date('Y-m-d'));
    }
    
    public static function createContactTable(): void {
        $db = get_db();
        if (!$db) {
            Logger::warning('Cannot create contact table - no database connection');
            return;
        }
        
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS contact_submissions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    company VARCHAR(255),
                    subject VARCHAR(50) NOT NULL,
                    seats INT DEFAULT 0,
                    message TEXT NOT NULL,
                    ip VARCHAR(45),
                    user_agent TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_email (email),
                    INDEX idx_subject (subject),
                    INDEX idx_created (created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            
            $db->exec($sql);
            Logger::db('Contact submissions table created/verified');
            
        } catch (PDOException $e) {
            Logger::error('Failed to create contact table', ['error' => $e->getMessage()]);
        }
    }
}
