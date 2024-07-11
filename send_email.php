<?php
require 'process/conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path if necessary

$recipientEmail = isset($_POST['email']) ? $_POST['email'] : '';
$recipientName = isset($_POST['name']) ? $_POST['name'] : '';

// Static details
$senderEmail = 'outlook_861FC590E62D9A25@outlook.com';
$senderName = 'Khenneth Puerto';
$smtpPassword = 'khenneth2000'; // Consider fetching this securely

// Dynamic email content
$emailSubject = 'Subject of the Email';
$emailBody = '<p>This is the HTML message body of the email.</p>';
$emailAltBody = 'This is the plain text version of the email.';

// Create an instance of PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = 0;                      // Enable verbose debug output (set to 2 for detailed debug)
    $mail->isSMTP();                           // Set mailer to use SMTP
    $mail->Host = 'smtp-mail.outlook.com';      // Outlook SMTP server
    $mail->SMTPAuth = true;                    // Enable SMTP authentication
    $mail->Username = $senderEmail;            // Your Outlook email address
    $mail->Password = $smtpPassword;           // Your Outlook email password
    $mail->SMTPSecure = 'tls';                 // Enable TLS encryption
    $mail->Port = 587;                         // TCP port to connect to

    // Recipients
    $mail->setFrom($senderEmail, $senderName);
    $mail->addAddress($recipientEmail, $recipientName); // Add a recipient

    // Attachments (if any)
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                      // Set email format to HTML
    $mail->Subject = $emailSubject;           // Subject of the email
    $mail->Body = $emailBody;                 // HTML message body
    $mail->AltBody = $emailAltBody;           // Plain text version for clients that don't support HTML

    // Send email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
