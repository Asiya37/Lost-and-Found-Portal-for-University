<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

<<<<<<< HEAD
    
=======
>>>>>>> 57c9e9ed2c8f34eb19ca2860a5a5eeed9fe9ef2b
    if (!empty($name) && !empty($email) && !empty($message)) {


        $to = "asiyaakram45@gmail.com";  
        $subject = "New Contact Us Message from $name";

        $body = "Name: $name\n";
        $body .= "Email: $email\n\n";
        $body .= "Message:\n$message\n";

        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";

        if (mail($to, $subject, $body, $headers)) {
            echo "<script>alert('✅ Message sent successfully!'); window.location='home.html';</script>";
        } else {
            echo "<script>alert('⚠️ Failed to send message. Please try again later.'); window.location='home.html';</script>";
        }

    } else {
        echo "<script>alert('⚠️ Please fill all fields.'); window.location='home.html';</script>";
    }
} else {
    header("Location: home.html");
    exit;
}
?>
