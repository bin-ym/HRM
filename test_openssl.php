<?php
require_once('vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 2; // Enable debug output
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'binyam.tagel@gmail.com';
    $mail->Password = 'mxab gkzy uydt taoo'; // Your App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('binyam.tagel@gmail.com', 'Debark HRM');
    $mail->addAddress('betesh561@gmail.com'); // Replace with a real recipient email
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from Debark HRM';
    $mail->Body = 'This is a YM from Debark HRM!';
    $mail->send();
    echo "Test email sent successfully!";
} catch (Exception $e) {
    echo "Email failed: " . $mail->ErrorInfo;
}
?>