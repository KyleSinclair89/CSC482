<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Limit message to 150 words
    $message = substr($message, 0, 150);

    // Email settings
    $to = "kylesinclair@adelphi.edu";
    $headers = "From: $name <$email>";
    $body = "Subject: $subject\n\n$message";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email. Please try again later.";
    }
}
?>
