<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // You can add additional validation and sanitation here

    // Send an email (example)
    $to = 'gomo.cpsu@gmail.com'; // Replace with your email address
    $subject = 'New Contact Form Submission';
    $headers = 'From: ' . $email;

    // Construct the email body
    $body = "Name: $name\n";
    $body .= "Email: $email\n\n";
    $body .= "Message:\n$message";

    // Send the email
    mail($to, $subject, $body, $headers);

    // Redirect back to the contact page with a success message
    header('Location: about.php?status=success');
    exit();
} else {
    // If the form is not submitted, redirect to the contact page
    header('Location: about.html');
    exit();
}
?>
