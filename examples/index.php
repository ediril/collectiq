<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CollectIQ - Component Examples</title>
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
        h1 { 
            text-align: center; 
            margin-bottom: 3rem;
            font-size: 3rem;
            background: linear-gradient(135deg, #ff6b6b, #00d4aa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        h2 {
            color: #fff;
            margin-bottom: 1rem;
        }
        .intro {
            text-align: center;
            margin-bottom: 4rem;
            padding: 2rem;
            background: #111;
            border-radius: 12px;
            border: 1px solid #333;
        }
        .intro p {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        .server-info {
            background: #1a1a1a;
            border-left: 4px solid #ff6b6b;
            padding: 1.5rem;
            margin-bottom: 3rem;
            border-radius: 4px;
        }
        .server-info h3 {
            color: #ff6b6b;
            margin-top: 0;
        }
        .server-info code {
            background: #0a0a0a;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #00d4aa;
            display: inline-block;
            margin: 0.5rem 0;
        }
        .components-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin: 3rem 0;
        }
        .component-card {
            background: #111;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 2rem;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .component-card:hover {
            border-color: #555;
            background: #1a1a1a;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
        .component-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
            text-align: center;
        }
        .waitlist-card .component-icon {
            color: #00d4aa;
        }
        .ratelimit-card .component-icon {
            color: #ff6b6b;
        }
        .component-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
        }
        .component-description {
            margin-bottom: 1.5rem;
            color: #ccc;
        }
        .component-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .component-features li {
            padding: 0.3rem 0;
            color: #aaa;
            font-size: 0.9rem;
        }
        .component-features li::before {
            content: "✓ ";
            color: inherit;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        .waitlist-card .component-features li::before {
            color: #00d4aa;
        }
        .ratelimit-card .component-features li::before {
            color: #ff6b6b;
        }
        .cta {
            text-align: center;
            margin-top: 1.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .waitlist-card .cta {
            background: rgba(0, 212, 170, 0.2);
            color: #00d4aa;
            border: 1px solid #00d4aa;
        }
        .ratelimit-card .cta {
            background: rgba(255, 107, 107, 0.2);
            color: #ff6b6b;
            border: 1px solid #ff6b6b;
        }
        .component-card:hover .cta {
            background: var(--cta-color);
            color: #000;
        }
        .waitlist-card:hover .cta {
            background: #00d4aa;
        }
        .ratelimit-card:hover .cta {
            background: #ff6b6b;
        }
        .footer {
            text-align: center;
            margin-top: 4rem;
            padding: 2rem;
            border-top: 1px solid #333;
            color: #666;
        }
        @media (max-width: 768px) {
            .components-grid {
                grid-template-columns: 1fr;
            }
            h1 {
                font-size: 2rem;
            }
            body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>CollectIQ</h1>
        
        <div class="intro">
            <p><strong>Email collection and rate limiting components for modern web applications</strong></p>
            <p>Choose a component below to see interactive examples and learn how to integrate them into your project.</p>
        </div>
        
        <div class="components-grid">
            <a href="waitlist.php" class="component-card waitlist-card">
                <span class="component-icon">📧</span>
                <h2 class="component-title">Waitlist Component</h2>
                <p class="component-description">
                    Complete email collection solution with built-in rate limiting, spam protection, and beautiful animations.
                </p>
                <ul class="component-features">
                    <li>Email validation & collection</li>
                    <li>Built-in rate limiting</li>
                    <li>Honeypot spam protection</li>
                    <li>Responsive design</li>
                    <li>Smooth animations</li>
                    <li>SQLite database storage</li>
                </ul>
                <div class="cta">View Waitlist Examples →</div>
            </a>
            
            <a href="ratelimit.php" class="component-card ratelimit-card">
                <span class="component-icon">🛡️</span>
                <h2 class="component-title">RateLimit Component</h2>
                <p class="component-description">
                    Standalone rate limiting component that can be used independently to protect any endpoint from abuse.
                </p>
                <ul class="component-features">
                    <li>IP-based rate limiting</li>
                    <li>Configurable time windows</li>
                    <li>Automatic cleanup</li>
                    <li>Proxy support</li>
                    <li>Manual IP testing</li>
                    <li>Remaining time calculation</li>
                </ul>
                <div class="cta">View RateLimit Examples →</div>
            </a>
        </div>
        
        <div class="footer">
            <p>Both components work independently or together • SQLite • No dependencies • MIT License</p>
        </div>
    </div>
</body>
</html>