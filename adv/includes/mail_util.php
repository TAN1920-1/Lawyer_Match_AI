<?php
require_once __DIR__ . '/../PHPMailer-master/class.phpmailer.php';
require_once __DIR__ . '/../PHPMailer-master/class.smtp.php';

/**
 * Base URL of the running site, so links inside emails work on any domain.
 * Set SITE_URL in the environment to override the auto-detected value.
 */
function site_base_url() {
    $override = getenv('SITE_URL');
    if ($override !== false && $override !== '') {
        return rtrim($override, '/') . '/';
    }
    $secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $dir = isset($_SERVER['SCRIPT_NAME']) ? dirname(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'])) : '/';
    $dir = rtrim($dir, '/');
    return ($secure ? 'https' : 'http') . '://' . $host . $dir . '/';
}

function send_notification_email($to, $subject, $message) {
    $mail = new PHPMailer();
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->IsHTML(true);
    
    // Using valid Gmail App Password provided by user
    $mail->Username = "lawyermatch.ai@gmail.com";
    $mail->Password = "ubzdarybccahlywm";
    $mail->setFrom('lawyermatch.ai@gmail.com', 'Lawyer Match AI');
    
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AddAddress($to);
    
    if(!$mail->Send()) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    }
    return true;
}
?>
