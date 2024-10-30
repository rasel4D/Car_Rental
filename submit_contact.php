<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    // In a real-world scenario, you would typically:
    // 1. Validate the input
    // 2. Sanitize the input to prevent XSS attacks
    // 3. Send an email or store the message in a database
    
    // For this example, we'll just simulate a successful submission
    $response = array(
        "success" => true,
        "message" => "Thank you for your message. We'll get back to you soon!"
    );
    
    // Redirect back to the contact page with a success message
    header("Location: contact.html?status=success");
    exit();
} else {
    // If someone tries to access this file directly, redirect them to the contact page
    header("Location: contact.html");
    exit();
}
?>