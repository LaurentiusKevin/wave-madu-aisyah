<?php
$nama = 'Agung';
$html = file_get_contents('email-template.html');
$html = str_replace('{ nama }',$nama,$html);
/* Namespace alias. */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'plugin/PHPMailer-master/src/Exception.php';
require 'plugin/PHPMailer-master/src/PHPMailer.php';
require 'plugin/PHPMailer-master/src/SMTP.php';

/* If you installed PHPMailer without Composer do this instead: */
/*
require 'C:\PHPMailer\src\Exception.php';
require 'C:\PHPMailer\src\PHPMailer.php';
require 'C:\PHPMailer\src\SMTP.php';
*/

/* Create a new PHPMailer object. Passing TRUE to the constructor enables exceptions. */
$mail = new PHPMailer(TRUE);

/* Open the try/catch block. */
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    /* Username (email address). */
    $mail->Username = 'laurentiuskevin44@gmail.com';

    /* Google account password. */
    $mail->Password = 'epndbraguafjmdkj';

    /* Set the mail sender. */
    $mail->setFrom('laurentiuskevin44@gmail.com', 'Laurentius Kevin');
// craz.devteam@gmail.com
// 562015018@student.uksw.edu
    /* Add a recipient. */
    $mail->addAddress('craz.devteam@gmail.com', 'Emperor');

    /* Set the subject. */
    $mail->Subject = date('H:i:s - d/m/Y');

    /* Set the mail message body. */
    $mail->Body = $html;

    $mail->isHTML(true);

    /* Finally send the mail. */
    $mail->send();
}
catch (Exception $e)
{
    /* PHPMailer exception. */
    echo $e->errorMessage();
}
catch (\Exception $e)
{
    /* PHP exception (note the backslash to select the global namespace Exception class). */
    echo $e->getMessage();
}