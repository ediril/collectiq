<?php require_once '../components/waitlist/WaitlistComponent.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waitlist Component Example</title>
    <?php 
    $waitlist = new WaitlistComponent();
    ?>
    <!-- Inline waitlist component CSS -->
    <style>
        /* Waitlist Component Styles */
        .collectiq-waitlist-form {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 0 auto;
            max-width: 600px;
            opacity: 1;
        }

        .collectiq-input-container {
            flex: 1;
            position: relative;
            border: 1px solid #6366f1;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
            min-width: 280px;
        }

        .collectiq-input-container.collectiq-thank-you {
            padding: 1rem 0;
            margin: 0;
            text-align: center;
        }

        .collectiq-input-container::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.8), rgba(168, 85, 247, 0.4));
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: xor;
            -webkit-mask-composite: xor;
            opacity: 0.3;
        }

        .collectiq-input {
            position: relative;
            width: 100%;
            padding: 1rem;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1rem;
            border-radius: 0.5rem;
            z-index: 1;
        }

        .collectiq-input:focus {
            outline: none;
        }

        .collectiq-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .collectiq-submit-btn {
            position: relative;
            min-width: 160px;
            max-width: 364px;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border: none;
            border-radius: 0.5rem;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            white-space: nowrap;
        }

        .collectiq-submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .collectiq-submit-btn:active {
            transform: translateY(1px);
        }

        .collectiq-submit-btn .collectiq-shimmer-container {
            position: absolute;
            inset: 0;
            overflow: visible;
            border-radius: inherit;
            filter: blur(2px);
        }

        .collectiq-submit-btn .collectiq-shimmer {
            position: absolute;
            inset: 0;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: collectiq-shimmerSlide 3s ease-in-out infinite alternate;
        }

        .collectiq-submit-btn .collectiq-shimmer::before {
            content: '';
            position: absolute;
            inset: -100%;
            background: conic-gradient(from 0deg at 50% 50%, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: collectiq-spinAround 6s infinite linear;
        }

        .collectiq-submit-btn .collectiq-highlight {
            position: absolute;
            inset: 0;
            border-radius: 0.5rem;
            transition: all 0.3s ease-in-out;
        }

        /* Default highlight effects */
        .collectiq-submit-btn:hover .collectiq-highlight {
            box-shadow: inset 0 -6px 10px rgba(255, 255, 255, 0.25);
        }

        .collectiq-submit-btn:active .collectiq-highlight {
            box-shadow: inset 0 -10px 10px rgba(255, 255, 255, 0.25);
        }

        .collectiq-submit-btn .collectiq-backdrop {
            position: absolute;
            inset: 0.05em;
            background-color: rgb(1, 4, 65);
            border-radius: inherit;
            z-index: -1;
        }

        @keyframes collectiq-shimmerSlide {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes collectiq-spinAround {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes collectiq-fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .collectiq-waitlist-form {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .collectiq-input-container {
                min-width: auto;
            }
            
            .collectiq-submit-btn {
                min-width: auto;
                max-width: none;
            }
        }

        /* Example Page Styles */
        body { 
            background: #000; 
            color: #fff; 
            font-family: 'Inter', Arial, sans-serif; 
            padding: 2rem; 
            min-height: 100vh;
        }
        .container { 
            max-width: 800px; 
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
        }
        .info {
            background: #1a1a1a;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #00d4aa;
        }
        .info h3 {
            margin-top: 0;
            color: #00d4aa;
        }
        
        /* Custom styling for newsletter form (using container ID) */
        #newsletter-signup .collectiq-submit-btn {
            background: linear-gradient(135deg, #ffd700, #ffb347);
            color: #1a1a1a;
            border-radius: 25px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        }
        
        #newsletter-signup .collectiq-submit-btn:hover {
            background: linear-gradient(135deg, #ffb347, #ff6b35);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.5);
        }
        
        #newsletter-signup .collectiq-submit-btn:active {
            transform: translateY(0px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.6);
        }
        
        /* Disable shimmer and other effects for newsletter form */
        #newsletter-signup .collectiq-submit-btn .collectiq-shimmer-container,
        #newsletter-signup .collectiq-submit-btn .collectiq-highlight,
        #newsletter-signup .collectiq-submit-btn .collectiq-backdrop {
            display: none;
        }
        
        /* Custom styling for beta access form (using container ID) */
        #beta-access .collectiq-submit-btn {
            background: linear-gradient(135deg, #00d4aa, #00a086);
            border-radius: 8px;
            padding: 1.2rem 2.5rem;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        #beta-access .collectiq-submit-btn:hover {
            background: linear-gradient(135deg, #00a086, #008566);
            transform: translateY(-1px);
        }
        
        #beta-access .collectiq-submit-btn:active {
            background: linear-gradient(135deg, #008566, #006b4f);
            transform: translateY(1px);
        }
        
        /* Disable effects for beta form */
        #beta-access .collectiq-submit-btn .collectiq-highlight,
        #beta-access .collectiq-submit-btn .collectiq-shimmer-container,
        #beta-access .collectiq-submit-btn .collectiq-backdrop {
            display: none;
        }
        
        #beta-access .collectiq-input-container {
            border: 2px solid #00d4aa;
            background: rgba(0, 212, 170, 0.05);
        }
        
        #beta-access .collectiq-input {
            font-size: 1.1rem;
            padding: 1.2rem;
        }
        
        /* Custom placeholder colors for each form (using container IDs) */
        #newsletter-signup .collectiq-input::placeholder {
            color: rgba(26, 26, 26, 0.7); /* Darker for golden theme */
        }
        
        #beta-access .collectiq-input::placeholder {
            color: rgba(255, 255, 255, 0.8); /* Brighter for teal theme */
        }

        .back-link {
            text-align: center;
            margin-bottom: 2rem;
        }
        .back-link a {
            color: #00d4aa;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid #00d4aa;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .back-link a:hover {
            background: #00d4aa;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-link">
            <a href="example.php">← Back to Main Examples</a>
        </div>

        <h1>Waitlist Component Example</h1>
        
        <div class="info">
            <h3>About This Component</h3>
            <p>The <strong>WaitlistComponent</strong> is a complete email collection solution that includes:</p>
            <ul>
                <li>Email validation (client and server-side)</li>
                <li>Built-in rate limiting (prevents spam)</li>
                <li>Honeypot spam protection</li>
                <li>SQLite database storage</li>
                <li>Responsive design with animations</li>
            </ul>
            <p><strong>Rate Limit:</strong> 1 request per 60 seconds per IP address (configurable)</p>
        </div>
        
        <div class="section">
            <h2>Basic Usage</h2>
            <p>Default styling with shimmer effects</p>
            <?php echo $waitlist->renderForm(); ?>
        </div>
        
        <div class="section" id="newsletter-signup">
            <h2>Custom Newsletter Form</h2>
            <p>Golden gradient button with shimmer effect disabled</p>
            <?php echo $waitlist->renderForm('Subscribe to our newsletter...', 'Subscribe Now'); ?>
        </div>
        
        <div class="section" id="beta-access">
            <h2>Beta Access Form</h2>
            <p>Teal theme with custom input styling and uppercase button text</p>
            <?php echo $waitlist->renderForm('Get early access...', 'Join Beta'); ?>
        </div>

        <div class="info">
            <h3>Usage Code</h3>
            <pre><code>&lt;?php require_once 'collectiq/components/waitlist/WaitlistComponent.php'; ?&gt;

&lt;?php 
$waitlist = new WaitlistComponent();

// Custom rate limit (2 minutes)
$waitlist->setRateLimitWindow(120);

// Render with custom text
echo $waitlist->renderForm('Enter your email...', 'Join Waitlist');
?&gt;</code></pre>
        </div>
    </div>
    
    <!-- No JavaScript needed - forms submit directly to endpoint -->
</body>
</html>