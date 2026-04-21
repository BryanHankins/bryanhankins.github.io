<?php
// contact.php — Contact form email handler for bryanhankinsdev@gmail.com

header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Sanitize inputs
$name    = trim(strip_tags($_POST['name']    ?? ''));
$email   = trim(strip_tags($_POST['email']   ?? ''));
$phone   = trim(strip_tags($_POST['phone']   ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

// Basic validation
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Name, email, and message are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

// Destination
$to      = 'bryanhankinsdev@gmail.com';
$subject = "Portfolio Contact from {$name}";

// Email body
$body  = "You have a new message from your portfolio contact form.\n\n";
$body .= "-------------------------------------------\n";
$body .= "Name:    {$name}\n";
$body .= "Email:   {$email}\n";
if (!empty($phone)) {
    $body .= "Phone:   {$phone}\n";
}
$body .= "-------------------------------------------\n\n";
$body .= "Message:\n{$message}\n\n";
$body .= "-------------------------------------------\n";
$body .= "Sent from: bryanhankinsdev.com\n";

// Headers — use sender's email as reply-to so you can reply directly
$headers  = "From: Portfolio Contact Form <no-reply@bryanhankinsdev.com>\r\n";
$headers .= "Reply-To: {$name} <{$email}>\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send
$sent = mail($to, $subject, $body, $headers);

if ($sent) {
    echo json_encode(['success' => true, 'message' => 'Your message was sent successfully!']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sorry, the message could not be sent. Please try emailing directly.']);
}
?>
