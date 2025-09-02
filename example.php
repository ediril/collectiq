<?php require_once 'component/WaitlistComponent.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waitlist Component Example</title>
    <link rel="stylesheet" href="component/assets/waitlist.css?v=<?php echo time(); ?>">
    <style>
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
        
        /* Custom styling for newsletter form */
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
        
        /* Custom styling for beta access form */
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
        
        #beta-access .collectiq-waitlist-form input {
            font-size: 1.1rem;
            padding: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Waitlist Component Examples</h1>
        
        <div class="section">
            <h2>Basic Usage</h2>
            <?php 
            $waitlist = new WaitlistComponent();
            echo $waitlist->renderForm(); 
            ?>
        </div>
        
        <div class="section">
            <h2>Custom Newsletter Form</h2>
            <p>Golden gradient button with shimmer effect disabled</p>
            <?php echo $waitlist->renderForm('newsletter-signup', 'Subscribe to our newsletter...', 'Subscribe Now'); ?>
        </div>
        
        <div class="section">
            <h2>Beta Access Form</h2>
            <p>Teal theme with custom input styling and uppercase button text</p>
            <?php echo $waitlist->renderForm('beta-access', 'Get early access...', 'Join Beta'); ?>
        </div>
    </div>
    
    <script src="component/assets/waitlist.js"></script>
    <script>
        // Initialize custom forms
        new WaitlistHandler('newsletter-signup');
        new WaitlistHandler('beta-access');
    </script>
</body>
</html>