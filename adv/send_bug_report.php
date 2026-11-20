<?php
include_once 'includes/session_init.php';
include_once 'includes/mail_util.php';

if(isset($_POST['submit_bug'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $bug_type = $_POST['bug_type'];
    $description = nl2br(htmlspecialchars($_POST['description']));
    
    $to = "lawyermatch.ai@gmail.com";
    $subject = "NEW BUG REPORT: [$bug_type]";
    
    // Modern HTML Email Template
    $message = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden;'>
        <div style='background: #dc3545; color: white; padding: 20px; text-align: center;'>
            <h2 style='margin: 0;'>New Bug Reported</h2>
        </div>
        <div style='padding: 30px; background: #fff;'>
            <table style='width: 100%; border-collapse: collapse;'>
                <tr>
                    <td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #666; width: 150px;'><strong>Reporter Name:</strong></td>
                    <td style='padding: 10px 0; border-bottom: 1px solid #eee;'>$name</td>
                </tr>
                <tr>
                    <td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #666;'><strong>Email Address:</strong></td>
                    <td style='padding: 10px 0; border-bottom: 1px solid #eee;'><a href='mailto:$email'>$email</a></td>
                </tr>
                <tr>
                    <td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #666;'><strong>Contact Info:</strong></td>
                    <td style='padding: 10px 0; border-bottom: 1px solid #eee;'>$contact</td>
                </tr>
                <tr>
                    <td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #666;'><strong>Bug Category:</strong></td>
                    <td style='padding: 10px 0; border-bottom: 1px solid #eee;'><span style='background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px;'>$bug_type</span></td>
                </tr>
                <tr>
                    <td colspan='2' style='padding: 20px 0 10px 0; color: #666;'><strong>Issue Description:</strong></td>
                </tr>
                <tr>
                    <td colspan='2' style='padding: 15px; background: #f8f9fa; border-radius: 5px; line-height: 1.6; color: #333;'>
                        $description
                    </td>
                </tr>
            </table>
            <div style='margin-top: 30px; text-align: center; color: #999; font-size: 12px;'>
                Reported on: " . date("d M Y, h:i A") . "<br>
                System: Lawyer Match AI Technical Team
            </div>
        </div>
    </div>";

    // Use the robust mailing utility
    $sent = send_notification_email($to, $subject, $message);
    
    if($sent) {
        header("Location: report_bug.php?status=success");
    } else {
        header("Location: report_bug.php?status=error");
    }
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
