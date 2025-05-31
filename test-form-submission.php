<?php
// This is a test script to simulate a form submission
// IMPORTANT: Delete this file after testing!

// Start session to get CSRF token
session_start();

// Generate a CSRF token if none exists
if(!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to show results
function showResult($success, $message, $details = null) {
    echo "<div style='padding: 15px; margin: 10px 0; border-radius: 5px; " . 
         ($success ? "background-color: #d4edda; color: #155724;" : "background-color: #f8d7da; color: #721c24;") . 
         "'>";
    echo "<h3>" . ($success ? "✅ Success" : "❌ Error") . "</h3>";
    echo "<p>$message</p>";
    if ($details) {
        echo "<pre>" . print_r($details, true) . "</pre>";
    }
    echo "</div>";
}

// Check if form is submitted
if(isset($_POST['submit'])) {
    // Get the form data
    $data = [
        'csrf_token' => $_SESSION['csrf_token'],
        'user_name' => $_POST['user_name'] ?? 'Test User',
        'user_email' => $_POST['user_email'] ?? 'test@example.com',
        'user_phone' => $_POST['user_phone'] ?? '123-456-7890',
        'subject' => $_POST['subject'] ?? 'Test Submission',
        'service' => $_POST['service'] ?? 'corporate-training',
        'message' => $_POST['message'] ?? 'This is a test message sent from the test script.',
        'test_mode' => 'true'
    ];
    
    // Determine if we're using HTTPS
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    
    // Get the absolute path to the process-form.php file
    $current_dir = dirname($_SERVER['SCRIPT_NAME']);
    $form_url = $protocol . $_SERVER['HTTP_HOST'] . rtrim($current_dir, '/') . '/process-form.php';
    
    // Show the URL being used
    echo "<div class='info-message'>Submitting to: $form_url</div>";
    
    // Create a new cURL resource
    $ch = curl_init($form_url);
    
    // Setup request to send json via POST
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Important: Follow redirects
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    
    // Execute the request
    $response = curl_exec($ch);
    $err = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Close curl resource to free up system resources
    curl_close($ch);
    
    // Handle the result
    if ($err) {
        showResult(false, "cURL Error: " . $err);
    } else {
        $result = json_decode($response, true);
        
        if($httpCode == 200 && isset($result['success'])) {
            showResult($result['success'], $result['message'], $data);
        } else {
            showResult(false, "Server returned an unexpected response", [
                'HTTP Code' => $httpCode,
                'Response' => $response
            ]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        h1 {
            color: #1a73e8;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #1a73e8;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0d62c9;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        
        .info-message {
            background-color: #e2f0fd;
            color: #0c5460;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-family: monospace;
        }
        
        pre {
            background: #f8f8f8;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <h1>Form Submission Test</h1>
    
    <div class="warning">
        <strong>Warning:</strong> This page is for testing purposes only. Delete this file after testing is complete.
    </div>
    
    <p>This page simulates a form submission to test if your form processing works correctly.</p>
    
    <div class="container">
        <h2>Testing Options</h2>
        <ul>
            <li>
                <strong>cURL Test:</strong> Uses PHP cURL to send form data in the background (useful for server-side debugging)
            </li>
            <li>
                <strong>Direct Post:</strong> Submits directly to the form handler (use if cURL doesn't work)
            </li>
        </ul>
        
        <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #ddd;">
            <h3>Option 1: cURL Test</h3>
            <form method="post">
                <label for="user_name">Name:</label>
                <input type="text" id="user_name" name="user_name" value="Test User">
                
                <label for="user_email">Email:</label>
                <input type="email" id="user_email" name="user_email" value="test@example.com">
                
                <label for="user_phone">Phone:</label>
                <input type="text" id="user_phone" name="user_phone" value="123-456-7890">
                
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" value="Test Submission">
                
                <label for="service">Service:</label>
                <select id="service" name="service">
                    <option value="language-services">Language Services</option>
                    <option value="corporate-training" selected>Corporate Training</option>
                    <option value="pharma-export">Pharma Export Consultancy</option>
                    <option value="corporate-consulting">Corporate Consulting</option>
                    <option value="other">Other</option>
                </select>
                
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5">This is a test message sent from the test script.</textarea>
                
                <button type="submit" name="submit">Test with cURL</button>
            </form>
        </div>
        
        <div>
            <h3>Option 2: Direct Form Submission</h3>
            <form method="post" action="process-form.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="test_mode" value="true">
                
                <label for="direct_name">Name:</label>
                <input type="text" id="direct_name" name="user_name" value="Test User">
                
                <label for="direct_email">Email:</label>
                <input type="email" id="direct_email" name="user_email" value="test@example.com">
                
                <label for="direct_phone">Phone:</label>
                <input type="text" id="direct_phone" name="user_phone" value="123-456-7890">
                
                <label for="direct_subject">Subject:</label>
                <input type="text" id="direct_subject" name="subject" value="Test Submission">
                
                <label for="direct_service">Service:</label>
                <select id="direct_service" name="service">
                    <option value="language-services">Language Services</option>
                    <option value="corporate-training" selected>Corporate Training</option>
                    <option value="pharma-export">Pharma Export Consultancy</option>
                    <option value="corporate-consulting">Corporate Consulting</option>
                    <option value="other">Other</option>
                </select>
                
                <label for="direct_message">Message:</label>
                <textarea id="direct_message" name="message" rows="5">This is a test message sent from the test script.</textarea>
                
                <button type="submit">Direct Submit</button>
            </form>
        </div>
    </div>
</body>
</html> 