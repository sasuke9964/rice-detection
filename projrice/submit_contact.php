<?php
// Include the database connection
require_once 'includes/config.php';

$response = [
    'success' => false,
    'message' => 'An error occurred while processing your request.'
];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $response['message'] = 'All fields are required.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
    } else {
        // In a real application, you would:
        // 1. Save the message to a database
        // 2. Send an email notification
        // 3. Set up more robust spam protection
        
        // For demo purposes, we'll just show a success message
        $response['success'] = true;
        $response['message'] = 'Thank you! Your message has been sent successfully.';
        
        // Optional: Create a table to store contact messages
        // createContactTable($conn);
        
        // Optional: Save message to database
        // saveContactMessage($conn, $name, $email, $message);
        
        // Optional: Send email notification
        // sendEmailNotification($name, $email, $message);
    }
}

// If it's an AJAX request, return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// If it's a regular form submission, redirect with a message
$redirect_url = 'about.php';
if ($response['success']) {
    $redirect_url .= '?success=1&message=' . urlencode($response['message']);
} else {
    $redirect_url .= '?success=0&message=' . urlencode($response['message']);
}

header('Location: ' . $redirect_url);
exit;

/**
 * Optional: Create a table to store contact messages
 */
function createContactTable($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
    $conn->query($sql);
}

/**
 * Optional: Save contact message to database
 */
function saveContactMessage($conn, $name, $email, $message) {
    $sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $name, $email, $message);
    $stmt->execute();
}

/**
 * Optional: Send email notification
 */
function sendEmailNotification($name, $email, $message) {
    $to = 'info@riceanalyzer.com'; // Change to your email
    $subject = 'New Contact Form Submission';
    
    $email_body = "Name: $name\n";
    $email_body .= "Email: $email\n\n";
    $email_body .= "Message:\n$message";
    
    $headers = "From: $email\r\n";
    
    mail($to, $subject, $email_body, $headers);
}
?> 