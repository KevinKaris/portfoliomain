<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    extract($_POST);
    $current_year = date('Y');
    if($name == ''){
        echo 'n';
    }
    else if($email == ''){
        echo 'e';
    }
    else if($subject == ''){
        echo 's';
    }
    else if($message == ''){
        echo 'm';
    }
    else{
        $email_body = '<html>
        <h4>'.$subject.'</h4>
        <p>'.$message.'</p>
        </html>';

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kevinkarish983@gmail.com';
        $mail->Password = 'lvbjyescnujgntnr';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->isHTML(true);
        $mail->setFrom($email, $name);
        $mail->addAddress('kevinkarish983@gmail.com');
        $mail->addReplyTo($email, $name);
        $mail->Subject = ($subject);
        $mail->Body = $email_body;
        
        if($mail->send()){
            echo '1';
        }
        else{
            echo '0';
        }
    }
}
