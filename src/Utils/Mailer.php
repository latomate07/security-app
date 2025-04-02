<?php
namespace App\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Mail utility class
 * Handles email sending with PHPMailer
 */
class Mailer 
{
    private $mailer;
    
    public function __construct() 
    {
        $config = require_once __DIR__ . '/../../config/mail.php';
        
        $this->mailer = new PHPMailer(true);
        
        // Server settings
        $this->mailer->isSMTP();
        $this->mailer->Host = $config['host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config['username'];
        $this->mailer->Password = $config['password'];
        $this->mailer->SMTPSecure = $config['encryption'];
        $this->mailer->Port = $config['port'];
        
        // Default sender
        $this->mailer->setFrom($config['from_address'], $config['from_name']);
    }
    
    public function send($to, $subject, $body, $isHtml = true) 
    {
        try {
            $this->mailer->addAddress($to);
            $this->mailer->isHTML($isHtml);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            
            return $this->mailer->send();
        } catch (Exception $e) {
            throw new \Exception("Email could not be sent: " . $this->mailer->ErrorInfo);
        }
    }
}