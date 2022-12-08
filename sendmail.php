<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';
require "utils.php";
$article = getArticle($_GET["id"]);
$content = "<h1>" . $article[0]["title"] . "</h1>";
if (isset($article[0]["image"])) {
    $content .= "<img src='" . $article[0]["image"] ."'/>";
}
$content .=  $article[0]["text"];
$stylesheet = file_get_contents('article.css');

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf();

// Write some HTML code:
$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($content);

// Output a PDF file directly to the browser
$path = "export/".trim($article[0]["title"]).'.pdf';
$mpdf->Output($path, \Mpdf\Output\Destination::FILE);

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'localhost';                     //Set the SMTP server to send through
    $mail->SMTPAuth = false;
    $mail->SMTPAutoTLS = false; 
    $mail->Port = 25;
    $mail->CharSet = 'UTF-8';

    //Recipients
    $mail->setFrom('twenews@mail.cz', 'TWE News');
    $mail->addAddress('joe@mama.net', 'Joe Mama');     //Add a recipient


    //Content
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->addAttachment($path);

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
unlink($path);
echo "<script>history.back()</script>";

