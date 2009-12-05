<?php

# Define page access level:
session_start();
$page_access = 1;

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
$invoice_id = $_GET['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['billing_email_address'])) {

# Define billing e-mail address:
$billing_email_address = $_POST['billing_email_address'];

$invoice_id = $_POST['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

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
"[account_password]",
"[invoice_number]",
"[invoice_date_created]",
"[invoice_date_due]",
"[invoice_easypay_number]",
"[invoice_notes]",
"[invoice_due]",
);

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

# Setup PHPMailer values:
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet = 'UTF-8';
$mail->From = $show_company['email_address'];
$mail->FromName = $show_company['company_name'];
$mail->AddAddress($billing_email_address);
$mail->addBCC($show_company['email_address'], $show_company['company_name']); 
$mail->Subject = "Invoice Created: Invoice #: $invoice_id - " . $show_invoice['purpose'];
$mail->Body = str_replace($search_values, $replacement_values, $show_company_message['invoice_created']);
$attachment = $pdf->Output('', 'S');
$mail->AddStringAttachment($attachment, 'Invoice_' . $invoice_id . '.pdf', 'base64', 'application/pdf');

# Send email(s) and report errors if any:
if(!$mail->Send()) {
echo $mail->ErrorInfo; exit;
}

# Assign values to a database table:
$doSQL = "UPDATE invoices SET date_sent = NOW() WHERE invoice_id = '$invoice_id'";

mysql_query($doSQL) or die(mysql_error());

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - E-mail Invoice</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/form_assist.js"></script>
</head>
<body onload="document.getElementById('billing_email_address').focus()" onunload="window.opener.location.reload();window.close()">
<div id="smallwrap">
  <div id="header">
    <h1><img src="../images/icons/email.png" alt="E-mail Invoice" width="16" height="16" /> E-mail Invoice:</h1>
  </div>
  <div id="content">
    <form id="E-mail" name="E-mail" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
      <table class="fulltable">
        <tr>
          <td class="firstcell">billing e-mail address:</td>
          <td><input name="billing_email_address" type="text" class="entrytext" id="billing_email_address" value="<?php echo $show_invoice['billing_email_address'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="email" type="submit" class="button" id="email" value="E-MAIL" />
            <input name="invoice_id" type="hidden" id="invoice_id" value="<?php echo $show_invoice['invoice_id'] ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
