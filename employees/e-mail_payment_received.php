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

# Get payment data:
$payment_id = $_GET['payment_id'];
$get_payment = mysql_query("SELECT * FROM payments WHERE payment_id = '$payment_id'");
$show_payment = mysql_fetch_array($get_payment);

# Get client details:
$get_payment_methods = mysql_query("SELECT * FROM payment_methods WHERE method_id = " . $show_payment['method_id'] . "");
$show_payment_method = mysql_fetch_array($get_payment_methods);

# Get associated invoice:
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = " . $show_payment['invoice_id'] . "");
$show_invoice = mysql_fetch_array($get_invoice);

# Get client details:
$get_clients = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_payment['client_id'] . "");
$show_client = mysql_fetch_array($get_clients);

$search_values = array(
"[client_first_name]",
"[client_last_name]",
"[invoice_number]",
"[invoice_amount_due]",
"[payment_amount]",
"[payment_method]",
"[payment_date_received]",
);

$replacement_values = array(
$show_client['first_name'],
$show_client['last_name'],
$show_invoice['invoice_id'],
$show_invoice['due'],
$show_payment['amount'],
$show_payment_method['name'],
$show_payment['date_received'],
);

$body = str_replace($search_values, $replacement_values, $show_company_message['payment_received']);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['email'])) {

# Get POST data:
$email = $_POST['email'];
$subject = $_POST['subject'];
$body = $_POST['body'];
$payment_id = $_POST['payment_id'];
$invoice_id = $_POST['invoice_id'];

# Setup PHPMailer values:
require("../inc/phpmailer/class.phpmailer.php");
$mail = new PHPMailer();

$mail->From = $show_company['email_address'];
$mail->FromName = $show_company['company_name'];
$mail->AddAddress($email);
$mail->addBCC($show_company['email_address']); 

$mail->Subject = $subject;
$mail->Body = $body;

# Add invoice attachment:
include_once("../global/generate_invoice.php");
$invoice_attachment = $pdf->Output('', 'S');
$mail->AddStringAttachment($invoice_attachment, 'invoice_' . $invoice_id . '.pdf');

# Add payment attachment:
include_once("../global/generate_receipt.php");
$payment_attachment = $pdf->Output('', 'S');
$mail->AddStringAttachment($payment_attachment, 'payment_' . $payment_id . '.pdf');

# Send email(s) and report errors if any:
if(!$mail->Send()) {
echo $mail->ErrorInfo; exit;
};

# Return to screen:
header("Location: e-mail_survey_invite.php?invoice_id=$invoice_id");
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
<body onunload="window.opener.location.reload();">
<div id="smallwrap">
  <div id="header">
    <h2>E-mail Payment Received:</h2>
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
          <td><input name="subject" type="text" class="entrytext" id="subject" value="Payment Received #: <?php echo $show_invoice['invoice_id'] ?> - <?php echo $show_invoice['purpose'] ?>" /></td>
        </tr>
      </table>
      <h2>Message / Body:</h2>
      <textarea name="body" class="entrybox" id="body"><?php echo $body ?>
          </textarea>
      <table class="fulltable">
        <tr>
          <td><input name="send" type="submit" class="button" id="send" value="SEND" />
            <input name="invoice_id" type="hidden" id="invoice_id" value="<?php echo $show_payment['invoice_id'] ?>" />
          <input name="payment_id" type="hidden" id="payment_id" value="<?php echo $show_payment['payment_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
