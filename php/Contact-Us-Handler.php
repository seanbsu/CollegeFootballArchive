<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $subject = $_POST["subject"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $to = "college.fb.archive@gmail.com";
    $subject = "Contact Form Submission";
    $messageBody = "Name: $name\nSubject: $subject\nEmail: $email\nMessage: $message";

    // Use mail() function to send the email
    mail($to, $subject, $messageBody);


    header("Location: index.php?success=true");
    exit();
} else {
    header("Location: Contact-Us.php");
    exit();
}