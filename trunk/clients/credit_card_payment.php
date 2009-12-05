<?php

# Define page access level:
session_start();

# Include session (security check):
include("session_check.php");

# Include session check and database connection:
include("../inc/dbconfig.php");

# Include security POST loop:
include("../global/make_safe.php");

# Include e-mail class:
include("../inc/phpmailer/class.phpmailer.php");

$get_company = mysql_query("SELECT * FROM company");
$show_company = mysql_fetch_array($get_company);

# Get client data:
$invoice_id = $_POST['invoice_id'];
$get_invoice = mysql_query("SELECT * FROM invoices WHERE invoice_id = '$invoice_id'");
$show_invoice = mysql_fetch_array($get_invoice);

# Process form when $_POST data is found for the specified form:
if(isset($_POST['process'])) {

# Define POST data:
$type = $_POST['type'];
$number = $_POST['number'];
$expiration = $_POST['expiration'];
$invoice_id = $_POST['invoice_id'];
$amount = $_POST['amount'];

# Setup PHPMailer values:
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->From = $show_company['email_address'];
$mail->FromName = $show_company['company_name'];
$mail->AddAddress($show_company['email_address'], $show_company['company_name']); 
$mail->Subject = "Credit Card Payment Request";
$mail->Body = "Authorization received for invoice #: $invoice_id to be paid by $type: $number exp: $expiration for $$amount.";

# Send email(s) and report errors if any:
if(!$mail->Send()) {
echo $mail->ErrorInfo; exit;
}

# Return to screen:
header("Location: credit_card_payment_complete.php");

# End if condition:
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $show_company['session_timeout'] ?>;URL=../timeout.php" />
<title><?php echo $show_company['company_name'] ?> - Credit Card Payment</title>
<link href="../billwerx.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/modulus10.js"></script>
</head>
<body onload="document.getElementById('number').focus()">
<div id="smallwrap">
  <div id="header">
    <h2>Credit Card Payment:</h2>
    <h3>To continue simply enter your credit card number and expiration date.</h3>
  </div>
  <div id="content">
    <form id="form1" name="form1" method="post" action="">
      <table class="fulltable">
        <tr>
          <td class="firstcell">type:</td>
          <td><select name="type" class="entrytext" id="type">
              <option value="VISA">VISA</option>
              <option value="MasterCard">MasterCard</option>
              <option value="Amex">Amex</option>
            </select></td>
        </tr>
        <tr>
          <td class="firstcell">number:</td>
          <td><input name="number" type="text" class="entrytext" id="number" onblur="cleanNumber()" /></td>
        </tr>
        <tr>
          <td class="firstcell">expiration:</td>
          <td><input name="expiration" type="text" class="entrytext" id="expiration" onblur="cleanNumber()" /></td>
        </tr>
        <tr>
          <td class="firstcell">invoice #:</td>
          <td><input name="invoice_id" type="text" class="entrytext" id="invoice_id" value="<?php echo $show_invoice['invoice_id'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">purpose:</td>
          <td><input name="purpose" type="text" class="entrytext" id="purpose" readonly="readonly" value="<?php echo $show_invoice['purpose'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">amount:</td>
          <td><input name="amount" type="text" class="entrytext" id="amount" value="<?php echo $show_invoice['due'] ?>" /></td>
        </tr>
        <tr>
          <td class="firstcell">&nbsp;</td>
          <td><input name="process" type="submit" class="button" id="process" value="PROCESS" />
            <input name="cancel" type="button" class="button" id="cancel" onclick="window.location='index.php'" value="CANCEL" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
