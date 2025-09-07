<?php

class RateLimitComponent {
    private $dbPath;
    private $window;
    
    public function __construct($dbPath = null, $window = 60) {
        $this->dbPath = $dbPath ?: __DIR__ . '/data/rate_limit.db';
        $this->window = $window;
        
        // Ensure data directory exists
        $dataDir = dirname($this->dbPath);
        if (!file_exists($dataDir)) {
            mkdir($dataDir, 0755, true);
        }
    }
    
    public function setWindow($seconds) {
        $this->window = $seconds;
        return $this;
    }
    
    public function getWindow() {
        return $this->window;
    }
    
    public function createDatabaseTable() {
        $db = new PDO('sqlite:' . $this->dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("CREATE TABLE IF NOT EXISTS rate_limit (
            ip TEXT PRIMARY KEY,
            window_start INTEGER)");
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
    
    public function checkRateLimit($ip = null) {
        if ($ip === null) {
            $ip = $this->getClientIp();
        }
        
        $db = new PDO('sqlite:' . $this->dbPath);
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
    
    public function getRemainingTime($ip = null) {
        if ($ip === null) {
            $ip = $this->getClientIp();
        }
        
        $db = new PDO('sqlite:' . $this->dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $db->prepare("SELECT window_start FROM rate_limit WHERE ip = ?");
        $stmt->execute([$ip]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return 0;
        }
        
        $currentTime = time();
        $remainingTime = $this->window - ($currentTime - $row['window_start']);
        
        return max(0, $remainingTime);
    }
}