<?php
// Start session for CSRF protection
session_start();

// Set recipient email address
$recipient_email = "info@linguacreatives.com"; // Change to your email address

// Check if this is a test submission
$is_test_mode = isset($_POST['test_mode']) && $_POST['test_mode'] === 'true';

// Verify CSRF token except in test mode
if (!$is_test_mode && (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'Security validation failed.']);
    exit;
}

// Check honeypot field - if it's filled, it's likely a bot
if (!empty($_POST['website'])) {
    // Silent exit - don't give bots any feedback
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
    exit;
}

// Get and sanitize form data
$name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
$phone = filter_input(INPUT_POST, 'user_phone', FILTER_SANITIZE_STRING);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING) ?: 'New form submission from website';
$service = filter_input(INPUT_POST, 'service', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
$ip = $_SERVER['REMOTE_ADDR']; // Store IP for tracking spam

// Validate required fields
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

// Simple spam check - if message contains typical spam keywords
$spam_keywords = ['viagra', 'cialis', 'casino', 'lottery', 'prize', 'winner', 'free money'];
foreach ($spam_keywords as $keyword) {
    if (stripos($message, $keyword) !== false) {
        // Silent rejection
        echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
        exit;
    }
}

// Prepare email content
$email_content = "Name: " . htmlspecialchars($name) . "\n";
$email_content .= "Email: " . htmlspecialchars($email) . "\n";
$email_content .= "Phone: " . htmlspecialchars($phone) . "\n";
$email_content .= "Service: " . htmlspecialchars($service) . "\n";
$email_content .= "IP Address: " . $ip . "\n\n";
$email_content .= "Message:\n" . htmlspecialchars($message) . "\n";

// LOG FORM SUBMISSION TO FILE
$log_file = 'form_submissions.log';
$log_entry = date('Y-m-d H:i:s') . " | " . $ip . " | " . $email . " | " . $subject . "\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

// Simple mail function for now
// In a production environment, replace with PHPMailer or similar library
$headers = "From: " . $name . " <" . $email . ">\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$success = mail($recipient_email, $subject, $email_content, $headers);

// Return response
if ($success) {
    // Clear the CSRF token after successful submission
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Oops! Something went wrong. Please try again later.']);
}
?> 