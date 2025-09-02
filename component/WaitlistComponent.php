<?php

class WaitlistComponent {
    private $dbPath;
    private $rateLimitDbPath;
    private $window;
    
    public function __construct($dbPath = null, $rateLimitDbPath = null, $window = 60) {
        $this->dbPath = $dbPath ?: __DIR__ . '/data/waitlist.db';
        $this->rateLimitDbPath = $rateLimitDbPath ?: __DIR__ . '/data/rate_limit.db';
        $this->window = $window;
        
        // Ensure data directory exists
        if (!file_exists(__DIR__ . '/data')) {
            mkdir(__DIR__ . '/data', 0755, true);
        }
    }
    
    public function createDatabaseTables() {
        // Create rate limit table
        $db = new PDO('sqlite:' . $this->rateLimitDbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("CREATE TABLE IF NOT EXISTS rate_limit (
            ip TEXT PRIMARY KEY,
            window_start INTEGER)");

        // Create waitlist table
        $db = new PDO('sqlite:' . $this->dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("CREATE TABLE IF NOT EXISTS waitlist (
            email TEXT PRIMARY KEY,
            ip TEXT,
            ts INTEGER DEFAULT (unixepoch()),
            dt TEXT GENERATED ALWAYS AS (datetime(ts, 'unixepoch')) STORED
        )");
    }
    
    private function getClientIp() {
        // Check if we have a trusted proxy header set
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && $this->validateIp($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // HTTP_X_FORWARDED_FOR can contain a comma-separated list of IPs.
            // The first one is typically the original client's IP.
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($ipList as $ip) {
                $ip = trim($ip);
                if ($this->validateIp($ip)) {
                    return $ip;
                }
            }
        }
        
        // Fallback to REMOTE_ADDR if no proxy headers are set
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    private function validateIp($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
    
    public function checkRateLimit() {
        $ip = $this->getClientIp();
        $db = new PDO('sqlite:' . $this->rateLimitDbPath);
        $db->exec("PRAGMA busy_timeout = 5000");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();
        $stmt = $db->prepare("SELECT window_start FROM rate_limit WHERE ip = ?");
        $stmt->execute([$ip]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $currentTime = time();

        if ($row) {
            // Check if the limit has been exceeded
            if ($currentTime - $row['window_start'] <= $this->window) {
                $db->rollBack();
                return false;
            }
        }

        // Update rate limit
        $stmt = $db->prepare("
            INSERT INTO rate_limit (ip, window_start)
            VALUES (:ip, :window_start)
            ON CONFLICT(ip) DO UPDATE SET
                window_start = :new_window_start");
        $stmt->execute([
            ':ip'              => $ip,
            ':window_start'    => $currentTime,
            ':new_window_start'=> $currentTime,
        ]);

        // Clean up old entries
        $db->exec("DELETE FROM rate_limit WHERE window_start < " . ($currentTime - $this->window));
        $db->commit();
        
        return true;
    }
    
    public function addToWaitlist($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email address'];
        }
        
        $ip = $this->getClientIp();
        $wdb = new PDO('sqlite:' . $this->dbPath);
        $wdb->exec("PRAGMA busy_timeout = 5000");
        $wdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $wdb->beginTransaction();
        $stmt = $wdb->prepare("
            INSERT INTO waitlist (email, ip)
            VALUES (:email, :ip)
            ON CONFLICT(email) DO NOTHING");
        $stmt->execute([
            ':email' => $email,           
            ':ip'    => $ip,
        ]);
        $wdb->commit();

        return ['success' => true];
    }
    
    public function handleRequest() {
        // Check rate limit
        if (!$this->checkRateLimit()) {
            http_response_code(429);
            header("Content-Type: application/json");
            echo json_encode(['success' => false, 'message' => 'too many requests']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        // Get the raw POST data
        $data = file_get_contents('php://input');
        $json = json_decode($data, true);

        // Check honeypot
        $honeypot = $json['name'] ?? '';
        if (!empty($honeypot)) {
            // Honeypot field is not empty, likely a bot
            echo json_encode(['success' => true, 'message' => 'not human']);
            return;
        }

        // Check if email is set and valid
        if (isset($json['email'])) {
            $result = $this->addToWaitlist($json['email']);
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'message' => 'Email is required']);
        }
    }
    
    public function renderForm($formId = 'waitlist-form', $placeholder = 'Enter your email to get updates', $buttonText = 'Join') {
        return "
        <form id=\"{$formId}\" class=\"collectiq-waitlist-form\">
            <div class=\"collectiq-input-container\">
                <input type=\"email\" placeholder=\"{$placeholder}\">
            </div>
            <input type=\"text\" name=\"your_name\" style=\"display:none;\">
            <button type=\"submit\" class=\"collectiq-submit-btn collectiq-default\">
                <div class=\"collectiq-shimmer-container\">
                    <div class=\"collectiq-shimmer\"></div>
                </div>
                <span>{$buttonText}</span>
                <div class=\"collectiq-highlight\"></div>
                <div class=\"collectiq-backdrop\"></div>
            </button>
        </form>";
    }
    
    public function getCSSPath() {
        return __DIR__ . '/assets/waitlist.css';
    }
    
    public function getJSPath() {
        return __DIR__ . '/assets/waitlist.js';
    }
    
    public function includeAssets() {
        $cssPath = '/components/waitlist/assets/waitlist.css';
        $jsPath = '/components/waitlist/assets/waitlist.js';
        
        return [
            'css' => "<link rel=\"stylesheet\" href=\"{$cssPath}\">",
            'js' => "<script src=\"{$jsPath}\"></script>"
        ];
    }
}

?>