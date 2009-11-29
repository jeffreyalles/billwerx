<?php

# Define page access level:
session_start();
$page_access = 2;

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");
include("../inc/phpmailer/class.phpmailer.php");

# Include security POST loop:
include("../global/make_safe.php");

# Get company data:
$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get company messages:
$get_company_messages = mysql_query("SELECT * FROM company_messages");
$show_company_message = mysql_fetch_array($get_company_messages);

# Get invoice data:
$payment_id = $_GET['payment_id'];

# Get payment data from invoice data:
$get_payment = mysql_query("SELECT * FROM payments WHERE payment_id = '$payment_id'");
$show_payment = mysql_fetch_array($get_payment);

$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = " . $show_payment['invoice_id'] . "");
$show_invoice = mysql_fetch_array($get_invoice);

# Get employee from invoice:
$get_clients = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_clients);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['billing_email_address'])) {

# Get POST data:
$payment_id = $_POST['payment_id'];
$billing_email_address = $_POST['billing_email_address'];

# Get payment data from invoice data:
$get_payment = mysql_query("SELECT * FROM payments WHERE payment_id = '$payment_id'");
$show_payment = mysql_fetch_array($get_payment);

# Get payment method from invoice data:
$get_payment_method = mysql_query("SELECT * FROM payment_methods WHERE method_id = " . $show_payment['method_id'] . "");
$show_payment_method = mysql_fetch_array($get_payment_method);

$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = " . $show_payment['invoice_id'] . "");
$show_invoice = mysql_fetch_array($get_invoice);

# Get employee from invoice:
$get_clients = mysql_query("SELECT * FROM clients WHERE client_id = " . $show_invoice['client_id'] . "");
$show_client = mysql_fetch_array($get_clients);

$invoice_id = $show_invoice['invoice_id'];

# Include FPDF class:
define('FPDF_FONTPATH','font/');
require('../inc/fpdf/html_table.php');

ob_start();
include("../templates/invoice.php");
$html = ob_get_contents();
ob_end_clean();

# Setup fpdf values:
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetAuthor($show_company['company_name']);
$pdf->SetMargins(2, 2, 2);
$pdf->SetAutoPageBreak('false');
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->WriteHTML($html);
$pdf->Output();

$search_values = array(
"[client_first_name]",
"[client_last_name]",
"[invoice_number]",
"[easypay_id]",
"[invoice_date_created]",
"[invoice_date_due]",
"[invoice_amount_due]",
"[payment_amount]",
"[payment_method]",
"[payment_date_received]",
);

$replacement_values = array(
$show_client['first_name'],
$show_client['last_name'],
$show_invoice['invoice_id'],
$show_invoice['easypay_id'],
$show_invoice['date_created'],
$show_invoice['date_due'],
$show_invoice['due'],
$show_payment['amount'],
$show_payment_method['name'],
$show_payment['date_received'],
);

# Setup PHPMailer values:
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet = 'UTF-8';
$mail->From = $show_company['email_address'];
$mail->FromName = $show_company['company_name'];
$mail->AddAddress("$billing_email_address");
$mail->addBCC($show_company['email_address'], $show_company['company_name']); 
$mail->Subject = "Payment Received: Invoice #: " . $show_invoice['invoice_id'] . " - " . $show_invoice['purpose'];
$mail->Body = str_replace($search_values, $replacement_values, $show_company_message['payment_received']);
$attachment = $pdf->Output('', 'S');
$mail->AddStringAttachment($attachment, 'Invoice_' . $show_invoice['invoice_id'] . '.pdf', 'base64', 'application/pdf');

# Send email(s) and report errors if any:
if(!$mail->Send()) {
echo $mail->ErrorInfo; exit;
}

# Check to see if we should sent a survey invite:
if(($show_invoice['due'] == 0) AND ($_POST['survey_invite'] == 1)) {

# Setup PHPMailer values:
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet = 'UTF-8';
$mail->From = $show_company['email_address'];
$mail->FromName = $show_company['company_name'];
$mail->AddAddress("$billing_email_address");
$mail->addBCC($show_company['email_address'], $show_company['company_name']); 
$mail->Subject = "Survey Invite #: " . $show_invoice['invoice_id'] . " - " . $show_invoice['purpose'];
$mail->Body = str_replace($search_values, $replacement_values, $show_company_message['survey_invite']);
}

# Send email(s) and report errors if any:
if(!$mail->Send()) {
echo $mail->ErrorInfo; exit;
}

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
<body onload="document.getElementById('billing_email_address').focus()" onunload="window.opener.location.reload();window.close()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/email.png" alt="E-mail Receipt" width="16" height="16" /> E-mail Payment Received:</h1>
  </div>
  <div id="content">
    <form id="E-mail" name="E-mail" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">survey invite:</td>
          <td><input name="survey_invite" type="checkbox" id="survey_invite" value="1" checked="checked" /></td>
        </tr>
        <tr>
          <td class="firstcell">billing e-mail address:</td>
          <td><input name="billing_email_address" type="text" class="entrytext" id="billing_email_address" value="<?php echo $show_invoice['billing_email_address'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="email" type="submit" class="button" id="email" value="E-MAIL" />
            <input name="payment_id" type="hidden" id="payment_id" value="<?php echo $show_payment['payment_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
