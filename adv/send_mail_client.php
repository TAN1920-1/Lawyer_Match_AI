<?php
    $first_Name=isset($first_Name) ? $first_Name : 'User';
    $last_Name=isset($last_Name) ? $last_Name : '';
    $password=isset($password) ? $password : '';
	
	$mailto = $email;
    $mailSub = "Notification For Account Information";

    include_once 'includes/mail_util.php';
    $login_link = site_base_url() . 'login.php';
	$message="
	<table border='1' width='590' align='center' cellpadding='2px' cellspacing='2' >
			<tr>
				<td align='left' style='color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;'>
				<h2 style=''>Dear ".$first_Name." <hr/>Your Account information</h2>
				<h6 style=''><b> Full Name : </b>".$first_Name." ".$last_Name."</h6>
				<h6 style=''><b> Your Email Address: </b>".$mailto."</h6>
				<h6 style=''><b> Your Password: </b>".$password."</h6>
				<p>Now You can easily Login & Update your password</p>
				<a href='".$login_link."'>Login Here</a>
			</td>
		</tr>
	</table>";

    send_notification_email($mailto, $mailSub, $message);
?>
