<?php

# Define page access level:
session_start();
$page_access = 1;

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

# Get invoice data:
$invoice_id = $_GET['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Get client data from invoice_id:
$get_client = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_client);

# Replace these:
$search_values = array(
"[client_first_name]",
"[client_last_name]",
"[account_password]",
"[invoice_number]",
"[invoice_date_created]",
"[invoice_date_due]",
"[invoice_easypay_number]",
"[invoice_notes]",
"[invoice_due]",
);

# With these values:
$replacement_values = array(
$show_client['first_name'],
$show_client['last_name'],
$show_client['account_password'],
$show_invoice['invoice_id'],
$show_invoice['date_created'],
$show_invoice['date_due'],
$show_invoice['easypay_number'],
$show_invoice['notes'],
$show_invoice['due'],
);

# Create body area in textarea to make on-the-fly changes:
$body = str_replace($search_values, $replacement_values, $show_company_message['invoice_created']);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['email'])) {

# Define POST variables:
$email = $_POST['email'];
$subject = $_POST['subject'];
$body = $_POST['body'];

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

# Get invoice attachment:
include_once("../global/generate_invoice.php");

$invoice_attachment = $pdf->Output('', 'S');
$mail->AddStringAttachment($invoice_attachment, 'invoice_' . $invoice_id . '.pdf');

# Send email(s) and report errors if any:
if(!$mail->Send()) {
echo $mail->ErrorInfo; exit;
};

# Update invoice table to reflect date and time sent:
$doSQL = "UPDATE invoices SET date_sent = NOW() WHERE invoice_id = '$invoice_id'";
mysql_query($doSQL) or die(mysql_error());

# Return to screen:
header("Location: email_sent.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?>- E-mail Invoice</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onunload="window.opener.location.reload()">
<div id="smallwrap">
  <div id="header">
    <h2>E-mail Invoice:</h2>
    <h3>A copy of the invoice (as a PDF) will be attached to this e-mail.</h3>
  </div>
  <div id="content">
    <form id="email" name="email" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">e-mail address:</td>
          <td colspan="2"><input name="email" type="text" class="entrytext" id="email" value="<?php echo $show_invoice['billing_email_address'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">subject:</td>
          <td><input name="subject" type="text" class="entrytext" id="subject" value="Invoice #: <?php echo $show_invoice['invoice_id'] ?> - <?php echo $show_invoice['purpose'] ?>" /></td>
          <td class="lastcell"><a href="../global/print_invoice.php?invoice_id=<?php echo $show_invoice['invoice_id'] ?>"><img src="../images/icons/email_attach.png" alt="View Attachment" width="16" height="16" class="iconspacer" /></a></td>
        </tr>
      </table>
      <h2>Message / Body:</h2>
      <textarea name="body" class="entrybox" id="body"><?php echo $body ?>
          </textarea>
      <table class="fulltable">
        <tr>
          <td><input name="send" type="submit" class="button" id="send" value="SEND" />
            <input name="invoice_id" type="hidden" id="invoice_id" value="<?php echo $show_invoice['invoice_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
