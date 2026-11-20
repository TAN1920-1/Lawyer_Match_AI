<?php
    $firm_name = isset($firm_name) ? $firm_name : 'Law Firm';
    $password = isset($password) ? $password : '';
	$email = isset($email) ? $email : '';
	$mailto = $email;
    $mailSub = "Notification For Law Firm Account Information";

    include_once 'includes/mail_util.php';
    $login_link = site_base_url() . 'login.php';
	$message="
	<table border='1' width='590' align='center' cellpadding='2px' cellspacing='2' >
			<tr>
				<td align='left' style='color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;'>
				<h2 style=''>Dear ".$firm_name." <hr/>Your Law Firm Account information</h2>
				<h6 style=''><b> Firm Name : </b>".$firm_name."</h6>
				<h6 style=''><b> Your Email Address: </b>".$mailto."</h6>
				<h6 style=''><b> Your Temporary Password: </b>".$password."</h6>
				<p>Wait for Approval Email. Then You Can Easily Login to the system with your temporary password.</p>
				<a href='".$login_link."'>Login Here</a>
			</td>
		</tr>
	</table>";

    send_notification_email($mailto, $mailSub, $message);
?>
