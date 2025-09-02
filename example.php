<?php require_once 'component/WaitlistComponent.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waitlist Component Example</title>
    <link rel="stylesheet" href="component/assets/waitlist.css">
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
            <?php echo $waitlist->renderForm('newsletter-signup', 'Subscribe to our newsletter...', 'Subscribe Now'); ?>
        </div>
        
        <div class="section">
            <h2>Beta Access Form</h2>
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