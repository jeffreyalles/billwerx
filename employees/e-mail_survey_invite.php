<?php

# Define page access level:
session_start();
$page_access = 2;

# Include_once session (security check):
include_once("session_check.php");
include_once("../inc/dbconfig.php");

# Include FPDF class:
define('FPDF_FONTPATH','font/');
require('../inc/fpdf/html_table.php');

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get company messages:
$get_company_messages = mysql_query("SELECT * FROM company_messages");
$show_company_message = mysql_fetch_array($get_company_messages);

# Get associated invoice:
$invoice_id = $_GET['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Get client details:
$get_clients = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_clients);

$search_values = array(
"[client_first_name]",
"[client_last_name]",
"[invoice_number]",
"[easypay_id]",
);

$replacement_values = array(
$show_client['first_name'],
$show_client['last_name'],
$show_invoice['invoice_id'],
$show_invoice['easypay_id'],
);

$body = str_replace($search_values, $replacement_values, $show_company_message['survey_invite']);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['email'])) {

# Get POST data:
$email = $_POST['email'];
$subject = $_POST['subject'];
$body = $_POST['body'];

# Setup PHPMailer values:
require("../inc/phpmailer/class.phpmailer.php");
$mail = new PHPMailer();

$mail->From = $show_company['email_address'];
$mail->FromName = $show_company['company_name'];
$mail->AddAddress($email);
$mail->addBCC($show_company['email_address']); 

$mail->Subject = $subject;
$mail->Body = $body;

# Send email(s) and report errors if any:
if(!$mail->Send()) {
echo $mail->ErrorInfo; exit;
};

# Return to screen:
header("Location: email_sent.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - E-mail Payment Received</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body>
<div id="smallwrap">
  <div id="header">
    <h2>E-mail Survey Inivite:</h2>
    <h3>A copy of the invoice and receipt (as a PDF) will be attached to this e-mail.</h3>
  </div>
  <div id="content">
    <form id="E-mail" name="E-mail" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">

        <tr>
          <td class="firstcell">e-mail address:</td>
          <td><input name="email" type="text" class="entrytext" id="email" value="<?php echo $show_invoice['billing_email_address'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">subject:</td>
          <td><input name="subject" type="text" class="entrytext" id="subject" value="Survey Invite #: <?php echo $show_invoice['invoice_id'] ?> - <?php echo $show_invoice['purpose'] ?>" /></td>
        </tr>
      </table>
      <h2>Message / Body:</h2>
      <textarea name="body" class="entrybox" id="body"><?php echo $body ?>
          </textarea>
      <table class="fulltable">
        <tr>
          <td><input name="send" type="submit" class="button" id="send" value="SEND" />
          <input name="close" type="button" class="button" id="close" onclick="window.close()" value="CLOSE" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
