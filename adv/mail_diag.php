<?php
// TEMPORARY diagnostic endpoint. Delete after use.
$secret = 'lm-diag-9f3c2a7b';
if (!isset($_GET['k']) || $_GET['k'] !== $secret) {
    http_response_code(404);
    exit('Not found');
}
header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/includes/mail_util.php';

echo "=== CONFIG RESOLUTION ===\n";
echo "getenv DB_HOST present        : " . (getenv('DB_HOST') !== false ? 'yes' : 'no') . "\n";
echo "getenv MAIL_PASSWORD present  : " . (getenv('MAIL_PASSWORD') !== false ? 'yes' : 'no') . "\n";
echo "getenv MAIL_USERNAME present  : " . (getenv('MAIL_USERNAME') !== false ? 'yes' : 'no') . "\n";
$envLocal = __DIR__ . '/.env.local';
echo ".env.local exists in container: " . (is_file($envLocal) ? 'yes' : 'no') . "\n";
echo "MAIL_HOST                     : " . mail_env('MAIL_HOST', '(none)') . "\n";
echo "MAIL_PORT                     : " . mail_env('MAIL_PORT', '(none)') . "\n";
echo "MAIL_ENCRYPTION               : " . mail_env('MAIL_ENCRYPTION', '(none)') . "\n";
echo "MAIL_USERNAME                 : " . mail_env('MAIL_USERNAME', '(none)') . "\n";
$pw = mail_env('MAIL_PASSWORD', '(none)');
echo "MAIL_PASSWORD length          : " . strlen($pw) . "\n";
echo "MAIL_PASSWORD is default      : " . ($pw === 'ubzdarybccahlywm' ? 'YES (fallback!)' : 'no') . "\n";

function lm_try_smtp($secure, $port) {
    $mail = new PHPMailer();
    $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
    $mail->isSMTP();
    $mail->SMTPDebug = 1;
    $mail->Debugoutput = 'echo';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = $secure;
    $mail->Host = mail_env('MAIL_HOST', 'smtp.gmail.com');
    $mail->Port = $port;
    $mail->Username = mail_env('MAIL_USERNAME', '');
    $mail->Password = mail_env('MAIL_PASSWORD', '');
    $mail->Timeout = 15;
    $mail->setFrom(mail_env('MAIL_FROM', $mail->Username), 'diag');
    $mail->addAddress(mail_env('MAIL_FROM', $mail->Username));
    $mail->Subject = 'diag';
    $mail->Body = 'diag';
    $ok = $mail->Send();
    return $ok ? 'OK' : ('FAIL -> ' . $mail->ErrorInfo);
}

echo "\n=== SMTP TEST ssl:465 ===\n";
echo "RESULT: " . lm_try_smtp('ssl', 465) . "\n";

echo "\n=== SMTP TEST tls:587 ===\n";
echo "RESULT: " . lm_try_smtp('tls', 587) . "\n";
