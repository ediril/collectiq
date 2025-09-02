<?php require_once 'component/WaitlistComponent.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waitlist Component Example</title>
    <?php 
    $waitlist = new WaitlistComponent();
    echo $waitlist->renderStyles(); 
    ?>
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
        
        #beta-access .collectiq-waitlist-form input {
            font-size: 1.1rem;
            padding: 1.2rem;
        }
        
        /* Placeholder text styling examples */
        /* Note: Default placeholder is light white (rgba(255, 255, 255, 0.4)) for dark backgrounds */
        
        /* For light backgrounds, use darker placeholders */
        .light-background .collectiq-waitlist-form input::placeholder {
            color: rgba(0, 0, 0, 0.6);
        }
        
        /* Custom placeholder colors for each form (using container IDs) */
        #newsletter-signup .collectiq-waitlist-form input::placeholder {
            color: rgba(26, 26, 26, 0.7); /* Darker for golden theme */
        }
        
        #beta-access .collectiq-waitlist-form input::placeholder {
            color: rgba(255, 255, 255, 0.8); /* Brighter for teal theme */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Waitlist Component Examples</h1>
        
        <div class="section">
            <h2>Basic Usage</h2>
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
    </div>
    
    <?php echo $waitlist->renderScripts(); ?>
    <!-- All forms are automatically initialized by the script -->
</body>
</html>