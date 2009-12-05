<?php

# Define page access level:
session_start();
$page_access = 3;

# Include session (security check):
include("session_check.php");

# Include security POST loop:
include("../global/make_safe.php");

# Include session check and database connection:
include("../inc/dbconfig.php");
include("../inc/phpmailer/class.phpmailer.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$get_company_messages = mysql_query("SELECT * FROM company_messages");
$show_company_messages = mysql_fetch_array($get_company_messages);

# If the size of the file is greater than zero (0) process:
if((isset($_POST['email'])) AND (($_FILES['file']['size'] > 0))) {

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Define POST file variables:
$temp_name  = $_FILES['file']['tmp_name'];

# Define message subject:
$subject = $_POST['subject'];

# Define message body:
$readfile = fopen($temp_name, 'r');
$content = fread($readfile, filesize($temp_name));
fclose($readfile);

# Get clients:
$get_clients = mysql_query("SELECT * FROM clients");
while($show_client = mysql_fetch_array($get_clients)) {

# Setup PHPMailer values:
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->IsHTML(true);
$mail->CharSet = 'UTF-8';
$mail->From = $show_company['email_address'];
$mail->FromName = $show_company['company_name'];
$mail->ConfirmReadingTo = $show_company['email_address'];
$mail->AddAddress($show_client['email_address']);
$mail->AddCC($show_client['billing_email_address']);
$mail->Subject = $subject;
$mail->Body = $content;
$mail->Send();

// Stop the query:
}; }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Mass E-mail Clients</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/email.png" alt="Mass E-mail Clients" width="16" height="16" /> Mass E-mail Clients:</h1>
    <p>You can sent a composed e-mail to all clients by using this form.</p>
  </div>
  <div id="content">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table class="fulltable">
        <tr>
          <td class="firstcell">subject:</td>
          <td><input name="subject" type="text" class="entrytext" id="subject" /></td>
        </tr>
        <tr>
          <td class="firstcell">upload html file:</td>
          <td><input name="file" type="file" class="entrytext" id="file" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="email" type="submit" class="button" id="email" value="E-MAIL" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
