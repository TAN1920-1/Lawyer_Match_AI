<?php
// TEMPORARY diagnostic endpoint. Delete after use.
// Credentials are supplied at runtime via POST so they are never committed.
$secret = 'lm-diag-9f3c2a7b';
if (!isset($_GET['k']) || $_GET['k'] !== $secret) {
    http_response_code(404);
    exit('Not found');
}
header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/includes/mail_util.php';

$user = isset($_POST['user']) ? $_POST['user'] : '';
$pass = isset($_POST['pass']) ? $_POST['pass'] : '';
$to   = isset($_POST['to']) && $_POST['to'] !== '' ? $_POST['to'] : $user;

if ($user === '' || $pass === '') {
    echo "Send user/pass/to as POST fields.\n";
    exit;
}

function lm_try_smtp($secure, $port, $user, $pass, $to) {
    $m = new PHPMailer();
    $m->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
    $m->isSMTP();
    $m->SMTPDebug = 1;
    $m->Debugoutput = 'echo';
    $m->SMTPAuth = true;
    $m->SMTPSecure = $secure;
    $m->Host = 'smtp.gmail.com';
    $m->Port = $port;
    $m->Username = $user;
    $m->Password = $pass;
    $m->Timeout = 15;
    $m->setFrom($user, 'Lawyer Match AI');
    $m->addAddress($to);
    $m->isHTML(true);
    $m->Subject = 'Lawyer Match AI - SMTP test (' . $secure . ':' . $port . ')';
    $m->Body = '<p>If you receive this, SMTP works from the Vercel container.</p>';
    $ok = $m->Send();
    return $ok ? 'OK' : ('FAIL -> ' . $m->ErrorInfo);
}

echo "=== SMTP ssl:465 ===\n";
echo "RESULT: " . lm_try_smtp('ssl', 465, $user, $pass, $to) . "\n\n";

echo "=== SMTP tls:587 ===\n";
echo "RESULT: " . lm_try_smtp('tls', 587, $user, $pass, $to) . "\n";
