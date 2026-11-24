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

/**
 * Resolve a mail setting from the process environment first, then from a
 * .env.local file (web root or project root), matching dbCon.php behaviour.
 */
function mail_env($key, $default = '') {
    $value = getenv($key);
    if ($value !== false && $value !== '') {
        return $value;
    }
    static $env = null;
    if ($env === null) {
        $env = array();
        $candidates = array(
            __DIR__ . '/../.env.local',
            __DIR__ . '/../../.env.local',
        );
        foreach ($candidates as $path) {
            if (is_file($path) && is_readable($path)) {
                $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if ($lines) {
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                            continue;
                        }
                        list($k, $v) = explode('=', $line, 2);
                        $env[trim($k)] = trim($v);
                    }
                }
                break;
            }
        }
    }
    return (isset($env[$key]) && $env[$key] !== '') ? $env[$key] : $default;
}

function send_notification_email($to, $subject, $message) {
    // SMTP settings are read from the environment so credentials can be
    // rotated without editing code (set them in .env.local / Vercel dashboard).
    $host     = mail_env('MAIL_HOST', 'smtp.gmail.com');
    $port     = (int) mail_env('MAIL_PORT', 465);
    $username = mail_env('MAIL_USERNAME', 'lawyermatch.ai@gmail.com');
    $password = mail_env('MAIL_PASSWORD', 'ubzdarybccahlywm');
    $from     = mail_env('MAIL_FROM', $username);
    $fromName = mail_env('MAIL_FROM_NAME', 'Lawyer Match AI');
    $secure   = mail_env('MAIL_ENCRYPTION', 'ssl');

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
    $mail->SMTPSecure = $secure;
    $mail->Host = $host;
    $mail->Port = $port;
    $mail->IsHTML(true);

    $mail->Username = $username;
    $mail->Password = $password;
    $mail->setFrom($from, $fromName);

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
