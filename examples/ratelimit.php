<?php
require_once '../components/ratelimit/RateLimitComponent.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    $window = intval($_POST['window'] ?? 60);
    
    // Initialize rate limiter with custom window
    $rateLimit = new RateLimitComponent(null, $window);
    $rateLimit->createDatabaseTable();
    
    switch ($action) {
        case 'check':
            $allowed = $rateLimit->checkRateLimit();
            $remaining = $rateLimit->getRemainingTime();
            
            echo json_encode([
                'allowed' => $allowed,
                'remaining' => $remaining,
                'message' => $allowed 
                    ? 'Request allowed' 
                    : "Rate limit exceeded. Try again in {$remaining} seconds."
            ]);
            break;
            
        case 'test_ip':
            $testIp = $_POST['test_ip'] ?? '';
            if (empty($testIp) || !filter_var($testIp, FILTER_VALIDATE_IP)) {
                echo json_encode(['error' => 'Invalid IP address']);
                break;
            }
            
            $allowed = $rateLimit->checkRateLimit($testIp);
            $remaining = $rateLimit->getRemainingTime($testIp);
            
            echo json_encode([
                'allowed' => $allowed,
                'remaining' => $remaining,
                'ip' => $testIp,
                'message' => $allowed 
                    ? "Request allowed for {$testIp}" 
                    : "Rate limit exceeded for {$testIp}. Try again in {$remaining} seconds."
            ]);
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RateLimit Component Example</title>
    <style>
        body { 
            background: #000; 
            color: #fff; 
            font-family: 'Inter', Arial, sans-serif; 
            padding: 2rem; 
            min-height: 100vh;
            line-height: 1.6;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
        }
        h1, h2 { 
            text-align: center; 
            margin-bottom: 2rem; 
        }
        .section { 
            margin: 3rem 0; 
            padding: 2rem; 
            border: 1px solid #333; 
            border-radius: 8px;
            background: #111;
        }
        .info {
            background: #1a1a1a;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #ff6b6b;
        }
        .info h3 {
            margin-top: 0;
            color: #ff6b6b;
        }
        .demo-area {
            background: #0a0a0a;
            padding: 2rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #ccc;
            font-weight: 500;
        }
        input, select {
            width: 100%;
            max-width: 300px;
            padding: 0.75rem;
            background: #222;
            border: 1px solid #444;
            border-radius: 4px;
            color: #fff;
            font-size: 1rem;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #ff6b6b;
            box-shadow: 0 0 0 2px rgba(255, 107, 107, 0.2);
        }
        button {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        button:hover {
            background: linear-gradient(135deg, #ee5a24, #d63031);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        }
        button:active {
            transform: translateY(0);
        }
        button:disabled {
            background: #444;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .result {
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
        .result.success {
            background: rgba(0, 255, 0, 0.1);
            border: 1px solid #0f5;
            color: #0f5;
        }
        .result.error {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid #f50;
            color: #f50;
        }
        .result.info {
            background: rgba(0, 150, 255, 0.1);
            border: 1px solid #09f;
            color: #09f;
        }
        .status-display {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 1rem 0;
        }
        .status-item {
            background: #1a1a1a;
            padding: 1rem;
            border-radius: 4px;
            text-align: center;
        }
        .status-item strong {
            display: block;
            font-size: 1.5rem;
            color: #ff6b6b;
        }
        .back-link {
            text-align: center;
            margin-bottom: 2rem;
        }
        .back-link a {
            color: #ff6b6b;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid #ff6b6b;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .back-link a:hover {
            background: #ff6b6b;
            color: #000;
        }
        pre {
            background: #0a0a0a;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
            border: 1px solid #333;
        }
        code {
            color: #f8f8f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-link">
            <a href="../example.php">← Back to Main Examples</a>
        </div>

        <h1>RateLimit Component Example</h1>
        
        <div class="info">
            <h3>About This Component</h3>
            <p>The <strong>RateLimitComponent</strong> is a standalone rate limiting solution that can be used independently from the waitlist. Features include:</p>
            <ul>
                <li>IP-based rate limiting with configurable time windows</li>
                <li>Automatic cleanup of expired entries</li>
                <li>Support for proxy headers (X-Forwarded-For)</li>
                <li>Manual IP testing capabilities</li>
                <li>SQLite database storage</li>
                <li>Remaining time calculations</li>
            </ul>
        </div>
        
        <div class="section">
            <h2>Interactive Rate Limit Testing</h2>
            
            <div class="demo-area">
                <div class="form-group">
                    <label for="window">Rate Limit Window (seconds):</label>
                    <select id="window">
                        <option value="10">10 seconds (for testing)</option>
                        <option value="30">30 seconds</option>
                        <option value="60" selected>60 seconds (default)</option>
                        <option value="120">2 minutes</option>
                        <option value="300">5 minutes</option>
                    </select>
                </div>
                
                <button onclick="testRateLimit()">Test Rate Limit (Your IP)</button>
                <button onclick="clearResults()">Clear Results</button>
                
                <div class="status-display">
                    <div class="status-item">
                        <strong id="status">Ready</strong>
                        <span>Status</span>
                    </div>
                    <div class="status-item">
                        <strong id="remaining">-</strong>
                        <span>Seconds Remaining</span>
                    </div>
                </div>
                
                <div id="results"></div>
            </div>
        </div>
        
        <div class="section">
            <h2>Test Specific IP Address</h2>
            
            <div class="demo-area">
                <div class="form-group">
                    <label for="test_ip">IP Address to Test:</label>
                    <input type="text" id="test_ip" placeholder="192.168.1.100" value="192.168.1.100">
                </div>
                
                <button onclick="testSpecificIP()">Test This IP</button>
                
                <div id="ip-results"></div>
            </div>
        </div>

        <div class="info">
            <h3>Usage Code</h3>
            <pre><code>&lt;?php
require_once 'collectiq/components/ratelimit/RateLimitComponent.php';

// Initialize with custom database path and window
$rateLimit = new RateLimitComponent('/path/to/db.sqlite', 60);

// Check if current request is allowed
if ($rateLimit->checkRateLimit()) {
    echo "Request allowed";
    // Process the request...
} else {
    http_response_code(429);
    $remaining = $rateLimit->getRemainingTime();
    echo "Rate limited. Try again in {$remaining} seconds";
}

// Test specific IP address
$allowed = $rateLimit->checkRateLimit('192.168.1.100');

// Change rate limit window
$rateLimit->setWindow(120); // 2 minutes

// Get current window
$window = $rateLimit->getWindow();
?&gt;</code></pre>
        </div>
    </div>

    <script>
        let testCount = 0;
        
        function addResult(message, type = 'info') {
            const results = document.getElementById('results');
            const result = document.createElement('div');
            result.className = `result ${type}`;
            result.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
            results.appendChild(result);
            results.scrollTop = results.scrollHeight;
        }
        
        function addIPResult(message, type = 'info') {
            const results = document.getElementById('ip-results');
            const result = document.createElement('div');
            result.className = `result ${type}`;
            result.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
            results.appendChild(result);
            results.scrollTop = results.scrollHeight;
        }
        
        function updateStatus(status, remaining = '-') {
            document.getElementById('status').textContent = status;
            document.getElementById('remaining').textContent = remaining === 0 ? '0' : remaining || '-';
        }
        
        async function testRateLimit() {
            const window = document.getElementById('window').value;
            testCount++;
            
            addResult(`Test #${testCount}: Checking rate limit (${window}s window)...`, 'info');
            
            try {
                const response = await fetch('ratelimit.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `ajax=1&action=check&window=${window}`
                });
                
                const data = await response.json();
                
                if (data.allowed) {
                    addResult(`✅ ${data.message}`, 'success');
                    updateStatus('Allowed', 0);
                } else {
                    addResult(`❌ ${data.message}`, 'error');
                    updateStatus('Rate Limited', data.remaining);
                }
            } catch (error) {
                addResult(`Error: ${error.message}`, 'error');
                updateStatus('Error');
            }
        }
        
        async function testSpecificIP() {
            const testIp = document.getElementById('test_ip').value;
            const window = document.getElementById('window').value;
            
            if (!testIp) {
                addIPResult('Please enter an IP address', 'error');
                return;
            }
            
            addIPResult(`Testing IP: ${testIp} (${window}s window)...`, 'info');
            
            try {
                const response = await fetch('ratelimit.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `ajax=1&action=test_ip&test_ip=${testIp}&window=${window}`
                });
                
                const data = await response.json();
                
                if (data.error) {
                    addIPResult(`❌ ${data.error}`, 'error');
                } else if (data.allowed) {
                    addIPResult(`✅ ${data.message}`, 'success');
                } else {
                    addIPResult(`❌ ${data.message}`, 'error');
                }
            } catch (error) {
                addIPResult(`Error: ${error.message}`, 'error');
            }
        }
        
        function clearResults() {
            document.getElementById('results').innerHTML = '';
            updateStatus('Ready');
            testCount = 0;
        }
        
        // Add some helpful tips
        setTimeout(() => {
            addResult('💡 Tip: Try clicking "Test Rate Limit" multiple times quickly to see the rate limiting in action!', 'info');
            addResult('💡 Tip: Use shorter windows (10-30 seconds) for faster testing', 'info');
        }, 500);
    </script>
</body>
</html>