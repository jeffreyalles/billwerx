<?php

# Include session check and database connection:
include("inc/dbconfig.php");
include("global/make_safe.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

$get_company_messages = mysql_query("SELECT * FROM company_messages");
$show_company_message = mysql_fetch_array($get_company_messages);

# Process form when $_POST data is found for the specified form:
if((isset($_POST['send'])) and (!empty($_POST['email_address']))) {

# Define variables from user_login.php form:
$email_address = $_POST['email_address'];

// Query the client database table:
$get_clients = mysql_query("SELECT * FROM clients WHERE email_address = '$email_address' OR billing_email_address = '$email_address'");
$show_client = mysql_fetch_array($get_clients);

$search_values = array(
"[client_first_name]",
"[client_last_name]",
"[account_password]",
"[email_address]",
"[billing_email_address]",
);

$replacement_values = array(
$show_client['first_name'],
$show_client['last_name'],
$show_client['account_password'],
$show_client['email_address'],
$show_client['billing_email_address'],
);

# Setup PHPMailer values:
require("inc/phpmailer/class.phpmailer.php");
$mail = new PHPMailer();

$mail->From = $show_company['email_address'];
$mail->FromName = $show_company['company_name'];
$mail->AddAddress($show_client['email_address']);
$mail->addCC($show_client['billing_email_address']); 
$mail->addBCC($show_company['email_address']); 

$mail->Subject = "Forgot Password Request";
$mail->Body = str_replace($search_values, $replacement_values, $show_company_message['forgot_password']);

# Send email(s) and report errors if any:
if(!$mail->Send()) {
echo $mail->ErrorInfo; exit;
};

# Return to screen:
header("Location: forgot_password_complete.php");

};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $show_company['company_name'] ?> - Forgot Password</title>
<link href="billwerx.css" rel="stylesheet" type="text/css" />
</head>
<body onload="document.getElementById('email_address').focus()">
<div id="smallwrap">
  <div id="header">
    <h2>Forgot Password:</h2>
    <h3>Enter the e-mail address associated with your account.</h3>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
        <tr>
          <td class="firstcell">e-mail address:</td>
          <td><input name="email_address" type="text" class="entrytext" id="email_address" value="<?php echo $_COOKIE['billwerx'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="send" type="submit" class="button" id="send" value="SEND" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
