<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 19/02/2018
 * Time: 12:52
 */

namespace Delco\Model\BSL;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use \Slim\Http\Request;
use \Slim\Http\Response;
//use \Exception;

use Delco\Util\DBLayer;
use Delco\Util\Utils;

use Delco\Util\StatusCode;
use Delco\Util\StatusMessage;

class SendMail
{
    public $host = "smtp.gmail.com";
    public $port = 587;
    public $userName = "desenv.pmro@gmail.com";
    public $password = "S15tem@5PMR0";
    public $emailFrom = "desenv.pmro@gmail.com";
    public $nameFrom = "Desenvolvimento PMRO";
    public $mail = null;


    public function __construct()
    {


    }

    public function to($subject = "asunto", $body = "corpo", $toEmail = "desenv.pmro@gmail.com")
    {
        // Passing `true` enables exceptions
        $this->mail = new PHPMailer(true);

        //Server settings
        //Enable SMTP debugging Enable verbose debug output
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $this->mail->SMTPDebug = 0;
        $this->mail->isSMTP();                   // Set mailer to use SMTP
        $this->mail->Host = $this->host;         // Specify main and backup SMTP servers
        $this->mail->SMTPAuth = true;            // Enable SMTP authentication
        $this->mail->Username = $this->userName; // SMTP username
        $this->mail->Password = $this->password; // SMTP password
        $this->mail->SMTPSecure = 'tls';         // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = 587;                 //587 é para TLS SSL é a 465

        //Recipients
        $this->mail->setFrom($this->emailFrom, $this->nameFrom);
        $this->mail->addAddress($toEmail, $this->nameFrom);     // Add a recipient
        /* $mail->addAddress('ellen@example.com');               // Name is optional
         $mail->addReplyTo('info@example.com', 'Information');
         $mail->addCC('cc@example.com');
         $mail->addBCC('bcc@example.com');*/

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $this->mail->isHTML(true);   // Set email format to HTML
        $this->mail->Subject = $subject; //asunto
        $this->mail->Body = $body;  //corpo
        $this->mail->AltBody = 'Seu cliente de e-mail não suporta HTML';

        // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        $isSender = $this->mail->send();

        /*if ($isSender)
        {
            $this->saveMail();
        }*/
    }

    public function saveMail()
    {
        //You can change 'Sent Mail' to any other folder or tag
        $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";
        //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
        $imapStream = imap_open($path, $this->mail->Username, $this->mail->Password);
        $result = imap_append($imapStream, $path, $this->mail->getSentMIMEMessage());
        imap_close($imapStream);
        return $result;
    }

}